<x-dashboard-layout title="Tambah Voucher">
    <div class="max-w-2xl">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-xl">
            <form action="{{ route('vouchers.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kode Voucher (Unik)</label>
                    <input type="text" name="code" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] uppercase" placeholder="CONTOH: DISKON50" required>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Tipe Potongan</label>
                        <select name="type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                            <option value="fixed">Nominal (Rp)</option>
                            <option value="percent">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nilai Potongan</label>
                        <input type="number" name="amount" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Minimal Pembelian (Rp)</label>
                    <input type="number" name="min_purchase" value="0" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-700 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-gray-800">
                        <span>Status Aktif</span>
                    </label>
                </div>
                
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-90 transition">Simpan Voucher</button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
