<div class="fade-in">
    <div class="page-header">
        <h1 class="page-title">
            Edit User
        </h1>
        <p class="page-subtitle">
            Perbarui informasi user yang sudah ada dalam sistem
        </p>
    </div>

    <div class="card">
        <form class="card-body" wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- name --}}
                <div class="space-y-2 col-span-2">
                    <label for="name" class="form-label">
                        Nama Lengkap
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="name" wire:model="name" class="form-input pl-10"
                            placeholder="Masukkan nama lengkap">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    @error('name')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- email --}}
                <div class="space-y-2">
                    <label for="email" class="form-label">
                        Email
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" id="email" wire:model="email" class="form-input pl-10"
                            placeholder="Masukkan alamat email">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    @error('email')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- role --}}
                <div class="space-y-2">
                    <label for="role" class="form-label">
                        Role
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <select id="role" wire:model="role" class="form-input pl-10"
                            {{ auth()->user()->id === $user->id ? 'disabled' : '' }}>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tag text-gray-400"></i>
                        </div>
                    </div>
                    @error('role')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- password --}}
                <div class="space-y-2">
                    <label for="password" class="form-label">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password" wire:model="password" class="form-input pl-10"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    @error('password')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    <p class="text-gray-500 text-sm">Kosongkan jika tidak ingin mengubah password</p>
                </div>

                {{-- password confirmation --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="form-label">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" wire:model="password_confirmation"
                            class="form-input pl-10" placeholder="Ulangi password baru">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <div class="flex items-center gap-2 text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            {{-- action buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}"
                    class="btn btn-secondary flex items-center justify-center gap-2 order-2 sm:order-1">
                    <i class="fas fa-circle-arrow-left"></i>
                    <span>Kembali</span>
                </a>

                <button type="submit" class="btn btn-primary flex items-center justify-center gap-2 order-1 sm:order-2"
                    wire:loading.attr="disabled" wire:target="update">
                    <span wire:loading.remove wire:target="update" class="flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Perbarui User</span>
                    </span>
                    <span wire:loading wire:target="update" class="flex items-center gap-2">
                        <div class="spinner"></div>
                        <span>Memperbarui...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
