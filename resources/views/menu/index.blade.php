<x-layout>
    <div class="min-h-screen bg-dark-bg" x-data="{ 
        search: '',
        selectedCategory: 'all',
        minPrice: 0,
        maxPrice: 500000,
        priceLimit: 500000,
        sortBy: 'default',
        isFilterOpen: false,
        
        selectedProduct: null,
        showPromoOnly: false,
        
        isVisible(name, price, categoryId, discountAmount) {
            const searchMatch = name.toLowerCase().includes(this.search.toLowerCase());
            const categoryMatch = this.selectedCategory === 'all' || this.selectedCategory == categoryId;
            const priceMatch = price <= this.priceLimit;
            const promoMatch = !this.showPromoOnly || (this.showPromoOnly && discountAmount > 0);
            
            return searchMatch && categoryMatch && priceMatch && promoMatch;
        },

        openProductModal(product) {
            this.selectedProduct = product;
            document.body.style.overflow = 'hidden';
        },

        closeProductModal() {
            this.selectedProduct = null;
            document.body.style.overflow = '';
        }
    }">

        <!-- Hero Section with Carousel (PRESERVED) -->
        @if($banners->count() > 0)
            <div class="relative overflow-hidden mb-8 group" 
                 x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }" 
                 x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)">
                <!-- Banner Container with 3:1 aspect ratio -->
                
                <div class="relative w-full" style="aspect-ratio: 3/1;">
                    @foreach($banners as $index => $banner)
                        <div class="absolute inset-0 transition-transform duration-1000 ease-out"
                             x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-1000"
                             x-transition:enter-start="opacity-0 scale-105"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-1000"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-bg via-black/30 to-transparent"></div>
                            <!-- Title/Description Overlay -->
                            <div class="absolute inset-x-0 bottom-0 pb-4 md:pb-8 container mx-auto px-4 md:px-6">
                                <div class="max-w-3xl" x-show="activeSlide === {{ $index }}"
                                     x-transition:enter="transition ease-out duration-1000 delay-300"
                                     x-transition:enter-start="opacity-0 translate-y-10"
                                     x-transition:enter-end="opacity-100 translate-y-0">
                                    @if($banner->title)
                                        <h2 class="text-xl md:text-4xl lg:text-5xl font-bold text-white mb-1 md:mb-3 drop-shadow-lg">{{ $banner->title }}</h2>
                                    @endif
                                    @if($banner->description)
                                        <p class="text-sm md:text-lg lg:text-xl text-gray-200 font-light drop-shadow-md line-clamp-2">{{ $banner->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                 
                <!-- Navigation Controls -->
                <div class="absolute bottom-2 md:bottom-6 right-2 md:right-6 flex gap-1 md:gap-2 z-20">
                    <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)" 
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 backdrop-blur text-white flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)" 
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 backdrop-blur text-white flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>

                <!-- Slide Indicators -->
                <div class="absolute bottom-2 md:bottom-6 left-1/2 transform -translate-x-1/2 flex gap-1.5 md:gap-2 z-20">
                    @foreach($banners as $index => $banner)
                        <button @click="activeSlide = {{ $index }}; clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)"
                                class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full transition-all duration-300"
                                :class="activeSlide === {{ $index }} ? 'bg-white w-6 md:w-8' : 'bg-white/40 hover:bg-white/60'"></button>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Fallback Header if no banners -->
            <div class="pt-32 pb-12 container mx-auto px-6 text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-4 tracking-tight">Menu Spesial</h1>
                <p class="text-xl text-gray-400 font-light">Rasakan kenikmatan dalam setiap gigitan</p>
            </div>
        @endif

        <!-- Search & Filter Bar (UPDATED) -->
        <div class="sticky top-[70px] z-40 py-4 transition-all duration-300 bg-dark-bg/95 backdrop-blur-xl border-b border-white/5 shadow-2xl">
            <div class="container mx-auto px-6 flex flex-col md:flex-row gap-3">
                <!-- Search -->
                <div class="relative flex-grow">
                    <input type="text" x-model="search" placeholder="Cari menu..." 
                           class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>

                <div class="flex gap-3">
                    <!-- Category Dropdown -->
                    <div class="relative min-w-[150px]">
                        <select x-model="selectedCategory" 
                                class="w-full appearance-none bg-white/5 border border-white/10 rounded-xl pl-4 pr-10 py-3 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition cursor-pointer">
                            <option value="all" class="bg-dark-card text-white">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" class="bg-dark-card text-white">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                
                    <!-- Filter Button -->
                    <button @click="isFilterOpen = true" 
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white hover:bg-white/10 transition flex items-center justify-center gap-2 relative min-w-[50px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="4" y1="21" y2="14"/><line x1="4" x2="4" y1="10" y2="3"/><line x1="12" x2="12" y1="21" y2="12"/><line x1="12" x2="12" y1="8" y2="3"/><line x1="20" x2="20" y1="21" y2="16"/><line x1="20" x2="20" y1="12" y2="3"/><line x1="1" x2="7" y1="14" y2="14"/><line x1="9" x2="15" y1="8" y2="8"/><line x1="17" x2="23" y1="16" y2="16"/></svg>
                        <!-- Active Filter Indicator -->
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-primary rounded-full" 
                             x-show="priceLimit < maxPrice || sortBy !== 'default' || showPromoOnly" 
                             x-transition></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Drawer (UPDATED: Category removed from here) -->
        <div class="fixed inset-0 z-50 pointer-events-none" x-show="isFilterOpen" x-cloak>
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm pointer-events-auto" 
                 x-show="isFilterOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="isFilterOpen = false"></div>

            <!-- Drawer Panel -->
            <div class="absolute inset-y-0 right-0 max-w-sm w-full bg-dark-card border-l border-white/10 shadow-2xl pointer-events-auto flex flex-col"
                 x-show="isFilterOpen"
                 x-transition:enter="transition transform ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition transform ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                <div class="p-6 border-b border-white/10 flex justify-between items-center bg-dark-card z-10">
                    <h2 class="text-xl font-bold">Filter Tambahan</h2>
                    <button @click="isFilterOpen = false" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-8">
                    <!-- Promo Filter -->
                    <div>
                         <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Promo</h3>
                         <label class="flex items-center gap-3 cursor-pointer group bg-white/5 p-4 rounded-xl border border-white/5 hover:border-primary/50 transition">
                            <div class="relative">
                                <input type="checkbox" x-model="showPromoOnly" class="peer sr-only">
                                <div class="w-10 h-6 bg-gray-600 rounded-full peer-checked:bg-primary transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-white font-medium" :class="showPromoOnly ? 'text-primary' : ''">Hanya Promo</span>
                         </label>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Maksimal Harga</h3>
                        <div class="px-2">
                            <input type="range" class="w-full accent-primary h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer" 
                                   min="0" :max="maxPrice" step="5000" x-model="priceLimit">
                            <div class="flex justify-between text-sm mt-2 font-mono">
                                <span>Rp 0</span>
                                <span class="text-primary font-bold">Rp <span x-text="Number(priceLimit).toLocaleString('id-ID')"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Urutkan</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <span class="w-5 h-5 rounded-full border border-gray-600 flex items-center justify-center group-hover:border-primary transition">
                                    <span class="w-2.5 h-2.5 rounded-full bg-primary opacity-0 transition" :class="sortBy === 'default' ? 'opacity-100' : ''"></span>
                                </span>
                                <input type="radio" value="default" x-model="sortBy" class="hidden">
                                <span :class="sortBy === 'default' ? 'text-white' : 'text-gray-400'">Rekomendasi</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <span class="w-5 h-5 rounded-full border border-gray-600 flex items-center justify-center group-hover:border-primary transition">
                                    <span class="w-2.5 h-2.5 rounded-full bg-primary opacity-0 transition" :class="sortBy === 'price_asc' ? 'opacity-100' : ''"></span>
                                </span>
                                <input type="radio" value="price_asc" x-model="sortBy" class="hidden">
                                <span :class="sortBy === 'price_asc' ? 'text-white' : 'text-gray-400'">Harga Terendah</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <span class="w-5 h-5 rounded-full border border-gray-600 flex items-center justify-center group-hover:border-primary transition">
                                    <span class="w-2.5 h-2.5 rounded-full bg-primary opacity-0 transition" :class="sortBy === 'price_desc' ? 'opacity-100' : ''"></span>
                                </span>
                                <input type="radio" value="price_desc" x-model="sortBy" class="hidden">
                                <span :class="sortBy === 'price_desc' ? 'text-white' : 'text-gray-400'">Harga Tertinggi</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-white/10 bg-dark-card mt-auto flex gap-4">
                    <button @click="priceLimit = maxPrice; sortBy = 'default'; showPromoOnly = false" 
                            class="flex-1 py-3 rounded-xl font-bold border border-white/10 text-gray-400 hover:bg-white/5 transition">
                        Reset
                    </button>
                    <button @click="isFilterOpen = false" 
                            class="flex-1 py-3 rounded-xl font-bold bg-primary text-white hover:brightness-110 transition shadow-lg shadow-primary/30">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Grid (UPDATED: Dropdown logic) -->
        <div class="container mx-auto px-6 pb-20 pt-8">
            <div x-show="selectedCategory === 'all' && search === '' && priceLimit === maxPrice && sortBy === 'default' && !showPromoOnly" class="mb-4">
               <!-- Show all categories naturally if no filter to preserve structure -->
            </div>

            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <!-- Category Section with Alpine x-show logic linked to Dropdown -->
                    <div x-show="selectedCategory === 'all' || selectedCategory == {{ $category->id }}" 
                         class="mb-12"
                         x-transition>
                         
                        <div class="flex items-end gap-6 mb-8 border-b border-white/5 pb-4" x-show="search === '' && priceLimit === maxPrice && sortBy === 'default' && !showPromoOnly">
                            <h2 class="text-3xl font-bold text-white">{{ $category->name }}</h2>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6">
                            @foreach($category->products as $product)
                                @php
                                    $productData = [
                                        'id' => $product->id,
                                        'name' => $product->name,
                                        'description' => str_replace(["\r\n", "\r", "\n"], ' ', $product->description ?? ''),
                                        'price' => $product->price,
                                        'final_price' => $product->final_price,
                                        'image' => $product->image,
                                        'discount_amount' => $product->discount_amount,
                                        'discount_type' => $product->discount_type,
                                    ];
                                @endphp
                                <div x-data="{ 
                                        name: {{ Js::from($product->name) }}, 
                                        price: {{ $product->final_price }}, 
                                        catId: {{ $category->id }},
                                        discountAmount: {{ $product->discount_amount }} 
                                     }"
                                     x-show="isVisible(name, price, catId, discountAmount)"
                                     @click="openProductModal({{ Js::from($productData) }})"
                                     class="group relative bg-dark-card/50 backdrop-blur-sm rounded-2xl md:rounded-3xl p-3 md:p-4 border border-white/5 hover:border-primary/30 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] transition-all duration-500 hover:-translate-y-2 flex flex-col h-full cursor-pointer"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    
                                     <!-- Image Container -->
                                    <div class="relative aspect-square rounded-xl md:rounded-2xl overflow-hidden mb-3 md:mb-6 bg-dark-bg">
                                        @if($product->image)
                                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-white/5 text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/></svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay (Removed Form, moved to modal) -->
                                        <div class="absolute inset-0 bg-black/40 md:opacity-0 md:group-hover:opacity-100 opacity-0 group-active:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                            <span class="bg-primary text-white px-6 py-3 rounded-full font-bold shadow-xl hover:scale-105 transition-transform flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <span>Lihat Detail</span>
                                            </span>
                                        </div>

                                        @if($product->discount_amount > 0)
                                            <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                {{ $product->discount_type == 'percent' ? $product->discount_amount . '% OFF' : 'HEMAT Rp ' . number_format($product->discount_amount, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="px-2 pb-4 flex-1 flex flex-col">
                                        <div class="flex flex-col items-start mb-2 gap-2">
                                            <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors leading-tight w-full">{{ $product->name }}</h3>
                                            <div class="w-full text-left">
                                                @if($product->discount_amount > 0)
                                                    <span class="block text-xs text-gray-500 line-through decoration-red-500 decoration-auto">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    <span class="block text-base font-bold text-primary">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="block text-base font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-gray-400 text-xs leading-relaxed line-clamp-2">{{ $product->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if($categories->isEmpty())
                 <div class="flex flex-col items-center justify-center py-32 text-center opacity-50">
                    <p class="text-xl font-light">Belum ada menu yang tersedia.</p>
                </div>
            @endif
        </div>
        
        <!-- Product Detail Modal -->
        <div class="fixed inset-0 z-[60] flex items-center justify-center px-4" 
             x-show="selectedProduct" 
             x-cloak 
             @keyup.escape.window="closeProductModal()">
            
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/90 backdrop-blur-md" 
                 x-show="selectedProduct"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeProductModal()"></div>

            <!-- Modal Panel -->
            <div class="relative bg-dark-card w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]"
                 x-show="selectedProduct"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-10"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-10">
                
                <!-- Close Button -->
                <button @click="closeProductModal()" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>

                <!-- Product Image -->
                <div class="w-full md:w-1/2 h-64 md:h-auto bg-dark-bg relative">
                     <template x-if="selectedProduct && selectedProduct.image">
                        <img :src="selectedProduct.image.startsWith('http') ? selectedProduct.image : '/storage/' + selectedProduct.image" 
                             :alt="selectedProduct.name" 
                             class="w-full h-full object-cover">
                     </template>
                     <template x-if="selectedProduct && !selectedProduct.image">
                        <div class="w-full h-full flex items-center justify-center bg-white/5 text-gray-600">
                             <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/></svg>
                        </div>
                     </template>
                </div>

                <!-- Product Details -->
                <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col overflow-y-auto">
                    <template x-if="selectedProduct">
                        <div>
                             <div class="flex items-start justify-between mb-2">
                                <span class="px-3 py-1 rounded-full bg-primary/20 text-primary text-xs font-bold uppercase tracking-wider">Coffee</span>
                                <!-- Stock Status (Optional/Placeholder) -->
                             </div>
                             
                             <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" x-text="selectedProduct.name"></h2>
                             
                             <div class="flex items-baseline gap-4 mb-6">
                                <template x-if="selectedProduct.discount_amount > 0">
                                    <span class="text-xl text-gray-500 line-through decoration-red-500 decoration-auto" x-text="'Rp ' + Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                                </template>
                                <span class="text-3xl font-bold text-primary" x-text="'Rp ' + Number(selectedProduct.final_price).toLocaleString('id-ID')"></span>
                             </div>

                             <div class="prose prose-invert max-w-none text-gray-300 mb-8 leading-relaxed">
                                 <p x-text="selectedProduct.description"></p>
                             </div>

                             <!-- Add to Cart Form -->
                             <form action="{{ route('cart.add') }}" method="POST" class="mt-auto pt-6 border-t border-white/10">
                                @csrf
                                <input type="hidden" name="product_id" :value="selectedProduct.id">
                                
                                <div class="flex items-center gap-4 mb-6">
                                    <span class="text-gray-400 font-medium">Jumlah</span>
                                    <div class="flex items-center bg-white/5 rounded-lg p-1 border border-white/10" x-data="{ qty: 1 }">
                                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-md transition text-white">-</button>
                                        <input type="number" name="quantity" x-model="qty" class="w-12 bg-transparent text-center text-white border-0 focus:ring-0 p-0 font-bold" min="1" readonly>
                                        <button type="button" @click="qty++" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded-md transition text-white">+</button>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-primary/20 hover:brightness-110 hover:shadow-primary/40 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    Tambah ke Keranjang
                                </button>
                             </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Float Cart for Mobile -->
        @php
            $cartCount = 0;
            if(Auth::check()) {
                $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
            } else {
                $cart = session('cart', []);
                foreach($cart as $item) {
                     if(isset($item['quantity'])) $cartCount += $item['quantity'];
                }
            }
        @endphp
        
        <div class="fixed bottom-6 right-6 z-40 md:hidden" 
             x-show="{{ $cartCount > 0 ? 'true' : 'false' }}"
             x-transition>
            <a href="{{ route('cart.index') }}" class="group flex items-center gap-3 bg-primary text-white pl-4 pr-6 py-4 rounded-full shadow-[0_10px_30px_#35c75966] font-bold transition-transform hover:scale-105 active:scale-95">
                <span class="bg-white text-primary w-8 h-8 rounded-full flex items-center justify-center text-sm font-extrabold shadow-inner">{{ $cartCount }}</span>
                <span>Keranjang</span>
            </a>
        </div>
    </div>
</x-layout>
