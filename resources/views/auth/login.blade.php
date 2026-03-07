@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<h5 class="fw-bold mb-3">Masuk Akun</h5>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="login_id" class="form-label">Email, Username, atau No. Handphone</label>
        <input id="login_id" type="text" name="login_id" value="{{ old('login_id') }}" class="form-control @error('login_id') is-invalid @enderror" required autofocus autocomplete="username">
        @error('login_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label" for="remember_me">Remember me</label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small">Lupa password?</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary w-100">Log in</button>
</form>

<p class="text-center text-muted small mt-3 mb-0">
    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
</p>
@endsection
