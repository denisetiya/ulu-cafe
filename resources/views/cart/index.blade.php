<x-layout>
    <section class="py-12 sm:py-20 container mx-auto px-4 sm:px-6">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8">Keranjang Belanja</h1>
        
        @if(session('success'))
            <div class="bg-[var(--color-primary)]/20 border border-[var(--color-primary)] text-[var(--color-primary)] px-4 py-3 rounded-lg mb-6 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-[var(--color-dark-card)] rounded-xl sm:rounded-2xl overflow-hidden p-4 sm:p-6 fade-up">
                        <!-- Desktop View -->
                        <div class="hidden md:block">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-700 text-gray-400 text-sm">
                                        <th class="pb-4">Produk</th>
                                        <th class="pb-4">Harga</th>
                                        <th class="pb-4">Jumlah</th>
                                        <th class="pb-4">Total</th>
                                        <th class="pb-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $details)
                                        <tr class="border-b border-gray-700/50 last:border-0">
                                            <td class="py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-16 h-16 bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                                        @if(isset($details['image']) && $details['image'])
                                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="font-bold">{{ $details['name'] }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 text-gray-300">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                            <td class="py-4">
                                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden">
                                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-14 bg-[var(--color-dark-input)] px-2 py-2 text-center text-white focus:outline-none">
                                                    </div>
                                                    <button type="submit" class="text-xs px-3 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition text-gray-300 hover:text-white">Update</button>
                                                </form>
                                            </td>
                                            <td class="py-4 font-bold text-[var(--color-primary)]">
                                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 text-right">
                                                <form action="{{ route('cart.remove') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <button type="submit" class="text-red-500 hover:text-red-400 p-2 hover:bg-red-500/10 rounded-lg transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View -->
                        <div class="md:hidden space-y-4">
                            @foreach($cart as $id => $details)
                                <div class="flex gap-3 sm:gap-4 border-b border-gray-700/50 pb-4 last:border-0 last:pb-0">
                                    <!-- Product Image -->
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-700 rounded-xl overflow-hidden flex-shrink-0">
                                        @if(isset($details['image']) && $details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start gap-2 mb-2">
                                            <h3 class="font-bold text-sm sm:text-base truncate">{{ $details['name'] }}</h3>
                                            <form action="{{ route('cart.remove') }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-red-500 hover:text-red-400 p-1.5 hover:bg-red-500/10 rounded-lg transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <div class="text-[var(--color-primary)] font-bold text-sm sm:text-base mb-3">
                                            Rp {{ number_format($details['price'], 0, ',', '.') }}
                                        </div>
                                        
                                        <div class="flex flex-wrap justify-between items-center gap-2">
                                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden">
                                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-12 bg-[var(--color-dark-input)] px-2 py-1.5 text-center text-white text-sm focus:outline-none">
                                                </div>
                                                <button type="submit" class="text-xs px-2.5 py-1.5 bg-gray-700 hover:bg-gray-600 rounded-lg transition text-gray-300">Update</button>
                                            </form>
                                            <span class="text-xs sm:text-sm font-bold text-gray-300">
                                                Total: <span class="text-[var(--color-primary)]">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-[var(--color-dark-card)] rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:sticky lg:top-24">
                        <h2 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm sm:text-base text-gray-300">
                                <span>Subtotal ({{ count($cart) }} item)</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-700 pt-4 mb-6">
                            <div class="flex justify-between text-lg sm:text-xl font-bold">
                                <span>Total</span>
                                <span class="text-[var(--color-primary)]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('checkout') }}" class="block w-full bg-[var(--color-primary)] text-white text-center py-3 sm:py-3.5 rounded-xl font-bold hover:brightness-110 transition text-sm sm:text-base">
                            Lanjut ke Checkout
                        </a>
                        
                        <a href="{{ route('menu.index') }}" class="block w-full text-center py-3 text-gray-400 hover:text-white transition text-sm mt-3">
                            ← Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16 sm:py-20">
                <div class="w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-6 bg-gray-800 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold mb-2">Keranjang Kosong</h2>
                <p class="text-gray-400 text-sm sm:text-base mb-6 max-w-md mx-auto">Belum ada produk di keranjang Anda. Yuk, mulai belanja dan temukan menu favorit!</p>
                <a href="{{ route('menu.index') }}" class="inline-block bg-[var(--color-primary)] text-white px-6 sm:px-8 py-3 rounded-xl font-bold hover:brightness-110 transition text-sm sm:text-base">
                    Lihat Menu
                </a>
            </div>
        @endif
    </section>
</x-layout>

