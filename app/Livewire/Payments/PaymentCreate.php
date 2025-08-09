<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use App\Models\Sale;
use Livewire\Component;

class PaymentCreate extends Component
{
    public $code = '';
    public $saleId = '';
    public $paymentDate = '';
    public $amount = '';
    public $notes = '';

    public function __construct()
    {
        $this->authorize('create-payments');
    }

    public function mount()
    {
        $this->code = Payment::generateCode();
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function updatedSaleId()
    {
        if ($this->saleId) {
            $sale = Sale::find($this->saleId);
            if ($sale) {
                $remainingAmount = $sale->remaining_amount;
                if ($remainingAmount > 0) {
                    $this->amount = (int) $remainingAmount;
                }
            }
        }
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:payments,code',
            'saleId' => 'required|exists:sales,id',
            'paymentDate' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();

        // Check if sale exists and get remaining amount
        $sale = Sale::find($this->saleId);
        if (!$sale) {
            $this->addError('saleId', 'Penjualan tidak ditemukan.');
            return;
        }

        // Check if payment amount doesn't exceed remaining amount
        if ($this->amount > $sale->remaining_amount) {
            $this->addError('amount', 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($sale->remaining_amount, 0, ',', '.') . ').');
            return;
        }

        try {
            // Create payment record
            Payment::create([
                'code' => $this->code,
                'sale_id' => $this->saleId,
                'payment_date' => $this->paymentDate,
                'amount' => $this->amount,
                'notes' => $this->notes,
                'created_by' => auth()->id(),
            ]);

            session()->flash('message', 'Pembayaran berhasil ditambahkan.');
            return redirect()->route('payments.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan pembayaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        $sales = Sale::with(['createdBy', 'updatedBy'])
            ->where('status', '!=', 'Sudah Dibayar')
            ->orderBy('sale_date', 'desc')
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'code' => $sale->code,
                    'saleDate' => $sale->sale_date,
                    'userName' => $sale->customer_name ?? '-',
                    'totalAmount' => $sale->total_amount,
                    'remainingAmount' => $sale->remaining_amount,
                    'display' => $sale->code . ' - ' . ($sale->customer_name ?? 'no name') . ' (Sisa: Rp ' . number_format($sale->remaining_amount, 0, ',', '.') . ')'
                ];
            });

        return view('livewire.payments.payment-create', compact('sales'))
            ->layout('layouts.app', ['title' => 'Tambah Pembayaran']);
    }
}
