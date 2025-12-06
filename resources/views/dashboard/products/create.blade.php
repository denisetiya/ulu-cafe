<x-dashboard-layout title="Tambah Menu">
    <div class="max-w-2xl">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-xl">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Menu</label>
                    <input type="text" name="name" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Harga</label>
                    <input type="number" name="price" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kategori</label>
                    <select name="category_id" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Gambar Menu</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[var(--color-primary)] file:text-white hover:file:bg-opacity-90">
                </div>
                
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-90 transition">Simpan Menu</button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
