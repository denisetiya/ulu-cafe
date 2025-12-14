<x-layout>
    <section class="py-20 container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>
        
        @if(session('success'))
            <div class="bg-[var(--color-primary)]/20 border border-[var(--color-primary)] text-[var(--color-primary)] px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-[var(--color-dark-card)] rounded-2xl overflow-hidden p-6 fade-up">
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
                                                    <div class="w-16 h-16 bg-gray-700 rounded overflow-hidden">
                                                        <img src="https://placehold.co/100x100/333/white?text={{ $details['name'] }}" class="w-full h-full object-cover">
                                                    </div>
                                                    <span class="font-bold">{{ $details['name'] }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                            <td class="py-4">
                                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 bg-[var(--color-dark-input)] border border-gray-700 rounded px-2 py-1 text-center text-white focus:outline-none focus:border-[var(--color-primary)]">
                                                    <button type="submit" class="text-xs text-gray-400 hover:text-white">Update</button>
                                                </form>
                                            </td>
                                            <td class="py-4 font-bold text-[var(--color-primary)]">
                                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 text-right">
                                                 <form action="{{ route('cart.remove') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <button type="submit" class="text-red-500 hover:text-red-400">
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
                         <div class="md:hidden space-y-6">
                            @foreach($cart as $id => $details)
                                <div class="flex gap-4 border-b border-gray-700 pb-6 last:border-0 last:pb-0">
                                    <div class="w-20 h-20 bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="https://placehold.co/100x100/333/white?text={{ $details['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-bold text-lg">{{ $details['name'] }}</h3>
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-red-500 hover:text-red-400 p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="text-[var(--color-primary)] font-bold mb-3">Rp {{ number_format($details['price'], 0, ',', '.') }}</div>
                                        <div class="flex justify-between items-center">
                                             <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-14 bg-[var(--color-dark-input)] border border-gray-700 rounded px-2 py-1 text-center text-white focus:outline-none focus:border-[var(--color-primary)]">
                                                <button type="submit" class="text-xs text-gray-400 hover:text-white">Update</button>
                                            </form>
                                            <span class="text-sm font-bold text-gray-300">
                                                Total: Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                         </div>
                    </div>
                </div>
                <div class="lg:w-1/3">
                    <div class="bg-[var(--color-dark-card)] rounded-2xl p-6">
                        <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>
                        <div class="flex justify-between mb-4 text-gray-300">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="border-t border-gray-700 pt-4 flex justify-between text-xl font-bold mb-8">
                            <span>Total</span>
                            <span class="text-[var(--color-primary)]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="block w-full bg-[var(--color-primary)] text-white text-center py-3 rounded-full font-bold hover:bg-opacity-90 transition">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl mb-6">Keranjang belanja Anda kosong.</p>
                <a href="{{ route('home') }}" class="inline-block bg-[var(--color-primary)] text-white px-8 py-3 rounded-full font-bold hover:bg-opacity-90 transition">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </section>
</x-layout>
