@extends('layouts.publiclayout')

@section('title', 'Akun Saya')

@push('styles')
<style>
    /* Styling Form Input Glassmorphism (Konsisten dengan Auth) */
    .custom-input-group {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0, 86, 179, 0.2);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .custom-input-group:focus-within {
        background: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }
    .custom-input-group .input-group-text {
        background: transparent;
        border: none;
        color: rgba(0, 86, 179, 0.5);
        padding-left: 1.25rem;
    }
    .custom-input-group .form-control {
        background: transparent;
        border: none;
        padding: 0.8rem 1rem;
        box-shadow: none;
        color: #333;
    }
    .btn-toggle-pass {
        background: transparent;
        border: none;
        color: rgba(0, 86, 179, 0.5);
        padding-right: 1.25rem;
        cursor: pointer;
    }
    .btn-toggle-pass:hover { color: #007bff; }

    /* Tombol Danger Glass */
    .btn-glass-danger {
        background: rgba(220, 53, 69, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #dc3545;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-glass-danger:hover {
        background: rgba(220, 53, 69, 0.9);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
    }
    
    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center g-4 fade-in-up">
    
    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 p-md-5 border-0 shadow-sm">
            <section>
                <div class="d-flex align-items-center mb-2">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-regular fa-id-badge fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-gradient-blue mb-0">Informasi Profil</h5>
                        <p class="text-glass-blue small mb-0">Perbarui nama dan email akun Anda.</p>
                    </div>
                </div>
                
                <hr class="opacity-10 mb-4">

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success d-flex align-items-center rounded-3 small py-2 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> Link verifikasi baru telah dikirim ke email Anda.
                    </div>
                @endif

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="name">Nama Lengkap</label>
                        <div class="input-group custom-input-group @error('name') border-danger @enderror">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control" required autofocus autocomplete="name">
                        </div>
                        @error('name')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="username">Username</label>
                        <div class="input-group custom-input-group">
                            <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                            <input id="username" type="text" value="{{ $user->username }}" class="form-control" readonly>
                        </div>
                        <div class="form-text small ms-1">Username tidak dapat diubah.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="email">Alamat Email</label>
                        <div class="input-group custom-input-group">
                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control" readonly>
                        </div>
                        <div class="form-text small ms-1">Alamat email tidak dapat diubah</div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="form-text mt-2 ms-1 text-warning">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Email belum diverifikasi.
                                <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline text-decoration-none fw-semibold ms-1" type="submit">Kirim ulang verifikasi</button>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="phone">Nomor Telepon</label>
                        <div class="input-group custom-input-group @error('phone') border-danger @enderror">
                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="form-control" autocomplete="tel">
                        </div>
                        @error('phone')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="address">Alamat</label>
                        <div class="input-group custom-input-group @error('address') border-danger @enderror">
                            <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                            <input id="address" name="address" type="text" value="{{ old('address', $user->address) }}" class="form-control" autocomplete="street-address">
                        </div>
                        @error('address')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-custom-primary rounded-pill px-4 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                        </button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small fw-medium d-flex align-items-center" style="animation: fadeInUp 0.3s ease forwards;">
                                <i class="fa-solid fa-check me-1"></i> Tersimpan.
                            </span>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="glass-card p-4 p-md-5 border-0 shadow-sm" style="animation-delay: 0.1s;">
            <section>
                <div class="d-flex align-items-center mb-2">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-gradient-blue mb-0">Update Password</h5>
                        <p class="text-glass-blue small mb-0">Gunakan password yang kuat untuk menjaga keamanan.</p>
                    </div>
                </div>

                <hr class="opacity-10 mb-4">

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="update_password_current_password">Password Saat Ini</label>
                        <div class="input-group custom-input-group @if($errors->updatePassword->has('current_password')) border-danger @endif">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                            <button type="button" class="btn-toggle-pass toggle-password" data-target="update_password_current_password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('current_password'))<div class="text-danger small mt-1 ms-1">{{ $errors->updatePassword->first('current_password') }}</div>@endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="update_password_password">Password Baru</label>
                        <div class="input-group custom-input-group @if($errors->updatePassword->has('password')) border-danger @endif">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                            <button type="button" class="btn-toggle-pass toggle-password" data-target="update_password_password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('password'))<div class="text-danger small mt-1 ms-1">{{ $errors->updatePassword->first('password') }}</div>@endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary ms-1" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
                        <div class="input-group custom-input-group @if($errors->updatePassword->has('password_confirmation')) border-danger @endif">
                            <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            <button type="button" class="btn-toggle-pass toggle-password" data-target="update_password_password_confirmation">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('password_confirmation'))<div class="text-danger small mt-1 ms-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>@endif
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-custom-primary rounded-pill px-4 shadow-sm">
                            <i class="fa-solid fa-key me-2"></i> Update Password
                        </button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small fw-medium d-flex align-items-center" style="animation: fadeInUp 0.3s ease forwards;">
                                <i class="fa-solid fa-check me-1"></i> Tersimpan.
                            </span>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="col-12 col-lg-8 mb-5">
        <div class="glass-card p-4 p-md-5 border border-danger border-opacity-25 shadow-sm" style="animation-delay: 0.2s; background: rgba(255, 255, 255, 0.6);">
            <section>
                <div class="d-flex align-items-center mb-2">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-user-xmark fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-danger mb-0">Hapus Akun</h5>
                        <p class="text-danger opacity-75 small mb-0">Data yang dihapus tidak dapat dikembalikan.</p>
                    </div>
                </div>

                <hr class="opacity-10 mb-4 border-danger">

                <p class="text-glass-blue small mb-4">
                    Sekali akun Anda dihapus, seluruh sumber daya dan data akan terhapus secara permanen. Silakan unduh data yang Anda perlukan sebelum melakukan penghapusan akun.
                </p>

                <button class="btn btn-glass-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" type="button">
                    <i class="fa-solid fa-trash-can me-2"></i> Hapus Akun Permanen
                </button>
            </section>
        </div>
    </div>

</div>
@endsection

@push('modals')
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                    <div class="d-flex align-items-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle me-3" style="width: 45px; height: 45px;">
                            <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-danger mb-0">Konfirmasi Hapus Akun</h5>
                    </div>
                    <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-glass-blue pt-4 pb-2 px-4 ms-2">
                    <p class="small mb-4" style="line-height: 1.6;">
                        Apakah Anda yakin ingin menghapus akun ini? Masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
                    </p>

                    <div class="input-group custom-input-group @if($errors->userDeletion->has('password')) border-danger @endif">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input id="delete_password" name="password" type="password" class="form-control" placeholder="Masukkan Password Anda" required>
                        <button type="button" class="btn-toggle-pass toggle-password" data-target="delete_password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @if($errors->userDeletion->has('password'))
                        <div class="text-danger small mt-2">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <div class="modal-footer border-0 pt-3 pb-3 px-4">
                    <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-trash-can me-1"></i> Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Script untuk Toggle Lihat/Sembunyikan Password
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = document.getElementById(this.getAttribute('data-target'));
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Jika ada error pada form hapus akun, otomatis buka modalnya kembali saat direfresh
    @if($errors->userDeletion->has('password'))
        document.addEventListener("DOMContentLoaded", function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        });
    @endif
</script>
@endpush