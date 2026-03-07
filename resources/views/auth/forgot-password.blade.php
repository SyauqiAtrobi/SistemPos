@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<h5 class="fw-bold mb-2">Reset Password</h5>
<p class="text-muted small">Masukkan email Anda untuk menerima link reset password.</p>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
</form>
@endsection
