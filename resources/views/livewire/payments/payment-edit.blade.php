<div class="fade-in">
    <div class="page-header">
        <h1 class="page-title">
            Edit Pembayaran
        </h1>
        <p class="page-subtitle">
            Perbarui informasi pembayaran yang sudah ada
        </p>
    </div>

    <div class="card">
        <form class="card-body" wire:submit.prevent="update">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Kode Pembayaran --}}
                <div class="space-y-2">
                    <label for="code" class="form-label">
                        Kode Pembayaran
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="code" wire:model="code" class="form-input pl-10"
                                placeholder="Masukkan kode pembayaran">
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

                <div class="lg:col-span-2 space-y-2 relative">
                    <label class="form-label flex items-center gap-2">
                        Penjualan
                        <span class="text-red-500 text-xs">*</span>
                    </label>

                    <input type="hidden" wire:model="saleId" value="{{ $payment->sale_id }}">

                    <div class="relative">
                        <div class="form-input pl-10 bg-gray-50 cursor-not-allowed">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $payment->sale->code }}</span>
                                    <span class="text-gray-600">- {{ $payment->sale->user->name ?? 'no name' }}</span>
                                </div>
                                <div class="text-sm text-green-600 font-semibold">
                                    Sisa: Rp @rupiah($this->availableAmount)
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-shopping-cart text-gray-400"></i>
                        </div>
                    </div>
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
                    class="btn btn-secondary flex items-center justify-center gap-2 order-2 sm:order-1">
                    <i class="fas fa-arrow-left"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary flex items-center justify-center gap-2 order-1 sm:order-2"
                    wire:loading.attr="disabled" wire:target="update">
                    <span wire:loading.remove wire:target="update">
                        <i class="fas fa-save"></i>
                        Update Pembayaran
                    </span>
                    <span wire:loading wire:target="update" class="flex items-center gap-2">
                        <div class="spinner"></div>
                        Mengupdate...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
