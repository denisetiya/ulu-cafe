<x-dashboard-layout title="Edit Voucher">
    <div class="max-w-2xl">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-xl">
            <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kode Voucher (Unik)</label>
                    <input type="text" name="code" value="{{ $voucher->code }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] uppercase" required>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Tipe Potongan</label>
                        <select name="type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                            <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                            <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nilai Potongan</label>
                        <input type="number" name="amount" value="{{ $voucher->amount }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Minimal Pembelian (Rp)</label>
                    <input type="number" name="min_purchase" value="{{ $voucher->min_purchase }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-700 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-gray-800">
                        <span>Status Aktif</span>
                    </label>
                </div>
                
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-90 transition">Update Voucher</button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
