@extends('layouts.userlayout')

@section('title', 'Manajemen Pesanan')

@push('styles')
<style>
    /* Utility Classes Biru-Putih */
    .text-gradient-blue {
        background: linear-gradient(135deg, #007bff 0%, #003d99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .text-glass-blue {
        color: rgba(0, 50, 120, 0.6);
    }

    /* Table Glass Styling */
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
    
    /* Tombol Aksi Kaca */
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid rgba(0, 86, 179, 0.2);
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
    }
    .action-btn:hover { 
        transform: translateY(-2px); 
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.1);
    }

    /* Status Badges Glassmorphism */
    .badge-glass-pending { background: rgba(255, 193, 7, 0.15); border: 1px solid rgba(255, 193, 7, 0.3); color: #b08d00; }
    .badge-glass-paid { background: rgba(25, 135, 84, 0.15); border: 1px solid rgba(25, 135, 84, 0.3); color: #157347; }
    .badge-glass-danger { background: rgba(220, 53, 69, 0.15); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }

    /* Custom Select Glass */
    .custom-select-glass {
        background-color: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        font-weight: 500;
        transition: all 0.3s;
    }
    .custom-select-glass:focus {
        background-color: rgba(255, 255, 255, 0.9);
        border-color: rgba(0, 123, 255, 0.5);
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
    }

    /* Modal Styling */
    .modal-info-box {
        background: rgba(0, 86, 179, 0.05);
        border: 1px solid rgba(0, 86, 179, 0.1);
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <div>
        <h3 class="fw-bold mb-0 text-gradient-blue">
            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Manajemen Pesanan
        </h3>
        <p class="text-glass-blue small mt-1 mb-0">Pantau transaksi masuk dan status pembayaran pelanggan.</p>
    </div>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up shadow-sm" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover align-middle">
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
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-gradient-blue">{{ $o->order_number }}</div>
                        <small class="text-glass-blue">{{ $o->created_at->format('d M Y, H:i') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $o->user->name }}</div>
                        <small class="text-glass-blue">{{ $o->user->phone ?? $o->user->email }}</small>
                    </td>
                    <td class="fw-bold" style="color: var(--primary-blue);">
                        Rp {{ number_format($o->total_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @php
                                $statusClass = match($o->status) {
                                    'pending' => 'badge-glass-pending',
                                    'paid' => 'badge-glass-paid',
                                    default => 'badge-glass-danger',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill px-3">{{ strtoupper($o->status) }}</span>
                            
                            <select class="form-select form-select-sm custom-select-glass fulfillment-inline" data-id="{{ $o->id }}" style="width:160px;">
                                <option value="">-- Pengiriman --</option>
                                <option value="dikemas" {{ ($o->fulfillment_status ?? '') === 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                                <option value="dikirim" {{ ($o->fulfillment_status ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ ($o->fulfillment_status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="pengembalian" {{ ($o->fulfillment_status ?? '') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                                <option value="dibatalkan" {{ ($o->fulfillment_status ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn text-primary me-1" 
                                onclick="openDetailModal(this)"
                                data-id="{{ $o->id }}"
                                data-invoice="{{ $o->order_number }}"
                                data-date="{{ $o->created_at->format('d M Y, H:i') }}"
                                data-customer="{{ $o->user->name }}"
                                data-total="Rp {{ number_format($o->total_amount, 0, ',', '.') }}"
                                data-status="{{ strtoupper($o->status) }}"
                                data-fulfillment="{{ $o->fulfillment_status ?? '' }}"
                                data-items="{{ json_encode($o->items->map(function($item) {
                                    return [
                                        'name' => $item->product->name,
                                        'qty' => $item->qty,
                                        'price' => number_format($item->price, 0, ',', '.'),
                                        'subtotal' => number_format($item->price * $item->qty, 0, ',', '.')
                                    ];
                                })) }}"
                                data-shipping='@json($o->shipping_address)'>
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        @if($o->status === 'pending')
                        <form action="{{ route('order.cancel', $o->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="button" class="action-btn text-danger"
                                    onclick="showConfirmModal('Batalkan Pesanan', 'Yakin ingin membatalkan pesanan {{ $o->order_number }} secara manual?', () => this.form.submit())">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fa-solid fa-receipt fa-3x mb-3 text-glass-blue opacity-50"></i>
                        <p class="text-glass-blue">Belum ada transaksi masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    <div class="mt-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <form id="perPageForm" method="GET" class="d-flex align-items-center" style="gap:.75rem;">
            @foreach(request()->except(['perPage','page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label class="small text-glass-blue mb-0">Tampilkan</label>
            <select name="perPage" class="form-select form-select-sm custom-select-glass" onchange="this.form.submit()" style="width:100px;">
                @foreach([10,25,50,100] as $opt)
                    <option value="{{ $opt }}" {{ request('perPage', 10) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </form>

        @php $p = $orders; @endphp

        <div class="d-flex align-items-center justify-content-end gap-3 w-100 w-md-auto">
            <div class="small text-glass-blue">@if($p->total()) Menampilkan {{ $p->firstItem() }} - {{ $p->lastItem() }} dari {{ $p->total() }} @endif</div>

            @php
                $last = $p->lastPage();
                $current = $p->currentPage();
                $pages = [];
                if ($last <= 5) {
                    $pages = range(1, $last);
                } elseif ($current <= 3) {
                    $pages = array_merge(range(1,3), [$last-1, $last]);
                } elseif ($current >= $last - 2) {
                    $start = max(1, $last - 4);
                    $pages = range($start, $last);
                } else {
                    $pages = [1, $current-1, $current, $current+1, $last];
                }
            @endphp

            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $p->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $p->url(1) }}" aria-label="First">&laquo;</a>
                    </li>
                    @php $prev = max(1, $current - 1); @endphp
                    <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $p->url($prev) }}">‹</a>
                    </li>

                    @php $lastRendered = 0; @endphp
                    @foreach($pages as $pg)
                        @if($lastRendered && $pg - $lastRendered > 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item {{ $current == $pg ? 'active' : '' }}"><a class="page-link" href="{{ $p->url($pg) }}">{{ $pg }}</a></li>
                        @php $lastRendered = $pg; @endphp
                    @endforeach

                    @php $next = min($last, $current + 1); @endphp
                    <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $p->url($next) }}">›</a>
                    </li>
                    <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $p->url($last) }}" aria-label="Last">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection

@push('modals')
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-receipt fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0">Detail Pesanan</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body pt-4 px-4 ms-2">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <small class="text-glass-blue d-block fw-medium">Nomor Invoice</small>
                        <strong id="modalInvoice" class="fs-5 text-gradient-blue"></strong>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <small class="text-glass-blue d-block fw-medium">Status Pembayaran</small>
                        <span id="modalStatus" class="badge rounded-pill px-3 mt-1 shadow-sm"></span>
                    </div>
                </div>

                <div class="row mb-4 modal-info-box p-3 mx-0">
                    <div class="col-sm-6">
                        <small class="text-glass-blue d-block fw-medium">Pelanggan</small>
                        <strong id="modalCustomer" class="text-dark"></strong>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                        <small class="text-glass-blue d-block fw-medium">Tanggal Transaksi</small>
                        <strong id="modalDate" class="text-dark"></strong>
                    </div>
                </div>
                
                    <div class="row mb-3">
                        <div class="col-12">
                            <small class="text-glass-blue d-block fw-medium">Tujuan Pengiriman</small>
                            <div id="modalShipping" class="p-3 modal-info-box mt-2">
                                <div><strong id="modalShipLabel" class="text-dark"></strong></div>
                                <div id="modalShipAddress" class="text-glass-blue small"></div>
                                <div class="mt-1"><small class="text-glass-blue">No. HP: <span id="modalShipPhone"></span></small></div>
                                <div class="mt-1"><small class="text-glass-blue">Koordinat: <span id="modalShipCoords"></span></small></div>
                            </div>
                        </div>
                    </div>

                <h6 class="fw-bold mb-3 text-gradient-blue">
                    <i class="fa-solid fa-box-open me-2"></i> Item yang Dibeli
                </h6>
                
                <div class="table-responsive mb-2">
                    <table class="table table-sm table-borderless border-bottom border-opacity-25 border-primary">
                        <thead class="border-bottom border-opacity-25 border-primary">
                            <tr>
                                <th class="text-glass-blue fw-medium pb-2">Nama Parfum</th>
                                <th class="text-center text-glass-blue fw-medium pb-2">Qty</th>
                                <th class="text-end text-glass-blue fw-medium pb-2">Harga</th>
                                <th class="text-end text-glass-blue fw-medium pb-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody" class="align-middle">
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end pt-3 text-glass-blue fw-medium">Total Tagihan:</th>
                                <th class="text-end text-gradient-blue fs-5 pt-3 fw-bold" id="modalTotal"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer border-0 pt-0 pb-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="w-100 w-md-auto mb-3 mb-md-0">
                    <label class="form-label small mb-1 fw-medium text-glass-blue">Ubah Status Pengiriman</label>
                    <select id="modalFulfillmentSelect" class="form-select form-select-sm custom-select-glass w-100" style="min-width: 200px;">
                        <option value="">-- Pilih Status --</option>
                        <option value="dikemas">Dikemas</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="pengembalian">Pengembalian</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto justify-content-end">
                    <button type="button" id="saveFulfillmentBtn" class="btn btn-outline-primary rounded-pill px-3" style="background: rgba(255,255,255,0.5);">Simpan Status</button>
                    <button type="button" class="btn btn-custom-primary rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

    function openDetailModal(btn) {
        document.getElementById('modalInvoice').innerText = btn.getAttribute('data-invoice');
        document.getElementById('modalDate').innerText = btn.getAttribute('data-date');
        document.getElementById('modalCustomer').innerText = btn.getAttribute('data-customer');
        document.getElementById('modalTotal').innerText = btn.getAttribute('data-total');
        
        const statusBadge = document.getElementById('modalStatus');
        const statusVal = btn.getAttribute('data-status');
        statusBadge.innerText = statusVal;
        
        // Reset class dan terapkan warna badge glassmorphism
        statusBadge.className = 'badge rounded-pill px-3 mt-1 shadow-sm'; 
        if(statusVal === 'PAID') statusBadge.classList.add('badge-glass-paid');
        else if(statusVal === 'PENDING') statusBadge.classList.add('badge-glass-pending');
        else statusBadge.classList.add('badge-glass-danger');

        const items = JSON.parse(btn.getAttribute('data-items'));
        const tbody = document.getElementById('modalItemsBody');
        tbody.innerHTML = '';

        items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="fw-semibold py-2" style="color: rgba(0, 50, 120, 0.9);">${item.name}</td>
                <td class="text-center py-2 text-dark">${item.qty}</td>
                <td class="text-end py-2 text-glass-blue">Rp ${item.price}</td>
                <td class="text-end py-2 fw-bold" style="color: var(--primary-blue);">Rp ${item.subtotal}</td>
            `;
            tbody.appendChild(tr);
        });

        const fulfillSelect = document.getElementById('modalFulfillmentSelect');
        if (fulfillSelect) fulfillSelect.value = btn.getAttribute('data-fulfillment') || '';

        // Shipping address
        try {
            const shippingRaw = btn.getAttribute('data-shipping');
            const ship = shippingRaw ? JSON.parse(shippingRaw) : null;
            const shipLabelEl = document.getElementById('modalShipLabel');
            const shipAddrEl = document.getElementById('modalShipAddress');
            const shipPhoneEl = document.getElementById('modalShipPhone');
            const shipCoordsEl = document.getElementById('modalShipCoords');

            if (ship) {
                shipLabelEl.innerText = ship.label || '';
                let addrHtml = ship.address || '';
                if (ship.city) addrHtml += (addrHtml ? '<br>' : '') + ship.city;
                if (ship.postal_code) addrHtml += (addrHtml ? ' - ' : '') + ship.postal_code;
                shipAddrEl.innerHTML = addrHtml;
                shipPhoneEl.innerText = ship.phone || '';
                if (ship.lat && ship.lng) {
                    const lat = ship.lat; const lng = ship.lng;
                    shipCoordsEl.innerHTML = `<a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">${lat},${lng}</a>`;
                } else {
                    shipCoordsEl.innerText = '';
                }
            } else {
                shipLabelEl.innerText = '';
                shipAddrEl.innerText = 'Tidak ada data alamat pengiriman.';
                shipPhoneEl.innerText = '';
                shipCoordsEl.innerText = '';
            }
        } catch (e) {
            console.error('Failed to parse shipping data', e);
        }

        const saveBtn = document.getElementById('saveFulfillmentBtn');
        if (saveBtn) saveBtn.setAttribute('data-order-id', btn.getAttribute('data-id'));

        detailModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Save fulfillment status from Modal ---
        const saveBtn = document.getElementById('saveFulfillmentBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', async function () {
                const orderId = this.getAttribute('data-order-id');
                const val = document.getElementById('modalFulfillmentSelect').value;
                if (!orderId || !val) {
                    showToastError('Pilih status pengiriman terlebih dahulu.');
                    return;
                }

                try {
                    const res = await fetch(`/manajemen-pesanan/${orderId}/status`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ fulfillment_status: val })
                    });
                    if (!res.ok) throw new Error('Request failed');
                    const json = await res.json();
                    
                    updateTableBadge(orderId, json.fulfillment_status);
                    detailModal.hide();
                    showToastSuccess('Status pengiriman berhasil disimpan.');
                } catch (e) {
                    console.error(e);
                    showToastError('Gagal mengubah status pengiriman.');
                }
            });
        }
        
        // --- 2. Inline fulfillment change from Table ---
        document.querySelectorAll('.fulfillment-inline').forEach(select => {
            select.addEventListener('change', async function () {
                const orderId = this.getAttribute('data-id');
                const val = this.value;
                if (!orderId) return;
                
                try {
                    const res = await fetch(`/manajemen-pesanan/${orderId}/status`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ fulfillment_status: val })
                    });
                    if (!res.ok) throw new Error('Request failed');
                    const json = await res.json();
                    
                    updateTableBadge(orderId, json.fulfillment_status);
                    
                    const modalSave = document.getElementById('saveFulfillmentBtn');
                    if (modalSave && modalSave.getAttribute('data-order-id') === orderId) {
                        const modalSel = document.getElementById('modalFulfillmentSelect');
                        if (modalSel) modalSel.value = json.fulfillment_status || '';
                    }
                    showToastSuccess('Status pengiriman diperbarui.');
                } catch (e) {
                    console.error(e);
                    showToastError('Gagal mengubah status pengiriman.');
                }
            });
        });

        // Helper update UI Badge di Tabel
        function updateTableBadge(orderId, newFulfillment) {
            document.querySelectorAll('button[data-id="' + orderId + '"]').forEach(b => {
                const row = b.closest('tr');
                if (!row) return;
                const cellBadge = row.querySelector('td:nth-child(4) .badge');
                if (cellBadge && newFulfillment) {
                    const baseStatus = cellBadge.innerText.split(' · ')[0];
                    cellBadge.innerText = baseStatus + ' · ' + newFulfillment.toUpperCase();
                }
                // Update atribut button agar jika modal dibuka lagi, datanya up-to-date
                b.setAttribute('data-fulfillment', newFulfillment);
            });
        }

        // Helper Custom Toast Notifikasi
        function showToastSuccess(message) {
            const toastHTML = `<div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex align-items-center p-2"><div class="toast-body d-flex align-items-center fw-medium flex-grow-1 toast-text-glass"><i class="fa-solid fa-circle-check fs-4 me-3 toast-icon-success"></i><span class="lh-sm">${message}</span></div><button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast"></button></div></div>`;
            document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
            new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 3000 }).show();
        }
        
        function showToastError(message) {
            const toastHTML = `<div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex align-items-center p-2"><div class="toast-body d-flex align-items-center fw-medium flex-grow-1 toast-text-glass"><i class="fa-solid fa-triangle-exclamation fs-4 me-3 toast-icon-error"></i><span class="lh-sm">${message}</span></div><button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast"></button></div></div>`;
            document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHTML);
            new bootstrap.Toast(document.querySelector('.toast-container').lastElementChild, { delay: 3000 }).show();
        }
    });
</script>
@endpush