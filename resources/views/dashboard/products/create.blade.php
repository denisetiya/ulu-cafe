<x-dashboard-layout title="Tambah Menu">
    <div class="flex flex-col justify-center items-center p-6">
        <div class="bg-[var(--color-dark-card)] w-full max-w-2xl rounded-2xl shadow-2xl border border-white/10 flex flex-col">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-black/20">
                <h2 class="text-xl font-bold text-white">Tambah Menu Baru</h2>
                <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
            
            <div class="p-6">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ imagePreview: null }">
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

                    <!-- Name & Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Menu</label>
                            <input type="text" name="name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" placeholder="Contoh: Kopi Susu Gula Aren" required>
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

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category_id" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] appearance-none transition">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition resize-none"></textarea>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-white/10 bg-black/20 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" onclick="document.querySelector('form').submit()" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                    Simpan Menu
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>
