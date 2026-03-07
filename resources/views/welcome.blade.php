<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BabaPOS') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5 text-center">
                        <h1 class="h3 fw-bold mb-3">BabaPOS</h1>
                        <p class="text-muted mb-4">Sistem pemesanan dan manajemen penjualan parfum berbasis Laravel.</p>
                        <div class="d-flex justify-content-center gap-2">
                            @auth
                                <a href="{{ route('katalog.index') }}" class="btn btn-primary">Masuk Katalog</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
