@extends('layouts.userlayout')

@section('title', 'Manajemen Kategori')

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

    /* Badges Glassmorphism */
    .badge-glass-slug { background: rgba(108, 117, 125, 0.08); border: 1px solid rgba(108, 117, 125, 0.2); color: rgba(0, 50, 120, 0.6); }
    .badge-glass-primary { background: rgba(0, 123, 255, 0.1); border: 1px solid rgba(0, 123, 255, 0.2); color: #0056b3; }

    /* Input Form Glassmorphism (Untuk Modal) */
    .custom-input-glass {
        background-color: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: #333;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .custom-input-glass:focus {
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }

    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-glass-cancel:hover { background: rgba(255, 255, 255, 0.9); border-color: #007bff; color: #0056b3; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <div>
        <h3 class="fw-bold mb-0 text-gradient-blue">
            <i class="fa-solid fa-tags me-2"></i> Manajemen Kategori
        </h3>
        <p class="text-glass-blue small mt-1 mb-0">Kelola varian aroma parfum Anda.</p>
    </div>
    <button class="btn btn-custom-primary rounded-pill shadow-sm" onclick="openCreateModal()">
        <i class="fa-solid fa-plus me-1"></i> <span class="d-none d-md-inline">Tambah Kategori</span>
    </button>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up shadow-sm" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover align-middle">
            <thead>
                <tr>
                    <th class="ps-4 py-3">Nama Aroma</th>
                    <th>Slug (URL)</th>
                    <th class="text-center">Total Produk</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold" style="color: rgba(0, 50, 120, 0.9);">{{ $c->name }}</div>
                        <small class="text-glass-blue">{{ Str::limit($c->description, 50) ?: 'Tidak ada deskripsi' }}</small>
                    </td>
                    <td>
                        <span class="badge badge-glass-slug rounded-pill px-3">{{ $c->slug }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-glass-primary rounded-pill px-3">{{ $c->products_count }} Parfum</span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn text-primary me-1" 
                                onclick="openEditModal(this)"
                                data-id="{{ $c->id }}" data-name="{{ $c->name }}" data-desc="{{ $c->description }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('category.destroy', $c->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="action-btn text-danger"
                                    onclick="showConfirmModal('Hapus Kategori?', 'YAKIN INGIN MENGHAPUS {{ strtoupper($c->name) }}? \n\nPERHATIAN: Semua data produk parfum yang berada di dalam kategori ini juga akan terhapus permanen dari sistem!', () => this.form.submit())">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fa-solid fa-tags fa-3x mb-3 text-glass-blue opacity-50"></i>
                        <p class="text-glass-blue mb-0">Belum ada data kategori.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-tag fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0" id="modalTitle">Tambah Kategori</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="categoryForm" method="POST" action="{{ route('category.store') }}">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body pt-4 px-4 ms-2">
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Nama Kategori (Aroma)</label>
                        <input type="text" class="form-control custom-input-glass" name="name" id="inputName" placeholder="Contoh: Woody, Citrus, Floral" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Deskripsi</label>
                        <textarea class="form-control custom-input-glass" name="description" id="inputDesc" rows="3" placeholder="Deskripsikan karakteristik aroma ini..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-2 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary rounded-pill px-4 shadow-sm" id="btnSubmit">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Kategori
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const formModal = new bootstrap.Modal(document.getElementById('formModal'));
    const form = document.getElementById('categoryForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');

    function openCreateModal() {
        modalTitle.innerText = 'Tambah Kategori Baru';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Simpan Kategori';
        form.action = "{{ route('category.store') }}";
        methodContainer.innerHTML = ''; 
        form.reset(); 
        formModal.show();
    }

    function openEditModal(btn) {
        modalTitle.innerText = 'Edit Kategori';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data';
        
        const id = btn.getAttribute('data-id');
        form.action = `/manajemen-kategori/${id}`; 
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
        
        document.getElementById('inputName').value = btn.getAttribute('data-name');
        document.getElementById('inputDesc').value = btn.getAttribute('data-desc');
        
        formModal.show();
    }
</script>
@endpush