<x-dashboard-layout title="Owner Dashboard - Statistik Penjualan">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-[var(--color-primary)]">
            <h3 class="text-gray-400 mb-2">Penjualan Hari Ini</h3>
            <p class="text-3xl font-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-blue-500">
            <h3 class="text-gray-400 mb-2">Penjualan Bulan Ini</h3>
            <p class="text-3xl font-bold">Rp {{ number_format($monthlySales, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[var(--color-dark-card)] p-6 rounded-2xl border-l-4 border-yellow-500">
            <h3 class="text-gray-400 mb-2">Total Transaksi</h3>
            <p class="text-3xl font-bold">{{ $totalOrders }}</p>
        </div>
    </div>

    <div class="bg-[var(--color-dark-card)] rounded-2xl p-8">
        <h2 class="text-xl font-bold mb-6">Menu Terlaris</h2>
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
</x-dashboard-layout>
