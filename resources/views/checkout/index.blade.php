<x-layout>
    <section class="py-20 container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-8">Checkout (Dine-in)</h1>
        
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col lg:flex-row gap-8" id="checkout-form">
            @csrf
            <div class="lg:w-2/3">
                <div class="bg-[var(--color-dark-card)] rounded-2xl p-8 mb-8">
                    <h2 class="text-xl font-bold mb-6">Informasi Meja</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Pemesan</label>
                            <input type="text" name="customer_name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nomor Meja</label>
                            <input type="text" name="table_number" required class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" placeholder="Contoh: 12">
                        </div>
                         <div>
                            <label class="block text-sm text-gray-400 mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" placeholder="Contoh: Jangan terlalu pedas"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="bg-[var(--color-dark-card)] rounded-2xl p-8">
                    <h2 class="text-xl font-bold mb-6">Metode Pembayaran</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 transition bg-[var(--color-dark-input)]">
                            <input type="radio" name="payment_method" value="qris" checked class="text-[var(--color-primary)] focus:ring-[var(--color-primary)] w-5 h-5">
                            <div class="flex-1">
                                <div class="font-bold text-white">QRIS (Gopay, ShopeePay, dll)</div>
                                <div class="text-sm text-gray-400">Scan QR code untuk membayar</div>
                            </div>
                        </label>
                        
                        <div class="text-sm text-gray-400 mt-4 mb-2 font-semibold">Transfer Bank (Virtual Account)</div>
                        
                        <label class="flex items-center gap-3 p-4 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 transition bg-[var(--color-dark-input)]">
                            <input type="radio" name="payment_method" value="bank_transfer_bca" class="text-[var(--color-primary)] focus:ring-[var(--color-primary)] w-5 h-5">
                            <div class="font-bold text-white">BCA Virtual Account</div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 transition bg-[var(--color-dark-input)]">
                            <input type="radio" name="payment_method" value="bank_transfer_bni" class="text-[var(--color-primary)] focus:ring-[var(--color-primary)] w-5 h-5">
                            <div class="font-bold text-white">BNI Virtual Account</div>
                        </label>
        
                        <label class="flex items-center gap-3 p-4 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 transition bg-[var(--color-dark-input)]">
                            <input type="radio" name="payment_method" value="bank_transfer_bri" class="text-[var(--color-primary)] focus:ring-[var(--color-primary)] w-5 h-5">
                            <div class="font-bold text-white">BRI Virtual Account</div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/3">
                 <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6">Ringkasan</h2>
                    <ul class="mb-6 space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cart as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Voucher Section -->
                    <div class="border-t border-gray-700 py-4">
                        <label class="block text-sm text-gray-400 mb-2">Kode Voucher</label>
                        <div class="flex gap-2">
                            <input type="text" id="voucher_code" name="voucher_code" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-[var(--color-primary)] uppercase" placeholder="Kode Promo">
                            <button type="button" id="apply-voucher" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-600 transition">Pakai</button>
                        </div>
                        <p id="voucher-message" class="text-xs mt-2 hidden"></p>
                    </div>

                    <div class="border-t border-gray-700 pt-4 space-y-2">
                        <div class="flex justify-between text-gray-300">
                            <span>Subtotal</span>
                            <span id="subtotal-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[var(--color-primary)] hidden" id="discount-row">
                            <span>Diskon</span>
                            <span id="discount-display">-Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold pt-4 text-white">
                            <span>Total</span>
                            <span id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[var(--color-primary)] text-white font-bold py-4 rounded-full mt-8 hover:bg-opacity-90 transition">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
    </section>

    <script>
        const applyVoucherBtn = document.getElementById('apply-voucher');
        const voucherInput = document.getElementById('voucher_code');
        const voucherMessage = document.getElementById('voucher-message');
        const discountRow = document.getElementById('discount-row');
        const discountDisplay = document.getElementById('discount-display');
        const totalDisplay = document.getElementById('total-display');
        
        applyVoucherBtn.onclick = function() {
            const code = voucherInput.value;
            if(!code) return;

            fetch('{{ route('vouchers.check') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code, total: {{ $total }} })
            })
            .then(response => response.json())
            .then(data => {
                if(data.valid) {
                    voucherMessage.textContent = "Voucher berhasil dipasang!";
                    voucherMessage.className = "text-xs mt-2 text-green-500";
                    voucherMessage.classList.remove('hidden');
                    
                    discountRow.classList.remove('hidden');
                    discountDisplay.textContent = "-Rp " + new Intl.NumberFormat('id-ID').format(data.discount_amount);
                    totalDisplay.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.new_total);
                } else {
                    voucherMessage.textContent = data.message;
                    voucherMessage.className = "text-xs mt-2 text-red-500";
                    voucherMessage.classList.remove('hidden');
                    
                    discountRow.classList.add('hidden');
                    totalDisplay.textContent = "Rp {{ number_format($total, 0, ',', '.') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        };
    </script>
</x-layout>
