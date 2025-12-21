<x-dashboard-layout>
    <x-slot name="title">Riwayat Pesanan</x-slot>

    <!-- Custom Scrollbar Styles -->
    <style>
        .custom-table-scroll::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }
        .custom-table-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        .custom-table-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .custom-table-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    <!-- Scrollable Table -->
    <div class="bg-[var(--color-dark-card)] rounded-xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left min-w-[700px]">
                <thead class="bg-white/5 text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Waktu</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Order ID</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Meja</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Detail Pesanan</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Total</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    @forelse($orders as $order)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-gray-400 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-white">{{ $order->updated_at->format('d M Y') }}</span>
                                    <span class="text-xs">{{ $order->updated_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 font-mono text-gray-400 whitespace-nowrap">#{{ substr($order->id, -6) }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold whitespace-nowrap">Meja {{ $order->table_number }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <ul class="space-y-1">
                                    @foreach($order->items as $item)
                                        <li class="flex gap-2 text-gray-300">
                                            <span class="text-[var(--color-primary)] font-bold">{{ $item->quantity }}x</span>
                                            <span>{{ $item->product ? $item->product->name : 'Item Dihapus' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($order->notes)
                                    <p class="text-xs text-yellow-500 mt-1 italic">"{{ $order->notes }}"</p>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @if($order->status == 'delivered')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Selesai
                                    </span>
                                @elseif($order->status == 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500 border border-red-500/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="uppercase font-bold text-xs">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3 opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <p>Belum ada riwayat pesanan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="p-4 border-t border-white/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
