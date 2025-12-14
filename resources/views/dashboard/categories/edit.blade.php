<x-dashboard-layout title="Edit Kategori">
    <div class="flex flex-col justify-center items-center p-6">
        <div class="bg-[var(--color-dark-card)] w-full max-w-lg rounded-2xl shadow-2xl border border-white/10 flex flex-col">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-black/20">
                <h2 class="text-xl font-bold text-white">Edit Kategori</h2>
                <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
            
            <div class="p-6">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nama Kategori</label>
                        <input type="text" name="name" value="{{ $category->name }}" class="w-full bg-[var(--color-dark-input)] border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[var(--color-primary)] placeholder-gray-600 transition" required>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-white/10 bg-black/20 flex justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" onclick="document.querySelector('form').submit()" class="bg-[var(--color-primary)] text-white px-8 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow-lg shadow-orange-500/20">
                    Update Kategori
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>
