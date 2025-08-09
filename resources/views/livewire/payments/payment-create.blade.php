<div class="fade-in">
    <div class="page-header">
        <h1 class="page-title">
            Tambah Pembayaran Baru
        </h1>
        <p class="page-subtitle">
            Lengkapi informasi pembayaran untuk penjualan yang dipilih
        </p>
    </div>

    <div class="card">
        <form class="card-body" wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Kode Pembayaran --}}
                <div class="space-y-2">
                    <label for="code" class="form-label">
                        Kode Pembayaran
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="code" wire:model="code" class="form-input pl-10" readonly
                                placeholder="Kode akan digenerate otomatis">
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

                {{-- Tanggal Pembayaran --}}
                <div class="space-y-2">
                    <label for="paymentDate" class="form-label">
                        Tanggal Pembayaran
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" id="paymentDate" wire:model="paymentDate" class="form-input pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('paymentDate')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Penjualan --}}
                <div class="lg:col-span-2 space-y-2">
                    <label for="saleId" class="form-label">
                        Penjualan
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <select id="saleId" wire:model.live="saleId" class="form-input pl-10">
                            <option value="">Pilih Penjualan</option>
                            @foreach ($sales as $sale)
                                <option value="{{ $sale['id'] }}">{{ $sale['display'] }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-shopping-cart text-gray-400"></i>
                        </div>
                    </div>
                    @error('saleId')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Jumlah Pembayaran --}}
                <div class="space-y-2">
                    <label for="amount" class="form-label">
                        Jumlah Pembayaran
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="amount" wire:model="amount" step="0.01" min="0"
                            class="form-input pl-10" placeholder="0">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-semibold">Rp</span>
                        </div>
                    </div>
                    @error('amount')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Informasi Penjualan --}}
                @if ($saleId)
                    @php
                        $selectedSale = collect($sales)->firstWhere('id', $saleId);
                    @endphp
                    @if ($selectedSale)
                        <div
                            class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                <h4 class="font-semibold text-blue-900">Informasi Penjualan</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-barcode text-blue-600"></i>
                                    <span><strong>Kode:</strong> {{ $selectedSale['code'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                    <span><strong>Tanggal:</strong>
                                        {{ date('d/m/Y', strtotime($selectedSale['saleDate'])) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    <span><strong>Customer:</strong> {{ $selectedSale['userName'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave text-blue-600"></i>
                                    <span><strong>Total:</strong> Rp
                                        {{ number_format($selectedSale['totalAmount'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                    <span><strong>Sisa Tagihan:</strong> Rp
                                        {{ number_format($selectedSale['remainingAmount'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Catatan --}}
                <div class="lg:col-span-2 space-y-2">
                    <label for="notes" class="form-label">
                        Catatan
                    </label>
                    <div class="relative">
                        <textarea id="notes" wire:model="notes" rows="3" class="form-input pl-10"
                            placeholder="Catatan pembayaran (opsional)"></textarea>
                        <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                            <i class="fas fa-sticky-note text-gray-400"></i>
                        </div>
                    </div>
                    @error('notes')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('payments.index') }}"
                    class="btn-secondary flex items-center justify-center gap-2 order-2 sm:order-1">
                    <i class="fas fa-circle-arrow-left"></i>
                    Batal
                </a>
                <button type="submit" class="btn-primary flex items-center justify-center gap-2 order-1 sm:order-2"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Pembayaran
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <div class="spinner"></div>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
