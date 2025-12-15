<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulu Coffee - Tempat Nongkrong Asik di Lampung</title>
    <meta name="description" content="Nikmati kopi terbaik dan suasana nyaman di Ulu Coffee Lampung. Tempat nongkrong asik dengan menu lezat, harga terjangkau, dan fasilitas lengkap. Kunjungi kami di ulucoffee.com.">
    <meta name="keywords" content="cafe, ulu coffee, ulucoffee, cafe lampung, tempat nongkrong lampung, kopi lampung, cafe murah, coffee shop lampung, kuliner lampung">
    <meta name="author" content="Ulu Coffee">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://ulucafe.cloud">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/logo-background.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/logo-background.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ulucafe.cloud/">
    <meta property="og:title" content="Ulu Coffee - Tempat Nongkrong Asik di Lampung">
    <meta property="og:description" content="Nikmati kopi terbaik dan suasana nyaman di Ulu Coffee Lampung. Menu lezat, harga terjangkau.">
    <meta property="og:image" content="{{ asset('images/hero-bg.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ulucafe.cloud/">
    <meta property="twitter:title" content="Ulu Coffee - Tempat Nongkrong Asik di Lampung">
    <meta property="twitter:description" content="Nikmati kopi terbaik dan suasana nyaman di Ulu Coffee Lampung.">
    <meta property="twitter:image" content="{{ asset('images/hero-bg.jpg') }}">

    <!-- Model Viewer -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        /* Mobile Optimization for Typography */
        @media (max-width: 640px) {
            h1 { font-size: 2.25rem !important; line-height: 2.5rem !important; }
            h2 { font-size: 1.875rem !important; line-height: 2.25rem !important; }
            .container { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
        }

        /* 3D Animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        @keyframes steam {
            0% { transform: translateY(0) scale(1); opacity: 0; }
            20% { opacity: 0.8; transform: translateY(-10px) scale(1.1); }
            50% { opacity: 0.6; transform: translateY(-25px) scale(1.3); }
            80% { opacity: 0.3; transform: translateY(-40px) scale(1.5); }
            100% { opacity: 0; transform: translateY(-60px) scale(1.8); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Global Animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
    </style>
    
    <!-- Alpine.js for interactive components -->
    <!-- Alpine is loaded via app.js -->
</head>
<body class="bg-[var(--color-dark-bg)] text-white font-sans antialiased">
    <header class="fixed w-full z-50 bg-[#0a0a0a]/80 backdrop-blur-xl transition-all duration-300">
        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-primary/50 to-transparent opacity-80"></div>
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <a href="/" class="text-xl font-bold flex items-center gap-2">
                    <span class="text-[var(--color-primary)]">ULU</span> COFFEE
                </a>
                
                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-6 text-sm text-gray-300">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                    <a href="{{ route('menu.index') }}" class="hover:text-white transition">Menu</a>
                    <a href="{{ route('home') }}#gallery" class="hover:text-white transition">Galeri</a>
                    @auth
                        <a href="{{ route('order.history') }}" class="hover:text-white transition">Riwayat Pesanan</a>
                    @endauth
                    <a href="#" class="hover:text-white transition">Kontak</a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden" x-data="{ mobileMenuOpen: false }">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <!-- Mobile Menu Dropdown -->
                    <div x-show="mobileMenuOpen" 
                         @click.away="mobileMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute top-16 left-0 right-0 bg-[var(--color-dark-card)] border-b border-gray-700 shadow-xl p-4 flex flex-col space-y-4 z-50">
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white block py-2">Beranda</a>
                        <a href="{{ route('menu.index') }}" class="text-gray-300 hover:text-white block py-2">Menu</a>
                        <a href="{{ route('home') }}#gallery" class="text-gray-300 hover:text-white block py-2">Galeri</a>
                        @auth
                            <a href="{{ route('order.history') }}" class="text-gray-300 hover:text-white block py-2">Riwayat Pesanan</a>
                        @endauth
                        <a href="#" class="text-gray-300 hover:text-white block py-2">Kontak</a>
                    </div>
                </div>
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
            <p>&copy; {{ date('Y') }} ULU COFFEE. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.fade-up').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
