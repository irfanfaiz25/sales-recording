<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use App\Models\Sale;
use Livewire\Component;

class PaymentEdit extends Component
{
    public Payment $payment;
    public $code = '';
    public $saleId = '';
    public $paymentDate = '';
    public $amount = '';
    public $notes = '';
    public $originalAmount = 0;

    public function __construct()
    {
        $this->authorize('edit-payments');
    }

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->code = $payment->code;
        $this->saleId = $payment->sale_id;
        $this->paymentDate = $payment->payment_date->format('Y-m-d');
        $this->amount = (int) $payment->amount;
        $this->notes = $payment->notes;
        $this->originalAmount = (int) $payment->amount;
    }

    public function getAvailableAmountProperty()
    {
        if (!$this->payment || !$this->payment->sale) {
            return 0;
        }

        // count other payments exclude current payment
        $otherPayments = $this->payment->sale->payments()
            ->where('id', '!=', $this->payment->id)
            ->sum('amount');

        // Available amount
        return $this->payment->sale->total_amount - $otherPayments;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:payments,code,' . $this->payment->id,
            'saleId' => 'required|exists:sales,id',
            'paymentDate' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function update()
    {
        $this->validate();

        // Check if sale exists and get remaining amount
        $sale = Sale::find($this->saleId);
        if (!$sale) {
            $this->addError('saleId', 'Penjualan tidak ditemukan.');
            return;
        }

        // Calculate available amount for this payment
        $availableAmount = $sale->remaining_amount + $this->originalAmount;

        // Check if payment amount doesn't exceed available amount
        if ($this->amount > $availableAmount) {
            $this->addError('amount', 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($availableAmount, 0, ',', '.') . ').');
            return;
        }

        try {
            // Update payment record
            $this->payment->update([
                'code' => $this->code,
                'sale_id' => $this->saleId,
                'payment_date' => $this->paymentDate,
                'amount' => $this->amount,
                'notes' => $this->notes,
                'updated_by' => auth()->id(),
            ]);

            session()->flash('message', 'Pembayaran berhasil diperbarui.');
            return redirect()->route('payments.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        $sales = Sale::with(['createdBy', 'updatedBy'])
            ->orderBy('sale_date', 'desc')
            ->get()
            ->map(function ($sale) {
                // Calculate available amount for this sale
                $availableAmount = $sale->remaining_amount;
                if ($sale->id == $this->saleId) {
                    $availableAmount += $this->originalAmount;
                }

                return [
                    'id' => $sale->id,
                    'code' => $sale->code,
                    'userName' => $sale->customer_name ?? '-',
                    'totalAmount' => $sale->total_amount,
                    'remainingAmount' => $sale->remaining_amount,
                    'availableAmount' => $availableAmount,
                    'display' => $sale->code . ' - ' . ($sale->customer_name ?? 'no name') . ' (Tersedia: Rp ' . number_format($availableAmount, 0, ',', '.') . ')'
                ];
            });

        return view('livewire.payments.payment-edit', compact('sales'))
            ->layout('layouts.app', ['title' => 'Edit Pembayaran']);
    }
}
