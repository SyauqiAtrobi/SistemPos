@extends('layouts.publiclayout')

@section('title', 'Verifikasi Email')

@section('content')
<h5 class="fw-bold mb-2">Verifikasi Email</h5>
<p class="text-muted small">Silakan verifikasi email Anda dari link yang sudah dikirim. Jika belum menerima, kirim ulang di bawah.</p>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success">Link verifikasi baru sudah dikirim.</div>
@endif

<div class="d-grid gap-2">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100">Kirim Ulang Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">Log Out</button>
    </form>
</div>
@endsection
