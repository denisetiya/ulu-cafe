<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Delivery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
</head>
<body class="bg-[var(--color-dark-bg)] text-white font-sans antialiased">
    <header class="fixed w-full z-50 bg-[var(--color-dark-bg)]/90 backdrop-blur-md border-b border-white/10">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <a href="/" class="text-xl font-bold flex items-center gap-2">
                    <span class="text-[var(--color-primary)]">ULU</span> CAFE
                </a>
                <nav class="hidden md:flex space-x-6 text-sm text-gray-300">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                    <a href="{{ route('home') }}#menu" class="hover:text-white transition">Menu</a>
                    @auth
                        <a href="{{ route('order.history') }}" class="hover:text-white transition">Riwayat Pesanan</a>
                    @endauth
                    <a href="#" class="hover:text-white transition">Kontak</a>
                </nav>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="relative group">
                    <div class="w-10 h-10 rounded-full bg-[var(--color-dark-card)] flex items-center justify-center group-hover:bg-[var(--color-primary)] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    @php
                        $cartCount = 0;
                        if(Auth::check()) {
                            $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                        } else {
                            $cart = session('cart', []);
                            foreach($cart as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-[var(--color-primary)] text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="w-10 h-10 rounded-full bg-[var(--color-dark-card)] overflow-hidden hover:ring-2 hover:ring-[var(--color-primary)] transition flex items-center justify-center focus:outline-none">
                             @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                             @else
                                <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                             @endif
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-[var(--color-dark-card)] rounded-lg shadow-xl py-2 border border-gray-700 z-50" 
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-700">
                                <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('order.history') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">Riwayat Pesanan</a>
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner' || Auth::user()->role === 'cashier')
                                @if(Auth::user()->role === 'owner')
                                    <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">Owner Dashboard</a>
                                @elseif(Auth::user()->role === 'cashier')
                                    <a href="{{ route('cashier.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">Cashier Dashboard</a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">Admin Dashboard</a>
                                @endif
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-700">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="w-10 h-10 rounded-full bg-[var(--color-dark-card)] flex items-center justify-center hover:bg-[var(--color-primary)] transition" title="Login">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <footer class="bg-[var(--color-dark-card)] mt-20 py-10 border-t border-white/5">
        <div class="container mx-auto px-6 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} ULU CAFE. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
