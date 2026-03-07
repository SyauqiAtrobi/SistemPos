@extends('layouts.role')

@section('title', 'Manajemen Pesanan')

@push('styles')
<style>
    .table-glass { margin-bottom: 0; color: #333; }
    .table-glass th {
        background: rgba(0, 86, 179, 0.05);
        color: var(--primary-blue);
        border-bottom: 2px solid rgba(0, 86, 179, 0.1);
        font-weight: 600;
        white-space: nowrap;
    }
    .table-glass td {
        background: transparent;
        vertical-align: middle;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        white-space: nowrap;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: transform 0.2s;
        border: none;
    }
    .action-btn:hover { transform: translateY(-2px); }
    
    /* Status Badges */
    .status-pending { background-color: #ffc107; color: #000; }
    .status-paid { background-color: #198754; color: #fff; }
    .status-failed, .status-cancelled { background-color: #dc3545; color: #fff; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">
            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Manajemen Pesanan
        </h3>
        <p class="text-muted small mt-1">Pantau transaksi masuk dan status pembayaran pelanggan.</p>
    </div>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover">
            <thead>
                <tr>
                    <th class="ps-4">No. Invoice / Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-primary">{{ $o->order_number }}</div>
                        <small class="text-muted">{{ $o->created_at->format('d M Y, H:i') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $o->user->name }}</div>
                        <small class="text-muted">{{ $o->user->phone ?? $o->user->email }}</small>
                    </td>
                    <td class="fw-bold">Rp {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge status-{{ $o->status }} rounded-pill px-3">
                            {{ strtoupper($o->status) }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn bg-light text-primary border shadow-sm me-1" 
                                onclick="openDetailModal(this)"
                                data-invoice="{{ $o->order_number }}"
                                data-date="{{ $o->created_at->format('d M Y, H:i') }}"
                                data-customer="{{ $o->user->name }}"
                                data-total="Rp {{ number_format($o->total_amount, 0, ',', '.') }}"
                                data-status="{{ strtoupper($o->status) }}"
                                data-items="{{ json_encode($o->items->map(function($item) {
                                    return [
                                        'name' => $item->product->name,
                                        'qty' => $item->qty,
                                        'price' => number_format($item->price, 0, ',', '.'),
                                        'subtotal' => number_format($item->price * $item->qty, 0, ',', '.')
                                    ];
                                })) }}">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        @if($o->status === 'pending')
                        <form action="{{ route('order.cancel', $o->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="button" class="action-btn bg-light text-danger border shadow-sm"
                                    onclick="showConfirmModal('Batalkan Pesanan', 'Yakin ingin membatalkan pesanan {{ $o->order_number }} secara manual?', () => this.form.submit())">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-receipt fa-3x mb-3 opacity-50"></i>
                        <p>Belum ada transaksi masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <small class="text-muted d-block">Nomor Invoice</small>
                        <strong id="modalInvoice" class="fs-5 text-primary"></strong>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <small class="text-muted d-block">Status Pembayaran</small>
                        <span id="modalStatus" class="badge rounded-pill px-3 mt-1"></span>
                    </div>
                </div>

                <div class="row mb-4 bg-light p-3 rounded-3 mx-0">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Pelanggan</small>
                        <strong id="modalCustomer"></strong>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                        <small class="text-muted d-block">Tanggal Transaksi</small>
                        <strong id="modalDate"></strong>
                    </div>
                </div>

                <h6 class="fw-bold mb-3" style="color: var(--primary-blue);">Item yang Dibeli</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless border-bottom">
                        <thead class="border-bottom">
                            <tr>
                                <th class="text-muted">Nama Parfum</th>
                                <th class="text-center text-muted">Qty</th>
                                <th class="text-end text-muted">Harga</th>
                                <th class="text-end text-muted">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody">
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end pt-3">Total Tagihan:</th>
                                <th class="text-end text-primary fs-5 pt-3" id="modalTotal"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-custom-primary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

    function openDetailModal(btn) {
        // Isi header modal
        document.getElementById('modalInvoice').innerText = btn.getAttribute('data-invoice');
        document.getElementById('modalDate').innerText = btn.getAttribute('data-date');
        document.getElementById('modalCustomer').innerText = btn.getAttribute('data-customer');
        document.getElementById('modalTotal').innerText = btn.getAttribute('data-total');
        
        // Atur warna badge status
        const statusBadge = document.getElementById('modalStatus');
        const statusVal = btn.getAttribute('data-status');
        statusBadge.innerText = statusVal;
        statusBadge.className = 'badge rounded-pill px-3 mt-1'; // Reset class
        if(statusVal === 'PAID') statusBadge.classList.add('bg-success');
        else if(statusVal === 'PENDING') statusBadge.classList.add('bg-warning', 'text-dark');
        else statusBadge.classList.add('bg-danger');

        // Render baris produk (Parse JSON dari atribut data-items)
        const items = JSON.parse(btn.getAttribute('data-items'));
        const tbody = document.getElementById('modalItemsBody');
        tbody.innerHTML = ''; // Kosongkan isi sebelumnya

        items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="fw-semibold">${item.name}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-end text-muted">Rp ${item.price}</td>
                <td class="text-end fw-bold">Rp ${item.subtotal}</td>
            `;
            tbody.appendChild(tr);
        });

        detailModal.show();
    }
</script>
@endpush