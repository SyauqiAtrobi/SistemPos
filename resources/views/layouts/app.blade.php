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

        /* Glassmorphism Card Style */
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

        /* Scale Up (Tablet & Desktop) */
        @media (min-width: 768px) {
            body { font-size: 16px; }
            .container { max-width: 720px; }
        }
        
        @media (min-width: 992px) {
            .container { max-width: 960px; }
            .glass-card { padding: 2rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ auth()->check() ? route('katalog.index') : url('/') }}">
                BabaPOS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                @if(Auth::check())
                    @php
                        $cartCount = Auth::user()->role === 'customer'
                            ? \App\Models\Cart::where('user_id', Auth::id())->sum('qty')
                            : 0;
                        $cartBadge = $cartCount > 99 ? '99+' : $cartCount;
                    @endphp

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('katalog.index') ? 'active fw-semibold' : '' }}" href="{{ route('katalog.index') }}">Katalog</a></li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item me-1">
                            <a class="nav-link position-relative {{ request()->routeIs('cart.*') ? 'active fw-semibold' : '' }}" href="{{ route('cart.index') }}" aria-label="Keranjang">
                                <i class="fa-solid fa-cart-shopping"></i>
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartBadge }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    <main class="container py-4 fade-in-up">
        @yield('content')
    </main>

    <x-toast />
    <x-confirm-modal />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inisialisasi Toast Global
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 })
            });
            toastList.forEach(toast => toast.show());

        });

        // Fungsi Global untuk memanggil Custom Confirm Modal
        function showConfirmModal(title, message, confirmCallback) {
            document.getElementById('customModalTitle').innerText = title;
            document.getElementById('customModalBody').innerText = message;
            
            let confirmBtn = document.getElementById('customModalConfirmBtn');
            // Hapus event listener lama dengan clone node
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