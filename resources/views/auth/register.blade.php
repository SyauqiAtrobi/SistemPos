@extends('layouts.publiclayout')

@section('title', 'Register')

@push('styles')
<style>
    /* Styling sama dengan halaman login agar konsisten */
    .auth-wrapper { max-width: 500px; margin: 0 auto; padding: 2rem 1rem; }
    .custom-input-group { background: rgba(255, 255, 255, 0.6); border: 1px solid rgba(0, 86, 179, 0.2); border-radius: 12px; overflow: hidden; transition: all 0.3s; }
    .custom-input-group:focus-within { background: #fff; border-color: #007bff; box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1); }
    .custom-input-group .input-group-text { background: transparent; border: none; color: rgba(0, 86, 179, 0.5); padding-left: 1.25rem; }
    .custom-input-group .form-control { background: transparent; border: none; padding: 0.8rem 1rem; box-shadow: none; color: #333; }
    .btn-toggle-pass { background: transparent; border: none; color: rgba(0, 86, 179, 0.5); padding-right: 1.25rem; cursor: pointer; }
    .btn-toggle-pass:hover { color: #007bff; }
    .text-gradient-blue { background: linear-gradient(135deg, #007bff 0%, #003d99 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
</style>
@endpush

@section('content')
<div class="auth-wrapper fade-in-up">
    <div class="glass-card p-4 p-md-5 border-0">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-plus fs-3"></i>
            </div>
            <h4 class="fw-bold text-gradient-blue mb-1">Buat Akun Baru</h4>
            <p class="text-muted small">Lengkapi data di bawah ini untuk bergabung.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label small fw-semibold text-secondary ms-1">Nama Lengkap</label>
                <div class="input-group custom-input-group @error('name') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Budi Santoso" required autofocus autocomplete="name">
                </div>
                @error('name')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="username" class="form-label small fw-semibold text-secondary ms-1">Username</label>
                <div class="input-group custom-input-group @error('username') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-regular fa-at"></i></span>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Contoh: budi123" required autocomplete="username">
                </div>
                @error('username')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <label for="email" class="form-label small fw-semibold text-secondary ms-1">Email</label>
                    <div class="input-group custom-input-group @error('email') border-danger @enderror">
                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="email@anda.com" required autocomplete="email">
                    </div>
                    @error('email')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="phone" class="form-label small fw-semibold text-secondary ms-1">No. Handphone</label>
                    <div class="input-group custom-input-group @error('phone') border-danger @enderror">
                        <span class="input-group-text"><i class="fa-solid fa-mobile-screen"></i></span>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="08xxxxxxxxx" required autocomplete="tel">
                    </div>
                    @error('phone')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary ms-1">Password</label>
                <div class="input-group custom-input-group @error('password') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input id="password" type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                    <button type="button" class="btn-toggle-pass toggle-password" data-target="password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                @error('password')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label small fw-semibold text-secondary ms-1">Konfirmasi Password</label>
                <div class="input-group custom-input-group @error('password_confirmation') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-solid fa-lock-open"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password di atas" required autocomplete="new-password">
                    <button type="button" class="btn-toggle-pass toggle-password" data-target="password_confirmation">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-custom-primary w-100 py-2 rounded-pill shadow-sm mb-4">
                Daftar Sekarang <i class="fa-solid fa-check ms-2"></i>
            </button>
        </form>

        <p class="text-center text-secondary small mb-0">
            Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Masuk di sini</a>
        </p>

    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush