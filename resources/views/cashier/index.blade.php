<x-dashboard-layout title="Kitchen Display System">
    <!-- Auto Refresh Meta -->
    <meta http-equiv="refresh" content="30">

    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Dapur & Kasir</h1>
            <p class="text-gray-400">Pantau dan kelola pesanan masuk secara real-time</p>
        </div>
        <div class="flex gap-4">
             <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Pending
                <span class="w-3 h-3 rounded-full bg-blue-500"></span> Proses
                <span class="w-3 h-3 rounded-full bg-green-500"></span> Siap
            </div>
            <button onclick="window.location.reload()" class="bg-[var(--color-dark-card)] hover:bg-[var(--color-primary)] hover:text-white text-gray-300 px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                Refresh
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-xl mb-6 backdrop-blur-sm flex items-center justify-between">
            {{ session('success') }}
             <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-300">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 min-h-[60vh]">
        <!-- Pending Column -->
        <div class="flex flex-col gap-6">
            <div class="bg-yellow-500/10 p-5 rounded-2xl border border-yellow-500/20 flex justify-between items-center sticky top-24 backdrop-blur-md z-10 shadow-lg">
                <h2 class="text-xl font-bold text-yellow-500 flex items-center gap-3">
                    <div class="p-2 bg-yellow-500/20 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    Pesanan Masuk
                </h2>
                <span class="bg-yellow-500 text-black text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-yellow-500/20">{{ $orders->where('status', 'pending')->count() }}</span>
            </div>
            
            <div class="space-y-6">
                 @forelse($orders->where('status', 'pending') as $order)
                    <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border border-gray-800 shadow-xl hover:shadow-yellow-500/10 transition-all group relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-500"></div>
                        
                        <div class="flex justify-between items-start mb-6 pl-4">
                            <div>
                                <h3 class="text-3xl font-bold mb-1">Meja {{ $order->table_number }}</h3>
                                <div class="flex items-center gap-2 text-gray-400 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="bg-gray-800 text-gray-400 px-3 py-1 rounded-full text-xs font-mono border border-gray-700">#{{ substr($order->id, -4) }}</span>
                        </div>
                        
                         <div class="py-4 border-y border-gray-800 my-4 pl-4 space-y-3">
                            <ul class="space-y-3">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between items-start text-base">
                                        <div class="flex gap-3">
                                            <span class="font-bold text-yellow-500 min-w-[1.5rem]">{{ $item->quantity }}x</span>
                                            <span class="text-gray-200">{{ $item->product ? $item->product->name : 'Menu Dihapus' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($order->notes)
                                <div class="mt-4 bg-yellow-500/10 border border-yellow-500/20 p-3 rounded-xl text-sm text-yellow-200 italic flex gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="min-w-[16px] mt-0.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    "{{ $order->notes }}"
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-3 pl-4">
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                {{ $order->customer_name }}
                            </div>
                            <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg hover:shadow-blue-500/25">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                    Mulai Masak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
                        <p class="text-gray-500 font-medium">Belum ada pesanan baru</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Processing Column -->
        <div class="flex flex-col gap-6">
            <div class="bg-blue-500/10 p-5 rounded-2xl border border-blue-500/20 flex justify-between items-center sticky top-24 backdrop-blur-md z-10 shadow-lg">
                <h2 class="text-xl font-bold text-blue-500 flex items-center gap-3">
                    <div class="p-2 bg-blue-500/20 rounded-lg">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 2v2"/><path d="M12 22v-2"/><path d="M2 12h2"/><path d="M22 12h-2"/><path d="M4.93 4.93l1.41 1.41"/><path d="M17.66 17.66l1.41 1.41"/><path d="M4.93 19.07l1.41-1.41"/><path d="M17.66 6.34l1.41-1.41"/></svg>
                    </div>
                    Sedang Dimasak
                </h2>
                <span class="bg-blue-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-blue-500/20">{{ $orders->where('status', 'processing')->count() }}</span>
            </div>

            <div class="space-y-6">
                 @forelse($orders->where('status', 'processing') as $order)
                    <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border border-gray-800 shadow-xl hover:shadow-blue-500/10 transition-all relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
                        
                        <div class="flex justify-between items-start mb-6 pl-4">
                            <div>
                                <h3 class="text-2xl font-bold mb-1">Meja {{ $order->table_number }}</h3>
                                <div class="flex items-center gap-2 text-gray-400 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $order->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="animate-pulse">
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-500 rounded-full text-xs font-bold border border-blue-500/30">COOKING</span>
                            </div>
                        </div>
                        
                         <div class="py-4 border-y border-gray-800 my-4 pl-4 space-y-3 opacity-90">
                            <ul class="space-y-3">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between items-start text-base">
                                        <div class="flex gap-3">
                                            <span class="font-bold text-blue-400 min-w-[1.5rem]">{{ $item->quantity }}x</span>
                                            <span class="text-gray-200">{{ $item->product ? $item->product->name : 'Menu Dihapus' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="pl-4">
                            <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg hover:shadow-green-500/25">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Selesai Masak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
                        <p class="text-gray-500 font-medium">Tidak ada antrian masak</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Ready Column -->
        <div class="flex flex-col gap-6">
             <div class="bg-green-500/10 p-5 rounded-2xl border border-green-500/20 flex justify-between items-center sticky top-24 backdrop-blur-md z-10 shadow-lg">
                <h2 class="text-xl font-bold text-green-500 flex items-center gap-3">
                    <div class="p-2 bg-green-500/20 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    Siap Saji
                </h2>
                <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-green-500/20">{{ $orders->where('status', 'ready')->count() }}</span>
            </div>

            <div class="space-y-6">
                 @forelse($orders->where('status', 'ready') as $order)
                    <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border border-gray-800 shadow-xl hover:shadow-green-500/10 transition-all relative overflow-hidden group">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-500"></div>
                        
                        <div class="flex justify-between items-start mb-6 pl-4">
                            <div>
                                <h3 class="text-2xl font-bold mb-1">Meja {{ $order->table_number }}</h3>
                                <p class="text-xs text-green-400 font-bold uppercase tracking-wider flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span>
                                    Menunggu Diantar
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                        </div>
                        
                         <div class="py-4 border-y border-gray-800 my-4 pl-4 space-y-3">
                            <ul class="space-y-3">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between items-start text-base">
                                        <div class="flex gap-3">
                                            <span class="font-bold text-green-500 min-w-[1.5rem]">{{ $item->quantity }}x</span>
                                            <span class="text-gray-200">{{ $item->product ? $item->product->name : 'Menu Dihapus' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="pl-4">
                            <form action="{{ route('cashier.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Sudah Diantar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
                        <p class="text-gray-500 font-medium">Tidak ada pesanan menunggu diantar</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-dashboard-layout>
