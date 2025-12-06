<x-dashboard-layout title="Daftar Menu Makanan">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Menu</h2>
        <a href="{{ route('products.create') }}" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition">
            + Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-[var(--color-dark-card)] rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-800 text-gray-400">
                <tr>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($products as $product)
                    <tr>
                        <td class="p-4">{{ $product->name }}</td>
                        <td class="p-4">{{ $product->category->name }}</td>
                        <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4 flex gap-3">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dashboard-layout>
