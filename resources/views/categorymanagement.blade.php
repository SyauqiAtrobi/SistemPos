@extends('layouts.role')

@section('title', 'Manajemen Kategori')

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
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">
            <i class="fa-solid fa-tags me-2"></i> Manajemen Kategori
        </h3>
        <p class="text-muted small mt-1">Kelola varian aroma parfum Anda.</p>
    </div>
    <button class="btn btn-custom-primary shadow-sm" onclick="openCreateModal()">
        <i class="fa-solid fa-plus me-1"></i> <span class="d-none d-md-inline">Tambah Kategori</span>
    </button>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover">
            <thead>
                <tr>
                    <th class="ps-4">Nama Aroma</th>
                    <th>Slug (URL)</th>
                    <th class="text-center">Total Produk</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $c->name }}</div>
                        <small class="text-muted">{{ Str::limit($c->description, 50) ?: 'Tidak ada deskripsi' }}</small>
                    </td>
                    <td><span class="badge bg-light text-secondary border">{{ $c->slug }}</span></td>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill">{{ $c->products_count }} Parfum</span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn bg-light text-primary border shadow-sm me-1" 
                                onclick="openEditModal(this)"
                                data-id="{{ $c->id }}" data-name="{{ $c->name }}" data-desc="{{ $c->description }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('category.destroy', $c->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="action-btn bg-light text-danger border shadow-sm"
                                    onclick="showConfirmModal('Hapus Kategori?', 'YAKIN INGIN MENGHAPUS {{ strtoupper($c->name) }}? \n\nPERHATIAN: Semua data produk parfum yang berada di dalam kategori ini juga akan terhapus permanen dari sistem!', () => this.form.submit())">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-tag fa-3x mb-3 opacity-50"></i>
                        <p>Belum ada data kategori.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="categoryForm" method="POST" action="{{ route('category.store') }}">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nama Kategori (Aroma)</label>
                        <input type="text" class="form-control" name="name" id="inputName" placeholder="Contoh: Woody, Citrus, Floral" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="description" id="inputDesc" rows="3" placeholder="Deskripsikan karakteristik aroma ini..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary px-4" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const formModal = new bootstrap.Modal(document.getElementById('formModal'));
    const form = document.getElementById('categoryForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');

    function openCreateModal() {
        modalTitle.innerText = 'Tambah Kategori Baru';
        btnSubmit.innerText = 'Simpan Kategori';
        form.action = "{{ route('category.store') }}";
        methodContainer.innerHTML = ''; 
        form.reset(); 
        formModal.show();
    }

    function openEditModal(btn) {
        modalTitle.innerText = 'Edit Kategori';
        btnSubmit.innerText = 'Perbarui Data';
        
        const id = btn.getAttribute('data-id');
        form.action = `/manajemen-kategori/${id}`; 
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
        
        document.getElementById('inputName').value = btn.getAttribute('data-name');
        document.getElementById('inputDesc').value = btn.getAttribute('data-desc');
        
        formModal.show();
    }
</script>
@endpush