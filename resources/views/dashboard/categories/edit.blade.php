<x-dashboard-layout title="Edit Kategori">
    <div class="max-w-lg">
        <div class="bg-[var(--color-dark-card)] p-6 rounded-xl">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Kategori</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)]" required>
                </div>
                
                <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-90 transition">Update Kategori</button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
