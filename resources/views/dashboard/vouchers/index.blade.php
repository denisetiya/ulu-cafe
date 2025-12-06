<x-dashboard-layout title="Daftar Voucher">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Voucher Promo</h2>
        <a href="{{ route('vouchers.create') }}" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition">
            + Tambah Voucher
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
                    <th class="p-4">Kode</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Nilai</th>
                    <th class="p-4">Min. Pembelian</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($vouchers as $voucher)
                    <tr>
                        <td class="p-4 font-mono font-bold text-[var(--color-primary)]">{{ $voucher->code }}</td>
                        <td class="p-4 uppercase text-xs">{{ $voucher->type }}</td>
                        <td class="p-4">{{ $voucher->type == 'fixed' ? 'Rp ' . number_format($voucher->amount, 0, ',', '.') : $voucher->amount . '%' }}</td>
                        <td class="p-4">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs {{ $voucher->is_active ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500' }}">
                                {{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="p-4 flex gap-3">
                            <a href="{{ route('vouchers.edit', $voucher->id) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
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
