<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;

class SaleShow extends Component
{
    public Sale $sale;

    public function __construct()
    {
        $this->authorize('view-sales');
    }

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load(['createdBy', 'updatedBy', 'saleItems.item', 'payments']);
    }

    public function render()
    {
        return view('livewire.sales.sale-show')
            ->layout('layouts.app', ['title' => 'Detail Penjualan']);
    }
}
