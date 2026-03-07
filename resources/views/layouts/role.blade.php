<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Baba Parfum Depok - @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
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

        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 86, 179, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 86, 179, 0.15);
        }

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

        .role-sidebar {
            width: 270px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1030;
            background: linear-gradient(180deg, #0d4ea6 0%, #09397b 100%);
            color: #fff;
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.18);
            transition: width .25s ease;
        }

        .role-sidebar-link {
            display: block;
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 12px;
            margin: 4px 0;
            transition: background-color .2s ease, color .2s ease;
        }

        .role-sidebar-link i {
            width: 20px;
            text-align: center;
        }

        .role-sidebar-link:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .role-sidebar-link.active {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            font-weight: 600;
        }

        .role-main {
            padding-left: 0;
            transition: margin-left .25s ease, width .25s ease;
        }

        .role-sidebar-toggle {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .role-sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        @media (min-width: 768px) {
            body { font-size: 16px; }
            .container { max-width: 720px; }
        }

        @media (min-width: 992px) {
            .container { max-width: 960px; }
            .glass-card { padding: 2rem; }

            .role-main {
                margin-left: 270px;
                width: calc(100% - 270px);
            }

            body.role-sidebar-collapsed .role-sidebar {
                width: 88px;
            }

            body.role-sidebar-collapsed .role-main {
                margin-left: 88px;
                width: calc(100% - 88px);
            }

            body.role-sidebar-collapsed .role-sidebar-brand .sidebar-label,
            body.role-sidebar-collapsed .role-sidebar .sidebar-label,
            body.role-sidebar-collapsed .role-sidebar .role-user-name,
            body.role-sidebar-collapsed .role-sidebar .role-logout-label {
                display: none;
            }

            body.role-sidebar-collapsed .role-sidebar-link {
                text-align: center;
                padding: 12px 8px;
            }

            body.role-sidebar-collapsed .role-sidebar-link i {
                margin-right: 0 !important;
            }

            body.role-sidebar-collapsed .role-sidebar .btn {
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $cartCount = Auth::user()->role === 'customer'
            ? \App\Models\Cart::where('user_id', Auth::id())->sum('qty')
            : 0;
        $cartBadge = $cartCount > 99 ? '99+' : $cartCount;
    @endphp

    <div class="role-mobile-topbar d-lg-none bg-white border-bottom shadow-sm">
        <div class="container-fluid py-2 d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#roleSidebarMobile" aria-controls="roleSidebarMobile">
                <i class="fa-solid fa-bars me-1"></i> Menu
            </button>
            <a class="fw-bold text-primary text-decoration-none" href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('katalog.index') }}">
                BabaPOS
            </a>
            <a class="btn btn-light btn-sm" href="{{ route('profile.edit') }}">Profile</a>
        </div>
    </div>

    <aside class="role-sidebar d-none d-lg-flex flex-column">
        <div class="role-sidebar-brand px-3 py-3 d-flex align-items-center justify-content-between">
            <a class="fw-bold fs-5 text-decoration-none text-white" href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('katalog.index') }}">
                <i class="fa-solid fa-store me-2"></i><span class="sidebar-label">BabaPOS</span>
            </a>
            <button type="button" class="role-sidebar-toggle" id="roleSidebarToggle" aria-label="Toggle sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </button>
        </div>

        <div class="px-2 pb-3 flex-grow-1">
            @if(Auth::user()->role === 'admin')
                <a class="role-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge-high me-2"></i><span class="sidebar-label">Dashboard</span>
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('product.*') ? 'active' : '' }}" href="{{ route('product.manage') }}">
                    <i class="fa-solid fa-boxes-stacked me-2"></i><span class="sidebar-label">Produk</span>
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('category.*') ? 'active' : '' }}" href="{{ route('category.manage') }}">
                    <i class="fa-solid fa-tags me-2"></i><span class="sidebar-label">Kategori</span>
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('order.manage') ? 'active' : '' }}" href="{{ route('order.manage') }}">
                    <i class="fa-solid fa-file-invoice-dollar me-2"></i><span class="sidebar-label">Pesanan</span>
                </a>
            @else
                <a class="role-sidebar-link {{ request()->routeIs('katalog.index') ? 'active' : '' }}" href="{{ route('katalog.index') }}">
                    <i class="fa-solid fa-store me-2"></i><span class="sidebar-label">Katalog</span>
                </a>
                <a class="role-sidebar-link position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                    <i class="fa-solid fa-cart-shopping me-2"></i><span class="sidebar-label">Keranjang</span>
                    @if($cartCount > 0)
                        <span class="position-absolute top-50 end-0 translate-middle-y badge rounded-pill bg-danger me-2">{{ $cartBadge }}</span>
                    @endif
                </a>
            @endif

            <a class="role-sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                <i class="fa-solid fa-user me-2"></i><span class="sidebar-label">Profile</span>
            </a>
        </div>

        <div class="px-3 py-3 border-top border-light border-opacity-25 text-white-50 small">
            <div class="mb-2 role-user-name">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="fa-solid fa-right-from-bracket me-1"></i><span class="role-logout-label">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="roleSidebarMobile" aria-labelledby="roleSidebarMobileLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-primary" id="roleSidebarMobileLabel">BabaPOS</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            @if(Auth::user()->role === 'admin')
                <a class="role-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-gauge-high me-2"></i>Dashboard
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('product.*') ? 'active' : '' }}" href="{{ route('product.manage') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-boxes-stacked me-2"></i>Produk
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('category.*') ? 'active' : '' }}" href="{{ route('category.manage') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-tags me-2"></i>Kategori
                </a>
                <a class="role-sidebar-link {{ request()->routeIs('order.manage') ? 'active' : '' }}" href="{{ route('order.manage') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-file-invoice-dollar me-2"></i>Pesanan
                </a>
            @else
                <a class="role-sidebar-link {{ request()->routeIs('katalog.index') ? 'active' : '' }}" href="{{ route('katalog.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-store me-2"></i>Katalog
                </a>
                <a class="role-sidebar-link position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-cart-shopping me-2"></i>Keranjang
                    @if($cartCount > 0)
                        <span class="position-absolute top-50 end-0 translate-middle-y badge rounded-pill bg-danger me-2">{{ $cartBadge }}</span>
                    @endif
                </a>
            @endif

            <a class="role-sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}" data-bs-dismiss="offcanvas">
                <i class="fa-solid fa-user me-2"></i>Profile
            </a>

            <div class="mt-auto pt-3 border-top">
                <div class="small text-muted mb-2">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <main class="container py-4 fade-in-up role-main">
        @yield('content')
    </main>

    <x-toast />
    <x-confirm-modal />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
            toastList.forEach(function(toast) { toast.show(); });

            const sidebarToggle = document.getElementById('roleSidebarToggle');
            if (sidebarToggle) {
                const storageKey = 'babapos_role_sidebar_collapsed';
                const sidebarToggleIcon = sidebarToggle.querySelector('i');

                const syncSidebarToggleIcon = () => {
                    if (!sidebarToggleIcon) return;
                    const isCollapsed = document.body.classList.contains('role-sidebar-collapsed');
                    sidebarToggleIcon.classList.toggle('fa-angles-left', !isCollapsed);
                    sidebarToggleIcon.classList.toggle('fa-angles-right', isCollapsed);
                };

                if (localStorage.getItem(storageKey) === '1') {
                    document.body.classList.add('role-sidebar-collapsed');
                }
                syncSidebarToggleIcon();

                sidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('role-sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('role-sidebar-collapsed');
                    localStorage.setItem(storageKey, isCollapsed ? '1' : '0');
                    syncSidebarToggleIcon();
                });
            }
        });

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
