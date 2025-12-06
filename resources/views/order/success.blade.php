<x-layout>
    <section class="py-20 container mx-auto px-6 text-center">
        <div class="bg-[var(--color-dark-card)] rounded-2xl p-12 max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-4">Pesanan Berhasil!</h1>
            <p class="text-gray-400 mb-8">Terima kasih telah memesan di ULU CAFE. Pesanan Anda akan segera diproses oleh dapur kami dan diantar ke meja nomor <span class="text-white font-bold">{{ $order->table_number }}</span>.</p>
            
            <div class="bg-[var(--color-dark-bg)] p-6 rounded-xl mb-8 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-400">ID Pesanan</span>
                    <span class="font-bold">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-400">Status Pembayaran</span>
                    <span class="text-green-500 font-bold uppercase">{{ $order->payment_status }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total</span>
                    <span class="font-bold text-[var(--color-primary)]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('home') }}" class="inline-block bg-[var(--color-primary)] text-white px-8 py-3 rounded-full font-bold hover:bg-opacity-90 transition">
                Pesan Lagi
            </a>
        </div>
    </section>
</x-layout>
