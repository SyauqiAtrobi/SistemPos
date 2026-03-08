@extends('layouts.publiclayout')

@section('title', 'Katalog Parfum')

@push('styles')
    <style>
        .category-scroll {
            display: flex;
            overflow-x: auto;
            gap: 14px;
            padding-bottom: 20px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .category-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 86, 179, 0.1);
            color: #475569;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 86, 179, 0.04);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn-glass:hover {
            background: var(--pure-white);
            border-color: rgba(0, 123, 255, 0.3);
            color: var(--primary-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 86, 179, 0.12);
        }

        .product-card {
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 6px;
        }

        .product-img {
            height: 240px;
            object-fit: cover;
            border-radius: 16px;
            transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .img-wrapper {
            overflow: hidden;
            border-radius: 16px;
            position: relative;
            margin-bottom: 12px;
        }

        .img-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 50%);
            opacity: 0;
            transition: opacity 0.4s;
            pointer-events: none;
        }

        .glass-card:hover .img-wrapper::after {
            opacity: 1;
        }

        .glass-card:hover .product-img {
            transform: scale(1.1);
        }

        .badge-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 86, 179, 0.1);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 6px 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-glass-disabled {
            background: rgba(241, 245, 249, 0.6);
            border: 1px solid rgba(203, 213, 225, 0.5);
            color: #94a3b8;
            font-weight: 600;
            box-shadow: none;
            cursor: not-allowed;
        }

        .price-text {
            font-size: 1.35rem;
            letter-spacing: -0.5px;
        }

        .stock-text {
            font-size: 0.85rem;
            padding: 4px 10px;
            background: var(--light-blue);
            border-radius: 8px;
        }

        .btn-cart {
            font-size: 0.95rem;
            padding: 0.6rem 1.2rem;
        }

        .qty-control {
            background: var(--light-blue);
            border-radius: 50px;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(0, 123, 255, 0.1);
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background: var(--pure-white);
            color: var(--primary-blue);
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 86, 179, 0.05);
        }

        .qty-btn:hover {
            background: var(--primary-blue);
            color: var(--pure-white);
            transform: scale(1.05);
        }

        .qty-input {
            width: 45px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-main);
        }

        .qty-input:focus {
            outline: none;
        }

        @media (max-width: 575.98px) {
            .product-img {
                height: 160px;
            }

            .card-body {
                padding: 0.5rem;
            }

            .card-title {
                font-size: 0.95rem !important;
            }

            .card-text {
                font-size: 0.8rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin-bottom: 0.75rem !important;
            }

            .badge-glass {
                padding: 4px 8px;
                font-size: 0.7rem;
            }

            .price-text {
                font-size: 1.1rem !important;
            }

            .stock-text {
                font-size: 0.75rem !important;
            }

            .btn-cart {
                font-size: 0.85rem;
                padding: 0.5rem 0.8rem;
            }
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            .product-img {
                height: 200px;
            }
        }

        .custom-detail-modal {
            max-width: 750px;
        }

        .modal-img-container {
            height: 100%;
            min-height: 280px;
        }

        .modal-img-container img {
            height: 100%;
            max-height: 380px;
            object-fit: cover;
            width: 100%;
        }

        .desc-container {
            max-height: 140px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .desc-container::-webkit-scrollbar {
            width: 4px;
        }

        .desc-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        @media (max-width: 767.98px) {
            .modal-img-container {
                min-height: 220px;
            }

            .modal-img-container img {
                height: 220px;
            }

            .desc-container {
                max-height: 120px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="text-center mb-5 pt-3 fade-in-up">
        <h2 class="fw-bold text-gradient-blue display-6" style="letter-spacing: -1px;">Katalog Baba Parfum</h2>
        <p class="text-secondary fs-6 mt-2">Temukan aroma yang mencerminkan karakter elegan Anda</p>
    </div>

    <div class="mb-4 category-scroll fade-in-up" style="animation-delay: 0.1s;">
        <a href="{{ url('/katalog') }}"
            class="btn {{ request('category') ? 'btn-glass' : 'btn-custom-primary shadow-lg' }} text-nowrap rounded-pill px-4 py-2">
            <i class="fa-solid fa-layer-group me-2"></i>Semua Aroma
        </a>

        @foreach($categories as $category)
            <a href="{{ url('/katalog?category=' . $category->slug) }}"
                class="btn {{ request('category') == $category->slug ? 'btn-custom-primary shadow-lg' : 'btn-glass' }} text-nowrap rounded-pill px-4 py-2 text-decoration-none">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <div id="productGrid" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4 fade-in-up"
        style="animation-delay: 0.2s;">

        @forelse($products as $product)
            <div class="col">
                <div class="glass-card product-card">

                    <div class="img-wrapper">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400' }}"
                            class="w-100 product-img" alt="{{ $product->name }}">
                        <div class="position-absolute top-0 end-0 p-2 z-2">
                            <span class="badge badge-glass rounded-pill">{{ $product->category->name ?? 'Umum' }}</span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-2 pt-0 z-1 border-0 bg-transparent">
                        <h5 class="card-title fw-bold mb-1 text-truncate text-dark" title="{{ $product->name }}">
                            {{ $product->name }}
                        </h5>

                        <p class="card-text text-secondary small flex-grow-1 mb-3">
                            {{ Str::limit($product->description, 60, '...') }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex flex-column gap-2 mb-3">
                                <span class="price-text fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="stock-text fw-semibold {{ $product->stock > 0 ? 'text-primary' : 'text-secondary opacity-50 bg-light' }}">
                                        <i class="fa-solid {{ $product->stock > 0 ? 'fa-box' : 'fa-box-open' }} me-1"></i>
                                        {{ $product->stock > 0 ? 'Sisa ' . $product->stock : 'Habis' }}
                                    </span>
                                </div>
                            </div>

                            @if($product->stock > 0)
                                <button type="button" class="btn btn-custom-primary btn-cart w-100 rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#addToCartModal" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-category="{{ $product->category->name ?? 'Umum' }}"
                                    data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                                    data-stock="{{ $product->stock }}" data-desc="{{ $product->description }}"
                                    data-img="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400' }}">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Tambah
                                </button>
                            @else
                                <button class="btn btn-glass-disabled w-100 rounded-pill" disabled>
                                    Kosong
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @empty

            <div class="col-12 text-center py-5">
                <div class="glass-card p-5 mx-auto border-0" style="max-width: 450px; border-radius: 24px;">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-box-open fa-2x opacity-50"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Belum Ada Parfum</h4>
                    <p class="text-secondary small mb-0">Kategori atau produk yang Anda cari sedang kosong.</p>
                </div>
            </div>

        @endforelse

    </div>
@endsection

@push('modals')
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-detail-modal">
            <div class="modal-content glass-card border-0" style="border-radius: 24px;">

                <div class="modal-header border-0 pb-0 pt-4 px-4 px-md-5">
                    <h5 class="modal-title fw-bold text-gradient-blue fs-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-info me-2 text-primary opacity-75 fs-5"></i> Detail Parfum
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 px-md-5 pb-4 pt-4">
                    <div class="row g-4">

                        <div class="col-12 col-md-5">
                            <div
                                class="text-center position-relative modal-img-container rounded-4 shadow-sm overflow-hidden border border-light">
                                <img id="modalImg" src="" class="img-fluid bg-light" alt="Gambar Produk">

                                <div class="position-absolute bottom-0 start-0 w-100 p-3"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.65), transparent);">
                                    <span id="modalCategory"
                                        class="badge bg-primary text-white rounded-pill px-3 py-2 border border-white shadow-sm"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-7 d-flex flex-column">

                            <div class="mb-3">
                                <h4 id="modalName" class="fw-bold text-dark mb-2 fs-3"
                                    style="letter-spacing: -0.5px; line-height: 1.2;"></h4>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <span id="modalPrice" class="fs-4 fw-bold text-primary"></span>
                                    <span id="modalStock"
                                        class="small fw-semibold text-secondary bg-light px-3 py-1 rounded-pill border"></span>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-4 border mb-4 flex-grow-1 desc-container shadow-sm">
                                <p id="modalDesc" class="text-secondary small mb-0" style="line-height: 1.7;"></p>
                            </div>

                            <form id="addToCartForm" method="POST" action="" class="mt-auto">
                                @csrf
                                <div
                                    class="d-flex align-items-center justify-content-between gap-3 mb-3 bg-white p-2 px-3 rounded-4 border shadow-sm">
                                    <label class="text-dark fw-bold mb-0 ms-1 small">Kuantitas</label>

                                    <div class="qty-control" style="padding: 3px;">
                                        <button type="button" class="qty-btn" style="width: 32px; height: 32px;"
                                            onclick="decrementQty()">
                                            <i class="fa-solid fa-minus" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <input type="number" id="qtyInput" name="qty" value="1" min="1" class="qty-input"
                                            style="width: 40px; font-size: 1rem;" readonly>
                                        <button type="button" class="qty-btn" style="width: 32px; height: 32px;"
                                            onclick="incrementQty()">
                                            <i class="fa-solid fa-plus" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-custom-primary w-100 rounded-pill py-2 shadow-sm fs-6 fw-bold transition-smooth">
                                    <i class="fa-solid fa-basket-shopping me-2"></i> Tambah ke Keranjang
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
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
                        <div class="glass-card p-5 mx-auto border-0" style="max-width: 450px; border-radius: 24px;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-4" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-box-open fa-2x opacity-50"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Belum Ada Parfum</h4>
                            <p class="text-secondary small mb-0">Produk yang Anda cari tidak ditemukan.</p>
                        </div>
                    </div>
                `;
                    return;
                }

                for (const p of items) {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                    <div class="glass-card product-card">
                        <div class="img-wrapper">
                            <img src="${p.image}" class="w-100 product-img" alt="${p.name}">
                            <div class="position-absolute top-0 end-0 p-2 z-2">
                                <span class="badge badge-glass rounded-pill">${p.category || 'Umum'}</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column p-2 pt-0 z-1 border-0 bg-transparent">
                            <h5 class="card-title fw-bold mb-1 text-truncate text-dark" title="${p.name}">${p.name}</h5>
                            <p class="card-text text-secondary small flex-grow-1 mb-3">${(p.description || '').substring(0, 60)}${(p.description && p.description.length > 60) ? '...' : ''}</p>
                            <div class="mt-auto">
                                <div class="d-flex flex-column gap-2 mb-3">
                                    <span class="price-text fw-bold text-primary">${p.price_text}</span>
                                    <div class="d-flex align-items-center">
                                        <span class="stock-text fw-semibold ${p.stock > 0 ? 'text-primary' : 'text-secondary opacity-50 bg-light'}">
                                            <i class="fa-solid ${p.stock > 0 ? 'fa-box' : 'fa-box-open'} me-1"></i>${p.stock > 0 ? 'Sisa ' + p.stock : 'Habis'}
                                        </span>
                                    </div>
                                </div>
                                ${p.stock > 0 ? `<button type="button" class="btn btn-custom-primary btn-cart w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#addToCartModal" data-id="${p.id}" data-name="${escapeHtml(p.name)}" data-category="${escapeHtml(p.category || 'Umum')}" data-price="${escapeHtml(p.price_text)}" data-stock="${p.stock}" data-desc="${escapeHtml(p.description || '')}" data-img="${p.image}"><i class="fa-solid fa-cart-plus me-1"></i> Tambah</button>` : `<button class="btn btn-glass-disabled w-100 rounded-pill" disabled>Kosong</button>`}
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
                    timer = setTimeout(() => doSearch(''), 200);
                    return;
                }
                timer = setTimeout(() => doSearch(q), 300);
            });
        });

        let currentMaxStock = 0;

        const addToCartModal = document.getElementById('addToCartModal');
        if (addToCartModal) {
            addToCartModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const category = button.getAttribute('data-category');
                const price = button.getAttribute('data-price');
                const stock = button.getAttribute('data-stock');
                const desc = button.getAttribute('data-desc');
                const img = button.getAttribute('data-img');

                currentMaxStock = parseInt(stock);

                document.getElementById('modalName').textContent = name;
                document.getElementById('modalCategory').textContent = category;
                document.getElementById('modalDesc').textContent = desc || 'Tidak ada deskripsi lengkap untuk produk ini.';
                document.getElementById('modalPrice').textContent = price;
                document.getElementById('modalStock').textContent = 'Sisa stok: ' + stock;
                document.getElementById('modalImg').src = img;

                document.getElementById('qtyInput').value = 1;

                document.getElementById('addToCartForm').action = `/cart/add/${id}`;
            });
        }

        function incrementQty() {
            const input = document.getElementById('qtyInput');
            let val = parseInt(input.value);
            if (val < currentMaxStock) {
                input.value = val + 1;
            } else {
                const toastHTML = `
                    <div class="toast custom-toast border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex align-items-center p-3">
                            <div class="toast-icon-wrapper me-3" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%); border: 1px solid rgba(245, 158, 11, 0.2);">
                                <i class="fa-solid fa-triangle-exclamation fs-5" style="color: #f59e0b;"></i>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 me-2">
                                <span class="toast-text-title mb-1">Stok Terbatas</span>
                                <span class="toast-text-desc lh-sm">Maksimal pembelian adalah ${currentMaxStock} (Sesuai stok).</span>
                            </div>
                            <button type="button" class="btn-close shadow-none opacity-50 ms-auto align-self-start mt-1" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
                const newToast = new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 4000 });
                newToast.show();
            }
        }

        function decrementQty() {
            const input = document.getElementById('qtyInput');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
            }
        }

        const isAuthenticated = @json(Auth::check());

        document.addEventListener('DOMContentLoaded', function () {
            const toastContainer = document.querySelector('.toast-container');

            if (isAuthenticated) {
                const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
                if (guestCart.length) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    (async function () {
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
                            const html = `
                                <div class="toast custom-toast border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="d-flex align-items-center p-3">
                                        <div class="toast-icon-wrapper toast-success-bg me-3">
                                            <i class="fa-solid fa-check fs-5 toast-icon-success"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 me-2">
                                            <span class="toast-text-title mb-1">Berhasil</span>
                                            <span class="toast-text-desc lh-sm">Keranjang sementara dipindahkan ke akun Anda.</span>
                                        </div>
                                        <button type="button" class="btn-close shadow-none opacity-50 ms-auto align-self-start mt-1" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>`;
                            toastContainer.insertAdjacentHTML('beforeend', html);
                            new bootstrap.Toast(toastContainer.lastElementChild, { delay: 4000 }).show();
                        }
                    })();
                }
            }

            const addToCartForm = document.getElementById('addToCartForm');
            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function (e) {
                    if (!isAuthenticated) {
                        e.preventDefault();

                        const action = this.action || '';
                        const match = action.match(/\/cart\/add\/(\d+)/);
                        const id = match ? match[1] : (this.querySelector('[name="id"]') ? this.querySelector('[name="id"]').value : null);
                        const qty = parseInt(document.getElementById('qtyInput').value || '1');
                        if (!id) return;

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
                        if (typeof window.updateGuestBadge === 'function') {
                            window.updateGuestBadge();
                        }

                        const modalEl = document.getElementById('addToCartModal');
                        const modalInstance = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (modalInstance) modalInstance.hide();

                        if (toastContainer) {
                            const html = `
                                <div class="toast custom-toast border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="d-flex align-items-center p-3">
                                        <div class="toast-icon-wrapper me-3" style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0.05) 100%); border: 1px solid rgba(14, 165, 233, 0.2);">
                                            <i class="fa-solid fa-info fs-5" style="color: #0ea5e9;"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 me-2">
                                            <span class="toast-text-title mb-1">Keranjang Tersimpan</span>
                                            <span class="toast-text-desc lh-sm">Item disimpan sementara. Login diperlukan saat checkout.</span>
                                        </div>
                                        <button type="button" class="btn-close shadow-none opacity-50 ms-auto align-self-start mt-1" data-bs-dismiss="toast"></button>
                                    </div>
                                </div>`;
                            toastContainer.insertAdjacentHTML('beforeend', html);
                            new bootstrap.Toast(toastContainer.lastElementChild, { delay: 4000 }).show();
                        }
                    }
                });
            }
        });
    </script>
@endpush