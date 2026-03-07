<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BabaPOS') }} - @yield('title', 'Auth')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f4f7fb 0%, #e9eef7 100%);
            min-height: 100vh;
        }
        .auth-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="d-flex align-items-center py-4">
    <main class="container" style="max-width: 520px;">
        <div class="text-center mb-4">
            <a href="{{ url('/katalog') }}" class="text-decoration-none">
                <h4 class="fw-bold text-primary mb-0">BabaPOS</h4>
            </a>
            <small class="text-muted">Sistem Penjualan Baba Parfum</small>
        </div>

        <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
                @yield('content')
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
