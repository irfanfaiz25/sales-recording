<div class="fade-in">
    <div class="page-header">
        <h1 class="page-title">
            Edit Penjualan
        </h1>
        <p class="page-subtitle">
            Perbarui informasi penjualan dan item yang dijual
        </p>
    </div>

    <div class="card">
        <form class="card-body" wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Kode Penjualan --}}
                <div class="space-y-2">
                    <label for="code" class="form-label">
                        Kode Penjualan
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="code" wire:model="code" class="form-input pl-10" readonly
                                placeholder="Kode penjualan">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-receipt text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    @error('code')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Tanggal Penjualan --}}
                <div class="space-y-2">
                    <label for="sale_date" class="form-label">
                        Tanggal Penjualan
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" id="sale_date" wire:model="saleDate" class="form-input pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('saleDate')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Customer --}}
                <div class="lg:col-span-2 space-y-2">
                    <label for="customer_name" class="form-label">
                        Customer
                    </label>
                    <div class="relative">
                        <input type="text" id="customer_name" wire:model="customerName" class="form-input pl-10"
                            placeholder="Masukkan nama customer (jika ada)">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    @error('customerName')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Items Section --}}
            <div class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Items Penjualan</h3>
                            <p class="text-xs text-gray-600">Pilih item</p>
                        </div>
                    </div>
                    <button type="button" wire:click="addItem" class="btn btn-secondary flex items-center gap-2"
                        wire:loading.attr="disabled" wire:target="addItem">
                        <span wire:loading.remove wire:target="addItem">
                            <i class="fas fa-plus"></i>
                            Tambah Item
                        </span>
                        <span wire:loading wire:target="addItem" class="flex items-center gap-2">
                            <div class="spinner"></div>
                            Menambah...
                        </span>
                    </button>
                </div>

                @error('items')
                    <div class="flex items-center gap-2 text-red-500 text-sm mb-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="space-y-6">
                    @foreach ($items as $index => $item)
                        <div
                            class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                {{-- Item Selection --}}
                                <div class="md:col-span-3 space-y-2">
                                    <label class="form-label flex items-center gap-2">
                                        Item
                                        <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <div class="relative" x-data="{ open: @entangle('showDropdowns.' . $index) }">
                                        <div class="relative">
                                            <input type="text"
                                                wire:model.live.debounce.300ms="itemSearches.{{ $index }}"
                                                wire:focus="$set('showDropdowns.{{ $index }}', true)"
                                                placeholder="Cari item..." class="form-input pl-10 pr-10"
                                                autocomplete="off">

                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>

                                            @if (!empty($itemSearches[$index] ?? ''))
                                                <button type="button" wire:click="clearItem({{ $index }})"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Dropdown List --}}
                                        @if (($showDropdowns[$index] ?? false) && !empty($filteredItems[$index] ?? []))
                                            <div
                                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                                @foreach ($filteredItems[$index] as $filteredItem)
                                                    <div wire:click="selectItem({{ $index }}, {{ $filteredItem->id }})"
                                                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-box text-gray-600 text-sm"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-medium text-sm text-gray-900">
                                                                {{ $filteredItem->name }}
                                                            </div>
                                                            <div class="text-[10px] text-gray-500">
                                                                {{ $filteredItem->code }}
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-xs font-semibold text-green-600">
                                                                @rupiah($filteredItem->price)
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if (count($filteredItems[$index]) === 0 && !empty($itemSearches[$index] ?? ''))
                                                    <div class="px-4 py-3 text-gray-500 text-center">
                                                        <i class="fas fa-search text-gray-400 mb-2"></i>
                                                        <p class="text-sm">Item tidak ditemukan</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Show available items when input is focused but empty --}}
                                        @if (($showDropdowns[$index] ?? false) && empty($itemSearches[$index] ?? '') && count($availableItems) > 0)
                                            <div
                                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                                <div
                                                    class="px-4 py-2 bg-gray-50 border-b border-gray-200 text-sm font-medium text-gray-700">
                                                    Item Tersedia ({{ count($availableItems) }})
                                                </div>
                                                @foreach ($availableItems->take(10) as $availableItem)
                                                    <div wire:click="selectItem({{ $index }}, {{ $availableItem->id }})"
                                                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-box text-gray-600 text-sm"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-medium text-sm text-gray-900">
                                                                {{ $availableItem->name }}
                                                            </div>
                                                            <div class="text-[10px] text-gray-500">
                                                                {{ $availableItem->code }}
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-xs font-semibold text-green-600">
                                                                @rupiah($availableItem->price)
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    @error('items.' . $index . '.itemId')
                                        <div class="flex items-center gap-2 text-red-500 text-sm">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Quantity --}}
                                <div class="md:col-span-2 space-y-2">
                                    <label class="form-label flex items-center gap-2">
                                        Qty
                                        <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="decrementQuantity({{ $index }})"
                                            class="w-8 h-8 flex items-center justify-center rounded-full text-sm bg-gray-200 hover:bg-gray-300 transition-colors">
                                            <i class="fas fa-minus text-gray-600"></i>
                                        </button>

                                        <div class="relative flex-1">
                                            <div
                                                class="form-quantity bg-gradient-to-r from-gray-100 to-gray-200 pl-10 font-semibold text-gray-700">
                                                {{ $item['quantity'] }}
                                            </div>
                                        </div>

                                        <button type="button" wire:click="incrementQuantity({{ $index }})"
                                            class="w-8 h-8 flex items-center justify-center rounded-full text-sm bg-gray-200 hover:bg-gray-300 transition-colors">
                                            <i class="fas fa-plus text-gray-600"></i>
                                        </button>
                                    </div>
                                    @error('items.' . $index . '.quantity')
                                        <div class="flex items-center gap-2 text-red-500 text-sm">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Price --}}
                                <div class="md:col-span-3 space-y-2">
                                    <label class="form-label flex items-center gap-2">
                                        Harga
                                        <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="form-input bg-gradient-to-r from-gray-100 to-gray-200 pl-10 font-semibold text-gray-700">
                                            @rupiah($item['price'])
                                        </div>
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-semibold">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div class="md:col-span-3 space-y-2">
                                    <label class="form-label flex items-center gap-2">
                                        Total
                                        <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="form-input bg-gradient-to-r from-green-100 to-green-200 pl-10 font-bold text-green-700">
                                            @rupiah($item['totalPrice'])
                                        </div>
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-green-600 font-semibold">Rp</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Remove Button --}}
                                @if (count($items) > 1)
                                    <div class="md:col-span-1 flex justify-center">
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110"
                                            title="Hapus item" wire:loading.attr="disabled"
                                            wire:target="removeItem({{ $index }})">
                                            <span wire:loading.remove wire:target="removeItem({{ $index }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </span>
                                            <span wire:loading wire:target="removeItem({{ $index }})">
                                                <div class="spinner"></div>
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Total and Action Buttons --}}
            <div class="border-t border-gray-200 pt-8">
                <div class="bg-gray-50 p-4 rounded-lg mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-3 items-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-calculator text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Keseluruhan</p>
                                <p class="text-2xl font-bold text-green-600">@rupiah($totalAmount)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('sales.index') }}"
                        class="btn btn-secondary flex items-center justify-center gap-2 order-2 sm:order-1">
                        <i class="fas fa-circle-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <button type="submit"
                        class="btn btn-primary flex items-center justify-center gap-2 order-1 sm:order-2"
                        wire:loading.attr="disabled" wire:target="update">
                        <span wire:loading.remove wire:target="update" class="flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Perbarui Penjualan</span>
                        </span>
                        <span wire:loading wire:target="update" class="flex items-center gap-2">
                            <div class="spinner"></div>
                            <span>Memperbarui...</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
