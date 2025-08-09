<div>
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 bg-black rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-lock text-2xl text-white"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Login</h2>
        <p class="text-gray-600">Silakan masuk untuk mengakses sistem</p>
    </div>

    {{-- Login Form --}}
    <div class="bg-white py-8 px-6 shadow-lg rounded-lg border border-gray-200">
        <form wire:submit.prevent="login" class="space-y-6">
            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input wire:model="email" type="email" id="email"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda" autocomplete="email">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input wire:model="password" type="password" id="password"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password Anda" autocomplete="current-password">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox"
                        class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading class="flex items-center">
                        <div class="spinner -ml-1 mr-3"></div>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</div>
