@extends('layouts.publiclayout')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    /* Utility Classes Custom Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d82 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .text-glass-blue {
        color: #64748b;
    }

    .order-card { 
        border-radius: 24px; 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 86, 179, 0.08) !important;
        border-color: rgba(0, 123, 255, 0.2);
    }
    
    /* Custom Select Glass (Desktop Filter) */
    .custom-select-glass {
        background-color: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 123, 255, 0.15);
        color: #334155;
        border-radius: 12px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: all 0.3s;
    }
    .custom-select-glass:focus {
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }

    /* Badges Glassmorphism Modern Pastel */
    .badge-glass-pending { background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%); border: 1px solid rgba(245, 158, 11, 0.2); color: #d97706; }
    .badge-glass-success { background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2); color: #059669; }
    .badge-glass-danger { background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%); border: 1px solid rgba(239, 68, 68, 0.2); color: #dc2626; }
    .badge-glass-secondary { background: linear-gradient(135deg, rgba(100, 116, 139, 0.15) 0%, rgba(100, 116, 139, 0.05) 100%); border: 1px solid rgba(100, 116, 139, 0.2); color: #475569; }

    /* Mobile Tabs (Pill Glass) */
    .mobile-tabs-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 12px;
        margin-left: -0.5rem;
        margin-right: -0.5rem;
        scrollbar-width: none; /* Firefox */
    }
    .mobile-tabs-wrapper::-webkit-scrollbar { display: none; /* Safari and Chrome */ }
    
    .mobile-tabs-wrapper .nav {
        display: flex;
        gap: 0.8rem;
        flex-wrap: nowrap;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .mobile-tabs-wrapper .nav-item { flex: 0 0 auto; }
    .mobile-tabs-wrapper .nav-link { 
        white-space: nowrap; 
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 123, 255, 0.1);
        color: #64748b;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        transition: all 0.3s;
    }
    .mobile-tabs-wrapper .nav-link:hover {
        background: #ffffff;
        border-color: rgba(0, 123, 255, 0.3);
        color: var(--primary-blue);
        transform: translateY(-2px);
    }
    .mobile-tabs-wrapper .nav-link.active {
        background: var(--gradient-primary);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 15px rgba(0, 86, 179, 0.25);
    }
    
    /* Product Item List Inner */
    .order-item-list {
        background: #f8fafc;
        border: 1px solid rgba(0, 123, 255, 0.08);
        border-radius: 16px;
        padding: 12px 16px;
    }
    
    .order-item-row:not(:last-child) {
        border-bottom: 1px dashed rgba(0,0,0,0.05);
        padding-bottom: 8px;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="row fade-in-up">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-4 gap-3 pt-2">
            <div>
                <h3 class="fw-bold text-gradient-blue mb-1" style="letter-spacing: -0.5px;">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-primary opacity-75"></i> Pesanan Saya
                </h3>
                <p class="text-secondary small mb-0 ms-1">Pantau riwayat dan status pengiriman pesanan Anda.</p>
            </div>

            <div id="desktopFilterWrapper" class="d-none d-lg-block">
                <form method="get" class="d-flex align-items-center bg-white p-2 rounded-pill shadow-sm border border-light">
                    <select name="tab" id="desktopTabSelect" class="form-select border-0 shadow-none bg-transparent fw-medium text-secondary" style="min-width: 180px; cursor: pointer;">
                        <option value="semua" {{ ($tab ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Pesanan</option>
                        <option value="belum_bayar" {{ ($tab ?? '') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="dikemas" {{ ($tab ?? '') === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                        <option value="dikirim" {{ ($tab ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ ($tab ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="pengembalian" {{ ($tab ?? '') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        <option value="dibatalkan" {{ ($tab ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </form>
            </div>
        </div>

        <div id="mobileFilterWrapper" class="mobile-tabs-wrapper d-lg-none mb-4 mt-2">
            <ul class="nav" role="tablist">
            @php
                $tabs = ['semua'=>'Semua', 'belum_bayar'=>'Belum Bayar', 'dikemas'=>'Dikemas', 'dikirim'=>'Dikirim', 'selesai'=>'Selesai', 'pengembalian'=>'Retur', 'dibatalkan'=>'Dibatalkan'];
                $current = $tab ?? 'semua';
            @endphp
            @foreach($tabs as $key => $label)
                <li class="nav-item" role="presentation">
                    <a class="nav-link order-filter-link {{ $current === $key ? 'active' : '' }}" href="?tab={{ $key }}" data-tab="{{ $key }}">{{ $label }}</a>
                </li>
            @endforeach
            </ul>
        </div>

        <div id="ordersContent">
        @if($orders->isEmpty())
            <div class="glass-card text-center py-5 border-0 shadow-sm mt-3" style="border-radius: 24px;">
                <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-4" style="width: 90px; height: 90px;">
                    <i class="fa-solid fa-receipt fa-2x opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Tidak Ada Pesanan</h4>
                <p class="text-secondary small mb-4">Anda belum memiliki pesanan dalam kategori ini.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-custom-primary rounded-pill px-5 py-2 shadow-sm fw-bold">Mulai Belanja</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-12">
                        <div id="order-card-{{ $order->order_number }}" data-order-number="{{ $order->order_number }}" class="glass-card order-card p-4 border-0 shadow-sm bg-white">
                            
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center border-bottom border-secondary border-opacity-10 pb-3 mb-3 gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-none d-sm-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 42px; height: 42px;">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold mb-1" style="font-size: 1.05rem;">
                                            {{ $order->order_number }}
                                        </div>
                                        <div class="small text-secondary fw-medium">
                                            <i class="fa-regular fa-calendar me-1"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-start text-sm-end d-flex flex-row flex-sm-column align-items-center align-items-sm-end gap-2 gap-sm-1 w-100 w-sm-auto justify-content-between">
                                    @php
                                        $badgeClass = 'badge-glass-secondary';
                                        switch($order->status) {
                                            case 'pending': $badgeClass = 'badge-glass-pending'; break;
                                            case 'paid': $badgeClass = 'badge-glass-success'; break;
                                            case 'cancelled': $badgeClass = 'badge-glass-danger'; break;
                                        }
                                    @endphp
                                    <span id="orderStatus-{{ $order->order_number }}" class="badge rounded-pill px-3 py-2 {{ $badgeClass }} fw-bold" style="font-size: 0.8rem; letter-spacing: 0.3px;">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                    
                                    @if($order->fulfillment_status)
                                        <div id="orderFulfillment-{{ $order->order_number }}" class="small fw-bold text-primary mt-sm-1">
                                            <i class="fa-solid fa-truck-fast me-1 opacity-75"></i> {{ ucfirst($order->fulfillment_status) }}
                                        </div>
                                    @else
                                        <div id="orderFulfillment-{{ $order->order_number }}" class="small fw-bold text-primary mt-sm-1" style="display:none;"></div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4">
                                
                                <div class="flex-grow-1 order-item-list w-100">
                                    @foreach($order->items as $it)
                                        <div class="d-flex justify-content-between align-items-center order-item-row small">
                                            <span class="fw-semibold text-dark text-truncate pe-3" style="max-width: 85%;">{{ $it->product->name ?? 'Produk tidak ditemukan' }}</span>
                                            <span class="text-secondary fw-bold px-2 py-1 bg-white rounded-pill border shadow-sm" style="font-size: 0.75rem;">x{{ $it->qty }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end w-100 w-md-auto mt-1 mt-md-0 gap-3">
                                    <div class="text-start text-md-end">
                                        <div class="small text-secondary mb-1 fw-medium">Total Belanja</div>
                                        <div class="fw-bold fs-5 text-gradient-blue lh-1 mb-md-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-outline-primary fw-semibold btn-sm px-4 py-2 rounded-pill shadow-sm text-nowrap transition-smooth bg-white">
                                        Lihat Detail <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.8em;"></i>
                                    </a>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    const subscribedOrders = new Set();

    async function fetchOrdersByTab(tab) {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tab || 'semua');

        const res = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        });
        if (!res.ok) throw new Error('Gagal memuat data pesanan.');

        const html = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newDesktop = doc.getElementById('desktopFilterWrapper');
        const newMobile = doc.getElementById('mobileFilterWrapper');
        const newContent = doc.getElementById('ordersContent');

        if (!newDesktop || !newMobile || !newContent) {
            throw new Error('Struktur halaman tidak sesuai untuk pembaruan AJAX.');
        }

        const oldDesktop = document.getElementById('desktopFilterWrapper');
        const oldMobile = document.getElementById('mobileFilterWrapper');
        const oldContent = document.getElementById('ordersContent');

        if (oldDesktop) oldDesktop.replaceWith(newDesktop);
        if (oldMobile) oldMobile.replaceWith(newMobile);
        if (oldContent) oldContent.replaceWith(newContent);

        window.history.replaceState({}, '', url.toString());
        bindFilterEvents();
        initRealtimeSubscriptions();
    }

    function bindFilterEvents() {
        const desktopForm = document.querySelector('#desktopFilterWrapper form');
        const desktopSelect = document.getElementById('desktopTabSelect');

        if (desktopSelect) {
            desktopSelect.addEventListener('change', function () {
                const tab = this.value || 'semua';
                fetchOrdersByTab(tab).catch(err => console.error(err));
            });
        }

        if (desktopForm) {
            desktopForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const tab = desktopForm.querySelector('select[name="tab"]')?.value || 'semua';
                fetchOrdersByTab(tab).catch(err => console.error(err));
            });
        }

        document.querySelectorAll('.order-filter-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const tab = this.getAttribute('data-tab') || 'semua';
                fetchOrdersByTab(tab).catch(err => console.error(err));
            });
        });
    }

    function initRealtimeSubscriptions() {
    // Berlangganan (Subscribe) ke channel spesifik untuk setiap pesanan agar menerima update Realtime (WebSocket Reverb)
    document.querySelectorAll('[data-order-number]').forEach(el => {
        const orderNumber = el.getAttribute('data-order-number');
        if (!orderNumber || subscribedOrders.has(orderNumber)) return;
        subscribedOrders.add(orderNumber);
        
        try {
            if (window.Echo && typeof window.Echo.channel === 'function') {
                window.Echo.channel(`order.${orderNumber}`)
                    .listen('OrderStatusChanged', (e) => {
                        try {
                            const status = e.status || null;
                            const fulfillment = e.fulfillment_status || null;
                            const statusEl = document.getElementById('orderStatus-' + orderNumber);
                            
                            if (statusEl && status) {
                                statusEl.innerText = (status === 'pending') ? 'PENDING' : (status === 'paid' ? 'PAID' : status.toUpperCase());
                                
                                // Reset classes
                                statusEl.classList.remove('badge-glass-pending','badge-glass-success','badge-glass-danger','badge-glass-secondary');
                                
                                // Apply new class
                                if (status === 'pending') statusEl.classList.add('badge-glass-pending');
                                else if (status === 'paid') statusEl.classList.add('badge-glass-success');
                                else if (status === 'cancelled') statusEl.classList.add('badge-glass-danger');
                                else statusEl.classList.add('badge-glass-secondary');
                            }
                            
                            const fulfillEl = document.getElementById('orderFulfillment-' + orderNumber);
                            if (fulfillEl && fulfillment) {
                                fulfillEl.style.display = 'block';
                                fulfillEl.innerHTML = `<i class="fa-solid fa-truck-fast me-1 opacity-75"></i> ${fulfillment.charAt(0).toUpperCase() + fulfillment.slice(1)}`;
                            }

                            // Tampilkan notifikasi Toast Custom Glassmorphism yang baru
                            const toastContainer = document.querySelector('.toast-container');
                            if (toastContainer) {
                                const shortMsg = `Pesanan <b>${orderNumber}</b> diperbarui menjadi <b>${status || ''}</b> ${fulfillment ? (' (' + fulfillment + ')') : ''}`;
                                
                                const html = `
                                <div class="toast custom-toast border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="d-flex align-items-center p-3">
                                        <div class="toast-icon-wrapper me-3" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0.05) 100%); border: 1px solid rgba(14, 165, 233, 0.2);">
                                            <i class="fa-solid fa-bell fs-5" style="color: #0ea5e9;"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 me-2 min-w-0">
                                            <span class="toast-text-title mb-1">Status Diperbarui</span>
                                            <span class="toast-text-desc lh-sm text-truncate">${shortMsg}</span>
                                        </div>
                                        <button type="button" class="btn-close shadow-none opacity-50 ms-auto align-self-start mt-1" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>`;
                                
                                toastContainer.insertAdjacentHTML('beforeend', html);
                                const newToastEl = toastContainer.lastElementChild;
                                const newToast = new bootstrap.Toast(newToastEl, { delay: 6000 });
                                newToast.show();
                            }
                        } catch (inner) { console.error(inner); }
                    });
            }
        } catch (err) {
            console.warn('Realtime subscribe failed for order', orderNumber, err);
        }
    });
    }

    bindFilterEvents();
    initRealtimeSubscriptions();
});
</script>
@endpush