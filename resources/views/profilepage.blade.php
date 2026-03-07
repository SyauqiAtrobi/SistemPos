@extends('layouts.role')

@section('title', 'Profile')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 border-0">
            <section>
                <h5 class="fw-bold mb-1">Informasi Profil</h5>
                <p class="text-muted small mb-4">Perbarui nama dan email akun Anda.</p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success py-2">Link verifikasi baru telah dikirim ke email Anda.</div>
                @endif

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="form-text">
                                Email belum diverifikasi.
                                <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline" type="submit">Kirim ulang verifikasi</button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small">Saved.</span>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 border-0">
            <section>
                <h5 class="fw-bold mb-1">Update Password</h5>
                <p class="text-muted small mb-4">Gunakan password yang kuat untuk menjaga keamanan akun.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label class="form-label" for="update_password_current_password">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
                        @if($errors->updatePassword->has('current_password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>@endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="update_password_password">New Password</label>
                        <input id="update_password_password" name="password" type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
                        @if($errors->updatePassword->has('password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>@endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="update_password_password_confirmation">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                        @if($errors->updatePassword->has('password_confirmation'))<div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>@endif
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small">Saved.</span>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 border-0 border border-danger-subtle">
            <section>
                <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                <p class="text-muted small mb-4">Setelah akun dihapus, semua data Anda akan hilang permanen.</p>

                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" type="button">
                    Delete Account
                </button>

                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')

                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">Masukkan password untuk menghapus akun secara permanen.</p>
                                    <input id="delete_password" name="password" type="password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Password" required>
                                    @if($errors->userDeletion->has('password'))<div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>@endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
