@extends('layouts.publiclayout')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    .order-card { border-radius:12px; }
    .order-badge { font-size:0.85rem; }
    /* Mobile tabs: single-line horizontal scroll */
    .mobile-tabs-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 6px;
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }
    .mobile-tabs-wrapper .nav {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        white-space: nowrap;
    }
    .mobile-tabs-wrapper .nav-item { flex: 0 0 auto; }
    .mobile-tabs-wrapper .nav-link { white-space: nowrap; }
    /* Optional: hide horizontal scrollbar in WebKit while still scrollable */
    .mobile-tabs-wrapper::-webkit-scrollbar { height: 8px; }
    .mobile-tabs-wrapper::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Pesanan Saya</h4>
                <p class="small text-muted mb-0">Riwayat pesanan Anda</p>
            </div>

            <!-- Desktop: filter -->
            <div class="d-none d-lg-block">
                <form method="get" class="d-flex align-items-center">
                    <select name="tab" class="form-select form-select-sm me-2">
                        <option value="semua" {{ ($tab ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="belum_bayar" {{ ($tab ?? '') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="dikemas" {{ ($tab ?? '') === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                        <option value="dikirim" {{ ($tab ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ ($tab ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="pengembalian" {{ ($tab ?? '') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        <option value="dibatalkan" {{ ($tab ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button class="btn btn-outline-primary btn-sm" type="submit">Filter</button>
                </form>
            </div>
        </div>

        <!-- Mobile: tabs (horizontally scrollable) -->
        <div class="mobile-tabs-wrapper d-lg-none mb-3">
            <ul class="nav nav-tabs" role="tablist">
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
            <div class="text-center text-muted py-5">Belum ada pesanan untuk filter ini.</div>
        @else
            <div class="row g-3">
                @foreach($orders as $order)
                    <div class="col-12">
                        <div id="order-card-{{ $order->order_number }}" data-order-number="{{ $order->order_number }}" class="card order-card p-3 glass-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">No. Pesanan: {{ $order->order_number }}</div>
                                    <div class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    <div class="small mt-2">
                                        @foreach($order->items as $it)
                                            <div>{{ $it->product_name }} x{{ $it->qty }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">Rp {{ number_format($order->total_amount) }}</div>
                                    <div class="mt-2">
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            switch($order->status) {
                                                case 'pending': $badgeClass = 'bg-warning text-dark'; break;
                                                case 'paid': $badgeClass = 'bg-success'; break;
                                                case 'cancelled': $badgeClass = 'bg-danger'; break;
                                            }
                                        @endphp
                                        <span id="orderStatus-{{ $order->order_number }}" class="badge order-badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                        <div id="orderFulfillment-{{ $order->order_number }}" class="small text-muted mt-1">{{ $order->fulfillment_status ? ucfirst($order->fulfillment_status) : '' }}</div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    </div>
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
            // Subscribe to each order channel for realtime updates
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
                                        // update text and classes
                                        statusEl.innerText = (status === 'pending') ? 'Pending' : (status === 'paid' ? 'Paid' : status.charAt(0).toUpperCase() + status.slice(1));
                                        statusEl.classList.remove('bg-warning','text-dark','bg-success','bg-danger','bg-secondary');
                                        if (status === 'pending') statusEl.classList.add('bg-warning','text-dark');
                                        else if (status === 'paid') statusEl.classList.add('bg-success');
                                        else if (status === 'cancelled') statusEl.classList.add('bg-danger');
                                        else statusEl.classList.add('bg-secondary');
                                    }
                                    const fulfillEl = document.getElementById('orderFulfillment-' + orderNumber);
                                    if (fulfillEl && fulfillment) {
                                        fulfillEl.innerText = fulfillment.charAt(0).toUpperCase() + fulfillment.slice(1);
                                    }

                                    // show a small toast notification for the user
                                    const toastContainer = document.querySelector('.toast-container');
                                    if (toastContainer) {
                                        const shortMsg = `Pesanan ${orderNumber} diperbarui: ${status || ''} ${fulfillment ? (' - ' + fulfillment) : ''}`;
                                        const html = `\n<div class="toast align-items-center text-bg-primary border-0 glass-card" role="alert" aria-live="assertive" aria-atomic="true">\n  <div class="d-flex">\n    <div class="toast-body">${shortMsg}</div>\n    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>\n  </div>\n</div>`;
                                        toastContainer.insertAdjacentHTML('beforeend', html);
                                        const newToastEl = toastContainer.lastElementChild;
                                        const newToast = new bootstrap.Toast(newToastEl, { delay: 4000 });
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
