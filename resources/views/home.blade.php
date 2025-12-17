<x-layout>
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 flex items-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
             <div class="absolute inset-0 bg-dark-bg"></div>
             <!-- Ambient Glow -->
             <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] opacity-30 animate-pulse"></div>
             <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-coffee-accent/10 rounded-full blur-[120px] opacity-20"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Text Content -->
                <div class="w-full md:w-1/2 z-20 fade-up">
                    <span class="inline-block py-1 px-3 rounded-full bg-coffee-accent/20 text-coffee-accent border border-coffee-accent/30 text-sm font-medium mb-6 backdrop-blur-sm">
                        Experience the Art of Coffee
                    </span>
                    <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6">
                        Rasakan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-coffee-accent">Kopi Asli Ulu</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-400 mb-8 max-w-xl leading-relaxed">
                        Tempat di mana aroma kopi bertemu dengan kehangatan suasana. Nikmati setiap tegukan dalam kenyamanan yang tak terlupakan.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('menu.index') }}" class="group relative px-8 py-4 rounded-full bg-primary text-white font-bold text-lg overflow-hidden shadow-[0_0_20px_rgba(53,199,89,0.3)] transition-all hover:scale-105 hover:shadow-[0_0_40px_rgba(53,199,89,0.5)]">
                            <span class="relative z-10 flex items-center gap-2">
                                Pesan Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </span>
                        </a>
                        <a href="#gallery" class="px-8 py-4 rounded-full border border-white/10 text-white font-bold text-lg hover:bg-white/5 transition-all">
                            Lihat Galeri
                        </a>
                    </div>
                </div>

                <!-- 3D Hero Model -->
                <div class="w-full md:w-1/2 relative z-10 mt-12 md:mt-0 flex justify-center perspective-1000 h-[400px] md:h-[500px]">
                     <div class="relative w-full h-full">
                         <!-- Steam Animation Elements (Multiple Layers) -->
                         <div class="absolute top-10 left-1/2 -translate-x-1/2 w-20 h-20 bg-white/40 blur-lg rounded-full animate-[steam_4s_infinite_ease-out] pointer-events-none z-0" style="animation-delay: 0s;"></div>
                         <div class="absolute top-10 left-1/2 -translate-x-1/2 w-24 h-24 bg-white/30 blur-xl rounded-full animate-[steam_5s_infinite_ease-out] pointer-events-none z-0" style="animation-delay: 1.5s;"></div>
                         <div class="absolute top-12 left-1/2 -translate-x-1/2 w-16 h-16 bg-white/20 blur-md rounded-full animate-[steam_3s_infinite_ease-out] pointer-events-none z-0" style="animation-delay: 2.5s;"></div>
                         
                         <model-viewer 
                            src="{{ asset('3d/coffee_cup.glb') }}"
                            alt="Premium Coffee Cup"
                            auto-rotate
                            camera-controls
                            disable-zoom
                            shadow-intensity="2"
                            shadow-softness="1"
                            exposure="0.9"
                            environment-image="neutral"
                            camera-orbit="45deg 55deg 2.5m"
                            min-camera-orbit="auto 0deg auto"
                            max-camera-orbit="auto 90deg auto"
                            field-of-view="30deg"
                            class="w-full h-full relative z-10"
                            style="--poster-color: transparent; background-color: transparent;">
                        </model-viewer>
                    </div>
                </div>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
            </div>
        </div>
    </section>

    <!-- Signature Coffee Section -->
    <section id="signature" class="pt-0 pb-24 relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16 fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Signature Coffee</h2>
                <div class="w-20 h-1 bg-primary mx-auto rounded-full mb-6"></div>
                <p class="text-gray-400 max-w-2xl mx-auto">Pilihan kopi terbaik yang diracik khusus untuk memberikan pengalaman rasa yang unik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Manual Brew Card -->
                <div class="group relative rounded-3xl overflow-hidden glass-dark border border-white/5 hover:border-coffee-accent/50 transition-all duration-500 fade-up delay-100 hover:-translate-y-2">
                    <div class="h-72 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Manual Brew">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white mb-1">Manual Brew</h3>
                            <p class="text-coffee-accent text-sm font-medium tracking-wider uppercase">Di buat dengan sepenuh hati</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-400 mb-6 leading-relaxed">Nikmati kemurnian rasa kopi dengan metode seduh manual yang menonjolkan karakter asli biji kopi pilihan.</p>
                        <a href="{{ route('menu.index') }}?category=manual-brew" class="inline-flex items-center text-primary group-hover:text-white font-bold transition-colors">
                            Lihat Pilihan <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Espresso Based Card -->
                <div class="group relative rounded-3xl overflow-hidden glass-dark border border-white/5 hover:border-coffee-accent/50 transition-all duration-500 md:-mt-12 fade-up delay-200 hover:-translate-y-2">
                    <div class="h-72 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Espresso Based">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white mb-1">Espresso Based</h3>
                            <p class="text-coffee-accent text-sm font-medium tracking-wider uppercase">Latte • Cappuccino • Americano</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-400 mb-6 leading-relaxed">Perpaduan sempurna espresso yang kuat dengan susu segar yang creamy. Klasik yang tak tergantikan.</p>
                        <a href="{{ route('menu.index') }}?category=coffee" class="inline-flex items-center text-primary group-hover:text-white font-bold transition-colors">
                            Lihat Pilihan <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Signature Drinks Card -->
                <div class="group relative rounded-3xl overflow-hidden glass-dark border border-white/5 hover:border-coffee-accent/50 transition-all duration-500 fade-up delay-300 hover:-translate-y-2">
                    <div class="h-72 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Signature">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white mb-1">Ulu Signature</h3>
                            <p class="text-coffee-accent text-sm font-medium tracking-wider uppercase">Kopi original Ulu Coffee</p>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-400 mb-6 leading-relaxed">Kreasi spesial barista kami yang memadukan cita rasa lokal dengan teknik penyajian modern.</p>
                        <a href="{{ route('menu.index') }}?category=signature" class="inline-flex items-center text-primary group-hover:text-white font-bold transition-colors">
                            Lihat Pilihan <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section (NEW) -->
    <section id="gallery" class="py-24 bg-black/20 relative">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 fade-up">
                <div>
                    <h2 class="text-3xl md:text-5xl font-bold mb-4">Galeri Kami</h2>
                    <p class="text-gray-400">Intip suasana nyaman dan hidangan lezat di Ulu Coffee</p>
                </div>
                <div class="hidden md:block">
                     <a href="https://instagram.com/ulucoffe_" target="_blank" class="text-white hover:text-primary transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        Follow @ulucoffe_
                     </a>
                </div>
            </div>

            <!-- Bento Grid Gallery -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
                <!-- Item 1: Large Vertical -->
                <div class="col-span-1 md:col-span-2 md:row-span-2 relative group overflow-hidden rounded-3xl fade-up delay-100">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Cafe Interior">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-bold bg-white/10 backdrop-blur px-4 py-2 rounded-full">Interior</span>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="col-span-1 md:row-span-1 relative group overflow-hidden rounded-3xl fade-up delay-200">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Latte Art">
                     <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-bold bg-white/10 backdrop-blur px-4 py-2 rounded-full">Coffee</span>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="col-span-1 md:row-span-1 relative group overflow-hidden rounded-3xl fade-up delay-300">
                     <img src="https://images.unsplash.com/photo-1485962398705-ef6a13c41e8f?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Food">
                      <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-bold bg-white/10 backdrop-blur px-4 py-2 rounded-full">Food</span>
                    </div>
                </div>

                <!-- Item 4: Wide -->
                <div class="col-span-2 md:col-span-2 relative group overflow-hidden rounded-3xl fade-up delay-100">
                     <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Friends">
                      <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-bold bg-white/10 backdrop-blur px-4 py-2 rounded-full">Moments</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location / Map Section (NEW) -->
    <section class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="bg-dark-card rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl flex flex-col md:flex-row">
                <!-- Map Info -->
                <div class="w-full md:w-1/3 p-10 md:p-14 bg-dark-card relative z-10">
                    <h2 class="text-3xl font-bold mb-8">Kunjungi Kami</h2>
                    
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Alamat</h3>
                                <p class="text-gray-400">Jl. Pagar Alam No. 123, Kedaton, Bandar Lampung</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Jam Buka</h3>
                                <p class="text-gray-400">Setiap Hari<br>10:00 - 23:00 WIB</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Kontak</h3>
                                <p class="text-gray-400">+62 831 7024 9278</p>
                            </div>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/place/Ulu+Coffee/@-5.3792031,105.2632596,17z/data=!3m1!4b1!4m6!3m5!1s0x2e40db0008f08ee5:0xacdd45061e525eb9!8m2!3d-5.3792084!4d105.2658345!16s%2Fg%2F11x2jzr9sb?entry=ttu" target="_blank" class="mt-10 inline-block w-full text-center bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        Buka di Google Maps
                    </a>
                </div>

                <!-- Google Maps Embed -->
                <div class="w-full md:w-2/3 h-[400px] md:h-auto bg-gray-800">
                     <iframe src="https://maps.google.com/maps?q=Ulu%20Coffee%2C%20Bandar%20Lampung&t=&z=17&ie=UTF8&iwloc=&output=embed" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="grayscale hover:grayscale-0 transition duration-700"></iframe>
                </div>
            </div>
        </div>
    </section>

</x-layout>
