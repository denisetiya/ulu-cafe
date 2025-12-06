<x-layout>
    <section class="py-20 container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Orders -->
            <div class="bg-[var(--color-dark-card)] rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-6 text-[var(--color-primary)]">Pesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-700 text-gray-400">
                                <th class="pb-2">ID</th>
                                <th class="pb-2">Nama</th>
                                <th class="pb-2">Total</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-gray-700/50 last:border-0">
                                    <td class="py-3">#{{ $order->id }}</td>
                                    <td class="py-3">{{ $order->customer_name }}</td>
                                    <td class="py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <span class="bg-yellow-500/20 text-yellow-500 px-2 py-1 rounded text-xs">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-[var(--color-dark-card)] rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-6 text-[var(--color-primary)]">Produk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-700 text-gray-400">
                                <th class="pb-2">Nama</th>
                                <th class="pb-2">Kategori</th>
                                <th class="pb-2">Harga</th>
                                <th class="pb-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b border-gray-700/50 last:border-0">
                                    <td class="py-3">{{ $product->name }}</td>
                                    <td class="py-3">{{ $product->category->name }}</td>
                                    <td class="py-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <button class="text-[var(--color-primary)] hover:underline">Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</x-layout>
