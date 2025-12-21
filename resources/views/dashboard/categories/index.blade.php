<x-dashboard-layout title="Kategori Menu">
    <div x-data="categoryManager()" x-cloak>
        <div class="mb-6 flex flex-row justify-between items-center gap-4">
            <h2 class="text-xl sm:text-2xl font-bold">Daftar Kategori</h2>
            <button @click="openAddModal()" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition flex items-center justify-center gap-2 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                <span class="hidden sm:inline">Tambah Kategori</span>
                <span class="sm:hidden">Tambah</span>
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

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
        <div class="bg-[var(--color-dark-card)] rounded-xl overflow-hidden border border-white/10">
            <div class="overflow-x-auto custom-table-scroll">
                <table class="w-full text-left min-w-[400px]">
                    <thead class="bg-gray-800 text-gray-400 text-xs sm:text-sm">
                        <tr>
                            <th class="p-3 sm:p-4 whitespace-nowrap">Nama Kategori</th>
                            <th class="p-3 sm:p-4 whitespace-nowrap">Jumlah Menu</th>
                            <th class="p-3 sm:p-4 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-sm">
                        @foreach($categories as $category)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-3 sm:p-4 font-medium whitespace-nowrap">{{ $category->name }}</td>
                                <td class="p-3 sm:p-4 text-gray-400 whitespace-nowrap">{{ $category->products_count ?? 0 }} Menu</td>
                                <td class="p-3 sm:p-4 whitespace-nowrap">
                                    <div class="flex gap-3">
                                        <button @click="editCategory({{ $category->toJson() }})" class="text-blue-400 hover:text-blue-300 font-medium text-sm transition">Edit</button>
                                        <button @click="confirmDelete({{ $category->id }}, '{{ $category->name }}')" class="text-red-400 hover:text-red-300 font-medium text-sm transition">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-lg p-6 shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Tambah Kategori Baru</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nama Kategori</label>
                        <input type="text" name="name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" placeholder="Contoh: Makanan Berat" required>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-lg p-6 shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Edit Kategori</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form :action="editForm.action" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nama Kategori</label>
                        <input type="text" name="name" x-model="editForm.name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Update Kategori
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
                
                <h3 class="text-lg font-bold mb-2">Hapus Kategori?</h3>
                <p class="text-gray-400 text-sm mb-6">Apakah Anda yakin ingin menghapus "<span x-text="deleteTitle" class="text-white"></span>"?</p>
                
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
        function categoryManager() {
            return {
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                deleteUrl: '',
                deleteTitle: '',
                
                editForm: {
                    action: '',
                    name: ''
                },

                openAddModal() {
                    this.showAddModal = true;
                },

                editCategory(category) {
                    this.editForm.action = `/categories/${category.id}`;
                    this.editForm.name = category.name;
                    this.showEditModal = true;
                },
                
                confirmDelete(id, title) {
                    this.deleteUrl = `/categories/${id}`;
                    this.deleteTitle = title;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
</x-dashboard-layout>
