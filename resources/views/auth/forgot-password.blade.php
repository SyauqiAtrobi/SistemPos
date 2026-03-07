@extends('layouts.publiclayout')

@section('title', 'Lupa Password')

@push('styles')
<style>
    /* Styling sama dengan halaman login */
    .auth-wrapper { max-width: 450px; margin: 0 auto; padding: 2rem 1rem; }
    .custom-input-group { background: rgba(255, 255, 255, 0.6); border: 1px solid rgba(0, 86, 179, 0.2); border-radius: 12px; overflow: hidden; transition: all 0.3s; }
    .custom-input-group:focus-within { background: #fff; border-color: #007bff; box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1); }
    .custom-input-group .input-group-text { background: transparent; border: none; color: rgba(0, 86, 179, 0.5); padding-left: 1.25rem; }
    .custom-input-group .form-control { background: transparent; border: none; padding: 0.8rem 1rem; box-shadow: none; color: #333; }
    .text-gradient-blue { background: linear-gradient(135deg, #007bff 0%, #003d99 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
</style>
@endpush

@section('content')
<div class="auth-wrapper fade-in-up">
    <div class="glass-card p-4 p-md-5 border-0">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-key fs-3"></i>
            </div>
            <h4 class="fw-bold text-gradient-blue mb-1">Lupa Password?</h4>
            <p class="text-muted small">Jangan khawatir! Masukkan email yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center rounded-3 small" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label small fw-semibold text-secondary ms-1">Alamat Email</label>
                <div class="input-group custom-input-group @error('email') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="email@anda.com" required autofocus>
                </div>
                @error('email')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-custom-primary w-100 py-2 rounded-pill shadow-sm mb-4">
                Kirim Tautan Reset <i class="fa-regular fa-paper-plane ms-2"></i>
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none text-secondary small fw-medium hover-primary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke halaman Login
            </a>
        </div>

    </div>
</div>
@endsection