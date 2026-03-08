@extends('layouts.publiclayout')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    /* Utility Classes Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .text-glass-blue {
        color: rgba(0, 50, 120, 0.6);
    }

    .order-card { 
        border-radius: 16px; 
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    /* Custom Select Glass (Desktop Filter) */
    .custom-select-glass {
        background-color: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        border-radius: 10px;
        transition: all 0.3s;
    }
    .custom-select-glass:focus {
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }

    /* Badges Glassmorphism */
    .badge-glass-pending { background: rgba(255, 193, 7, 0.15); border: 1px solid rgba(255, 193, 7, 0.3); color: #b08d00; }
    .badge-glass-paid { background: rgba(25, 135, 84, 0.15); border: 1px solid rgba(25, 135, 84, 0.3); color: #157347; }
    .badge-glass-danger { background: rgba(220, 53, 69, 0.15); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }
    .badge-glass-secondary { background: rgba(108, 117, 125, 0.1); border: 1px solid rgba(108, 117, 125, 0.2); color: rgba(0, 50, 120, 0.6); }

    /* Tombol Glass Outline */
    .btn-glass-outline {
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(0, 123, 255, 0.3);
        color: #0056b3;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .btn-glass-outline:hover {
        background: rgba(0, 123, 255, 0.1);
        border-color: #007bff;
    }

    /* Mobile Tabs (Pill Glass) */
    .mobile-tabs-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }
    .mobile-tabs-wrapper .nav {
        display: flex;
        gap: 0.6rem;
        flex-wrap: nowrap;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .mobile-tabs-wrapper .nav-item { flex: 0 0 auto; }
    .mobile-tabs-wrapper .nav-link { 
        white-space: nowrap; 
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 86, 179, 0.15);
        color: rgba(0, 50, 120, 0.8);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    .mobile-tabs-wrapper .nav-link.active {
        background: var(--gradient-primary);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }
    .mobile-tabs-wrapper::-webkit-scrollbar { height: 4px; }
    .mobile-tabs-wrapper::-webkit-scrollbar-thumb { background: rgba(0, 123, 255, 0.2); border-radius: 8px; }
    
    /* Product Item List Inner */
    .order-item-list {
        background: rgba(0, 86, 179, 0.03);
        border-radius: 10px;
        padding: 10px;
    }
</style>
@endpush

@section('content')
<div class="row fade-in-up">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-gradient-blue">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Pesanan Saya
                </h4>
                <p class="small text-glass-blue mb-0">Pantau riwayat dan status pengiriman pesanan Anda.</p>
            </div>

            <div class="d-none d-lg-block">
                <form method="get" class="d-flex align-items-center">
                    <select name="tab" class="form-select form-select-sm me-2 custom-select-glass px-3 py-2">
                        <option value="semua" {{ ($tab ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Pesanan</option>
                        <option value="belum_bayar" {{ ($tab ?? '') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="dikemas" {{ ($tab ?? '') === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                        <option value="dikirim" {{ ($tab ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ ($tab ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="pengembalian" {{ ($tab ?? '') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        <option value="dibatalkan" {{ ($tab ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button class="btn btn-custom-primary btn-sm rounded-pill px-3 shadow-sm" type="submit">Terapkan</button>
                </form>
            </div>
        </div>

        <div class="mobile-tabs-wrapper d-lg-none mb-4">
            <ul class="nav" role="tablist">
            @php
                $tabs = ['semua'=>'Semua','belum_bayar'=>'Belum Bayar','dikemas'=>'Dikemas','dikirim'=>'Dikirim','selesai'=>'Selesai','pengembalian'=>'Pengembalian','dibatalkan'=>'Dibatalkan'];
                $current = $tab ?? 'semua';
            @endphp
            @foreach($tabs as $key => $label)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $current === $key ? 'active' : '' }}" href="?tab={{ $key }}">{{ $label }}</a>
                </li>
            @endforeach
            </ul>
        </div>

        @if($orders->isEmpty())
            <div class="glass-card text-center py-5 border-0 shadow-sm mt-2">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-receipt fa-2x opacity-75"></i>
                </div>
                <h5 class="fw-bold text-gradient-blue mb-1">Tidak Ada Pesanan</h5>
                <p class="text-glass-blue small mb-0">Anda belum memiliki pesanan dalam kategori ini.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-12">
                        <div id="order-card-{{ $order->order_number }}" data-order-number="{{ $order->order_number }}" class="glass-card order-card p-4 border-0 shadow-sm">
                            
                            <div class="d-flex justify-content-between align-items-start border-bottom border-light pb-3 mb-3">
                                <div>
                                    <div class="text-glass-blue small mb-1 fw-medium"><i class="fa-solid fa-hashtag me-1"></i> {{ $order->order_number }}</div>
                                    <div class="small text-glass-blue">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                                <div class="text-end">
                                    @php
                                        $badgeClass = 'badge-glass-secondary';
                                        switch($order->status) {
                                            case 'pending': $badgeClass = 'badge-glass-pending'; break;
                                            case 'paid': $badgeClass = 'badge-glass-success'; break;
                                            case 'cancelled': $badgeClass = 'badge-glass-danger'; break;
                                        }
                                    @endphp
                                    <span id="orderStatus-{{ $order->order_number }}" class="badge rounded-pill px-3 {{ $badgeClass }} shadow-sm">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <div id="orderFulfillment-{{ $order->order_number }}" class="small mt-1 fw-semibold" style="color: var(--primary-blue);">
                                        {{ $order->fulfillment_status ? ucfirst($order->fulfillment_status) : '' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
                                <div class="flex-grow-1 order-item-list w-100">
                                    @foreach($order->items as $it)
                                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                                            <span class="fw-semibold text-dark">{{ $it->product_name }}</span>
                                            <span class="text-glass-blue">x{{ $it->qty }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="text-end d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end w-100 w-md-auto mt-2 mt-md-0">
                                    <div class="text-start text-md-end">
                                        <div class="small text-glass-blue mb-1">Total Belanja</div>
                                        <div class="fw-bold fs-5 text-gradient-blue lh-1 mb-md-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-glass-outline btn-sm px-4 rounded-pill">
                                        Lihat Detail
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
@endsection

@push('scripts')
@vite(['resources/js/app.js'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Berlangganan (Subscribe) ke channel spesifik untuk setiap pesanan agar menerima update Realtime (WebSocket Reverb)
    document.querySelectorAll('[data-order-number]').forEach(el => {
        const orderNumber = el.getAttribute('data-order-number');
        if (!orderNumber) return;
        
        try {
            if (window.Echo && typeof window.Echo.channel === 'function') {
                window.Echo.channel(`order.${orderNumber}`)
                    .listen('OrderStatusChanged', (e) => {
                        try {
                            const status = e.status || null;
                            const fulfillment = e.fulfillment_status || null;
                            const statusEl = document.getElementById('orderStatus-' + orderNumber);
                            
                            if (statusEl && status) {
                                statusEl.innerText = (status === 'pending') ? 'Pending' : (status === 'paid' ? 'Paid' : status.charAt(0).toUpperCase() + status.slice(1));
                                
                                // Ganti badge dengan class glassmorphism kita
                                statusEl.classList.remove('badge-glass-pending','badge-glass-success','badge-glass-danger','badge-glass-secondary');
                                
                                if (status === 'pending') statusEl.classList.add('badge-glass-pending');
                                else if (status === 'paid') statusEl.classList.add('badge-glass-success');
                                else if (status === 'cancelled') statusEl.classList.add('badge-glass-danger');
                                else statusEl.classList.add('badge-glass-secondary');
                            }
                            
                            const fulfillEl = document.getElementById('orderFulfillment-' + orderNumber);
                            if (fulfillEl && fulfillment) {
                                fulfillEl.innerText = fulfillment.charAt(0).toUpperCase() + fulfillment.slice(1);
                            }

                            // Tampilkan notifikasi Toast Custom Glassmorphism
                            const toastContainer = document.querySelector('.toast-container');
                            if (toastContainer) {
                                const shortMsg = `Pesanan <b>${orderNumber}</b> diperbarui menjadi <b>${status || ''}</b> ${fulfillment ? (' (' + fulfillment + ')') : ''}`;
                                
                                const html = `
                                <div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="d-flex align-items-center p-2">
                                        <div class="toast-body d-flex align-items-center fw-medium flex-grow-1 text-glass-blue">
                                            <i class="fa-solid fa-bell fs-4 me-3 text-primary"></i>
                                            <span class="lh-sm">${shortMsg}</span>
                                        </div>
                                        <button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>`;
                                
                                toastContainer.insertAdjacentHTML('beforeend', html);
                                const newToastEl = toastContainer.lastElementChild;
                                const newToast = new bootstrap.Toast(newToastEl, { delay: 5000 });
                                newToast.show();
                            }
                        } catch (inner) { console.error(inner); }
                    });
            }
        } catch (err) {
            console.warn('Realtime subscribe failed for order', orderNumber, err);
        }
    });
});
</script>
@endpush