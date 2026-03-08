@extends('layouts.publiclayout')

@section('title', 'Keranjang Belanja')

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

    /* Wajib untuk mengatasi teks panjang yang merusak Flexbox dan menyebabkan horizontal scroll */
    .min-w-0 {
        min-width: 0 !important;
    }

    /* Styling Keranjang (Cart Items) */
    .cart-row {
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    .cart-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 86, 179, 0.08) !important;
        border-color: rgba(0, 123, 255, 0.2);
    }

    .cart-item-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 86, 179, 0.08);
        flex-shrink: 0; /* Mencegah gambar menyusut */
    }

    /* Styling Tombol Hapus */
    .btn-glass-danger {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .btn-glass-danger:hover {
        background: #ef4444;
        color: white;
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
        border-color: #ef4444;
        transform: scale(1.05);
    }

    /* Qty Control Glassmorphism */
    .qty-wrapper {
        background: #f8fafc;
        border: 1px solid rgba(0, 123, 255, 0.1);
        border-radius: 50px;
        padding: 4px;
    }
    .qty-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        color: var(--primary-blue);
        border: none;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0, 86, 179, 0.05);
    }
    .qty-btn:hover {
        background: var(--gradient-primary);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.2);
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        color: var(--text-main);
        font-size: 1rem;
    }
    .qty-input:focus { outline: none; }

    /* Input Form Glassmorphism (Untuk Modal Alamat) */
    .custom-input-glass {
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 86, 179, 0.15);
        color: #334155;
        border-radius: 14px;
        padding: 10px 16px;
        transition: all 0.3s;
    }
    .custom-input-glass:focus {
        background-color: #fff;
        border-color: rgba(0, 123, 255, 0.4);
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }
    
    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(0, 86, 179, 0.15);
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-glass-cancel:hover { 
        background: #ffffff; 
        border-color: rgba(0, 86, 179, 0.3); 
        color: #0f172a; 
    }

    /* Address Radio Card Styling */
    .address-radio-card {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .address-radio-card:hover {
        border-color: rgba(0, 123, 255, 0.2);
        background: rgba(255,255,255,0.95);
    }
    .address-radio-card.selected {
        border-color: #007bff;
        background: #f0f7ff;
    }

    /* --- RESPONSIVE FIX AGAR TIDAK LEBAR DI HP --- */
    @media (max-width: 575.98px) {
        .cart-row {
            padding: 12px !important; /* Kurangi padding bawaan p-3 */
            gap: 12px !important; /* Spasi antar elemen dikurangi */
        }
        .cart-item-img {
            width: 70px;
            height: 70px;
        }
        .qty-wrapper {
            padding: 2px;
        }
        .qty-btn {
            width: 26px;
            height: 26px;
        }
        .qty-input {
            width: 30px;
            font-size: 0.9rem;
        }
        .btn-glass-danger {
            width: 30px;
            height: 30px;
        }
        .mobile-fs-small {
            font-size: 0.95rem !important;
        }
    }

    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 110px;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4 pt-2 fade-in-up">
    <h3 class="fw-bold text-gradient-blue mb-1" style="letter-spacing: -0.5px;">
        <i class="fa-solid fa-basket-shopping me-2 text-primary opacity-75"></i> Keranjang Anda
    </h3>
    <p class="text-secondary small mb-0 ms-1">Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.</p>
</div>

<div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-lg-8">
        
        @forelse($carts as $cart)
        <div class="glass-card mb-3 p-3 border-0 d-flex flex-row align-items-center cart-row shadow-sm gap-2 gap-md-3" 
             data-cart-id="{{ $cart->id }}" data-delete-url="{{ route('cart.remove', $cart->id) }}" data-product-id="{{ $cart->product->id }}">

            <img src="{{ $cart->product->image ? asset('storage/'.$cart->product->image) : 'https://placehold.co/200' }}" 
                 alt="{{ $cart->product->name }}" class="cart-item-img">
                 
            <div class="flex-grow-1 min-w-0">
                <h6 class="fw-bold mb-1 text-truncate text-dark fs-6" title="{{ $cart->product->name }}">
                    {{ $cart->product->name }}
                </h6>
                <div class="small mb-2 fw-medium text-primary bg-primary bg-opacity-10 border border-primary border-opacity-25" style="padding: 2px 8px; border-radius: 6px; display: inline-block; font-size: 0.75rem;">
                    {{ $cart->product->category->name ?? 'Umum' }}
                </div>
                <div class="fw-bold text-gradient-blue fs-5 mobile-fs-small">
                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="d-flex flex-column align-items-end justify-content-between h-100 py-1">
                <form id="delete-form-{{ $cart->id }}" action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mb-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-glass-danger delete-btn shadow-sm" data-cart-id="{{ $cart->id }}" title="Hapus Produk">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center qty-wrapper shadow-sm">
                    <form id="dec-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="qty" value="-1">
                        <button type="button" class="qty-btn btn-decrement" data-cart-id="{{ $cart->id }}"><i class="fa-solid fa-minus" style="font-size: 10px;"></i></button>
                    </form>

                    <span class="qty-input d-flex align-items-center justify-content-center" id="qty-display-{{ $cart->id }}">{{ $cart->qty }}</span>

                    <form id="inc-form-{{ $cart->id }}" action="{{ route('cart.add', $cart->product->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="button" class="qty-btn btn-increment" data-product-id="{{ $cart->product->id }}"><i class="fa-solid fa-plus" style="font-size: 10px;"></i></button>
                    </form>
                </div>
            </div>
            
        </div>
        @empty
        
        <div class="glass-card text-center py-5 px-3 border-0 shadow-sm" style="border-radius: 24px;">
            <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-4" style="width: 90px; height: 90px;">
                <i class="fa-solid fa-basket-shopping fa-2x opacity-50"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Keranjang Masih Kosong</h4>
            <p class="text-secondary small mb-4">Yuk, cari varian aroma favoritmu sekarang!</p>
            <a href="{{ url('/katalog') }}" class="btn btn-custom-primary rounded-pill px-5 py-2 shadow-sm fw-bold">Belanja Sekarang</a>
        </div>
        
        @endforelse

    </div>

    @if($carts->isNotEmpty())
    <div class="col-12 col-lg-4 pb-5 pb-lg-0">
        <div class="glass-card p-4 border-0 sticky-summary shadow-sm" style="border-radius: 24px;">
            <h5 class="fw-bold text-dark mb-4 fs-4" style="letter-spacing: -0.5px;">Ringkasan Pesanan</h5>
            
            <div class="d-flex justify-content-between mb-3 bg-light p-3 rounded-4 border">
                <span class="text-secondary fw-semibold">Total Item</span>
                <span id="total-items" class="fw-bold text-primary">{{ $carts->sum('qty') }} Barang</span>
            </div>
            
            <hr class="opacity-10 my-4 border-secondary">
            
            <div class="d-flex justify-content-between mb-4 align-items-center">
                <span class="fs-6 fw-semibold text-secondary">Total Tagihan</span>
                <span id="total-amount" class="fs-4 fw-bold text-gradient-blue">
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

                <button type="button" id="checkoutBtn" class="btn btn-custom-primary rounded-pill w-100 shadow-sm py-3 fs-6 fw-bold transition-smooth">
                    Buat Pesanan & Bayar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4 bg-light py-2 rounded-pill border">
                <small class="text-secondary d-flex align-items-center justify-content-center fw-medium">
                    <i class="fa-solid fa-shield-halved me-2 text-success"></i> Pembayaran aman via QRIS
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('modals')
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-0 shadow-lg" style="border-radius: 24px;">
            
            <div class="modal-header border-0 pb-0 pt-4 px-4 px-md-5 align-items-center">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.05) 100%); border: 1px solid rgba(0, 123, 255, 0.1);">
                        <i class="fa-solid fa-truck-fast fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-dark mb-0 fs-4" style="letter-spacing: -0.5px;">Detail Pengiriman</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body pt-4 px-4 px-md-5">
                
                <div id="existingAddresses" class="mb-4">
                    <h6 class="fw-bold text-secondary small text-uppercase mb-3" style="letter-spacing: 1px;"><i class="fa-solid fa-book-bookmark me-2 text-primary opacity-75"></i> Alamat Tersimpan</h6>
                    <div id="addressesList" class="d-flex flex-column gap-2">Memuat alamat...</div>
                </div>

                <div class="position-relative text-center my-4">
                    <hr class="opacity-10 border-secondary m-0">
                    <span class="text-secondary small fw-bold px-3 position-absolute top-50 start-50 translate-middle bg-white border rounded-pill shadow-sm">ATAU</span>
                </div>

                <div id="newAddressForm" class="bg-light p-4 rounded-4 border">
                    <h6 class="fw-bold text-secondary small text-uppercase mb-3" style="letter-spacing: 1px;"><i class="fa-solid fa-plus-circle me-2 text-primary opacity-75"></i> Masukkan Alamat Baru</h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold ms-1">Label Alamat <span class="text-secondary fw-normal">(Opsional)</span></label>
                        <input type="text" id="new_label" class="form-control custom-input-glass" placeholder="Contoh: Rumah / Kantor / Kos">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold ms-1">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea id="new_address" class="form-control custom-input-glass" placeholder="Nama Jalan, RT/RW, Patokan..." rows="2"></textarea>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-7">
                            <label class="form-label text-dark small fw-bold ms-1">Kota/Kabupaten</label>
                            <input type="text" id="new_city" class="form-control custom-input-glass" placeholder="Contoh: Jakarta Barat">
                        </div>
                        <div class="col-5">
                            <label class="form-label text-dark small fw-bold ms-1">Kode Pos</label>
                            <input type="text" id="new_postal" class="form-control custom-input-glass" placeholder="12345">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold ms-1">No. Handphone Penerima</label>
                        <input type="text" id="new_phone" class="form-control custom-input-glass" placeholder="08xxxxxxxx" value="{{ Auth::user()->phone ?? '' }}">
                    </div>

                    <div class="row g-2 align-items-center mt-3 p-3 bg-white rounded-4 border shadow-sm">
                        <div class="col-8 col-md-9">
                            <label class="form-label text-dark small fw-bold ms-1 mb-1">Koordinat Lokasi (Maps)</label>
                            <input type="text" id="new_kordinat" name="kordinat" class="form-control custom-input-glass bg-light" placeholder="-6.200000, 106.816666" disabled>
                        </div>
                        <div class="col-4 col-md-3 d-grid">
                            <label class="form-label d-block small text-muted mb-1">&nbsp;</label>
                            <button type="button" id="openLocationPickerBtn" class="btn btn-outline-primary rounded-pill fw-semibold"><i class="fa-solid fa-map-location-dot me-1"></i> Pick</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pt-2 pb-4 px-4 px-md-5 d-flex justify-content-end gap-2 flex-nowrap">
                <button type="button" class="btn btn-glass-cancel rounded-pill px-4 w-100 w-md-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="addressConfirmBtn" class="btn btn-custom-primary rounded-pill px-4 shadow-sm w-100 w-md-auto">
                    Lanjut Bayar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="locationPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4 px-md-5 align-items-center">
                <h5 class="modal-title fw-bold text-dark fs-4 mb-0"><i class="fa-solid fa-map-location-dot text-primary me-2"></i> Tandai Titik Lokasi</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4 px-4 px-md-5">
                <div class="rounded-4 overflow-hidden border shadow-sm">
                    <x-location-picker />
                </div>
            </div>
            <div class="modal-footer border-0 pt-2 pb-4 px-4 px-md-5 d-flex justify-content-end gap-2 flex-nowrap">
                <button type="button" class="btn btn-glass-cancel rounded-pill px-4 w-100 w-md-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="lpConfirmBtn" class="btn btn-custom-primary rounded-pill px-4 shadow-sm w-100 w-md-auto">Simpan Lokasi <i class="fa-solid fa-check ms-2"></i></button>
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
        if (amountEl) amountEl.textContent = currencyFmt.format(totalAmount || 0).replace(',00', '');

        // Sync cart badge in navbar (desktop + mobile) after AJAX cart updates.
        const badges = document.querySelectorAll('.server-cart-badge');
        badges.forEach(function(badgeEl) {
            if (!badgeEl) return;
            if ((totalQty || 0) > 0) {
                badgeEl.textContent = totalQty > 99 ? '99+' : String(totalQty);
                badgeEl.classList.remove('d-none');
            } else {
                badgeEl.textContent = '';
                badgeEl.classList.add('d-none');
            }
        });
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
                        
                        // Check if cart is empty to reload page and show empty state
                        if(json.totalQty === 0) window.location.reload();
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
                    
                    if(json.totalQty === 0) window.location.reload();
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
                    data.addresses.forEach((addr, index) => {
                        const id = addr.id;
                        const radio = document.createElement('div');
                        
                        radio.className = 'form-check glass-card p-3 address-radio-card rounded-4 shadow-sm d-flex align-items-center bg-white m-0';
                        radio.style.cursor = 'pointer';
                        
                        radio.innerHTML = `
                            <input class="form-check-input me-3 mt-0" type="radio" name="selected_address" id="addr_${id}" value="${id}" style="transform: scale(1.3);">
                            <label class="form-check-label w-100" for="addr_${id}" style="cursor: pointer;">
                                <div class="fw-bold text-dark fs-6 mb-1">${addr.label || 'Alamat Tersimpan'}</div>
                                <div class="text-secondary small fw-medium" style="line-height: 1.5;">${addr.address} ${addr.city ? '<br>' + addr.city : ''} ${addr.postal_code ? ' - ' + addr.postal_code : ''}</div>
                                ${addr.phone ? `<div class="text-primary small mt-1 fw-bold"><i class="fa-solid fa-phone me-1"></i> ${addr.phone}</div>` : ''}
                            </label>
                        `;
                        
                        // Add class 'selected' when radio is clicked
                        radio.addEventListener('click', function() {
                            document.querySelectorAll('.address-radio-card').forEach(el => el.classList.remove('selected'));
                            this.classList.add('selected');
                            this.querySelector('input').checked = true;
                        });

                        // Select first address by default
                        if(index === 0) {
                            radio.classList.add('selected');
                            radio.querySelector('input').checked = true;
                        }

                        list.appendChild(radio);
                    });
                } else {
                    list.innerHTML = `
                        <div class="text-secondary small p-4 text-center bg-white rounded-4 border shadow-sm">
                            <i class="fa-solid fa-map-location-dot fa-2x mb-2 opacity-25"></i><br>
                            Belum ada alamat tersimpan.<br>Silakan isi form alamat baru di bawah.
                        </div>`;
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

    // Confirm picked location
    const lpConfirmBtn = document.getElementById('lpConfirmBtn');
    if (lpConfirmBtn) {
        lpConfirmBtn.addEventListener('click', function () {
            const lat = document.getElementById('lp-lat') ? document.getElementById('lp-lat').value : '';
            const lng = document.getElementById('lp-lng') ? document.getElementById('lp-lng').value : '';
            if (!lat || !lng) {
                alert('Silakan pilih lokasi pada peta terlebih dahulu.');
                return;
            }

            const coordDisplay = document.getElementById('new_kordinat');
            if (coordDisplay) coordDisplay.value = `${lat}, ${lng}`;
            if (document.getElementById('checkout-address-lat')) document.getElementById('checkout-address-lat').value = lat;
            if (document.getElementById('checkout-address-lng')) document.getElementById('checkout-address-lng').value = lng;

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
            const newAddr = document.getElementById('new_address').value.trim();

            if (selected && !newAddr) {
                document.getElementById('checkout-address-id').value = selected.value;
                form.submit();
                return;
            }

            if (!newAddr && !selected) {
                const toastHTML = `<div class="toast custom-toast border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex align-items-center p-3"><div class="toast-icon-wrapper toast-error-bg me-3"><i class="fa-solid fa-exclamation fs-5 toast-icon-error"></i></div><div class="d-flex flex-column flex-grow-1 me-2"><span class="toast-text-title mb-1">Alamat Kosong</span><span class="toast-text-desc lh-sm">Pilih alamat tersimpan atau isi alamat baru!</span></div><button type="button" class="btn-close shadow-none opacity-50 ms-auto align-self-start mt-1" data-bs-dismiss="toast"></button></div></div>`;
                document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
                new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 4000 }).show();
                return;
            }

            document.getElementById('checkout-address-id').value = '';
            document.getElementById('checkout-address-label').value = document.getElementById('new_label').value.trim();
            document.getElementById('checkout-address-address').value = newAddr;
            document.getElementById('checkout-address-city').value = document.getElementById('new_city').value.trim();
            document.getElementById('checkout-address-postal_code').value = document.getElementById('new_postal').value.trim();
            document.getElementById('checkout-address-phone').value = document.getElementById('new_phone').value.trim();

            const lpLat = document.getElementById('lp-lat') ? document.getElementById('lp-lat').value : '';
            const lpLng = document.getElementById('lp-lng') ? document.getElementById('lp-lng').value : '';
            document.getElementById('checkout-address-lat').value = lpLat;
            document.getElementById('checkout-address-lng').value = lpLng;

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
                row.style.transition = 'transform .2s cubic-bezier(0.165, 0.84, 0.44, 1)';
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
                            if(json.totalQty === 0) window.location.reload();
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