<x-dashboard-layout title="Edit Voucher">
    <div class="flex flex-col justify-center items-center p-6">
        <div class="bg-[var(--color-dark-card)] w-full max-w-2xl rounded-2xl shadow-2xl border border-white/10 flex flex-col">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-black/20">
                <h2 class="text-xl font-bold text-white">Edit Voucher</h2>
                <a href="{{ route('vouchers.index') }}" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
            
            <div class="p-6">
                <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kode Voucher (Unik)</label>
                        <input type="text" name="code" value="{{ $voucher->code }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white font-mono uppercase tracking-wider focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Tipe Potongan</label>
                            <div class="relative">
                                <select name="type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                    <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                    <option value="percent" {{ $voucher->type == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nilai Potongan</label>
                            <input type="number" name="amount" value="{{ $voucher->amount }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Minimal Pembelian (Rp)</label>
                        <input type="number" name="min_purchase" value="{{ $voucher->min_purchase }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-gray-700 hover:bg-white/5 transition">
                            <input type="checkbox" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-[var(--color-dark-input)]">
                            <div>
                                <span class="block font-bold">Status Aktif</span>
                                <span class="block text-xs text-gray-400">Voucher dapat digunakan segera setelah disimpan</span>
                            </div>
                        </label>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-white/10 bg-black/20 flex justify-end gap-3">
                <a href="{{ route('vouchers.index') }}" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" onclick="document.querySelector('form').submit()" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                    Update Voucher
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>
