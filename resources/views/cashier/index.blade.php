<x-dashboard-layout title="Kitchen Display System">
    <div id="cashier-app">
        <div class="mb-6 sm:mb-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-1 sm:mb-2">Dapur & Kasir</h1>
                    <p class="text-gray-400 text-sm sm:text-base">Pantau dan kelola pesanan masuk secara real-time</p>
                </div>
                <div class="flex items-center gap-3">
                    <div id="polling-status" class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span>Live</span>
                    </div>
                    <button onclick="fetchOrders()" class="bg-[var(--color-dark-card)] hover:bg-[var(--color-primary)] hover:text-white text-gray-300 px-4 py-2 rounded-lg transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- Status Legend -->
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-400">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-yellow-500"></span>
                    <span>Pending (<span id="count-pending">{{ $orders->where('status', 'pending')->count() }}</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-blue-500"></span>
                    <span>Proses (<span id="count-processing">{{ $orders->where('status', 'processing')->count() }}</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-green-500"></span>
                    <span>Siap (<span id="count-ready">{{ $orders->where('status', 'ready')->count() }}</span>)</span>
                </div>
            </div>
        </div>

        <div id="success-alert" class="hidden bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-xl mb-6 backdrop-blur-sm flex items-center justify-between">
            <span id="success-message"></span>
            <button onclick="document.getElementById('success-alert').classList.add('hidden')" class="text-green-500 hover:text-green-300">&times;</button>
        </div>

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
                    <span id="badge-pending" class="bg-yellow-500 text-black text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-yellow-500/20">{{ $orders->where('status', 'pending')->count() }}</span>
                </div>
                
                <div id="orders-pending" class="space-y-6">
                    @forelse($orders->where('status', 'pending') as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'type' => 'pending'])
                    @empty
                        <div class="empty-state text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
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
                    <span id="badge-processing" class="bg-blue-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-blue-500/20">{{ $orders->where('status', 'processing')->count() }}</span>
                </div>

                <div id="orders-processing" class="space-y-6">
                    @forelse($orders->where('status', 'processing') as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'type' => 'processing'])
                    @empty
                        <div class="empty-state text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
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
                    <span id="badge-ready" class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg shadow-green-500/20">{{ $orders->where('status', 'ready')->count() }}</span>
                </div>

                <div id="orders-ready" class="space-y-6">
                    @forelse($orders->where('status', 'ready') as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'type' => 'ready'])
                    @empty
                        <div class="empty-state text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
                            <p class="text-gray-500 font-medium">Tidak ada pesanan menunggu diantar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        const POLL_INTERVAL = 5000; // 5 seconds
        let pollTimer = null;
        let lastOrderIds = {
            pending: [],
            processing: [],
            ready: []
        };

        // Store initial order IDs
        document.addEventListener('DOMContentLoaded', function() {
            @foreach(['pending', 'processing', 'ready'] as $status)
                lastOrderIds['{{ $status }}'] = [
                    @foreach($orders->where('status', $status) as $order)
                        '{{ $order->id }}',
                    @endforeach
                ];
            @endforeach
            
            startPolling();
        });

        function startPolling() {
            pollTimer = setInterval(fetchOrders, POLL_INTERVAL);
        }

        function stopPolling() {
            if (pollTimer) {
                clearInterval(pollTimer);
            }
        }

        async function fetchOrders() {
            try {
                const response = await fetch('{{ route("cashier.ordersJson") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                
                // Update counts
                document.getElementById('count-pending').textContent = data.counts.pending;
                document.getElementById('count-processing').textContent = data.counts.processing;
                document.getElementById('count-ready').textContent = data.counts.ready;
                document.getElementById('badge-pending').textContent = data.counts.pending;
                document.getElementById('badge-processing').textContent = data.counts.processing;
                document.getElementById('badge-ready').textContent = data.counts.ready;

                // Check for new orders and update UI
                const currentIds = {
                    pending: data.orders.filter(o => o.status === 'pending').map(o => o.id),
                    processing: data.orders.filter(o => o.status === 'processing').map(o => o.id),
                    ready: data.orders.filter(o => o.status === 'ready').map(o => o.id)
                };

                // Check if there are changes
                let hasChanges = false;
                ['pending', 'processing', 'ready'].forEach(status => {
                    if (JSON.stringify(currentIds[status].sort()) !== JSON.stringify(lastOrderIds[status].sort())) {
                        hasChanges = true;
                    }
                });

                if (hasChanges) {
                    // Play notification sound for new pending orders
                    const newPending = currentIds.pending.filter(id => !lastOrderIds.pending.includes(id));
                    if (newPending.length > 0) {
                        playNotificationSound();
                    }

                    // Update the columns
                    updateOrderColumn('pending', data.orders.filter(o => o.status === 'pending'));
                    updateOrderColumn('processing', data.orders.filter(o => o.status === 'processing'));
                    updateOrderColumn('ready', data.orders.filter(o => o.status === 'ready'));

                    lastOrderIds = currentIds;
                }

            } catch (error) {
                console.error('Error fetching orders:', error);
            }
        }

        function updateOrderColumn(status, orders) {
            const container = document.getElementById(`orders-${status}`);
            
            if (orders.length === 0) {
                const emptyMessages = {
                    pending: 'Belum ada pesanan baru',
                    processing: 'Tidak ada antrian masak',
                    ready: 'Tidak ada pesanan menunggu diantar'
                };
                container.innerHTML = `
                    <div class="empty-state text-center py-20 border-2 border-dashed border-gray-800 rounded-2xl opacity-50">
                        <p class="text-gray-500 font-medium">${emptyMessages[status]}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = orders.map(order => renderOrderCard(order, status)).join('');
        }

        function renderOrderCard(order, status) {
            const colors = {
                pending: { border: 'yellow-500', text: 'yellow-500', bg: 'yellow-500' },
                processing: { border: 'blue-500', text: 'blue-400', bg: 'blue-500' },
                ready: { border: 'green-500', text: 'green-500', bg: 'green-500' }
            };
            const color = colors[status];

            const nextStatus = {
                pending: { value: 'processing', label: 'Mulai Masak', btnClass: 'bg-blue-600 hover:bg-blue-500 shadow-lg hover:shadow-blue-500/25' },
                processing: { value: 'ready', label: 'Selesai Masak', btnClass: 'bg-green-600 hover:bg-green-500 shadow-lg hover:shadow-green-500/25' },
                ready: { value: 'delivered', label: 'Sudah Diantar', btnClass: 'bg-gray-700 hover:bg-gray-600' }
            };
            const next = nextStatus[status];

            const itemsHtml = order.items.map(item => `
                <li class="flex justify-between items-start text-base">
                    <div class="flex gap-3">
                        <span class="font-bold text-${color.text} min-w-[1.5rem]">${item.quantity}x</span>
                        <span class="text-gray-200">${item.product_name}</span>
                    </div>
                </li>
            `).join('');

            const notesHtml = order.notes ? `
                <div class="mt-4 bg-yellow-500/10 border border-yellow-500/20 p-3 rounded-xl text-sm text-yellow-200 italic flex gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="min-w-[16px] mt-0.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    "${order.notes}"
                </div>
            ` : '';

            return `
                <div class="bg-[var(--color-dark-card)] rounded-2xl p-6 border border-gray-800 shadow-xl hover:shadow-${color.bg}/10 transition-all group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-${color.bg}"></div>
                    
                    <div class="flex justify-between items-start mb-6 pl-4">
                        <div>
                            <h3 class="text-${status === 'pending' ? '3xl' : '2xl'} font-bold mb-1">Meja ${order.table_number}</h3>
                            <div class="flex items-center gap-2 text-gray-400 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                ${status === 'pending' ? order.created_at : order.updated_at}
                            </div>
                        </div>
                        <span class="bg-gray-800 text-gray-400 px-3 py-1 rounded-full text-xs font-mono border border-gray-700">#${String(order.id).slice(-4)}</span>
                    </div>
                    
                    <div class="py-4 border-y border-gray-800 my-4 pl-4 space-y-3">
                        <ul class="space-y-3">${itemsHtml}</ul>
                        ${notesHtml}
                    </div>

                    <div class="flex flex-col gap-3 pl-4">
                        ${status === 'pending' ? `
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                ${order.customer_name}
                            </div>
                        ` : ''}
                        <button onclick="updateOrderStatus('${order.id}', '${next.value}')" class="w-full ${next.btnClass} text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                            ${next.label}
                        </button>
                    </div>
                </div>
            `;
        }

        async function updateOrderStatus(orderId, newStatus) {
            try {
                const response = await fetch(`/cashier/order/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (!response.ok) throw new Error('Failed to update status');

                const data = await response.json();
                
                // Show success message
                const alertEl = document.getElementById('success-alert');
                document.getElementById('success-message').textContent = data.message;
                alertEl.classList.remove('hidden');
                setTimeout(() => alertEl.classList.add('hidden'), 3000);

                // Immediately fetch updated orders
                await fetchOrders();

            } catch (error) {
                console.error('Error updating status:', error);
                alert('Gagal mengubah status pesanan. Silakan coba lagi.');
            }
        }

        function playNotificationSound() {
            // Create a simple beep sound using Web Audio API
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                gainNode.gain.value = 0.3;
                
                oscillator.start();
                setTimeout(() => oscillator.stop(), 200);
            } catch (e) {
                console.log('Audio notification not supported');
            }
        }

        // Pause polling when tab is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopPolling();
            } else {
                fetchOrders(); // Fetch immediately when tab becomes visible
                startPolling();
            }
        });
    </script>
</x-dashboard-layout>
