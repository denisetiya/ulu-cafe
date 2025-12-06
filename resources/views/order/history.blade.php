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
                            <div class="text-gray-400 text-sm">
                                Total: <span class="text-[var(--color-primary)] font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
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
