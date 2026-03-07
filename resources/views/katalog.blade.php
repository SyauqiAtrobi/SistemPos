@extends('layouts.app')

@section('title', 'Katalog Parfum')

@push('styles')
<style>
    /* Styling Khusus Katalog */
    
    /* Area Filter Kategori: Scroll menyamping (Horizontal Scroll) untuk Mobile */
    .category-scroll {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding-bottom: 15px;
        -webkit-overflow-scrolling: touch; /* Smooth scroll di iOS */
    }
    
    .category-scroll::-webkit-scrollbar {
        height: 4px;
    }
    
    .category-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 86, 179, 0.2);
        border-radius: 10px;
    }

    /* Styling Card Produk */
    .product-img {
        height: 220px;
        object-fit: cover;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        transition: transform 0.5s ease;
    }

    .glass-card:hover .product-img {
        transform: scale(1.05); /* Animasi zoom gambar saat di-hover */
    }

    .img-wrapper {
        overflow: hidden;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    .badge-category {
        background-color: var(--light-blue);
        color: var(--primary-blue);
        font-weight: 500;
        padding: 6px 12px;
    }
</style>
@endpush

@section('content')
<div class="text-center mb-5 fade-in-up">
    <h2 class="fw-bold" style="color: var(--primary-blue);">Katalog Baba Parfum</h2>
    <p class="text-muted">Temukan aroma yang mencerminkan karakter elegan Anda</p>
</div>

<div class="mb-4 category-scroll fade-in-up" style="animation-delay: 0.1s;">
    <a href="{{ url('/katalog') }}" class="btn {{ request('category') ? 'btn-light glass-card' : 'btn-custom-primary' }} text-nowrap rounded-pill px-4 shadow-sm">
        Semua Aroma
    </a>
    
    @foreach($categories as $category)
        <a href="{{ url('/katalog?category='.$category->slug) }}" 
           class="btn {{ request('category') == $category->slug ? 'btn-custom-primary' : 'btn-light glass-card' }} text-nowrap rounded-pill px-4 shadow-sm text-decoration-none">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 fade-in-up" style="animation-delay: 0.2s;">
    
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 glass-card border-0 d-flex flex-column">
            
            <div class="img-wrapper">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80' }}" 
                     class="card-img-top product-img" alt="{{ $product->name }}">
            </div>
            
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold mb-0 text-truncate" style="max-width: 70%;" title="{{ $product->name }}">
                        {{ $product->name }}
                    </h5>
                    <span class="badge badge-category rounded-pill">{{ $product->category->name ?? 'Umum' }}</span>
                </div>
                
                <p class="card-text text-muted small mb-4 flex-grow-1">
                    {{ Str::limit($product->description, 70, '...') }}
                </p>
                
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-5 fw-bold" style="color: var(--primary-blue);">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="small fw-semibold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}
                        </span>
                    </div>

                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button type="button" class="btn btn-custom-primary w-100 shadow-sm" 
                                    onclick="showConfirmModal('Tambah Keranjang', 'Masukkan {{ $product->name }} ke keranjang belanja Anda?', () => this.form.submit())">
                                <i class="fa-solid fa-cart-plus me-2"></i> Tambah Keranjang
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 rounded-pill opacity-50" disabled>
                            <i class="fa-solid fa-times-circle me-2"></i> Stok Habis
                        </button>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    @empty
    
    <div class="col-12 text-center py-5">
        <div class="glass-card p-5 mx-auto" style="max-width: 400px;">
            <i class="fa-solid fa-box-open fa-4x mb-3 text-muted opacity-50"></i>
            <h5 class="fw-bold text-muted">Belum Ada Parfum</h5>
            <p class="text-muted small">Kategori atau produk yang Anda cari sedang kosong.</p>
        </div>
    </div>
    
    @endforelse

</div>
@endsection