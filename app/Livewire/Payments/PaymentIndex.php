<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;

class PaymentIndex extends Component
{
    public $dateStart;
    public $dateEnd;

    public function __construct()
    {
        $this->authorize('view-payments');
    }

    public function updatedDateStart()
    {
        $this->reset('dateEnd');
    }

    public function resetFilter()
    {
        $this->reset('dateStart', 'dateEnd');
    }

    public function delete(Payment $payment)
    {
        $this->authorize('delete-payments');

        try {
            $payment->delete();
            session()->flash('message', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $payments = Payment::with(['sale.createdBy', 'sale.updatedBy'])
            ->when($this->dateStart, function ($query) {
                $query->whereDate('payment_date', '>=', $this->dateStart);
            })
            ->when($this->dateEnd, function ($query) {
                $query->whereDate('payment_date', '<=', $this->dateEnd);
            })
            ->latest()
            ->get();

        return view('livewire.payments.payment-index', compact('payments'))
            ->layout('layouts.app', ['title' => 'Pembayaran']);
    }
}
