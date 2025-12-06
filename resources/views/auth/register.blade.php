<x-layout>
    <div class="min-h-[80vh] flex items-center justify-center container mx-auto px-6 py-20">
        <div class="bg-[var(--color-dark-card)] p-8 rounded-2xl w-full max-w-md shadow-2xl border border-white/5">
            <h2 class="text-3xl font-bold mb-8 text-center">Daftar Akun</h2>

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                </div>
                
                <button type="submit" class="w-full bg-[var(--color-primary)] text-white font-bold py-3 rounded-full hover:bg-opacity-90 transition shadow-lg shadow-green-500/30">
                    Daftar
                </button>
            </form>

            <div class="mt-8">
                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-gray-700"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-sm">Atau daftar dengan</span>
                    <div class="flex-grow border-t border-gray-700"></div>
                </div>

                <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white text-black font-bold py-3 rounded-full hover:bg-gray-100 transition" style="color: black !important;">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.26.81-.58z"/>
                        <path fill="#EA4335" d="M12 4.36c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 1.09 14.97 0 12 0 7.7 0 3.99 2.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google
                </a>
            </div>

            <p class="text-center text-gray-400 mt-8 text-sm">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[var(--color-primary)] hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</x-layout>
