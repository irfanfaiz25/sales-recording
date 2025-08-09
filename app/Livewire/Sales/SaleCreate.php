<?php

namespace App\Livewire\Sales;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\Item;
use App\Models\User;
use App\Models\SaleItem;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleCreate extends Component
{
    #[Validate('required|string|max:255')]
    public $code = '';

    #[Validate('required|date')]
    public $saleDate = '';

    #[Validate('nullable|string|max:255')]
    public $customerName = '';

    public $items = [];
    public $totalAmount = 0;

    public $itemSearches = [];
    public $showDropdowns = [];
    public $filteredItems = [];

    public function __construct()
    {
        $this->authorize('create-sales');
    }

    public function mount()
    {
        $this->code = Sale::generateCode();
        $this->saleDate = Carbon::now()->format('Y-m-d');
        $this->addItem();
    }

    public function addItem()
    {
        $index = count($this->items);
        $this->items[] = [
            'itemId' => '',
            'quantity' => 1,
            'price' => 0,
            'totalPrice' => 0,
        ];

        // Initialize search properties for new item
        $this->itemSearches[$index] = '';
        $this->showDropdowns[$index] = false;
        $this->filteredItems[$index] = [];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            unset($this->itemSearches[$index]);
            unset($this->showDropdowns[$index]);
            unset($this->filteredItems[$index]);

            $this->items = array_values($this->items);
            $this->itemSearches = array_values($this->itemSearches);
            $this->showDropdowns = array_values($this->showDropdowns);
            $this->filteredItems = array_values($this->filteredItems);

            $this->calculateTotal();
        }
    }

    /**
     * Update item search and filter available items
     */
    public function updatedItemSearches($value, $index)
    {
        $this->showDropdowns[$index] = !empty($value);

        if (!empty($value)) {
            // Get selected item IDs to exclude them
            $selectedItemIds = array_filter(array_column($this->items, 'itemId'));

            // Filter items based on search and exclude selected items
            $this->filteredItems[$index] = Item::where('name', 'like', '%' . $value . '%')
                ->whereNotIn('id', $selectedItemIds)
                ->orderBy('name')
                ->limit(10)
                ->get();
        } else {
            $this->filteredItems[$index] = [];
        }
    }

    /**
     * Select item from dropdown
     */
    public function selectItem($itemIndex, $itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            $this->items[$itemIndex]['itemId'] = $item->id;
            $this->items[$itemIndex]['price'] = $item->price;
            $this->items[$itemIndex]['totalPrice'] = $this->items[$itemIndex]['quantity'] * $item->price;

            $this->itemSearches[$itemIndex] = $item->name;
            $this->showDropdowns[$itemIndex] = false;
            $this->filteredItems[$itemIndex] = [];

            $this->calculateTotal();
        }
    }

    /**
     * Clear item selection
     */
    public function clearItem($index)
    {
        $this->items[$index]['itemId'] = '';
        $this->items[$index]['price'] = 0;
        $this->items[$index]['totalPrice'] = 0;
        $this->itemSearches[$index] = '';
        $this->showDropdowns[$index] = false;
        $this->filteredItems[$index] = [];

        $this->calculateTotal();
    }

    /**
     * Hide dropdown when clicking outside
     */
    public function hideDropdown($index)
    {
        $this->showDropdowns[$index] = false;
    }

    /**
     * Increment quantity for specific item
     */
    public function incrementQuantity($index)
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['quantity']++;
            $this->items[$index]['totalPrice'] = $this->items[$index]['quantity'] * $this->items[$index]['price'];
            $this->calculateTotal();
        }
    }

    /**
     * Decrement quantity for specific item
     */
    public function decrementQuantity($index)
    {
        if (isset($this->items[$index]) && $this->items[$index]['quantity'] > 1) {
            $this->items[$index]['quantity']--;
            $this->items[$index]['totalPrice'] = $this->items[$index]['quantity'] * $this->items[$index]['price'];
            $this->calculateTotal();
        }
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'itemId' && $value) {
            $item = Item::find($value);
            if ($item) {
                $this->items[$index]['price'] = $item->price;
                $this->items[$index]['totalPrice'] = $this->items[$index]['quantity'] * $item->price;
            }
        }

        if ($field === 'quantity' || $field === 'price') {
            $this->items[$index]['totalPrice'] = $this->items[$index]['quantity'] * $this->items[$index]['price'];
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = array_sum(array_column($this->items, 'totalPrice'));
    }

    public function rules()
    {
        $rules = [
            'code' => 'required|string|max:255|unique:sales,code',
            'saleDate' => 'required|date',
            'customerName' => 'nullable|string|max:255',
        ];

        foreach ($this->items as $index => $item) {
            $rules["items.{$index}.itemId"] = 'required|exists:items,id';
            $rules["items.{$index}.quantity"] = 'required|integer|min:1';
            $rules["items.{$index}.price"] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        // Filter out empty items
        $validItems = array_filter($this->items, function ($item) {
            return !empty($item['itemId']) && $item['quantity'] > 0;
        });

        if (empty($validItems)) {
            $this->addError('items', 'Minimal harus ada satu item.');
            return;
        }

        try {
            DB::transaction(function () use ($validItems) {
                // Create Sale
                $sale = Sale::create([
                    'code' => $this->code,
                    'sale_date' => $this->saleDate,
                    'customer_name' => $this->customerName,
                    'total_amount' => $this->totalAmount,
                    'remaining_amount' => $this->totalAmount,
                    'status' => 'Belum Dibayar',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);

                // Create Sale Items
                foreach ($validItems as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'item_id' => $item['itemId'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total_price' => $item['totalPrice'],
                    ]);
                }
            });

            session()->flash('message', 'Penjualan berhasil dibuat!');
            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function saveAndPay()
    {
        $this->validate();

        // Filter out empty items
        $validItems = array_filter($this->items, function ($item) {
            return !empty($item['itemId']) && $item['quantity'] > 0;
        });

        if (empty($validItems)) {
            $this->addError('items', 'Minimal harus ada satu item.');
            return;
        }

        $sale = null;

        try {
            DB::transaction(function () use ($validItems, &$sale) {
                // Create Sale
                $sale = Sale::create([
                    'code' => $this->code,
                    'sale_date' => $this->saleDate,
                    'customer_name' => $this->customerName,
                    'total_amount' => $this->totalAmount,
                    'remaining_amount' => 0,
                    'status' => 'Sudah Dibayar',
                    'created_by' => auth()->user()->id,
                ]);

                // Create Sale Items
                foreach ($validItems as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'item_id' => $item['itemId'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total_price' => $item['totalPrice'],
                    ]);
                }

                // Create Payment directly for full amount
                Payment::create([
                    'code' => Payment::generateCode(),
                    'sale_id' => $sale->id,
                    'payment_date' => $this->saleDate,
                    'amount' => $this->totalAmount,
                    'notes' => 'Pembayaran langsung saat pembuatan penjualan',
                    'created_by' => auth()->user()->id,
                ]);
            });

            session()->flash('message', 'Penjualan berhasil dibuat dan dibayar.');
            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        // Get only unselected items for general use
        $selectedItemIds = array_filter(array_column($this->items, 'itemId'));
        $availableItems = Item::whereNotIn('id', $selectedItemIds)
            ->orderBy('name')
            ->get();

        return view('livewire.sales.sale-create', [
            'availableItems' => $availableItems,
        ])->layout('layouts.app', ['title' => 'Tambah Penjualan']);
    }
}
