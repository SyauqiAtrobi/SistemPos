<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $appName = config('app.name') ?: env('APP_NAME', 'BabaPOS');
        $appLogo = env('APP_LOGO', '');
    @endphp
    @if(!empty($appLogo))
        <link rel="icon" href="{{ $appLogo }}">
        <link rel="apple-touch-icon" href="{{ $appLogo }}">
    @endif
    <title>{{ $appName }} - @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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

        /* Utility Classes */
        .text-gradient-blue {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-glass-blue {
            color: rgba(0, 50, 120, 0.6);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glassmorphism Cards & Navs */
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

        .glass-nav {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
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

        /* --- SIDEBAR GLASSMORPHISM (DESKTOP) --- */
        .role-sidebar {
            width: 270px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 5px 0 30px rgba(0, 86, 179, 0.05);
            transition: width .3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .role-sidebar-link {
            display: block;
            color: rgba(0, 50, 120, 0.6);
            text-decoration: none;
            border-radius: 12px;
            padding: 12px 16px;
            margin: 6px 0;
            font-weight: 500;
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }

        .role-sidebar-link i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }

        .role-sidebar-link:hover {
            background: rgba(0, 123, 255, 0.08);
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .role-sidebar-link.active {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.25);
        }

        .role-sidebar-toggle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(0, 86, 179, 0.15);
            background: rgba(255, 255, 255, 0.5);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .role-sidebar-toggle:hover {
            background: rgba(0, 123, 255, 0.1);
            color: #003d99;
        }

        .role-main {
            padding-left: 0;
            transition: margin-left .3s cubic-bezier(0.165, 0.84, 0.44, 1), width .3s;
        }

        /* Responsive Layouting */
        @media (min-width: 768px) {
            body {
                font-size: 16px;
            }
        }

        @media (min-width: 992px) {
            .role-main {
                margin-left: 270px;
                width: calc(100% - 270px);
            }

            body.role-sidebar-collapsed .role-sidebar {
                width: 90px;
            }

            body.role-sidebar-collapsed .role-main {
                margin-left: 90px;
                width: calc(100% - 90px);
            }

            body.role-sidebar-collapsed .role-sidebar-brand .sidebar-label,
            body.role-sidebar-collapsed .role-sidebar .sidebar-label,
            body.role-sidebar-collapsed .role-sidebar .role-user-name,
            body.role-sidebar-collapsed .role-sidebar .role-logout-label {
                display: none;
            }

            body.role-sidebar-collapsed .role-sidebar-link {
                text-align: center;
                padding: 14px 0;
            }

            body.role-sidebar-collapsed .role-sidebar-link i {
                margin-right: 0 !important;
                font-size: 1.3rem;
            }

            body.role-sidebar-collapsed .role-sidebar-link:hover {
                transform: none;
            }
        }

        /* --- MOBILE OFFCANVAS GLASSMORPHISM --- */
        .glass-offcanvas {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }

        .admin-offcanvas {
            width: 280px;
            max-width: 85vw;
        }

        @media (max-width: 420px) {
            .admin-offcanvas {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        /* Tombol Logout Glass Danger */
        .btn-glass-logout {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #dc3545;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .btn-glass-logout:hover {
            background: #dc3545;
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }
    </style>
    @stack('styles')
</head>

<body>

    @php
        $cartCount = 0;
        $cartBadge = '0';
    @endphp

    @php
        $appName = config('app.name') ?: env('APP_NAME', 'BabaPOS');
        $appLogo = env('APP_LOGO', '');
    @endphp
    <div class="role-mobile-topbar d-lg-none glass-nav sticky-top shadow-sm">
        <div class="container-fluid py-3 d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#roleSidebarMobile">
                <i class="fa-solid fa-bars me-1"></i>
            </button>
            <a class="fw-bold fs-5 text-gradient-blue text-decoration-none mx-auto d-flex align-items-center"
                href="{{ route('dashboard') }}">
                <span>{{ $appName }}</span>
            </a>
            <a class="text-primary text-decoration-none" href="{{ route('admin.profile.show') }}">
                <i class="fa-regular fa-circle-user fs-4"></i>
            </a>
        </div>
    </div>

    <aside class="role-sidebar d-none d-lg-flex flex-column">
        <div class="role-sidebar-brand px-4 py-4 d-flex align-items-center justify-content-between mb-2">
            <a class="fw-bold fs-4 text-decoration-none text-gradient-blue d-flex align-items-center"
                href="{{ route('dashboard') }}">
                <span class="sidebar-label">{{ $appName }}</span>
            </a>
            <button type="button" class="role-sidebar-toggle" id="roleSidebarToggle" aria-label="Toggle sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </button>
        </div>

        <div class="px-3 pb-3 flex-grow-1 overflow-y-auto" style="scrollbar-width: thin;">
            <div class="sidebar-label text-glass-blue small fw-bold mb-2 px-2 ms-1 mt-2 text-uppercase"
                style="letter-spacing: 1px;">Main Menu</div>
            <a class="role-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-pie me-2"></i><span class="sidebar-label">Dashboard</span>
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('product.*') ? 'active' : '' }}"
                href="{{ route('product.manage') }}">
                <i class="fa-solid fa-boxes-stacked me-2"></i><span class="sidebar-label">Produk</span>
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
                href="{{ route('category.manage') }}">
                <i class="fa-solid fa-tags me-2"></i><span class="sidebar-label">Kategori</span>
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('order.manage') ? 'active' : '' }}"
                href="{{ route('order.manage') }}">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i><span class="sidebar-label">Pesanan</span>
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('user.manage') ? 'active' : '' }}"
                href="{{ route('user.manage') }}">
                <i class="fa-solid fa-users me-2"></i><span class="sidebar-label">Pengguna</span>
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                href="{{ route('settings.index') }}">
                <i class="fa-solid fa-gear me-2"></i><span class="sidebar-label">Setting</span>
            </a>

            <div class="sidebar-label text-glass-blue small fw-bold mb-2 px-2 ms-1 mt-4 text-uppercase"
                style="letter-spacing: 1px;">Akun</div>
            <a class="role-sidebar-link {{ request()->routeIs('admin.profile.show') ? 'active' : '' }}"
                href="{{ route('admin.profile.show') }}">
                <i class="fa-regular fa-id-badge me-2"></i><span class="sidebar-label">Profil Admin</span>
            </a>
        </div>

        <div class="px-4 py-4 border-top border-light" style="background: rgba(0, 86, 179, 0.02);">
            <div class="d-flex align-items-center mb-3 sidebar-label">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 shadow-sm"
                    style="width: 35px; height: 35px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="role-user-name fw-bold text-dark lh-1" style="font-size: 0.9rem;">
                        {{ Auth::user()->name }}
                    </div>
                    <small class="text-glass-blue lh-1">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </div>
            <a href="{{ route('katalog.index') }}"
                class="btn btn-outline-primary w-100 mb-2 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-chevron-left me-2"></i> Katalog
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="btn btn-glass-logout btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-right-from-bracket me-2"></i><span class="role-logout-label">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="offcanvas offcanvas-start glass-offcanvas admin-offcanvas" tabindex="-1" id="roleSidebarMobile"
        aria-labelledby="roleSidebarMobileLabel">
        <div class="offcanvas-header border-bottom border-light pb-3 pt-4">
            <h5 class="offcanvas-title fw-bold text-gradient-blue d-flex align-items-center"
                id="roleSidebarMobileLabel">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-2"
                    style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-store fs-6"></i>
                </div>
                {{ $appName }}
            </h5>
            <button type="button" class="btn-close opacity-75" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column px-3 pt-4">
            <div class="text-glass-blue small fw-bold mb-2 px-2 text-uppercase" style="letter-spacing: 1px;">Main Menu
            </div>
            <a class="role-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-pie me-2"></i>Dashboard
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('product.*') ? 'active' : '' }}"
                href="{{ route('product.manage') }}">
                <i class="fa-solid fa-boxes-stacked me-2"></i>Produk
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
                href="{{ route('category.manage') }}">
                <i class="fa-solid fa-tags me-2"></i>Kategori
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('order.manage') ? 'active' : '' }}"
                href="{{ route('order.manage') }}">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i>Pesanan
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('user.manage') ? 'active' : '' }}"
                href="{{ route('user.manage') }}">
                <i class="fa-solid fa-users me-2"></i>Pengguna
            </a>
            <a class="role-sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                href="{{ route('settings.index') }}">
                <i class="fa-solid fa-gear me-2"></i><span class="sidebar-label">Setting</span>
            </a>

            <div class="text-glass-blue small fw-bold mb-2 px-2 mt-4 text-uppercase" style="letter-spacing: 1px;">Akun
            </div>
            <a class="role-sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                href="{{ route('admin.profile.show') }}">
                <i class="fa-regular fa-id-badge me-2"></i>Profile
            </a>

            <div class="mt-auto pt-4 border-top border-light">
                <div class="d-flex align-items-center mb-3 px-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm"
                        style="width: 40px; height: 40px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark lh-1 mb-1">{{ Auth::user()->name }}</div>
                        <small class="text-glass-blue lh-1">{{ ucfirst(Auth::user()->role) }}</small>
                    </div>
                </div>
                <a href="{{ route('katalog.index') }}"
                    class="btn btn-outline-primary w-100 mb-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-chevron-left me-2"></i> Katalog
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="btn btn-glass-logout w-100 py-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="container-fluid px-4 py-4 fade-in-up role-main">
        @yield('content')
    </main>

    <x-toast />
    <x-confirm-modal />
    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Toast
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
            toastList.forEach(function (toast) { toast.show(); });

            // Sidebar Toggle Logic
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

            // Mobile offcanvas link navigation: hide then navigate to avoid offcanvas closing without navigation
            (function wireOffcanvasNavigation() {
                const offcanvasEl = document.getElementById('roleSidebarMobile');
                if (!offcanvasEl) return;
                const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                let pendingHref = null;

                offcanvasEl.addEventListener('click', function (e) {
                    const a = e.target.closest('a.role-sidebar-link');
                    if (!a) return;
                    const href = a.getAttribute('href');
                    const target = a.getAttribute('target');
                    if (!href || href === '#' || href.startsWith('javascript:')) return;
                    // If link is same-page anchor or has download behavior, let default
                    if (a.dataset.bsDismiss === 'offcanvas') return; // let bootstrap handle simple dismiss
                    e.preventDefault();
                    pendingHref = href;
                    if (target === '_blank') {
                        window.open(href, '_blank');
                        // still close offcanvas
                        offcanvasInstance.hide();
                        pendingHref = null;
                        return;
                    }
                    offcanvasInstance.hide();
                });

                offcanvasEl.addEventListener('hidden.bs.offcanvas', function () {
                    if (pendingHref) {
                        const url = pendingHref;
                        pendingHref = null;
                        window.location.href = url;
                    }
                });
            })();
        });

        // Global Confirm Modal Function
        function showConfirmModal(title, message, confirmCallback) {
            document.getElementById('customModalTitle').innerText = title;
            document.getElementById('customModalBody').innerText = message;

            let confirmBtn = document.getElementById('customModalConfirmBtn');
            let newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function () {
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