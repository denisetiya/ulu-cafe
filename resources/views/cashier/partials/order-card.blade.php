@php
$colors = [
    'pending' => ['border' => 'yellow-500', 'text' => 'yellow-500', 'bg' => 'yellow-500'],
    'processing' => ['border' => 'blue-500', 'text' => 'blue-400', 'bg' => 'blue-500'],
    'ready' => ['border' => 'green-500', 'text' => 'green-500', 'bg' => 'green-500'],
];
$color = $colors[$type];

$nextStatus = [
    'pending' => ['value' => 'processing', 'label' => 'Mulai Masak', 'btnClass' => 'bg-blue-600 hover:bg-blue-500 shadow-lg hover:shadow-blue-500/25'],
    'processing' => ['value' => 'ready', 'label' => 'Selesai Masak', 'btnClass' => 'bg-green-600 hover:bg-green-500 shadow-lg hover:shadow-green-500/25'],
    'ready' => ['value' => 'delivered', 'label' => 'Sudah Diantar', 'btnClass' => 'bg-gray-700 hover:bg-gray-600'],
];
$next = $nextStatus[$type];
@endphp

<div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border border-gray-800 shadow-xl hover:shadow-{{ $color['bg'] }}/10 transition-all group relative overflow-hidden" data-order-id="{{ $order->id }}">
    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $color['bg'] }}"></div>
    
    <div class="flex justify-between items-start mb-6 pl-4">
        <div>
            <h3 class="text-{{ $type === 'pending' ? '3xl' : '2xl' }} font-bold mb-1">Meja {{ $order->table_number }}</h3>
            <div class="flex items-center gap-2 text-gray-400 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $type === 'pending' ? $order->created_at->diffForHumans() : $order->updated_at->diffForHumans() }}
            </div>
        </div>
        @if($type === 'processing')
            <div class="animate-pulse">
                <span class="px-3 py-1 bg-blue-500/20 text-blue-500 rounded-full text-xs font-bold border border-blue-500/30">COOKING</span>
            </div>
        @elseif($type === 'ready')
            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
        @else
            <span class="bg-gray-800 text-gray-400 px-3 py-1 rounded-full text-xs font-mono border border-gray-700">#{{ substr($order->id, -4) }}</span>
        @endif
    </div>
    
    <div class="py-4 border-y border-gray-800 my-4 pl-4 space-y-3">
        <ul class="space-y-3">
            @foreach($order->items as $item)
                <li class="flex justify-between items-start text-base">
                    <div class="flex gap-3">
                        <span class="font-bold text-{{ $color['text'] }} min-w-[1.5rem]">{{ $item->quantity }}x</span>
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
        @if($type === 'pending')
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                {{ $order->customer_name }}
            </div>
        @endif
        @if($type === 'ready')
            <p class="text-xs text-green-400 font-bold uppercase tracking-wider flex items-center gap-1 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span>
                Menunggu Diantar
            </p>
        @endif
        <button onclick="updateOrderStatus('{{ $order->id }}', '{{ $next['value'] }}')" class="w-full {{ $next['btnClass'] }} text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
            @if($type === 'pending')
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            @elseif($type === 'processing')
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            @endif
            {{ $next['label'] }}
        </button>
    </div>
</div>
