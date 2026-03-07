@extends('layouts.publiclayout')

@section('title', 'Konfirmasi Password')

@section('content')
<h5 class="fw-bold mb-2">Konfirmasi Password</h5>
<p class="text-muted small">Area ini butuh konfirmasi password untuk melanjutkan.</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
</form>
@endsection
