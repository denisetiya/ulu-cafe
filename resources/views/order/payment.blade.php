<x-layout>
    <div class="container mx-auto py-20 px-6">
        <div class="max-w-2xl mx-auto bg-[var(--color-dark-card)] rounded-2xl p-8 text-center">
            <h1 class="text-2xl font-bold mb-4 text-white">Menunggu Pembayaran</h1>
            <p class="text-gray-400 mb-8">Silakan selesaikan pembayaran Anda.</p>

            @php
                $paymentInfo = json_decode($order->payment_info, true);
            @endphp

            @if(isset($paymentInfo['type']) && $paymentInfo['type'] == 'qris')
                <div class="bg-white p-4 rounded-xl inline-block mb-6">
                    @if(isset($paymentInfo['qr_url']))
                         <img src="{{ $paymentInfo['qr_url'] }}" alt="QRIS Code" class="w-64 h-64">
                    @elseif(isset($paymentInfo['qr_string']))
                         <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($paymentInfo['qr_string']) }}" alt="QRIS Code" class="w-64 h-64">
                    @endif
                </div>
                <p class="text-white font-bold mb-2">Scan QRIS di atas</p>
                <p class="text-sm text-gray-400">Dukungan: GoPay, ShopeePay, OVO, Dana, LinkAja, Mobile Banking</p>
            
            @elseif(isset($paymentInfo['type']) && $paymentInfo['type'] == 'bank_transfer')
                <div class="mb-8">
                    <p class="text-gray-400 mb-2">Bank Tujuan</p>
                    <p class="text-2xl font-bold text-white uppercase">{{ $paymentInfo['bank'] }}</p>
                </div>
                <div class="mb-8">
                    <p class="text-gray-400 mb-2">Nomor Virtual Account</p>
                    <div class="flex justify-center items-center gap-4">
                        <p class="text-3xl font-bold text-[var(--color-primary)]">{{ $paymentInfo['va_number'] }}</p>
                        <button onclick="navigator.clipboard.writeText('{{ $paymentInfo['va_number'] }}')" class="text-gray-500 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-4 text-left">
                    <p class="text-sm text-gray-300 mb-2">Cara Pembayaran:</p>
                    <ul class="list-disc list-inside text-sm text-gray-400 space-y-1">
                        <li>Buka aplikasi Mobile Banking Anda</li>
                        <li>Pilih menu Transfer Virtual Account</li>
                        <li>Masukkan nomor VA di atas</li>
                        <li>Pastikan nominal sesuai: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                    </ul>
                </div>
            @endif

            <div class="mt-10 space-y-4">
                 <a href="{{ route('order.success', $order->id) }}" class="block w-full bg-[var(--color-primary)] text-white font-bold py-4 rounded-full hover:bg-opacity-90 transition">
                    Cek Status Pembayaran
                </a>
                <a href="{{ route('home') }}" class="block w-full border border-gray-600 text-gray-300 font-bold py-4 rounded-full hover:bg-gray-800 transition">
                    Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</x-layout>
