@extends('layouts.publiclayout')

@section('title', 'Katalog Parfum')

@push('styles')
<style>
    /* Utility Classes Custom Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .text-glass-blue {
        color: rgba(0, 50, 120, 0.6);
    }

    /* Area Filter Kategori */
    .category-scroll {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding-bottom: 15px;
        -webkit-overflow-scrolling: touch;
    }
    .category-scroll::-webkit-scrollbar { height: 4px; }
    .category-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0.4));
        border-radius: 10px;
    }

    /* Tombol Kaca */
    .btn-glass {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        color: rgba(0, 60, 130, 0.8);
        box-shadow: 0 4px 15px rgba(0, 86, 179, 0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 255, 255, 1);
        color: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 86, 179, 0.15);
    }

    /* Styling Default (Desktop) Card & Elemen */
    .product-img {
        height: 220px;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .img-wrapper {
        overflow: hidden;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        position: relative;
    }
    .img-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to top, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
    }
    .glass-card:hover .product-img {
        transform: scale(1.08);
    }
    .badge-glass {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        color: rgba(0, 60, 130, 0.9);
        font-weight: 500;
        padding: 6px 12px;
        box-shadow: 0 2px 10px rgba(0, 86, 179, 0.05);
    }
    .btn-glass-disabled {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: rgba(0, 50, 120, 0.3);
        box-shadow: none;
        cursor: not-allowed;
    }

    .price-text { font-size: 1.25rem; }
    .stock-text { font-size: 0.875rem; }
    .btn-cart { font-size: 1rem; padding: 0.5rem 1rem; }

    /* Modal Qty Styling */
    .qty-control {
        background: rgba(0, 86, 179, 0.05);
        border-radius: 20px;
        padding: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.6);
        color: var(--primary-blue);
        transition: all 0.2s;
    }
    .qty-btn:hover { background: white; box-shadow: 0 2px 8px rgba(0, 86, 179, 0.15); }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: bold;
        color: var(--primary-blue);
    }
    .qty-input:focus { outline: none; }

    /* RESPONSIVE: Mobile (<576px) */
    @media (max-width: 575.98px) {
        .product-img { height: 130px; }
        .card-body { padding: 0.8rem; }
        .card-title { font-size: 0.85rem !important; }
        .card-text { 
            font-size: 0.75rem; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            margin-bottom: 0.5rem !important;
        } 
        .badge-glass { padding: 3px 6px; font-size: 0.65rem; }
        .price-text { font-size: 0.9rem !important; }
        .stock-text { font-size: 0.7rem !important; }
        .btn-cart { font-size: 0.75rem; padding: 0.4rem 0.5rem; }
        .btn-cart i { font-size: 0.75rem; margin-right: 0.2rem !important; }
        .btn-glass-disabled { font-size: 0.75rem; padding: 0.4rem 0.5rem; }
    }

    /* RESPONSIVE: Tablet (576px - 991px) */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .product-img { height: 180px; }
        .card-title { font-size: 1rem; }
        .price-text { font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')
<div class="text-center mb-5 fade-in-up">
    <h2 class="fw-bold text-gradient-blue">Katalog Baba Parfum</h2>
    <p class="text-glass-blue">Temukan aroma yang mencerminkan karakter elegan Anda</p>
</div>

<div class="mb-4 category-scroll fade-in-up" style="animation-delay: 0.1s;">
    <a href="{{ url('/katalog') }}" class="btn {{ request('category') ? 'btn-glass' : 'btn-custom-primary shadow-lg' }} text-nowrap rounded-pill px-4">
        Semua Aroma
    </a>
    
    @foreach($categories as $category)
        <a href="{{ url('/katalog?category='.$category->slug) }}" 
           class="btn {{ request('category') == $category->slug ? 'btn-custom-primary shadow-lg' : 'btn-glass' }} text-nowrap rounded-pill px-4 text-decoration-none">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<div id="productGrid" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4 fade-in-up" style="animation-delay: 0.2s;">
    
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 glass-card border-0 d-flex flex-column">
            
            <div class="img-wrapper">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80' }}" 
                     class="card-img-top product-img" alt="{{ $product->name }}">
            </div>
            
            <div class="card-body d-flex flex-column position-relative z-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold mb-0 text-truncate text-gradient-blue" style="max-width: 60%;" title="{{ $product->name }}">
                        {{ $product->name }}
                    </h5>
                    <span class="badge badge-glass rounded-pill">{{ $product->category->name ?? 'Umum' }}</span>
                </div>
                
                <p class="card-text text-glass-blue small flex-grow-1">
                    {{ Str::limit($product->description, 70, '...') }}
                </p>
                
                <div class="mt-auto">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-1">
                        <span class="price-text fw-bold text-gradient-blue">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="stock-text fw-semibold {{ $product->stock > 0 ? 'text-gradient-blue' : 'text-glass-blue opacity-50' }}">
                            {{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}
                        </span>
                    </div>

                    @if($product->stock > 0)
                        <button type="button" class="btn btn-custom-primary btn-cart w-100 shadow-sm rounded-pill" 
                                data-bs-toggle="modal" data-bs-target="#addToCartModal"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-category="{{ $product->category->name ?? 'Umum' }}"
                                data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                                data-stock="{{ $product->stock }}"
                                data-desc="{{ $product->description }}"
                                data-img="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80' }}">
                            <i class="fa-solid fa-cart-plus me-1"></i> Tambah
                        </button>
                    @else
                        <button class="btn btn-glass-disabled w-100 rounded-pill" disabled>
                            <i class="fa-solid fa-boxes-packing me-1"></i> Kosong
                        </button>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    @empty
    
    <div class="col-12 text-center py-5">
        <div class="glass-card p-5 mx-auto border-0" style="max-width: 400px; background: rgba(255,255,255,0.4);">
            <i class="fa-solid fa-droplet-slash fa-4x mb-3 text-glass-blue opacity-50"></i>
            <h5 class="fw-bold text-gradient-blue">Belum Ada Parfum</h5>
            <p class="text-glass-blue small">Kategori atau produk yang Anda cari sedang kosong.</p>
        </div>
    </div>
    
    @endforelse

</div>
@endsection

@push('modals')
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gradient-blue"><i class="fa-solid fa-circle-info me-2"></i> Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="text-center mb-3">
                    <img id="modalImg" src="" class="img-fluid rounded-4 shadow-sm" style="max-height: 220px; object-fit: cover; width: 100%;">
                </div>
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 id="modalName" class="fw-bold text-gradient-blue mb-0"></h4>
                    <span id="modalCategory" class="badge badge-glass rounded-pill"></span>
                </div>
                
                <p id="modalDesc" class="text-glass-blue small mb-4" style="line-height: 1.6;"></p>
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light">
                    <span id="modalPrice" class="fs-4 fw-bold text-gradient-blue"></span>
                    <span id="modalStock" class="small fw-semibold text-glass-blue"></span>
                </div>

                <form id="addToCartForm" method="POST" action="">
                    @csrf
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
                        <label class="text-glass-blue fw-semibold mb-0">Kuantitas:</label>
                        
                        <div class="qty-control shadow-sm">
                            <button type="button" class="qty-btn" onclick="decrementQty()"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" id="qtyInput" name="qty" value="1" min="1" class="qty-input" readonly>
                            <button type="button" class="qty-btn" onclick="incrementQty()"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom-primary w-100 rounded-pill py-2 shadow-sm fs-6">
                        Masukkan ke Keranjang <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
// Desktop AJAX search: replace product grid without reload
document.addEventListener('DOMContentLoaded', function () {
    const desktopInput = document.getElementById('desktopSearchInput');
    if (!desktopInput) return;
    let timer = null;

    function renderProductsToGrid(items) {
        const grid = document.getElementById('productGrid');
        grid.innerHTML = '';
        if (!items || !items.length) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="glass-card p-5 mx-auto border-0" style="max-width: 400px; background: rgba(255,255,255,0.4);">
                        <i class="fa-solid fa-droplet-slash fa-4x mb-3 text-glass-blue opacity-50"></i>
                        <h5 class="fw-bold text-gradient-blue">Belum Ada Parfum</h5>
                        <p class="text-glass-blue small">Produk yang Anda cari tidak ditemukan.</p>
                    </div>
                </div>
            `;
            return;
        }

        for (const p of items) {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="card h-100 glass-card border-0 d-flex flex-column">
                    <div class="img-wrapper">
                        <img src="${p.image}" class="card-img-top product-img" alt="${p.name}">
                    </div>
                    <div class="card-body d-flex flex-column position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0 text-truncate text-gradient-blue" style="max-width: 60%;" title="${p.name}">${p.name}</h5>
                            <span class="badge badge-glass rounded-pill">${p.category || 'Umum'}</span>
                        </div>
                        <p class="card-text text-glass-blue small flex-grow-1">${(p.description || '').substring(0,70)}${(p.description && p.description.length>70)?'...':''}</p>
                        <div class="mt-auto">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-1">
                                <span class="price-text fw-bold text-gradient-blue">${p.price_text}</span>
                                <span class="stock-text fw-semibold ${p.stock>0 ? 'text-gradient-blue' : 'text-glass-blue opacity-50'}">${p.stock>0 ? 'Stok: '+p.stock : 'Habis'}</span>
                            </div>
                            ${p.stock>0 ? `<button type="button" class="btn btn-custom-primary btn-cart w-100 shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addToCartModal" data-id="${p.id}" data-name="${escapeHtml(p.name)}" data-category="${escapeHtml(p.category||'Umum')}" data-price="${escapeHtml(p.price_text)}" data-stock="${p.stock}" data-desc="${escapeHtml(p.description||'') }" data-img="${p.image}"><i class="fa-solid fa-cart-plus me-1"></i> Tambah</button>` : `<button class="btn btn-glass-disabled w-100 rounded-pill" disabled><i class="fa-solid fa-boxes-packing me-1"></i> Kosong</button>`}
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(col);
        }
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    async function doSearch(q) {
        try {
            const res = await fetch(`/katalog?q=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } });
            const json = await res.json();
            renderProductsToGrid(json.products || []);
        } catch (err) {
            console.error('Search error', err);
        }
    }

    desktopInput.addEventListener('input', function () {
        const q = this.value.trim();
        if (timer) clearTimeout(timer);
        if (q.length < 1) {
            // reload full list by navigating or fetching without q
            timer = setTimeout(() => doSearch(''), 200);
            return;
        }
        timer = setTimeout(() => doSearch(q), 300);
    });
});

    let currentMaxStock = 0;
    
    // Script untuk menangkap event pembukaan modal
    const addToCartModal = document.getElementById('addToCartModal');
    if (addToCartModal) {
        addToCartModal.addEventListener('show.bs.modal', function (event) {
            // Button yang men-trigger modal
            const button = event.relatedTarget;
            
            // Ambil data dari data-* attributes
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const category = button.getAttribute('data-category');
            const price = button.getAttribute('data-price');
            const stock = button.getAttribute('data-stock');
            const desc = button.getAttribute('data-desc');
            const img = button.getAttribute('data-img');
            
            currentMaxStock = parseInt(stock);

            // Populate data ke dalam Modal
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalCategory').textContent = category;
            document.getElementById('modalDesc').textContent = desc || 'Tidak ada deskripsi.';
            document.getElementById('modalPrice').textContent = price;
            document.getElementById('modalStock').textContent = 'Stok tersedia: ' + stock;
            document.getElementById('modalImg').src = img;
            
            // Reset input qty ke 1 setiap modal dibuka
            document.getElementById('qtyInput').value = 1;

            // Update action form URL
            document.getElementById('addToCartForm').action = `/cart/add/${id}`;
        });
    }

    // Fungsi Tambah Qty
    function incrementQty() {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value);
        if (val < currentMaxStock) {
            input.value = val + 1;
        } else {
            // Menampilkan notifikasi jika stok mentok (pakai Toast Bootstrap)
            const toastHTML = `
                <div class="toast align-items-center text-bg-warning border-0 glass-card" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-dark">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Maksimal pembelian adalah ${currentMaxStock} (Sesuai stok).
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
            const newToast = new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 3000 });
            newToast.show();
        }
    }

    // Fungsi Kurang Qty
    function decrementQty() {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
        }
    }

    // Guest cart handling: save to localStorage if user not authenticated,
    // and sync to server when user becomes authenticated.
    const isAuthenticated = @json(Auth::check());

    document.addEventListener('DOMContentLoaded', function () {
        const toastContainer = document.querySelector('.toast-container');

        // If user just logged in and there is a guest cart, sync it to server
        if (isAuthenticated) {
            const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
            if (guestCart.length) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                (async function() {
                    for (const item of guestCart) {
                        try {
                            const fd = new FormData();
                            fd.append('qty', item.qty);
                            await fetch(`/cart/add/${item.id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: fd });
                        } catch (err) {
                            console.error('Sync guest cart failed for item', item, err);
                        }
                    }
                    localStorage.removeItem('guest_cart');
                    if (toastContainer) {
                        const html = `\
                            <div class="toast align-items-center text-bg-success border-0 glass-card" role="alert" aria-live="assertive" aria-atomic="true">\
                                <div class="d-flex">\
                                    <div class="toast-body text-white">Keranjang sementara dipindahkan ke akun Anda.</div>\
                                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>\
                                </div>\
                            </div>`;
                        toastContainer.insertAdjacentHTML('beforeend', html);
                        new bootstrap.Toast(toastContainer.lastElementChild, { delay: 3000 }).show();
                    }
                })();
            }
        }

        // Intercept add-to-cart form submit for guests
        const addToCartForm = document.getElementById('addToCartForm');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function (e) {
                if (!isAuthenticated) {
                    e.preventDefault();

                    // determine product id and qty
                    const action = this.action || '';
                    const match = action.match(/\/cart\/add\/(\d+)/);
                    const id = match ? match[1] : (this.querySelector('[name="id"]') ? this.querySelector('[name="id"]').value : null);
                    const qty = parseInt(document.getElementById('qtyInput').value || '1');
                    if (!id) return;

                    // Also store some product metadata so guest can preview cart
                    const pname = document.getElementById('modalName') ? document.getElementById('modalName').textContent : '';
                    const pprice = document.getElementById('modalPrice') ? document.getElementById('modalPrice').textContent : '';
                    const pimg = document.getElementById('modalImg') ? document.getElementById('modalImg').src : '';
                    const pdesc = document.getElementById('modalDesc') ? document.getElementById('modalDesc').textContent : '';

                    let guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
                    const existing = guestCart.find(it => String(it.id) === String(id));
                    if (existing) {
                        existing.qty = Math.min((existing.qty || 0) + qty, 9999);
                    } else {
                        guestCart.push({ id: id, qty: qty, name: pname, price: pprice, img: pimg, desc: pdesc, addedAt: Date.now() });
                    }
                    localStorage.setItem('guest_cart', JSON.stringify(guestCart));

                    // close modal if open
                    const modalEl = document.getElementById('addToCartModal');
                    const modalInstance = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                    if (modalInstance) modalInstance.hide();

                    if (toastContainer) {
                        const html = `\
                            <div class="toast align-items-center text-bg-info border-0 glass-card" role="alert" aria-live="assertive" aria-atomic="true">\
                                <div class="d-flex">\
                                    <div class="toast-body text-white">Item disimpan sementara. Login diperlukan saat checkout.</div>\
                                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>\
                                </div>\
                            </div>`;
                        toastContainer.insertAdjacentHTML('beforeend', html);
                        new bootstrap.Toast(toastContainer.lastElementChild, { delay: 3000 }).show();
                    }
                }
            });
        }
    });
</script>
@endpush