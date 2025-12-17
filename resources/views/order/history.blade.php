<x-layout>
    <section class="py-20 container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-8">Riwayat Pesanan</h1>
        
        @if($orders->isEmpty())
            <div class="text-center py-12 bg-[var(--color-dark-card)] rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h2 class="text-xl font-bold text-gray-300 mb-2">Belum ada pesanan</h2>
                <p class="text-gray-500 mb-6">Anda belum pernah melakukan pemesanan di sini.</p>
                <a href="{{ route('home') }}" class="bg-[var(--color-primary)] text-white px-6 py-3 rounded-full font-bold hover:bg-opacity-90 transition">
                    Pesan Sekarang
                </a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($orders as $order)
                    <div class="bg-[var(--color-dark-card)] p-6 rounded-xl border border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-bold text-lg">Order #{{ $order->id }}</span>
                                @if($order->payment_status == 'paid')
                                    <span class="bg-green-500/20 text-green-500 text-xs px-3 py-1 rounded-full font-bold uppercase">Lunas</span>
                                @elseif($order->payment_status == 'unpaid')
                                    <span class="bg-yellow-500/20 text-yellow-500 text-xs px-3 py-1 rounded-full font-bold uppercase">Belum Bayar</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="bg-red-500/20 text-red-500 text-xs px-3 py-1 rounded-full font-bold uppercase">Gagal</span>
                                @elseif($order->payment_status == 'expired')
                                    <span class="bg-gray-500/20 text-gray-500 text-xs px-3 py-1 rounded-full font-bold uppercase">Kadaluarsa</span>
                                @endif
                                <span class="text-gray-500 text-sm">{{ $order->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="text-gray-400 text-sm mb-1">
                                Meja: <span class="text-white font-semibold">{{ $order->table_number }}</span>
                            </div>
                            <div class="text-gray-400 text-sm mb-2">
                                Total: <span class="text-[var(--color-primary)] font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Status Pesanan --}}
                            @if($order->payment_status == 'paid')
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-gray-400 text-sm">Status Pesanan:</span>
                                    @if($order->status == 'pending')
                                        <span class="flex items-center gap-1 bg-blue-500/20 text-blue-400 text-xs px-3 py-1 rounded-full font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu Konfirmasi
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="flex items-center gap-1 bg-orange-500/20 text-orange-400 text-xs px-3 py-1 rounded-full font-bold animate-pulse">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                                            Sedang Dimasak
                                        </span>
                                    @elseif($order->status == 'ready')
                                        <span class="flex items-center gap-1 bg-green-500/20 text-green-400 text-xs px-3 py-1 rounded-full font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Diantarkan
                                        </span>
                                    @elseif($order->status == 'delivered')
                                        <span class="flex items-center gap-1 bg-gray-500/20 text-gray-400 text-xs px-3 py-1 rounded-full font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Selesai
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="flex items-center gap-1 bg-red-500/20 text-red-400 text-xs px-3 py-1 rounded-full font-bold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-3 w-full md:w-auto">
                             @if($order->payment_status == 'unpaid')
                                <a href="{{ route('order.payment', $order->id) }}" class="flex-1 md:flex-none text-center bg-[var(--color-primary)] text-white px-6 py-2 rounded-lg font-bold hover:bg-opacity-90 transition">
                                    Bayar Sekarang
                                </a>
                            @elseif($order->payment_status == 'paid')
                                <a href="{{ route('order.success', $order->id) }}" class="flex-1 md:flex-none text-center border border-gray-600 text-gray-300 px-6 py-2 rounded-lg font-bold hover:bg-gray-800 transition">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
