<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ulu Coffee</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('logo/logo-background.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
</head>
<body class="bg-[var(--color-dark-bg)] text-white font-sans antialiased flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-[var(--color-dark-card)] border-r border-white/10 flex flex-col h-full fixed inset-y-0 left-0 z-30">
        <div class="p-6 flex items-center gap-2 border-b border-white/10">
             <span class="text-[var(--color-primary)] text-xl font-bold">ULU COFFEE</span>
             <span class="text-xs bg-gray-700 px-2 py-1 rounded text-gray-300 uppercase">{{ Auth::user()->role }}</span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                @if(Auth::user()->isOwner())
                    <li>
                        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('owner.dashboard') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            Dashboard
                        </a>
                    </li>
                @endif

                @if(Auth::user()->isCashier())
                    <li>
                        <a href="{{ route('cashier.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('cashier.dashboard') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cashier.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('cashier.history') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Riwayat Pesanan
                        </a>
                    </li>
                @endif

                <!-- Shared Menus for Owner and Cashier (as requested) -->
                <li class="pt-4 pb-2 px-4 text-xs font-bold text-gray-500 uppercase">Manajemen Konten</li>

                <li>
                    <a href="{{ route('banners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('banners.*') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/></svg>
                        Banner Promo
                    </a>
                </li>

                <li class="pt-4 pb-2 px-4 text-xs font-bold text-gray-500 uppercase">Manajemen Menu</li>
                
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('products.*') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        Daftar Makanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                        Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('vouchers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('vouchers.*') ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)]' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                        Promo / Voucher
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-500 w-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-[var(--color-dark-bg)] ml-64">
        <header class="bg-[var(--color-dark-card)] border-b border-white/10 py-4 px-8 flex justify-between items-center sticky top-0 z-20">
            <h1 class="text-xl font-bold">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-400">Halo, {{ Auth::user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-gray-700 overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    @endif
                </div>
            </div>
        </header>
        
        <div class="p-8">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
