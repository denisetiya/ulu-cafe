<x-dashboard-layout title="Manajemen Banner">
    <div x-data="bannerManager()" x-cloak>
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Banner Promo</h2>
            <button @click="showAddModal = true" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                Tambah Banner
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Banner Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($banners as $banner)
                <div class="bg-[var(--color-dark-card)] rounded-xl border border-white/10 overflow-hidden group">
                    <div class="w-full bg-gray-800 relative" style="aspect-ratio: 3/1;">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover {{ !$banner->is_active ? 'grayscale opacity-50' : '' }}">
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $banner->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-gray-200' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1">{{ $banner->title ?: 'Tanpa Judul' }}</h3>
                        <p class="text-gray-400 text-sm line-clamp-2 mb-4">{{ $banner->description ?: 'Tidak ada deskripsi' }}</p>
                        
                        <div class="flex gap-2">
                            <form action="{{ route('banners.toggle', $banner->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 px-3 rounded-lg text-sm font-medium transition {{ $banner->is_active ? 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30' : 'bg-green-500/20 text-green-400 hover:bg-green-500/30' }}">
                                    {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <button @click="confirmDelete({{ $banner->id }}, '{{ $banner->title ?: 'Banner ini' }}')" class="py-2 px-3 rounded-lg text-sm font-medium bg-red-500/20 text-red-400 hover:bg-red-500/30 transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 border-2 border-dashed border-gray-700 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-600 mb-4"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/></svg>
                    <p class="text-gray-500 mb-4">Belum ada banner yang diupload</p>
                    <button @click="showAddModal = true" class="text-[var(--color-primary)] hover:underline">+ Tambah Banner Pertama</button>
                </div>
            @endforelse
        </div>

        <!-- Add Banner Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <!-- Modal Panel -->
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-full max-w-md p-5 shadow-2xl max-h-[85vh] overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <button @click="showAddModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>

                <h3 class="text-xl font-bold mb-6">Tambah Banner Baru</h3>
                
                <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Gambar Banner</label>
                        <p class="text-xs text-gray-500 mb-2">📐 Ukuran optimal: <span class="text-primary">1200 x 400 px</span> (rasio 3:1)</p>
                        <div class="border-2 border-dashed border-gray-700 rounded-lg p-4 text-center hover:border-[var(--color-primary)] transition cursor-pointer relative" id="dropzone">
                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required @change="previewImage($event)">
                            <div x-show="!imagePreview">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-500 mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <p class="text-xs text-gray-500">Klik atau drag gambar ke sini</p>
                            </div>
                            <div x-show="imagePreview" class="w-full" style="aspect-ratio: 3/1;">
                                <img :src="imagePreview" class="w-full h-full rounded-lg object-cover">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Judul (Opsional)</label>
                        <input type="text" name="title" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent placeholder-gray-600" placeholder="Contoh: Promo Spesial">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-primary)] focus:border-transparent placeholder-gray-600" placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer p-4 rounded-lg border border-gray-700 hover:bg-white/5 transition">
                            <input type="checkbox" name="send_notification" value="1" class="w-5 h-5 rounded border-gray-600 text-[var(--color-primary)] focus:ring-[var(--color-primary)] bg-gray-800">
                            <div>
                                <span class="block font-bold">📧 Kirim Email ke Pelanggan</span>
                                <span class="block text-xs text-gray-400">Semua pelanggan terdaftar akan menerima notifikasi promo ini</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showAddModal = false; imagePreview = null" class="px-6 py-2 rounded-lg font-bold border border-gray-600 text-gray-400 hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-[var(--color-primary)] text-black font-bold px-6 py-2 rounded-lg hover:brightness-110 transition">
                            Upload Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <!-- Modal Panel -->
            <div class="relative bg-[var(--color-dark-card)] rounded-2xl border border-white/10 w-auto min-w-[300px] max-w-sm p-6 shadow-2xl text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold mb-2">Hapus Banner?</h3>
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
        function bannerManager() {
            return {
                showAddModal: false,
                showDeleteModal: false,
                deleteUrl: '',
                deleteTitle: '',
                imagePreview: null,
                
                confirmDelete(id, title) {
                    this.deleteUrl = `/banners/${id}`;
                    this.deleteTitle = title;
                    this.showDeleteModal = true;
                },
                
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>
</x-dashboard-layout>
