<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Carbon\Carbon;

class SaleIndex extends Component
{
    public $dateStart;
    public $dateEnd;

    public function __construct()
    {
        $this->authorize('view-sales');
    }

    public function updatedDateStart()
    {
        $this->reset('dateEnd');
    }

    public function resetFilter()
    {
        $this->dateStart = null;
        $this->dateEnd = null;
    }

    public function delete($id)
    {
        $this->authorize('delete-sales');

        try {
            $sale = Sale::findOrFail($id);

            if ($sale->status === 'Sudah Dibayar') {
                session()->flash('error', 'Penjualan yang sudah dibayar tidak dapat dihapus!');
                return;
            }

            $sale->delete();
            session()->flash('message', 'Penjualan berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        $sales = Sale::with(['saleItems.item'])
            ->when($this->dateStart, function ($query) {
                $query->whereDate('sale_date', '>=', $this->dateStart);
            })
            ->when($this->dateEnd, function ($query) {
                $query->whereDate('sale_date', '<=', $this->dateEnd);
            })
            ->latest('sale_date')
            ->get();

        return view('livewire.sales.sale-index', [
            'sales' => $sales,
        ])->layout('layouts.app', ['title' => 'Daftar Penjualan']);
    }
}
