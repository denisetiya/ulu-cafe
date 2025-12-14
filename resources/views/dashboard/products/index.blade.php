<x-dashboard-layout title="Daftar Menu Makanan">
    <div x-data="productManager()" x-cloak>
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Daftar Menu</h2>
            <button @click="openAddModal()" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                Tambah Menu
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
                        <th class="p-4">Nama</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($products as $product)
                        <tr>
                            <td class="p-4 font-medium">{{ $product->name }}</td>
                            <td class="p-4 text-gray-400">{{ $product->category->name }}</td>
                            <td class="p-4">
                                @if($product->discount_amount > 0)
                                    <div>
                                        <span class="text-xs text-red-400 line-through block">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="text-white font-bold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] bg-red-500/20 text-red-400 px-1 py-0.5 rounded ml-1">
                                            -{{ $product->discount_type == 'percent' ? $product->discount_amount . '%' : 'Rp ' . number_format($product->discount_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-4 flex gap-3">
                                <button @click="editProduct({{ $product->toJson() }}, '{{ $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : '' }}')" class="text-blue-400 hover:text-blue-300 font-medium text-sm transition">Edit</button>
                                <button @click="confirmDelete({{ $product->id }}, '{{ $product->name }}')" class="text-red-400 hover:text-red-300 font-medium text-sm transition">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add Product Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-2xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Tambah Menu Baru</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Image Upload -->
                    <div class="flex justify-center">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-2xl bg-[var(--color-dark-input)] border-2 border-dashed border-gray-600 flex items-center justify-center overflow-hidden transition group-hover:border-[var(--color-primary)]">
                                <template x-if="!imagePreview">
                                    <div class="text-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        <span class="text-xs">Upload Foto</span>
                                    </div>
                                </template>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                </template>
                                <input type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Menu</label>
                            <input type="text" name="name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" placeholder="Contoh: Kopi Susu" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" placeholder="0" required>
                        </div>
                    </div>

                    <!-- Discount Section -->
                    <div class="bg-[var(--color-dark-bg)]/50 p-4 rounded-xl border border-white/5">
                        <h3 class="text-sm font-bold text-gray-300 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-primary)]"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2.5 0-4.8-2-5-4-10.5Z"/><path d="M12 10h1"/></svg>
                            Pengaturan Diskon
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Nominal / Persen Diskon</label>
                                <input type="number" name="discount_amount" value="0" step="0.01" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-[var(--color-primary)] transition">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Tipe Diskon</label>
                                <div class="relative">
                                    <select name="discount_type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                        <option value="fixed">Nominal (Rp)</option>
                                        <option value="percent">Persen (%)</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                @foreach($products->unique('category_id') as $p)
                                    <!-- Fallback if categories not passed, but normally should be shared content -->
                                @endforeach
                                <!-- We need categories here. Assume they are passed or use cached list from $products if not separate -->
                                @foreach(App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition resize-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-2xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Edit Menu</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <form :action="editForm.action" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf @method('PUT')
                    
                    <!-- Image Upload -->
                    <div class="flex justify-center">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-2xl bg-[var(--color-dark-input)] border-2 border-dashed border-gray-600 flex items-center justify-center overflow-hidden transition group-hover:border-[var(--color-primary)]">
                                <template x-if="!editForm.imagePreview">
                                    <div class="text-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        <span class="text-xs">Upload Foto</span>
                                    </div>
                                </template>
                                <template x-if="editForm.imagePreview">
                                    <img :src="editForm.imagePreview" class="w-full h-full object-cover">
                                </template>
                                <input type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" @change="editForm.imagePreview = URL.createObjectURL($event.target.files[0])">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Menu</label>
                            <input type="text" name="name" x-model="editForm.name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" x-model="editForm.price" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                        </div>
                    </div>

                    <!-- Discount Section -->
                    <div class="bg-[var(--color-dark-bg)]/50 p-4 rounded-xl border border-white/5">
                        <h3 class="text-sm font-bold text-gray-300 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-primary)]"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2.5 0-4.8-2-5-4-10.5Z"/><path d="M12 10h1"/></svg>
                            Pengaturan Diskon
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Nominal / Persen Diskon</label>
                                <input type="number" name="discount_amount" x-model="editForm.discount_amount" step="0.01" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-[var(--color-primary)] transition">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Tipe Diskon</label>
                                <div class="relative">
                                    <select name="discount_type" x-model="editForm.discount_type" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                        <option value="fixed">Nominal (Rp)</option>
                                        <option value="percent">Persen (%)</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id" x-model="editForm.category_id" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                @foreach(App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" x-model="editForm.description" rows="3" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition resize-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                            Update Menu
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
                
                <h3 class="text-lg font-bold mb-2">Hapus Menu?</h3>
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
        function productManager() {
            return {
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                deleteUrl: '',
                deleteTitle: '',
                imagePreview: null,
                
                editForm: {
                    action: '',
                    name: '',
                    price: '',
                    category_id: '',
                    description: '',
                    discount_amount: 0,
                    discount_type: 'fixed',
                    imagePreview: null
                },

                openAddModal() {
                    this.showAddModal = true;
                    this.imagePreview = null;
                },

                editProduct(product, imageUrl) {
                    this.editForm.action = `/products/${product.id}`;
                    this.editForm.name = product.name;
                    this.editForm.price = product.price;
                    this.editForm.category_id = product.category_id;
                    this.editForm.description = product.description;
                    this.editForm.discount_amount = product.discount_amount || 0;
                    this.editForm.discount_type = product.discount_type || 'fixed';
                    this.editForm.imagePreview = imageUrl;
                    
                    this.showEditModal = true;
                },
                
                confirmDelete(id, title) {
                    this.deleteUrl = `/products/${id}`;
                    this.deleteTitle = title;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
</x-dashboard-layout>
