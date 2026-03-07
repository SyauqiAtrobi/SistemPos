@extends('layouts.publiclayout')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--light-blue);
        color: var(--primary-blue);
        border: none;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background-color: var(--primary-blue);
        color: white;
    }

    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: var(--primary-blue);
    }

    .qty-input:focus {
        outline: none;
    }

    /* Summary Card Sticky on Desktop */
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4 fade-in-up">
    <h3 class="fw-bold" style="color: var(--primary-blue);">
        <i class="fa-solid fa-cart-shopping me-2"></i> Keranjang Anda
    </h3>
    <p class="text-muted small">Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.</p>
</div>

<div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-lg-8">
        
        @forelse($carts as $cart)
        <div class="glass-card mb-3 p-3 border-0 d-flex flex-row align-items-center cart-row" data-cart-id="{{ $cart->id }}" data-delete-url="{{ route('cart.remove', $cart->id) }}" data-product-id="{{ $cart->product->id }}">

            <img src="{{ $cart->product->image ? asset('storage/'.$cart->product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=100&q=80' }}" 
                 alt="{{ $cart->product->name }}" class="cart-item-img me-3 shadow-sm">
                 
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px;">{{ $cart->product->name }}</h6>
                <div class="text-muted small mb-2">{{ $cart->product->category->name ?? 'Varian' }}</div>
                <div class="fw-bold" style="color: var(--primary-blue);">
                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="d-flex flex-column align-items-end justify-content-between h-100">

                <form id="delete-form-{{ $cart->id }}" action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mb-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm text-danger opacity-75 hover-opacity-100 p-0 border-0 bg-transparent delete-btn" data-cart-id="{{ $cart->id }}">
                        <i class="fa-solid fa-trash-can fs-5"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center rounded-pill px-2 py-1" style="background-color: var(--soft-white); border: 1px solid rgba(0, 86, 179, 0.1);">
                    <form id="dec-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline me-2">
                        @csrf
                        <input type="hidden" name="qty" value="-1">
                        <button type="button" class="qty-btn btn-decrement" data-cart-id="{{ $cart->id }}">-</button>
                    </form>

                    <span class="qty-input" id="qty-display-{{ $cart->id }}">{{ $cart->qty }}</span>

                    <form id="inc-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="button" class="qty-btn btn-increment" data-product-id="{{ $cart->product->id }}">+</button>
                    </form>
                </div>
                
            </div>
            
        </div>
        @empty
        
        <div class="glass-card text-center p-5 border-0">
            <i class="fa-solid fa-basket-shopping fa-4x mb-3 text-muted opacity-25"></i>
            <h5 class="fw-bold text-muted">Keranjang Masih Kosong</h5>
            <p class="text-muted small mb-4">Yuk, cari parfum favoritmu sekarang!</p>
            <a href="{{ url('/katalog') }}" class="btn btn-custom-primary px-4">Belanja Sekarang</a>
        </div>
        
        @endforelse

    </div>

    @if($carts->isNotEmpty())
    <div class="col-12 col-lg-4">
        <div class="glass-card p-4 border-0 sticky-summary">
            <h5 class="fw-bold mb-4" style="color: var(--primary-blue);">Ringkasan Pesanan</h5>
            
            <div class="d-flex justify-content-between mb-3 text-muted">
                <span>Total Item</span>
                <span id="total-items" class="fw-bold text-dark">{{ $carts->sum('qty') }} Barang</span>
            </div>
            
            <hr class="opacity-10 my-3">
            
            <div class="d-flex justify-content-between mb-4">
                <span class="fs-5 fw-bold text-dark">Total Tagihan</span>
                <span id="total-amount" class="fs-5 fw-bold" style="color: var(--primary-blue);">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <button type="button" class="btn btn-custom-primary w-100 shadow-sm py-2 fs-6"
                        onclick="showConfirmModal('Konfirmasi Checkout', 'Anda akan diarahkan ke halaman pembayaran QRIS untuk total Rp {{ number_format($total, 0, ',', '.') }}. Lanjutkan?', () => this.form.submit())">
                    Buat Pesanan & Bayar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-shield-halved me-1 text-success"></i> Pembayaran aman via QRIS
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const currencyFmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

    async function postForm(url, data = {}) {
        const formData = new FormData();
        for (const k in data) formData.append(k, data[k]);
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        });
        return res.json();
    }

    async function deleteRequest(url) {
        const res = await fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
        return res.json();
    }

    function refreshSummary(totalQty, totalAmount) {
        const itemsEl = document.getElementById('total-items');
        const amountEl = document.getElementById('total-amount');
        if (itemsEl) itemsEl.textContent = `${totalQty} Barang`;
        if (amountEl) amountEl.textContent = currencyFmt.format(totalAmount || 0);
    }

    // Increment buttons
    document.querySelectorAll('.btn-increment').forEach(function(btn) {
        btn.addEventListener('click', async function () {
            const productId = this.getAttribute('data-product-id');
            const row = this.closest('.cart-row');
            if (!row) return;
            const cartId = row.getAttribute('data-cart-id');
            const action = row.querySelector('#inc-form-' + cartId)?.action || row.querySelector('#inc-form-' + cartId)?.getAttribute('action') || (`/cart/add/${productId}`);
            try {
                const json = await postForm(action, { qty: 1 });
                if (json.deleted) {
                    // removed
                    row.remove();
                } else if (json.qty !== undefined) {
                    const qtyEl = document.getElementById('qty-display-' + json.cart_id);
                    if (qtyEl) qtyEl.textContent = json.qty;
                }
                refreshSummary(json.totalQty, json.totalAmount);
            } catch (err) {
                console.error(err);
            }
        });
    });

    // Decrement buttons
    document.querySelectorAll('.btn-decrement').forEach(function(btn) {
        btn.addEventListener('click', async function (e) {
            const cartId = this.getAttribute('data-cart-id');
            const row = this.closest('.cart-row');
            const qtyEl = document.getElementById('qty-display-' + cartId);
            if (!qtyEl || !row) return;
            const current = parseInt(qtyEl.textContent || '0');
            const action = row.querySelector('#dec-form-' + cartId)?.action || row.querySelector('#dec-form-' + cartId)?.getAttribute('action') || (`/cart/add/${row.getAttribute('data-product-id')}`);

            if (current <= 1) {
                showConfirmModal('Hapus Item', 'Kuantitas produk saat ini adalah 1. Apakah Anda ingin menghapus produk ini dari keranjang?', async function() {
                    const deleteUrl = row.getAttribute('data-delete-url');
                    try {
                        const json = await deleteRequest(deleteUrl);
                        if (json.success) row.remove();
                        refreshSummary(json.totalQty, json.totalAmount);
                    } catch (err) { console.error(err); }
                });
            } else {
                try {
                    const json = await postForm(action, { qty: -1 });
                    if (json.deleted) {
                        row.remove();
                    } else if (json.qty !== undefined) {
                        const qtyEl2 = document.getElementById('qty-display-' + json.cart_id);
                        if (qtyEl2) qtyEl2.textContent = json.qty;
                    }
                    refreshSummary(json.totalQty, json.totalAmount);
                } catch (err) { console.error(err); }
            }
        });
    });

    // Delete buttons (desktop)
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function () {
            const row = this.closest('.cart-row');
            const cartId = this.getAttribute('data-cart-id');
            const productName = row.querySelector('.flex-grow-1 h6')?.textContent.trim() || 'produk ini';
            showConfirmModal('Hapus Item', `Yakin ingin menghapus ${productName} dari keranjang?`, async function() {
                const deleteUrl = row.getAttribute('data-delete-url');
                try {
                    const json = await deleteRequest(deleteUrl);
                    if (json.success) row.remove();
                    refreshSummary(json.totalQty, json.totalAmount);
                } catch (err) { console.error(err); }
            });
        });
    });

    // Mobile swipe-to-delete (horizontal swipe) - smoother via rAF
    (function() {
        const threshold = 80; // px to trigger delete
        document.querySelectorAll('.cart-row').forEach(function(row) {
            let startX = 0, startY = 0, currentX = 0, touching = false, rafId = null;

            function applyTransform(x) {
                row.style.transform = `translate3d(${x}px,0,0)`;
            }

            function onTouchStart(e) {
                if (e.touches.length !== 1) return;
                touching = true;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                currentX = 0;
                row.style.transition = 'none';
                row.style.willChange = 'transform';
            }

            function onTouchMove(e) {
                if (!touching) return;
                const dx = e.touches[0].clientX - startX;
                const dy = Math.abs(e.touches[0].clientY - startY);
                if (Math.abs(dx) > 6 && dy < 40) {
                    e.preventDefault();
                    currentX = dx;
                    if (rafId) cancelAnimationFrame(rafId);
                    rafId = requestAnimationFrame(() => applyTransform(currentX));
                }
            }

            function onTouchEnd() {
                touching = false;
                if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
                row.style.transition = 'transform .18s ease';
                if (Math.abs(currentX) > threshold) {
                    // reset transform first for consistent modal sizing
                    row.style.transform = '';
                    // force reflow
                    void row.offsetWidth;
                    const productName = row.querySelector('.flex-grow-1 h6') ? row.querySelector('.flex-grow-1 h6').textContent.trim() : 'produk ini';
                    showConfirmModal('Hapus Item', `Yakin ingin menghapus ${productName} dari keranjang?`, async function() {
                        const deleteUrl = row.getAttribute('data-delete-url');
                        try {
                            const res = await fetch(deleteUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' } });
                            const json = await res.json();
                            if (json.success) row.remove();
                            const totalQty = json.totalQty ?? 0;
                            const totalAmount = json.totalAmount ?? 0;
                            const currencyFmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
                            const itemsEl = document.getElementById('total-items'); if (itemsEl) itemsEl.textContent = `${totalQty} Barang`;
                            const amountEl = document.getElementById('total-amount'); if (amountEl) amountEl.textContent = currencyFmt.format(totalAmount);
                        } catch (err) { console.error(err); }
                    });
                } else {
                    row.style.transform = '';
                }
                currentX = 0;
            }

            row.addEventListener('touchstart', onTouchStart, { passive: true });
            row.addEventListener('touchmove', onTouchMove, { passive: false });
            row.addEventListener('touchend', onTouchEnd, { passive: true });
            row.addEventListener('touchcancel', onTouchEnd, { passive: true });
        });
    })();

});
</script>
@endpush