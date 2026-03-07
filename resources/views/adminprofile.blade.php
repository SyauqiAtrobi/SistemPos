@extends('layouts.userlayout')

@section('title', 'Profil Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Profil Admin</h3>
        <p class="small text-muted mb-0">Kelola informasi akun admin Anda.</p>
    </div>
    <div></div>
</div>

<div class="glass-card p-4">
    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Telepon</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="col-md-6"></div>

            <div class="col-md-6">
                <label class="form-label small">Ganti Password</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password">
            </div>

            <div class="col-12 text-end mt-3">
                <button class="btn btn-light me-2" type="button" onclick="location.reload()">Batal</button>
                <button class="btn btn-custom-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>

@endsection
