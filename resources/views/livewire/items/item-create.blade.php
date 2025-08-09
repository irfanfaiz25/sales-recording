<div class="fade-in">
    <div class="page-header">
        <h1 class="page-title">
            Tambah Item Baru
        </h1>
        <p class="page-subtitle">
            Lengkapi informasi item yang akan ditambahkan ke dalam sistem
        </p>
    </div>

    <div class="card">

        <form class="card-body" wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- code --}}
                <div class="space-y-2">
                    <label for="code" class="form-label">
                        Kode Item
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="code" wire:model="code" class="form-input pl-10" readonly
                                placeholder="Kode akan digenerate otomatis">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
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

                {{-- name --}}
                <div class="space-y-2">
                    <label for="name" class="form-label">
                        Nama Item
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="name" wire:model="name" class="form-input pl-10"
                            placeholder="Masukkan nama item">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-cube text-gray-400"></i>
                        </div>
                    </div>
                    @error('name')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- price --}}
                <div class="space-y-2">
                    <label for="price" class="form-label">
                        Harga
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="price" wire:model="price" class="form-input pl-10" step="0.01"
                            min="0" placeholder="0.00">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold">Rp</span>
                        </div>
                    </div>
                    @error('price')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- image --}}
                <div class="space-y-2">
                    <label for="image" class="form-label">
                        Gambar Item
                    </label>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="file" id="image" wire:model="image" class="form-input" accept="image/*">
                            <div wire:loading wire:target="image"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <div class="spinner"></div>
                            </div>
                        </div>

                        @error('image')
                            <div class="flex items-center gap-2 text-red-500 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        {{-- preview image --}}
                        @if ($image)
                            <div class="relative inline-block">
                                <div class="bg-gradient-to-br from-gray-100 to-gray-200 p-4 rounded-2xl shadow-lg">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="w-40 h-40 object-cover rounded-xl border-4 border-white shadow-md">
                                    <button type="button" wire:click="$set('image', null)"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110"
                                        title="Hapus gambar">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-sm text-gray-600 font-medium">
                                        Preview Gambar
                                    </span>
                                </div>
                            </div>
                        @else
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="space-y-3">
                                    <div
                                        class="mx-auto w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">
                                            Belum ada gambar dipilih
                                        </p>
                                        <p class="text-gray-400 text-sm">
                                            Upload gambar untuk preview
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('items.index') }}"
                    class="btn btn-secondary flex items-center justify-center gap-2 order-2 sm:order-1">
                    <i class="fas fa-circle-arrow-left"></i>
                    <span>Kembali</span>
                </a>

                <button type="submit" class="btn btn-primary flex items-center justify-center gap-2 order-1 sm:order-2"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Item</span>
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <div class="spinner"></div>
                        <span>Menyimpan...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
