@extends('layouts.role')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--light-blue);
        color: var(--primary-blue);
        border: none;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background-color: var(--primary-blue);
        color: white;
    }

    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: var(--primary-blue);
    }

    .qty-input:focus {
        outline: none;
    }

    /* Summary Card Sticky on Desktop */
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4 fade-in-up">
    <h3 class="fw-bold" style="color: var(--primary-blue);">
        <i class="fa-solid fa-cart-shopping me-2"></i> Keranjang Anda
    </h3>
    <p class="text-muted small">Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.</p>
</div>

<div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
    <div class="col-12 col-lg-8">
        
        @forelse($carts as $cart)
        <div class="glass-card mb-3 p-3 border-0 d-flex flex-row align-items-center">
            
            <img src="{{ $cart->product->image ? asset('storage/'.$cart->product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=100&q=80' }}" 
                 alt="{{ $cart->product->name }}" class="cart-item-img me-3 shadow-sm">
                 
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px;">{{ $cart->product->name }}</h6>
                <div class="text-muted small mb-2">{{ $cart->product->category->name ?? 'Varian' }}</div>
                <div class="fw-bold" style="color: var(--primary-blue);">
                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="d-flex flex-column align-items-end justify-content-between h-100">
                
                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mb-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm text-danger opacity-75 hover-opacity-100 p-0 border-0 bg-transparent"
                            onclick="showConfirmModal('Hapus Item', 'Yakin ingin menghapus {{ $cart->product->name }} dari keranjang?', () => this.form.submit())">
                        <i class="fa-solid fa-trash-can fs-5"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center rounded-pill px-2 py-1" style="background-color: var(--soft-white); border: 1px solid rgba(0, 86, 179, 0.1);">
                    <span class="qty-input">{{ $cart->qty }}x</span>
                </div>
                
            </div>
            
        </div>
        @empty
        
        <div class="glass-card text-center p-5 border-0">
            <i class="fa-solid fa-basket-shopping fa-4x mb-3 text-muted opacity-25"></i>
            <h5 class="fw-bold text-muted">Keranjang Masih Kosong</h5>
            <p class="text-muted small mb-4">Yuk, cari parfum favoritmu sekarang!</p>
            <a href="{{ url('/katalog') }}" class="btn btn-custom-primary px-4">Belanja Sekarang</a>
        </div>
        
        @endforelse

    </div>

    @if($carts->isNotEmpty())
    <div class="col-12 col-lg-4">
        <div class="glass-card p-4 border-0 sticky-summary">
            <h5 class="fw-bold mb-4" style="color: var(--primary-blue);">Ringkasan Pesanan</h5>
            
            <div class="d-flex justify-content-between mb-3 text-muted">
                <span>Total Item</span>
                <span class="fw-bold text-dark">{{ $carts->sum('qty') }} Barang</span>
            </div>
            
            <hr class="opacity-10 my-3">
            
            <div class="d-flex justify-content-between mb-4">
                <span class="fs-5 fw-bold text-dark">Total Tagihan</span>
                <span class="fs-5 fw-bold" style="color: var(--primary-blue);">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <button type="button" class="btn btn-custom-primary w-100 shadow-sm py-2 fs-6"
                        onclick="showConfirmModal('Konfirmasi Checkout', 'Anda akan diarahkan ke halaman pembayaran QRIS untuk total Rp {{ number_format($total, 0, ',', '.') }}. Lanjutkan?', () => this.form.submit())">
                    Buat Pesanan & Bayar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-shield-halved me-1 text-success"></i> Pembayaran aman via QRIS
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection