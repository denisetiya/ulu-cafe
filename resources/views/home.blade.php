<x-layout>
    <!-- Hero -->
    <section class="relative h-[600px] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[var(--color-dark-bg)] via-[var(--color-dark-bg)]/80 to-transparent"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6">
                Sajian Kuliner<br>
                <span class="text-[var(--color-primary)]">Segar & Hangat</span><br>
                dari ULU CAFE
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-xl">
                Nikmati hidangan lezat dari bahan berkualitas terbaik, disajikan langsung di meja Anda.
            </p>
            <button class="bg-[var(--color-primary)] text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-opacity-90 transition shadow-[0_0_20px_rgba(53,199,89,0.4)]">
                Pesan Sekarang
            </button>
        </div>
    </section>

    <!-- Business Lunch -->
    <section class="py-20 bg-[var(--color-dark-bg)]" id="menu">
        <div class="container mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Makan Siang Bisnis</h2>
                    <p class="text-gray-400">Menu spesial untuk menemani jam istirahat Anda</p>
                </div>
                <a href="#" class="text-[var(--color-primary)] hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($lunchProducts as $product)
                    <div class="bg-[var(--color-dark-card)] rounded-2xl overflow-hidden group hover:ring-1 hover:ring-[var(--color-primary)] transition duration-300 flex flex-col">
                        <div class="h-48 bg-gray-700 overflow-hidden relative">
                            <img src="https://placehold.co/400x300/333/white?text={{ $product->name }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-bold mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-400 text-sm mb-4 line-clamp-2 flex-1">{{ $product->description }}</p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-lg font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-8 h-8 rounded-full bg-[var(--color-primary)] flex items-center justify-center text-white hover:scale-110 transition shadow-lg shadow-green-500/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- WOK Builder -->
    <section class="py-20 bg-[var(--color-dark-card)]">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="md:w-1/3">
                    <h2 class="text-4xl font-bold mb-6">Racik WOK<br><span class="text-[var(--color-primary)]">Kreasi Sendiri</span></h2>
                    <p class="text-gray-400 mb-8">Pilih bahan dasar, saus favorit, sayuran segar, dan topping pilihanmu. Kami akan memasaknya dengan sempurna.</p>
                    <button class="bg-[var(--color-primary)] text-white px-8 py-3 rounded-full font-bold hover:bg-opacity-90 transition">
                        Mulai Racik
                    </button>
                </div>
                <div class="md:w-2/3">
                     <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                         <!-- Visual representation of ingredients -->
                         @foreach($wokIngredients['base'] ?? [] as $base)
                            <div class="bg-[var(--color-dark-bg)] p-4 rounded-xl text-center hover:ring-1 hover:ring-[var(--color-primary)] transition cursor-pointer">
                                <div class="w-16 h-16 mx-auto bg-gray-800 rounded-full mb-3 flex items-center justify-center text-xs text-gray-500 overflow-hidden">
                                     <img src="https://placehold.co/100x100/444/white?text=WOK" class="w-full h-full object-cover">
                                </div>
                                <p class="text-sm font-bold">{{ $base->name }}</p>
                                <p class="text-xs text-[var(--color-primary)] mt-1">Rp {{ number_format($base->price, 0, ',', '.') }}</p>
                            </div>
                         @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Desserts -->
    <section class="py-20 bg-[var(--color-dark-bg)]">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold mb-8 text-center">Dessert Penutup</h2>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($desserts as $product)
                    <div class="bg-[var(--color-dark-card)] rounded-2xl overflow-hidden group hover:ring-1 hover:ring-[var(--color-primary)] transition duration-300">
                         <div class="h-48 bg-gray-700 overflow-hidden relative">
                            <img src="https://placehold.co/400x300/333/white?text={{ $product->name }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-lg font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="px-4 py-2 rounded-full border border-[var(--color-primary)] text-[var(--color-primary)] text-sm font-bold hover:bg-[var(--color-primary)] hover:text-white transition">
                                        Tambah
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
