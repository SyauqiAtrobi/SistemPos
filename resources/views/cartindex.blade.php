@extends('layouts.publiclayout')

@section('title', 'Keranjang Belanja')

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

    /* Styling Gambar Produk di Keranjang */
    .cart-item-img {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 86, 179, 0.1);
    }

    /* Styling Tombol Hapus */
    .btn-glass-danger {
        background: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.15);
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .btn-glass-danger:hover {
        background: rgba(220, 53, 69, 0.9);
        color: white;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
    }

    /* Qty Control Glassmorphism */
    .qty-wrapper {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 86, 179, 0.15);
        border-radius: 50px;
        padding: 4px;
        backdrop-filter: blur(5px);
    }
    .qty-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0, 123, 255, 0.1);
        color: #0056b3;
        border: 1px solid rgba(0, 123, 255, 0.1);
        transition: all 0.3s;
        font-weight: bold;
    }
    .qty-btn:hover {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 2px 8px rgba(0, 86, 179, 0.2);
        border-color: transparent;
    }
    .qty-input {
        width: 35px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #0056b3;
    }
    .qty-input:focus { outline: none; }

    /* Input Form Glassmorphism (Untuk Modal Alamat) */
    .custom-input-glass {
        background-color: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: #333;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .custom-input-glass:focus {
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }
    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-glass-cancel:hover { background: rgba(255, 255, 255, 0.9); border-color: #007bff; color: #0056b3; }

    /* Summary Card Sticky on Desktop */
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 90px; /* Menyesuaikan tinggi navbar desktop */
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4 fade-in-up">
    <h3 class="fw-bold text-gradient-blue mb-1">
        <i class="fa-solid fa-basket-shopping me-2"></i> Keranjang Anda
    </h3>
    <p class="text-glass-blue small mb-0">Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.</p>
</div>

<div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-lg-8">
        
        @forelse($carts as $cart)
        <div class="glass-card mb-3 p-3 border-0 d-flex flex-row align-items-center cart-row shadow-sm" 
             data-cart-id="{{ $cart->id }}" data-delete-url="{{ route('cart.remove', $cart->id) }}" data-product-id="{{ $cart->product->id }}">

            <img src="{{ $cart->product->image ? asset('storage/'.$cart->product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=100&q=80' }}" 
                 alt="{{ $cart->product->name }}" class="cart-item-img me-3">
                 
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px; color: rgba(0, 50, 120, 0.9);">
                    {{ $cart->product->name }}
                </h6>
                <div class="small mb-2" style="color: rgba(0, 123, 255, 0.8); background: rgba(0, 123, 255, 0.1); padding: 2px 8px; border-radius: 6px; display: inline-block;">
                    {{ $cart->product->category->name ?? 'Umum' }}
                </div>
                <div class="fw-bold text-gradient-blue">
                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="d-flex flex-column align-items-end justify-content-between h-100">
                <form id="delete-form-{{ $cart->id }}" action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mb-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-glass-danger delete-btn" data-cart-id="{{ $cart->id }}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center qty-wrapper shadow-sm">
                    <form id="dec-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="qty" value="-1">
                        <button type="button" class="qty-btn btn-decrement" data-cart-id="{{ $cart->id }}"><i class="fa-solid fa-minus" style="font-size: 10px;"></i></button>
                    </form>

                    <span class="qty-input" id="qty-display-{{ $cart->id }}">{{ $cart->qty }}</span>

                    <form id="inc-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="button" class="qty-btn btn-increment" data-product-id="{{ $cart->product->id }}"><i class="fa-solid fa-plus" style="font-size: 10px;"></i></button>
                    </form>
                </div>
            </div>
            
        </div>
        @empty
        
        <div class="glass-card text-center py-5 px-3 border-0 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-basket-shopping fa-2x opacity-75"></i>
            </div>
            <h5 class="fw-bold text-gradient-blue mb-2">Keranjang Masih Kosong</h5>
            <p class="text-glass-blue small mb-4">Yuk, cari varian aroma favoritmu sekarang!</p>
            <a href="{{ url('/katalog') }}" class="btn btn-custom-primary rounded-pill px-5 shadow-sm">Belanja Sekarang</a>
        </div>
        
        @endforelse

    </div>

    @if($carts->isNotEmpty())
    <div class="col-12 col-lg-4 pb-5 pb-lg-0">
        <div class="glass-card p-4 border-0 sticky-summary shadow-sm">
            <h5 class="fw-bold text-gradient-blue mb-4">Ringkasan Pesanan</h5>
            
            <div class="d-flex justify-content-between mb-3">
                <span class="text-glass-blue fw-medium">Total Item</span>
                <span id="total-items" class="fw-bold text-dark">{{ $carts->sum('qty') }} Barang</span>
            </div>
            
            <hr class="opacity-10 my-3 border-primary">
            
            <div class="d-flex justify-content-between mb-4">
                <span class="fs-5 fw-bold text-dark">Total Tagihan</span>
                <span id="total-amount" class="fs-5 fw-bold text-gradient-blue">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="address_id" id="checkout-address-id">
                <input type="hidden" name="label" id="checkout-address-label">
                <input type="hidden" name="address" id="checkout-address-address">
                <input type="hidden" name="city" id="checkout-address-city">
                <input type="hidden" name="postal_code" id="checkout-address-postal_code">
                <input type="hidden" name="lat" id="checkout-address-lat">
                <input type="hidden" name="lng" id="checkout-address-lng">
                <input type="hidden" name="phone" id="checkout-address-phone">

                <button type="button" id="checkoutBtn" class="btn btn-custom-primary rounded-pill w-100 shadow-sm py-2 fs-6">
                    Buat Pesanan & Bayar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-glass-blue d-flex align-items-center justify-content-center fw-medium">
                    <i class="fa-solid fa-shield-halved me-2 text-success fs-6"></i> Pembayaran aman via QRIS Terverifikasi
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('modals')
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-truck-fast fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0">Detail Pengiriman</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body pt-4 px-4 ms-2">
                <div id="existingAddresses" class="mb-4">
                    <h6 class="fw-bold text-dark small text-uppercase mb-3" style="letter-spacing: 1px;">Alamat Tersimpan</h6>
                    <div id="addressesList">Memuat alamat...</div>
                </div>

                <div class="position-relative text-center my-4">
                    <hr class="opacity-10 border-primary m-0">
                    <span class="text-glass-blue small fw-bold px-2 position-absolute top-50 start-50 translate-middle" style="background: #fff; border-radius: 10px;">ATAU</span>
                </div>

                <div id="newAddressForm">
                    <h6 class="fw-bold text-dark small text-uppercase mb-3" style="letter-spacing: 1px;">Masukkan Alamat Baru</h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Label Alamat (Opsional)</label>
                        <input type="text" id="new_label" class="form-control custom-input-glass" placeholder="Contoh: Rumah / Kantor / Kos">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Alamat Lengkap</label>
                        <textarea id="new_address" class="form-control custom-input-glass" placeholder="Nama Jalan, RT/RW, Patokan..." rows="2"></textarea>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-7">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Kota/Kabupaten</label>
                            <input type="text" id="new_city" class="form-control custom-input-glass" placeholder="Kota">
                        </div>
                        <div class="col-5">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Kode Pos</label>
                            <input type="text" id="new_postal" class="form-control custom-input-glass" placeholder="12345">
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">No. Handphone Penerima</label>
                        <input type="text" id="new_phone" class="form-control custom-input-glass" placeholder="08xxxxxxxx">
                    </div>

                    <div class="row g-2 align-items-center mt-3">
                        <div class="col-9">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Koordinat (lat,lng)</label>
                            <input type="text" id="new_kordinat" name="kordinat" class="form-control custom-input-glass" placeholder="-6.200000,106.816666" disabled>
                        </div>
                        <div class="col-3 d-grid">
                            <label class="form-label d-block small text-muted mb-0">&nbsp;</label>
                            <button type="button" id="openLocationPickerBtn" class="btn btn-outline-primary">Pick Lokasi</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pt-2 pb-4 px-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="addressConfirmBtn" class="btn btn-custom-primary rounded-pill px-4 shadow-sm">
                    Lanjut Bayar <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
            
        </div>
    </div>
</div>
@endpush

        @push('modals')
        <!-- Location Picker Modal -->
        <div class="modal fade" id="locationPickerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content glass-card border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                        <h5 class="modal-title fw-bold text-gradient-blue mb-0">Pilih Lokasi</h5>
                        <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-4 px-4 ms-2">
                        <x-location-picker />
                    </div>
                    <div class="modal-footer border-0 pt-2 pb-4 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="lpConfirmBtn" class="btn btn-custom-primary rounded-pill px-4 shadow-sm">Pilih Lokasi</button>
                    </div>
                </div>
            </div>
        </div>
        @endpush

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
                    row.remove();
                } else if (json.qty !== undefined) {
                    const qtyEl = document.getElementById('qty-display-' + json.cart_id);
                    if (qtyEl) qtyEl.textContent = json.qty;
                }
                refreshSummary(json.totalQty, json.totalAmount);
            } catch (err) { console.error(err); }
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

    // Checkout modal logic
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', async function () {
            try {
                const res = await fetch('{{ route('addresses.index') }}', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                const list = document.getElementById('addressesList');
                list.innerHTML = '';
                if (data.addresses && data.addresses.length) {
                    data.addresses.forEach(addr => {
                        const id = addr.id;
                        const radio = document.createElement('div');
                        // Styling UI Radio Button Glassmorphism
                        radio.className = 'form-check glass-card p-3 mb-2 border-0 shadow-sm d-flex align-items-center';
                        radio.style.cursor = 'pointer';
                        radio.innerHTML = `
                            <input class="form-check-input me-3 mt-0" type="radio" name="selected_address" id="addr_${id}" value="${id}" style="transform: scale(1.2);">
                            <label class="form-check-label w-100" for="addr_${id}" style="cursor: pointer;">
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">${addr.label || 'Alamat Tersimpan'}</div>
                                <div class="text-glass-blue small" style="line-height: 1.4;">${addr.address} ${addr.city ? '<br>' + addr.city : ''} ${addr.postal_code ? ' - ' + addr.postal_code : ''}</div>
                            </label>
                        `;
                        list.appendChild(radio);
                    });
                } else {
                    list.innerHTML = '<div class="text-glass-blue small p-3 text-center bg-light rounded-3 border">Belum ada alamat tersimpan. Silakan isi form di bawah.</div>';
                }

                var modal = new bootstrap.Modal(document.getElementById('addressModal'));
                modal.show();
            } catch (err) {
                console.error(err);
                alert('Gagal memuat alamat. Coba lagi.');
            }
        });
    }

    // Open location picker modal when button clicked
    const openLocationPickerBtn = document.getElementById('openLocationPickerBtn');
    if (openLocationPickerBtn) {
        openLocationPickerBtn.addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('locationPickerModal'));
            modal.show();
        });
    }

    // Confirm picked location: copy lp-lat/lng into display and hidden inputs
    const lpConfirmBtn = document.getElementById('lpConfirmBtn');
    if (lpConfirmBtn) {
        lpConfirmBtn.addEventListener('click', function () {
            const lat = document.getElementById('lp-lat') ? document.getElementById('lp-lat').value : '';
            const lng = document.getElementById('lp-lng') ? document.getElementById('lp-lng').value : '';
            if (!lat || !lng) {
                alert('Silakan pilih lokasi pada peta terlebih dahulu.');
                return;
            }

            // populate display and hidden checkout fields
            const coordDisplay = document.getElementById('new_kordinat');
            if (coordDisplay) coordDisplay.value = `${lat},${lng}`;
            if (document.getElementById('checkout-address-lat')) document.getElementById('checkout-address-lat').value = lat;
            if (document.getElementById('checkout-address-lng')) document.getElementById('checkout-address-lng').value = lng;

            // close modal
            var modalEl = document.getElementById('locationPickerModal');
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        });
    }

    // When user confirms address selection
    const addressConfirmBtn = document.getElementById('addressConfirmBtn');
    if (addressConfirmBtn) {
        addressConfirmBtn.addEventListener('click', async function () {
            const selected = document.querySelector('input[name="selected_address"]:checked');
            const form = document.getElementById('checkout-form');

            if (selected) {
                document.getElementById('checkout-address-id').value = selected.value;
                form.submit();
                return;
            }

            const newAddr = document.getElementById('new_address').value.trim();
            if (!newAddr) {
                // Tampilkan Toast Gagal jika form kosong
                const toastHTML = `<div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex align-items-center p-2"><div class="toast-body d-flex align-items-center fw-medium flex-grow-1 toast-text-glass"><i class="fa-solid fa-triangle-exclamation fs-4 me-3 toast-icon-error" style="color: #ef4444;"></i><span class="lh-sm">Pilih alamat tersimpan atau isi alamat baru!</span></div><button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast"></button></div></div>`;
                document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
                new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 3000 }).show();
                return;
            }

            document.getElementById('checkout-address-id').value = '';
            document.getElementById('checkout-address-label').value = document.getElementById('new_label').value.trim();
            document.getElementById('checkout-address-address').value = newAddr;
            document.getElementById('checkout-address-city').value = document.getElementById('new_city').value.trim();
            document.getElementById('checkout-address-postal_code').value = document.getElementById('new_postal').value.trim();
            document.getElementById('checkout-address-phone').value = document.getElementById('new_phone').value.trim();

            // Copy coordinates from location picker (if any)
            const lpLat = document.getElementById('lp-lat') ? document.getElementById('lp-lat').value : '';
            const lpLng = document.getElementById('lp-lng') ? document.getElementById('lp-lng').value : '';
            document.getElementById('checkout-address-lat').value = lpLat;
            document.getElementById('checkout-address-lng').value = lpLng;
            // Also show coords in new_kordinat display
            if (lpLat && lpLng) {
                const coordDisplay = document.getElementById('new_kordinat');
                if (coordDisplay) coordDisplay.value = `${lpLat},${lpLng}`;
            }

            form.submit();
        });
    }

    // Mobile swipe-to-delete
    (function() {
        const threshold = 80;
        document.querySelectorAll('.cart-row').forEach(function(row) {
            let startX = 0, startY = 0, currentX = 0, touching = false, rafId = null;

            function applyTransform(x) { row.style.transform = `translate3d(${x}px,0,0)`; }

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
                    row.style.transform = '';
                    void row.offsetWidth;
                    const productName = row.querySelector('.flex-grow-1 h6') ? row.querySelector('.flex-grow-1 h6').textContent.trim() : 'produk ini';
                    showConfirmModal('Hapus Item', `Yakin ingin menghapus ${productName} dari keranjang?`, async function() {
                        const deleteUrl = row.getAttribute('data-delete-url');
                        try {
                            const res = await fetch(deleteUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' } });
                            const json = await res.json();
                            if (json.success) row.remove();
                            refreshSummary(json.totalQty ?? 0, json.totalAmount ?? 0);
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