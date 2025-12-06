<x-dashboard-layout title="Pesanan Masuk (Dapur/Kasir)">
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border-l-4 {{ $order->status == 'pending' ? 'border-yellow-500' : ($order->status == 'processing' ? 'border-blue-500' : 'border-green-500') }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold">Meja #{{ $order->table_number }}</h2>
                        <p class="text-sm text-gray-400">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-400">{{ $order->customer_name }}</p>
                    </div>
                    <span class="px-3 py-1 rounded text-xs font-bold uppercase {{ $order->status == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : ($order->status == 'processing' ? 'bg-blue-500/20 text-blue-500' : 'bg-green-500/20 text-green-500') }}">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="border-t border-b border-gray-700 py-4 my-4">
                    <ul class="space-y-2">
                        @foreach($order->items as $item)
                            <li class="flex justify-between text-sm">
                                <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($order->notes)
                        <div class="mt-4 bg-gray-800 p-3 rounded text-sm text-yellow-200">
                            <strong>Catatan:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>

                <div class="flex gap-2 mt-4">
                    @if($order->status == 'pending')
                        <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-bold transition">
                                Proses Masak
                            </button>
                        </form>
                    @elseif($order->status == 'processing')
                        <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-bold transition">
                                Siap Saji
                            </button>
                        </form>
                    @elseif($order->status == 'ready')
                        <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-bold transition">
                                Selesai / Diantar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <p class="text-xl">Tidak ada pesanan aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</x-dashboard-layout>
