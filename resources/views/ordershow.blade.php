@extends('layouts.role')

@section('title', 'Pembayaran Invoice')

@push('styles')
<style>
    .qris-wrapper {
        background: white;
        padding: 20px;
        border-radius: 20px;
        display: inline-block;
        box-shadow: 0 10px 30px rgba(0, 86, 179, 0.1);
        position: relative;
        overflow: hidden;
    }

    .qris-img {
        width: 250px;
        height: 250px;
        object-fit: contain;
    }

    /* Animasi Sukses */
    .success-checkmark {
        display: none; /* Disembunyikan secara default */
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 250px;
        animation: scaleUp 0.5s ease-in-out forwards;
    }

    .success-checkmark i {
        font-size: 5rem;
        color: #198754;
        margin-bottom: 1rem;
    }

    @keyframes scaleUp {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 86, 179, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(0, 86, 179, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 86, 179, 0); }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-12 col-lg-10">
        
        <div class="glass-card p-0 overflow-hidden border-0 mb-4">
            <div class="p-4 p-md-5 text-white" style="background: var(--gradient-primary);">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="mb-1 opacity-75">Nomor Pesanan</p>
                        <h4 class="fw-bold mb-0">{{ $order->order_number }}</h4>
                        <small class="opacity-75">{{ $order->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1 opacity-75">Total Pembayaran</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                        
                        <span id="orderStatusBadge" class="badge rounded-pill mt-2 fs-6 
                            {{ $order->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $order->status === 'paid' ? 'LUNAS' : 'MENUNGGU PEMBAYARAN' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4 p-md-5 row g-5">
                <div class="col-12 col-md-6 order-2 order-md-1">
                    <h5 class="fw-bold mb-4" style="color: var(--primary-blue);">Detail Pembelian</h5>
                    
                    <div class="list-group list-group-flush mb-4">
                        @foreach($order->items as $item)
                        <div class="list-group-item bg-transparent px-0 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->qty }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="p-3 rounded-3" style="background-color: var(--soft-white);">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-circle-info mt-1 me-2 text-primary"></i>
                            <small class="text-muted">
                                Pesanan akan diproses segera setelah pembayaran berhasil diverifikasi oleh sistem secara otomatis.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 order-1 order-md-2 text-center border-start border-light d-flex flex-column justify-content-center">
                    
                    <div id="paymentArea">
                        @if($order->status === 'paid')
                            <div class="success-checkmark d-flex">
                                <i class="fa-solid fa-circle-check"></i>
                                <h4 class="fw-bold text-success">Pembayaran Berhasil!</h4>
                                <p class="text-muted small">Terima kasih atas pesanan Anda.</p>
                            </div>
                        @else
                            <h5 class="fw-bold mb-2" style="color: var(--primary-blue);">Scan QRIS</h5>
                            <p class="text-muted small mb-4">Gunakan aplikasi E-Wallet atau M-Banking Anda.</p>
                            
                            <div id="qrisContainer" class="qris-wrapper pulse-animation mb-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($order->payment->qris_string ?? '') }}" 
                                     alt="QRIS Payment" class="qris-img">
                            </div>
                            
                            <div id="successAnimation" class="success-checkmark">
                                <i class="fa-solid fa-circle-check"></i>
                                <h4 class="fw-bold text-success">Pembayaran Berhasil!</h4>
                                <p class="text-muted small">Terima kasih atas pesanan Anda.</p>
                            </div>

                            <div id="waitingText" class="d-flex align-items-center justify-content-center mt-3 text-muted">
                                <div class="spinner-border spinner-border-sm me-2 text-primary" role="status"></div>
                                <small>Menunggu pembayaran otomatis...</small>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url('/katalog') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Katalog
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])

<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        
        const orderStatus = '{{ $order->status }}';
        const orderNumber = '{{ $order->order_number }}';

        // Hanya jalankan listener WebSocket jika status masih pending
        if (orderStatus === 'pending') {
            
            // Menggunakan Laravel Echo untuk mendengarkan channel OrderPaid
            // Pastikan Anda sudah menjalankan `npm run dev` dan `php artisan reverb:start`
            window.Echo.channel(`order.${orderNumber}`)
                .listen('OrderPaid', (e) => {
                    console.log('Event Diterima:', e);

                    // 1. Ubah Badge Status
                    const badge = document.getElementById('orderStatusBadge');
                    badge.classList.remove('bg-warning', 'text-dark');
                    badge.classList.add('bg-success');
                    badge.innerText = 'LUNAS';

                    // 2. Sembunyikan QR Code & Teks Loading
                    document.getElementById('qrisContainer').style.display = 'none';
                    document.getElementById('waitingText').style.display = 'none';

                    // 3. Tampilkan Animasi Sukses
                    const successAnim = document.getElementById('successAnimation');
                    successAnim.style.display = 'flex';
                    successAnim.style.animation = 'scaleUp 0.6s ease-out forwards';

                    // 4. Tampilkan Toast Bootstrap (Buat elemen toast secara dinamis)
                    const toastHTML = `
                        <div class="toast align-items-center text-bg-success border-0 glass-card" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="fa-solid fa-check-double me-2"></i> ${e.message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    `;
                    const toastContainer = document.querySelector('.toast-container');
                    if (toastContainer) {
                        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
                        const newToastEl = toastContainer.lastElementChild;
                        const newToast = new bootstrap.Toast(newToastEl, { delay: 5000 });
                        newToast.show();
                    }

                    // Opsional: Mainkan suara notifikasi kecil
                    // let audio = new Audio('/sounds/success.mp3');
                    // audio.play();
                });
        }
    });
</script>
@endpush