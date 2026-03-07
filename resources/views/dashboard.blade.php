@extends('layouts.userlayout')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    /* Utility Classes Custom Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .text-glass-blue {
        color: rgba(0, 50, 120, 0.6);
    }
    
    /* Icon Box Glassmorphism */
    .icon-box-glass {
        width: 50px;
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 61, 153, 0.05) 100%);
        border: 1px solid rgba(0, 123, 255, 0.15);
        color: #0056b3;
        margin-bottom: 12px;
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.05);
    }
</style>
@endpush

@section('content')
<div class="row g-3 fade-in-up">
    
    <div class="col-12 mb-2">
        <div class="glass-card p-4 border-0 d-flex align-items-center shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 55px; height: 55px;">
                <i class="fa-solid fa-chart-pie fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-gradient-blue mb-1">Dashboard Analitik</h4>
                <p class="text-glass-blue small mb-0">Pantau performa penjualan dan metrik utama toko Anda secara real-time.</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100 shadow-sm" style="animation-delay: 0.1s;">
            <div class="icon-box-glass">
                <i class="fa-solid fa-wallet fs-5"></i>
            </div>
            <div class="small fw-semibold text-glass-blue mb-1">Total Pendapatan</div>
            <div id="statRevenue" class="h5 fw-bold text-gradient-blue mb-0">Rp 0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100 shadow-sm" style="animation-delay: 0.2s;">
            <div class="icon-box-glass">
                <i class="fa-solid fa-cart-shopping fs-5"></i>
            </div>
            <div class="small fw-semibold text-glass-blue mb-1">Jumlah Pesanan</div>
            <div id="statOrders" class="h4 fw-bold text-gradient-blue mb-0">0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100 shadow-sm" style="animation-delay: 0.3s;">
            <div class="icon-box-glass">
                <i class="fa-solid fa-boxes-stacked fs-5"></i>
            </div>
            <div class="small fw-semibold text-glass-blue mb-1">Total Produk</div>
            <div id="statProducts" class="h4 fw-bold text-gradient-blue mb-0">0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100 shadow-sm" style="animation-delay: 0.4s;">
            <div class="icon-box-glass">
                <i class="fa-solid fa-users fs-5"></i>
            </div>
            <div class="small fw-semibold text-glass-blue mb-1">Total Pelanggan</div>
            <div id="statUsers" class="h4 fw-bold text-gradient-blue mb-0">0</div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="glass-card p-4 shadow-sm" style="animation-delay: 0.5s;">
            <div class="d-flex align-items-center mb-4">
                <i class="fa-solid fa-chart-area text-primary me-2 opacity-75"></i>
                <h6 class="fw-bold text-gradient-blue mb-0">Grafik Pendapatan (7 Hari Terakhir)</h6>
            </div>
            <div class="chart-container position-relative" style="height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revenueEl = document.getElementById('statRevenue');
        const ordersEl = document.getElementById('statOrders');
        const productsEl = document.getElementById('statProducts');
        const usersEl = document.getElementById('statUsers');
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Buat efek gradien biru transparan untuk bagian bawah garis grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.4)');
        gradient.addColorStop(1, 'rgba(0, 123, 255, 0.0)');

        // Konfigurasi Chart.js yang lebih elegan
        let chart = new Chart(ctx, {
            type: 'line',
            data: { 
                labels: [], 
                datasets: [{ 
                    label: 'Pendapatan (Rp)', 
                    data: [], 
                    backgroundColor: gradient, 
                    borderColor: '#0056b3', 
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0056b3',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Membuat kurva garis melengkung halus (smooth)
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#0056b3',
                        bodyColor: '#333',
                        borderColor: 'rgba(0, 86, 179, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }, 
                scales: { 
                    x: { 
                        grid: { display: false },
                        ticks: { color: 'rgba(0, 50, 120, 0.6)', font: { family: 'Poppins', size: 11 } }
                    },
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 86, 179, 0.05)', borderDash: [5, 5] },
                        ticks: { 
                            color: 'rgba(0, 50, 120, 0.6)', 
                            font: { family: 'Poppins', size: 11 },
                            callback: function(value) {
                                if(value >= 1000000) return (value / 1000000) + ' Jt';
                                if(value >= 1000) return (value / 1000) + ' Rb';
                                return value;
                            }
                        } 
                    } 
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });

        // Format angka ke mata uang Rupiah
        function formatRupiah(n) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
        }

        // Fungsi menerapkan data ke UI
        function applyStats(stats) {
            revenueEl.innerText = formatRupiah(stats.total_revenue || 0);
            ordersEl.innerText = stats.total_orders || 0;
            productsEl.innerText = stats.total_products || 0;
            usersEl.innerText = stats.total_users || 0;
            
            if (stats.chart && Array.isArray(stats.chart.labels)) {
                chart.data.labels = stats.chart.labels;
                chart.data.datasets[0].data = stats.chart.data;
                chart.update();
            }
        }

        // Ambil data pertama kali saat halaman dimuat
        fetch('{{ route('dashboard.stats') }}', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(json => applyStats(json))
            .catch(err => console.warn('Failed to load dashboard stats', err));

        // Update realtime via Laravel Echo (jika WebSocket aktif)
        if (window.Echo && typeof window.Echo.channel === 'function') {
            window.Echo.channel('dashboard').listen('DashboardUpdated', (e) => {
                applyStats(e);
            });
        }
    });
</script>
@endpush
@endsection