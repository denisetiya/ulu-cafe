<x-dashboard-layout title="Daftar Voucher">
    <div x-data="voucherManager()" x-cloak>
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Daftar Voucher Promo</h2>
            <button @click="openAddModal()" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                Tambah Voucher
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[var(--color-dark-card)] rounded-xl overflow-hidden border border-white/10">
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
                                <button @click="editVoucher({{ $voucher->toJson() }})" class="text-blue-400 hover:text-blue-300 font-medium text-sm transition">Edit</button>
                                <button @click="confirmDelete({{ $voucher->id }}, '{{ $voucher->code }}')" class="text-red-400 hover:text-red-300 font-medium text-sm transition">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add Voucher Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-2xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Tambah Voucher Baru</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form action="{{ route('vouchers.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kode Voucher (Unik)</label>
                        <input type="text" name="code" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white font-mono uppercase tracking-wider focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" placeholder="CONTOH: DISKON50" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Tipe Potongan</label>
                            <div class="relative">
                                <select name="type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                    <option value="fixed">Nominal (Rp)</option>
                                    <option value="percent">Persentase (%)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nilai Potongan</label>
                            <input type="number" name="amount" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Minimal Pembelian (Rp)</label>
                        <input type="number" name="min_purchase" value="0" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-gray-700 hover:bg-white/5 transition">
                            <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-600 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-[var(--color-dark-input)]">
                            <div>
                                <span class="block font-bold">Status Aktif</span>
                                <span class="block text-xs text-gray-400">Voucher dapat digunakan segera setelah disimpan</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Simpan Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Voucher Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-2xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Edit Voucher</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form :action="editForm.action" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kode Voucher (Unik)</label>
                        <input type="text" name="code" x-model="editForm.code" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white font-mono uppercase tracking-wider focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Tipe Potongan</label>
                            <div class="relative">
                                <select name="type" x-model="editForm.type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                    <option value="fixed">Nominal (Rp)</option>
                                    <option value="percent">Persentase (%)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nilai Potongan</label>
                            <input type="number" name="amount" x-model="editForm.amount" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Minimal Pembelian (Rp)</label>
                        <input type="number" name="min_purchase" x-model="editForm.min_purchase" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-gray-700 hover:bg-white/5 transition">
                            <input type="checkbox" name="is_active" value="1" x-model="editForm.is_active" class="w-5 h-5 rounded border-gray-600 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-[var(--color-dark-input)]">
                            <div>
                                <span class="block font-bold">Status Aktif</span>
                                <span class="block text-xs text-gray-400">Voucher dapat digunakan segera setelah disimpan</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Update Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-auto min-w-[300px] max-w-sm p-6 shadow-2xl text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold mb-2">Hapus Voucher?</h3>
                <p class="text-gray-400 text-sm mb-6">Apakah Anda yakin ingin menghapus voucher "<span x-text="deleteTitle" class="text-white font-mono"></span>"?</p>
                
                <div class="flex justify-center gap-3">
                    <button type="button" @click="showDeleteModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white font-bold px-6 py-2 rounded-lg hover:bg-red-600 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function voucherManager() {
            return {
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                deleteUrl: '',
                deleteTitle: '',
                
                editForm: {
                    action: '',
                    code: '',
                    type: 'fixed',
                    amount: 0,
                    min_purchase: 0,
                    is_active: true
                },

                openAddModal() {
                    this.showAddModal = true;
                },

                editVoucher(voucher) {
                    this.editForm.action = `/vouchers/${voucher.id}`;
                    this.editForm.code = voucher.code;
                    this.editForm.type = voucher.type;
                    this.editForm.amount = voucher.amount;
                    this.editForm.min_purchase = voucher.min_purchase;
                    this.editForm.is_active = voucher.is_active == 1;
                    this.showEditModal = true;
                },
                
                confirmDelete(id, title) {
                    this.deleteUrl = `/vouchers/${id}`;
                    this.deleteTitle = title;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
</x-dashboard-layout>
