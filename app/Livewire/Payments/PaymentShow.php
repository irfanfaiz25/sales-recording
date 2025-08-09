<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;

class PaymentShow extends Component
{
    public Payment $payment;

    public function __construct()
    {
        $this->authorize('view-payments');
    }

    public function mount(Payment $payment)
    {
        $this->payment = $payment->load(['sale.createdBy', 'sale.updatedBy', 'sale.saleItems.item']);
    }

    public function render()
    {
        return view('livewire.payments.payment-show')
            ->layout('layouts.app', ['title' => 'Detail Pembayaran']);
    }
}
