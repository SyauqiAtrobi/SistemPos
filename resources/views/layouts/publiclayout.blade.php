<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Baba Parfum Depok - @yield('title', 'Katalog')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Mobile First CSS & Custom Variables */
        :root {
            --primary-blue: #0056b3;
            --light-blue: #e3f2fd;
            --soft-white: #f8f9fa;
            --gradient-bg: linear-gradient(135deg, #f6f8fd 0%, #e9f0f7 100%);
            --gradient-primary: linear-gradient(135deg, #007bff 0%, #0052cc 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gradient-bg);
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Classes */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 86, 179, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Teks Gradasi */
        .text-gradient-blue {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom Button */
        .btn-custom-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom-primary:hover {
            background: linear-gradient(135deg, #0052cc 0%, #003d99 100%);
            color: white;
            transform: scale(1.05);
        }

        /* --- STYLING BOTTOM NAVIGATION (MOBILE) --- */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 -5px 20px rgba(0, 86, 179, 0.08);
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            padding-bottom: 12px;
            padding-top: 10px;
            z-index: 1040;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .bottom-nav-item {
            text-align: center;
            color: rgba(0, 50, 120, 0.4);
            text-decoration: none;
            font-size: 0.75rem;
            flex: 1;
            transition: all 0.3s;
            font-weight: 500;
        }

        .bottom-nav-item.active, .bottom-nav-item:hover {
            color: var(--primary-blue);
        }

        .bottom-nav-item i {
            font-size: 1.4rem;
            display: block;
            margin-bottom: 4px;
        }

        /* Floating Center Button (Katalog) */
        .bottom-nav-center {
            flex: 0 0 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            position: relative;
        }

        .bottom-nav-center .center-circle {
            position: absolute;
            top: -40px; 
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0, 86, 179, 0.3);
            border: 4px solid white;
            transition: transform 0.3s;
        }

        .bottom-nav-center .center-circle i {
            color: white;
            font-size: 1.6rem;
            margin: 0;
        }

        .bottom-nav-center span {
            margin-top: 25px; /* Memberi ruang untuk lingkaran yang melayang */
            color: var(--primary-blue);
            font-weight: 600;
        }

        /* --- RESPONSIVITAS KHUSUS --- */
        @media (max-width: 991.98px) {
            /* Beri jarak bawah agar konten tidak tertutup Bottom Nav di Mobile */
            body { padding-bottom: 90px; }
        }

        @media (min-width: 768px) {
            body { font-size: 16px; }
        }

        /* Input Pencarian Desktop */
        .search-desktop {
            width: 500px;
            background: rgba(0, 86, 179, 0.05);
            border: none;
        }
        .search-desktop:focus {
            background: white;
            box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.1);
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        // Kalkulasi jumlah keranjang untuk dipakai di Desktop maupun Mobile
        $cartCount = (Auth::check() && Auth::user()->role === 'customer')
            ? \App\Models\Cart::where('user_id', Auth::id())->sum('qty')
            : 0;
        $cartBadge = $cartCount > 99 ? '99+' : $cartCount;
    @endphp

    <nav class="navbar glass-nav sticky-top shadow-sm py-2 py-lg-3">
        <div class="container-fluid px-3 px-lg-4 d-flex justify-content-between align-items-center">
            
            <a class="navbar-brand fw-bold text-gradient-blue m-0 fs-4" href="{{ route('katalog.index') }}">
                BabaPOS
            </a>

            <div class="d-flex align-items-center gap-3 gap-lg-4">
                
                @if(request()->routeIs('katalog.index'))
                <div class="position-relative d-none d-lg-block">
                    <input id="desktopSearchInput" type="text" class="form-control rounded-pill search-desktop ps-3 pe-5" placeholder="Cari aroma parfum...">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 end-0 translate-middle-y me-3 opacity-50"></i>
                </div>
                @endif

                {{-- Orders page: show search for ordered products and hide cart --}}
                @if(request()->routeIs('orders.index'))
                <div class="position-relative d-none d-lg-block">
                    <input id="ordersSearchInput" type="text" class="form-control rounded-pill search-desktop ps-3 pe-5" placeholder="Cari produk di pesanan Anda...">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 end-0 translate-middle-y me-3 opacity-50"></i>
                </div>
                <a href="#" class="text-primary d-lg-none text-decoration-none" id="ordersMobileSearchToggle">
                    <i class="fa-solid fa-magnifying-glass fs-5"></i>
                </a>
                @endif

                <a href="#" class="text-primary position-relative text-decoration-none">
                    <i class="fa-regular fa-comment-dots fs-5"></i>
                </a>

                @if(!request()->routeIs('cart.*') && !request()->routeIs('orders.index'))
                <a href="{{ route('cart.index') }}" id="cartIcon" class="text-primary position-relative text-decoration-none me-1 d-none d-lg-inline-block">
                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm server-cart-badge" style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                            {{ $cartBadge }}
                        </span>
                    @endif
                </a>
                @endif

                <div class="d-none d-lg-flex align-items-center ms-2">
                    @if(Auth::check())
                        <div class="dropdown">
                            <a class="btn btn-light rounded-pill px-4 border shadow-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-user me-2"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 12px;">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item py-2" href="{{ route('order.manage') }}">Dashboard Admin</a></li>
                                @endif
                                <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">Pengaturan Akun</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger fw-medium">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-custom-primary">Daftar</a>
                    @endif
                </div>

            </div>
        </div>
    </nav>

    <div class="bottom-nav d-lg-none">
        
        <a href="{{ route('cart.index') }}" id="bottomCartIcon" class="bottom-nav-item position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fa-solid fa-basket-shopping"></i>
            <span>Keranjang</span>
            @if($cartCount > 0)
                <span class="position-absolute translate-middle badge rounded-pill bg-danger shadow-sm server-cart-badge" style="top:0; left: 60%; font-size: 0.65rem; padding: 0.25em 0.5em;">
                    {{ $cartBadge }}
                </span>
            @endif
        </a>
        
        <a href="{{ route('katalog.index') }}" class="bottom-nav-item bottom-nav-center">
            <div class="center-circle shadow-lg">
                <i class="fa-solid fa-store"></i>
            </div>
            <span>Katalog</span>
        </a>

        @if(Auth::check())
            <a href="#" id="mobileAccountBtn" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fa-regular fa-user"></i>
                <span>Akun Saya</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="bottom-nav-item">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Masuk</span>
            </a>
        @endif
    </div>

    <!-- Floating mobile search overlay (hidden by default) -->
    <div id="mobileSearchOverlay" style="display:none; position:fixed; inset:12px; z-index:1060;">
        <div class="card glass-card p-3 h-100 overflow-auto" style="border-radius:12px;">
            <div class="d-flex gap-2 mb-2 align-items-center">
                <input id="mobileSearchInput" type="search" class="form-control rounded-pill" placeholder="Cari parfum...">
                <button id="mobileSearchClose" class="btn btn-light"><i class="fa-solid fa-times"></i></button>
            </div>
            <div id="mobileSearchResults" class="row row-cols-1 g-3" style="min-height:60px;"></div>
        </div>
    </div>

    <!-- Mobile Account Menu Modal -->
    <div class="modal fade" id="mobileAccountMenu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-bottom">
            <div class="modal-content glass-card border-0">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Akun Saya</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">Pesanan Saya</a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">Akun</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container-fluid px-4 py-4 fade-in-up">
        @yield('content')
    </main>

    <x-toast />
    <x-confirm-modal />
    @stack('modals')

    <!-- Guest Cart Modal (rendered for both guests and auth; guests will see localStorage contents) -->
    <div class="modal fade" id="guestCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gradient-blue"><i class="fa-solid fa-shopping-cart me-2"></i> Keranjang Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="guestCartItems" class="list-group mb-3"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Catatan: Anda dapat menambahkan barang sebagai tamu. Login diperlukan saat checkout.</small>
                        <div>
                            <a href="{{ route('login') }}" id="guestCheckoutBtn" class="btn btn-custom-primary">Login untuk Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const isAuthenticatedClient = @json(Auth::check());

        function getGuestCart() {
            return JSON.parse(localStorage.getItem('guest_cart') || '[]');
        }

        function setGuestCart(cart) {
            localStorage.setItem('guest_cart', JSON.stringify(cart));
            updateGuestBadge();
        }

        function updateGuestBadge() {
            const cartIcon = document.getElementById('cartIcon');
            const bottomCart = document.getElementById('bottomCartIcon');
            const guestCart = getGuestCart();
            const totalQty = guestCart.reduce((s,i)=>s + (parseInt(i.qty)||0), 0);

            function upsertBadge(targetEl) {
                if (!targetEl) return;
                let guestBadge = targetEl.querySelector('.guest-cart-badge');
                if (totalQty > 0) {
                    if (!guestBadge) {
                        guestBadge = document.createElement('span');
                        guestBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm guest-cart-badge';
                        guestBadge.style.fontSize = '0.65rem';
                        guestBadge.style.padding = '0.25em 0.5em';
                        targetEl.appendChild(guestBadge);
                    }
                    guestBadge.textContent = totalQty > 99 ? '99+' : totalQty;
                } else if (guestBadge) {
                    guestBadge.remove();
                }
            }

            upsertBadge(cartIcon);
            upsertBadge(bottomCart);
        }

        function changeGuestQty(id, delta) {
            const cart = getGuestCart();
            const idx = cart.findIndex(it => String(it.id) === String(id));
            if (idx === -1) return;
            cart[idx].qty = Math.max(0, parseInt(cart[idx].qty||0) + delta);
            if (cart[idx].qty <= 0) {
                cart.splice(idx, 1);
            }
            setGuestCart(cart);
            renderGuestCartModal();
        }

        function renderGuestCartModal() {
            const itemsContainer = document.getElementById('guestCartItems');
            itemsContainer.innerHTML = '';
            const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
            if (!guestCart.length) {
                itemsContainer.innerHTML = '<div class="text-center py-4 text-muted">Keranjang sementara kosong.</div>';
                return;
            }

            let totalQty = 0;
            for (const it of guestCart) {
                totalQty += parseInt(it.qty || 0);
                const el = document.createElement('div');
                el.className = 'list-group-item d-flex gap-3 align-items-start';

                const img = document.createElement('img');
                img.src = it.img || 'https://via.placeholder.com/80';
                img.className = 'rounded-3';
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';

                const content = document.createElement('div');
                content.className = 'flex-fill';

                const title = document.createElement('div');
                title.className = 'fw-bold text-truncate';
                title.textContent = it.name || 'Produk';

                const price = document.createElement('div');
                price.className = 'text-muted small mb-1';
                price.textContent = it.price || '';

                const descContainer = document.createElement('div');
                descContainer.className = 'text-muted small';
                const fullDesc = (it.desc || '').trim();
                const maxLen = 120;
                if (fullDesc.length > maxLen) {
                    descContainer.textContent = fullDesc.slice(0, maxLen) + '...';
                    const toggleBtn = document.createElement('button');
                    toggleBtn.type = 'button';
                    toggleBtn.className = 'btn btn-link btn-sm p-0 ms-2';
                    toggleBtn.textContent = 'Tampilkan semua';
                    toggleBtn.addEventListener('click', function () {
                        if (toggleBtn.textContent === 'Tampilkan semua') {
                            descContainer.textContent = fullDesc;
                            toggleBtn.textContent = 'Tampilkan sebagian';
                        } else {
                            descContainer.textContent = fullDesc.slice(0, maxLen) + '...';
                            toggleBtn.textContent = 'Tampilkan semua';
                        }
                    });
                    const descWrap = document.createElement('div');
                    descWrap.appendChild(descContainer);
                    descWrap.appendChild(toggleBtn);
                    content.appendChild(title);
                    content.appendChild(price);
                    content.appendChild(descWrap);
                } else {
                    descContainer.textContent = fullDesc || '';
                    content.appendChild(title);
                    content.appendChild(price);
                    content.appendChild(descContainer);
                }

                const qtyDiv = document.createElement('div');
                qtyDiv.className = 'text-end d-flex flex-column align-items-end';

                const qtyLabel = document.createElement('div');
                qtyLabel.className = 'fw-semibold mb-2';
                qtyLabel.textContent = 'Qty: ';
                const qtyValue = document.createElement('span');
                qtyValue.className = 'badge bg-light text-dark';
                qtyValue.style.minWidth = '38px';
                qtyValue.style.display = 'inline-block';
                qtyValue.style.textAlign = 'center';
                qtyValue.textContent = it.qty;
                qtyLabel.appendChild(qtyValue);

                const btnGroup = document.createElement('div');
                btnGroup.className = 'btn-group btn-group-sm';

                const decBtn = document.createElement('button');
                decBtn.type = 'button';
                decBtn.className = 'btn btn-outline-secondary';
                decBtn.textContent = '-';
                decBtn.addEventListener('click', function () { changeGuestQty(it.id, -1); });

                const incBtn = document.createElement('button');
                incBtn.type = 'button';
                incBtn.className = 'btn btn-outline-secondary';
                incBtn.textContent = '+';
                incBtn.addEventListener('click', function () { changeGuestQty(it.id, 1); });

                btnGroup.appendChild(decBtn);
                btnGroup.appendChild(incBtn);

                qtyDiv.appendChild(qtyLabel);
                qtyDiv.appendChild(btnGroup);

                el.appendChild(img);
                el.appendChild(content);
                el.appendChild(qtyDiv);

                itemsContainer.appendChild(el);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cartIcon = document.getElementById('cartIcon');
            if (!cartIcon) return;

            // Show guest badge if not authenticated
            if (!isAuthenticatedClient) {
                const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
                const totalQty = guestCart.reduce((s,i)=>s + (parseInt(i.qty)||0), 0);

                // remove server badge if present (server shows 0 for guests)
                const serverBadge = cartIcon ? cartIcon.querySelector('.server-cart-badge') : null;
                if (serverBadge) serverBadge.remove();
                const bottomCart = document.getElementById('bottomCartIcon');
                const serverBadgeBottom = bottomCart ? bottomCart.querySelector('.server-cart-badge') : null;
                if (serverBadgeBottom) serverBadgeBottom.remove();

                if (totalQty > 0) {
                    updateGuestBadge();
                }

                // intercept click to show guest cart modal instead of redirecting to protected page
                [cartIcon, bottomCart].forEach(function(el) {
                    if (!el) return;
                    el.addEventListener('click', function (e) {
                        const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
                        if (!isAuthenticatedClient) {
                            e.preventDefault();
                            renderGuestCartModal();
                            const modal = new bootstrap.Modal(document.getElementById('guestCartModal'));
                            modal.show();
                        }
                    });
                });
            }
            // Mobile search handling: show floating overlay and perform AJAX search
            const mobileSearchBtn = document.querySelector('.d-lg-none .fa-magnifying-glass') ? document.querySelector('.d-lg-none .fa-magnifying-glass').closest('a') : null;
            const overlay = document.getElementById('mobileSearchOverlay');
            const input = document.getElementById('mobileSearchInput');
            const results = document.getElementById('mobileSearchResults');
            const closeBtn = document.getElementById('mobileSearchClose');

            function renderProductsGrid(items) {
                results.innerHTML = '';
                if (!items.length) {
                    results.innerHTML = '<div class="col-12 text-center text-muted py-4">Produk tidak ditemukan.</div>';
                    return;
                }
                for (const p of items) {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="d-flex gap-3 align-items-start">
                            <img src="${p.image}" style="width:84px;height:84px;object-fit:cover;border-radius:10px;"/>
                            <div class="flex-fill">
                                <div class="fw-bold text-truncate">${p.name}</div>
                                <div class="small text-muted">${p.category || ''}</div>
                                <div class="fw-semibold text-gradient-blue mt-1">${p.price_text}</div>
                            </div>
                            <div class="text-end">
                                <a href="/cart/add/${p.id}" class="btn btn-sm btn-custom-primary">Tambah</a>
                            </div>
                        </div>
                    `;
                    results.appendChild(col);
                }
            }

            let searchTimer = null;
            async function doMobileSearch(q) {
                try {
                    const res = await fetch(`/katalog?q=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    renderProductsGrid(json.products || []);
                } catch (err) {
                    console.error('Search failed', err);
                }
            }

            if (mobileSearchBtn && overlay && input) {
                mobileSearchBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    overlay.style.display = 'block';
                    input.focus();
                    input.value = '';
                    results.innerHTML = '<div class="col-12 text-center text-muted py-3">Ketik untuk mencari...</div>';
                });

                closeBtn.addEventListener('click', function () { overlay.style.display = 'none'; });

                input.addEventListener('input', function () {
                    const q = input.value.trim();
                    if (searchTimer) clearTimeout(searchTimer);
                    if (q.length < 1) {
                        results.innerHTML = '<div class="col-12 text-center text-muted py-3">Ketik untuk mencari...</div>';
                        return;
                    }
                    searchTimer = setTimeout(() => doMobileSearch(q), 300);
                });
            }

            // Mobile account menu trigger
            const mobileAccountBtn = document.getElementById('mobileAccountBtn');
            if (mobileAccountBtn) {
                mobileAccountBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var modal = new bootstrap.Modal(document.getElementById('mobileAccountMenu'));
                    modal.show();
                });
            }

            // Orders page mobile search toggle
            const ordersMobileSearchToggle = document.getElementById('ordersMobileSearchToggle');
            if (ordersMobileSearchToggle) {
                ordersMobileSearchToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    // reuse mobile search overlay but focus the input
                    overlay.style.display = 'block';
                    input.focus();
                    input.value = '';
                    results.innerHTML = '<div class="col-12 text-center text-muted py-3">Ketik untuk mencari...</div>';
                });
            }
        });
    </script>
    
    <script>
        // Inisialisasi Toast Global
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 })
            });
            toastList.forEach(toast => toast.show());
        });

        // Fungsi Global untuk Custom Confirm Modal
        function showConfirmModal(title, message, confirmCallback) {
            document.getElementById('customModalTitle').innerText = title;
            document.getElementById('customModalBody').innerText = message;
            
            let confirmBtn = document.getElementById('customModalConfirmBtn');
            let newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            newConfirmBtn.addEventListener('click', function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('customConfirmModal'));
                modal.hide();
                confirmCallback();
            });

            var modal = new bootstrap.Modal(document.getElementById('customConfirmModal'));
            modal.show();
        }
    </script>
    @stack('scripts')
</body>
</html>
