<x-dashboard-layout title="Owner Dashboard - Statistik & Withdraw">
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-[var(--color-primary)]">
            <h3 class="text-gray-400 mb-2">Penjualan Hari Ini</h3>
            <p class="text-2xl font-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-blue-500">
            <h3 class="text-gray-400 mb-2">Penjualan Bulan Ini</h3>
            <p class="text-2xl font-bold">Rp {{ number_format($monthlySales, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-yellow-500">
            <h3 class="text-gray-400 mb-2">Total Transaksi</h3>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-purple-500">
            <h3 class="text-gray-400 mb-2">💰 Saldo Midtrans</h3>
            <p class="text-2xl font-bold text-[var(--color-primary)]">Rp {{ number_format($irisBalance, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Withdraw Form -->
        <div class="bg-[var(--color-dark-card)] rounded-2xl p-6" x-data="{ showForm: false }">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">💸 Withdraw ke Rekening</h2>
                <button @click="showForm = !showForm" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition">
                    <span x-text="showForm ? 'Tutup' : 'Withdraw'"></span>
                </button>
            </div>

            <div x-show="showForm" x-transition class="space-y-4">
                <form action="{{ route('owner.withdraw') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Jumlah Withdraw (Min. Rp 10.000)</label>
                        <input type="number" name="amount" min="10000" step="1000" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent" 
                            placeholder="100000" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Bank Tujuan</label>
                        <select name="bank_code" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)]" required>
                            <option value="">Pilih Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nomor Rekening</label>
                        <input type="text" name="account_number" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent" 
                            placeholder="1234567890" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Pemilik Rekening</label>
                        <input type="text" name="account_name" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent" 
                            placeholder="John Doe" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent" 
                            placeholder="Keterangan withdraw..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-[var(--color-primary)] text-black font-bold py-3 rounded-lg hover:brightness-110 transition">
                        Proses Withdraw
                    </button>
                </form>
            </div>

            <div x-show="!showForm" class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4">
                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                </svg>
                <p>Klik tombol "Withdraw" untuk menarik saldo</p>
            </div>
        </div>

        <!-- Menu Terlaris -->
        <div class="bg-[var(--color-dark-card)] rounded-2xl p-6">
            <h2 class="text-xl font-bold mb-6">🏆 Menu Terlaris</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700 text-gray-400">
                            <th class="pb-4">Nama Menu</th>
                            <th class="pb-4">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bestSellers as $item)
                            <tr class="border-b border-gray-700/50 last:border-0">
                                <td class="py-4 font-bold">{{ $item->name }}</td>
                                <td class="py-4">{{ $item->total_sold }} Porsi</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-gray-400 text-center">Belum ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Withdrawal History -->
    <div class="bg-[var(--color-dark-card)] rounded-2xl p-6">
        <h2 class="text-xl font-bold mb-6">📜 Riwayat Withdraw</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-700 text-gray-400 text-sm">
                        <th class="pb-4">Tanggal</th>
                        <th class="pb-4">Jumlah</th>
                        <th class="pb-4">Bank</th>
                        <th class="pb-4">No. Rekening</th>
                        <th class="pb-4">Nama</th>
                        <th class="pb-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr class="border-b border-gray-700/50 last:border-0">
                            <td class="py-4 text-sm">{{ $w->created_at->format('d M Y H:i') }}</td>
                            <td class="py-4 font-bold">{{ $w->formatted_amount }}</td>
                            <td class="py-4 text-sm">{{ $w->bank_name }}</td>
                            <td class="py-4 font-mono text-sm">{{ $w->account_number }}</td>
                            <td class="py-4 text-sm">{{ $w->account_name }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $w->status_badge }}">
                                    {{ ucfirst($w->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-gray-400 text-center">Belum ada riwayat withdraw.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
