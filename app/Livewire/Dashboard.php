<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $startDate;
    public $endDate;

    public function __construct()
    {
        $this->authorize('view-reports');
    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->validateDates();
        $this->dispatch('refreshCharts', [
            'monthlySalesData' => $this->monthlySalesData,
            'itemSalesData' => $this->itemSalesData
        ]);
    }

    public function updatedEndDate()
    {
        $this->validateDates();
        $this->dispatch('refreshCharts', [
            'monthlySalesData' => $this->monthlySalesData,
            'itemSalesData' => $this->itemSalesData
        ]);
    }

    private function validateDates()
    {
        if ($this->startDate && $this->endDate) {
            if (Carbon::parse($this->startDate)->gt(Carbon::parse($this->endDate))) {
                $this->endDate = $this->startDate;
            }
        }
    }

    public function getTransactionCountProperty()
    {
        return Sale::whereBetween('sale_date', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ])->count();
    }

    public function getTotalSalesProperty()
    {
        return Payment::whereHas('sale', function ($query) {
            $query->whereBetween('payment_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        })->sum('amount');
    }

    public function getTotalItemsSoldProperty()
    {
        return SaleItem::whereHas('sale', function ($query) {
            $query->whereBetween('sale_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        })->sum('quantity');
    }

    public function getMonthlySalesDataProperty()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);

        $monthlySales = Payment::select(
            DB::raw('YEAR(payment_date) as year'),
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('sale_date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ]);
            })
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return $monthlySales->map(function ($item) {
            return [
                'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                'total' => $item->total
            ];
        });
    }

    public function getItemSalesDataProperty()
    {
        return SaleItem::select(
            'items.name',
            DB::raw('SUM(sale_items.quantity) as total_qty')
        )
            ->join('items', 'sale_items.item_id', '=', 'items.id')
            ->whereHas('sale', function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app', [
            'title' => 'Dashboard'
        ]);
    }
}
