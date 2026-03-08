@extends('layouts.userlayout')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    /* Utility Classes Custom Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d82 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .text-glass-blue {
        color: #64748b;
    }
    
    /* Icon Box Styles dengan warna pastel yang berbeda */
    .icon-box-glass {
        width: 56px;
        height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        margin-bottom: 16px;
        transition: transform 0.3s ease;
    }

    /* Varian Warna Icon Box */
    .icon-revenue {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #059669;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
    }
    .icon-orders {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.15) 0%, rgba(0, 123, 255, 0.05) 100%);
        border: 1px solid rgba(0, 123, 255, 0.2);
        color: #0056b3;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
    }
    .icon-products {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #d97706;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);
    }
    .icon-users {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(139, 92, 246, 0.05) 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
        color: #7c3aed;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.1);
    }

    .stat-card:hover .icon-box-glass {
        transform: scale(1.1) rotate(5deg);
    }

    /* Card Styling Khusus Dashboard */
    .dashboard-card {
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 86, 179, 0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .dashboard-card:hover {
        box-shadow: 0 15px 40px rgba(0, 86, 179, 0.08);
        transform: translateY(-3px);
    }
</style>
@endpush

@section('content')
<div class="row g-4 fade-in-up">
    
    <div class="col-12 mb-1">
        <div class="dashboard-card p-4 d-flex align-items-center border-0 shadow-sm" style="border-left: 5px solid #007bff;">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-4" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-chart-pie fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1 fs-3" style="letter-spacing: -0.5px;">Dashboard Analitik</h4>
                <p class="text-secondary small mb-0 fw-medium">Pantau performa penjualan dan metrik utama toko Anda secara real-time.</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card stat-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100" style="animation-delay: 0.1s;">
            <div class="icon-box-glass icon-revenue">
                <i class="fa-solid fa-wallet fs-4"></i>
            </div>
            <div class="small fw-bold text-secondary text-uppercase mb-2" style="letter-spacing: 0.5px; font-size: 0.75rem;">Total Pendapatan</div>
            <div id="statRevenue" class="h4 fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">Rp 0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card stat-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100" style="animation-delay: 0.2s;">
            <div class="icon-box-glass icon-orders">
                <i class="fa-solid fa-bag-shopping fs-4"></i>
            </div>
            <div class="small fw-bold text-secondary text-uppercase mb-2" style="letter-spacing: 0.5px; font-size: 0.75rem;">Jumlah Pesanan</div>
            <div id="statOrders" class="h3 fw-bold text-dark mb-0">0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card stat-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100" style="animation-delay: 0.3s;">
            <div class="icon-box-glass icon-products">
                <i class="fa-solid fa-box-open fs-4"></i>
            </div>
            <div class="small fw-bold text-secondary text-uppercase mb-2" style="letter-spacing: 0.5px; font-size: 0.75rem;">Total Produk</div>
            <div id="statProducts" class="h3 fw-bold text-dark mb-0">0</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="dashboard-card stat-card p-4 text-center d-flex flex-column align-items-center justify-content-center h-100" style="animation-delay: 0.4s;">
            <div class="icon-box-glass icon-users">
                <i class="fa-solid fa-users fs-4"></i>
            </div>
            <div class="small fw-bold text-secondary text-uppercase mb-2" style="letter-spacing: 0.5px; font-size: 0.75rem;">Total Pelanggan</div>
            <div id="statUsers" class="h3 fw-bold text-dark mb-0">0</div>
        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="dashboard-card p-4 p-md-5" style="animation-delay: 0.5s;">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-light">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                    <i class="fa-solid fa-chart-line fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Grafik Pendapatan</h5>
                    <small class="text-secondary fw-medium">Pergerakan 7 hari terakhir</small>
                </div>
            </div>
            <div class="chart-container position-relative w-100" style="height: 350px;">
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

        // Buat efek gradien biru transparan untuk area bawah garis grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.3)');
        gradient.addColorStop(0.8, 'rgba(0, 123, 255, 0.05)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

        // Konfigurasi Chart.js yang lebih elegan dan bersih
        let chart = new Chart(ctx, {
            type: 'line',
            data: { 
                labels: [], 
                datasets: [{ 
                    label: 'Pendapatan (Rp)', 
                    data: [], 
                    backgroundColor: gradient, 
                    borderColor: '#007bff', 
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#007bff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#007bff',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    fill: true,
                    tension: 0.4 // Curve halus
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#0f172a',
                        titleFont: { family: 'Poppins', size: 13, weight: '600' },
                        bodyColor: '#007bff',
                        bodyFont: { family: 'Poppins', size: 14, weight: '700' },
                        borderColor: 'rgba(0, 123, 255, 0.15)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        titleAlign: 'center',
                        bodyAlign: 'center',
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let val = context.parsed.y;
                                return val !== null ? 'Rp ' + new Intl.NumberFormat('id-ID').format(val) : '';
                            }
                        }
                    }
                }, 
                scales: { 
                    x: { 
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { family: 'Poppins', size: 12, weight: '500' }, padding: 10 }
                    },
                    y: { 
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false, tickLength: 0 },
                        ticks: { 
                            color: '#64748b', 
                            font: { family: 'Poppins', size: 11, weight: '500' },
                            padding: 15,
                            maxTicksLimit: 6,
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

        // Animasi counter untuk angka (Opsional tapi bikin UI lebih hidup)
        function animateValue(obj, start, end, duration, isCurrency = false) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                // Ease out cubic
                const easeProgress = 1 - Math.pow(1 - progress, 3); 
                const currentVal = Math.floor(easeProgress * (end - start) + start);
                
                obj.innerText = isCurrency ? formatRupiah(currentVal) : new Intl.NumberFormat('id-ID').format(currentVal);
                
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    obj.innerText = isCurrency ? formatRupiah(end) : new Intl.NumberFormat('id-ID').format(end);
                }
            };
            window.requestAnimationFrame(step);
        }

        let isFirstLoad = true;

        // Fungsi menerapkan data ke UI
        function applyStats(stats) {
            if(isFirstLoad) {
                animateValue(revenueEl, 0, stats.total_revenue || 0, 1500, true);
                animateValue(ordersEl, 0, stats.total_orders || 0, 1500, false);
                animateValue(productsEl, 0, stats.total_products || 0, 1500, false);
                animateValue(usersEl, 0, stats.total_users || 0, 1500, false);
                isFirstLoad = false;
            } else {
                revenueEl.innerText = formatRupiah(stats.total_revenue || 0);
                ordersEl.innerText = new Intl.NumberFormat('id-ID').format(stats.total_orders || 0);
                productsEl.innerText = new Intl.NumberFormat('id-ID').format(stats.total_products || 0);
                usersEl.innerText = new Intl.NumberFormat('id-ID').format(stats.total_users || 0);
            }
            
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