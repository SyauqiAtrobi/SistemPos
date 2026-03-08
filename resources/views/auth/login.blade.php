@extends('layouts.publiclayout')

@section('title', 'Login')

@push('styles')
<style>
    .auth-wrapper {
        max-width: 450px;
        margin: 0 auto;
    }
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
    .custom-input-group .form-control:focus {
        box-shadow: none;
    }
    .btn-toggle-pass {
        background: transparent;
        border: none;
        color: rgba(0, 86, 179, 0.5);
        padding-right: 1.25rem;
        cursor: pointer;
    }
    .btn-toggle-pass:hover { color: #007bff; }
    
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper fade-in-up">
    <div class="glass-card p-4 p-md-5 border-0">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-lock fs-3"></i>
            </div>
            <h4 class="fw-bold text-gradient-blue mb-1">Selamat Datang</h4>
            <p class="text-muted small">Silakan masuk ke akun Anda untuk melanjutkan.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center rounded-3 small" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="login_id" class="form-label small fw-semibold text-secondary ms-1">Email, Username, atau No. HP</label>
                <div class="input-group custom-input-group @error('login_id') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input id="login_id" type="text" name="login_id" value="{{ old('login_id') }}" class="form-control" placeholder="Masukkan ID Anda" required autofocus autocomplete="username">
                </div>
                @error('login_id')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary ms-1">Password</label>
                <div class="input-group custom-input-group @error('password') border-danger @enderror">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input id="password" type="password" name="password" class="form-control" placeholder="Masukkan Password" required autocomplete="current-password">
                    <button type="button" class="btn-toggle-pass toggle-password" data-target="password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                @error('password')<div class="text-danger small mt-1 ms-1">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                <div class="form-check">
                    <input class="form-check-input border-secondary" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small text-secondary" for="remember_me" style="user-select: none;">
                        Ingat Saya
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold">Lupa Password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-custom-primary w-100 py-2 rounded-pill shadow-sm mb-4">
                Masuk Akun <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
        </form>

        <p class="text-center text-secondary small mb-0">
            Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar Sekarang</a>
        </p>

    </div>
</div>
@endsection

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
</script>
@endpush