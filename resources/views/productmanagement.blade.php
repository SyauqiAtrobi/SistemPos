@extends('layouts.role')

@section('title', 'Manajemen Produk')

@push('styles')
<style>
    /* Styling khusus tabel bergaya Glassmorphism */
    .table-glass {
        margin-bottom: 0;
        color: #333;
    }
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
    .product-thumb {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">
            <i class="fa-solid fa-boxes-stacked me-2"></i> Manajemen Produk
        </h3>
        <p class="text-muted small mt-1">Kelola katalog parfum, harga, dan stok Anda di sini.</p>
    </div>
    <button class="btn btn-custom-primary shadow-sm" onclick="openCreateModal()">
        <i class="fa-solid fa-plus me-1"></i> <span class="d-none d-md-inline">Tambah Produk</span>
    </button>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover">
            <thead>
                <tr>
                    <th class="ps-4">Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td class="ps-4 d-flex align-items-center gap-3">
                        <img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://via.placeholder.com/45' }}" alt="{{ $p->name }}" class="product-thumb">
                        <div>
                            <div class="fw-bold">{{ $p->name }}</div>
                            <small class="text-muted">{{ Str::limit($p->description, 30) }}</small>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-primary border border-primary-subtle">{{ $p->category->name ?? '-' }}</span></td>
                    <td class="fw-semibold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $p->stock > 10 ? 'bg-success' : ($p->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn bg-light text-primary border shadow-sm me-1" 
                                onclick="openEditModal(this)"
                                data-id="{{ $p->id }}" data-name="{{ $p->name }}" 
                                data-category="{{ $p->category_id }}" data-price="{{ $p->price }}" 
                                data-stock="{{ $p->stock }}" data-desc="{{ $p->description }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('product.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="action-btn bg-light text-danger border shadow-sm"
                                    onclick="showConfirmModal('Hapus Produk', 'Yakin ingin menghapus parfum {{ $p->name }}? Data tidak dapat dikembalikan.', () => this.form.submit())">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-box-open fa-3x mb-3 opacity-50"></i>
                        <p>Belum ada data produk.</p>
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
                <h5 class="modal-title fw-bold text-primary" id="modalTitle">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="productForm" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div> <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nama Parfum</label>
                        <input type="text" class="form-control" name="name" id="inputName" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small fw-semibold">Kategori Aroma</label>
                            <select class="form-select" name="category_id" id="inputCategory" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small fw-semibold">Stok</label>
                            <input type="number" class="form-control" name="stock" id="inputStock" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Harga (Rp)</label>
                        <input type="number" class="form-control" name="price" id="inputPrice" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="description" id="inputDesc" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Gambar Produk (Opsional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted" id="imageHelp">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary px-4" id="btnSubmit">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const formModal = new bootstrap.Modal(document.getElementById('formModal'));
    const form = document.getElementById('productForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');

    // Fungsi membuka modal untuk TAMBAH data
    function openCreateModal() {
        modalTitle.innerText = 'Tambah Produk Baru';
        btnSubmit.innerText = 'Simpan Produk';
        form.action = "{{ route('product.store') }}"; // Route Create
        methodContainer.innerHTML = ''; // Kosongkan method spoofing
        form.reset(); // Bersihkan isi form
        
        document.getElementById('imageHelp').classList.add('d-none'); // Sembunyikan teks bantuan gambar
        formModal.show();
    }

    // Fungsi membuka modal untuk EDIT data
    function openEditModal(btn) {
        modalTitle.innerText = 'Edit Data Produk';
        btnSubmit.innerText = 'Perbarui Data';
        
        // Ambil data dari atribut tombol
        const id = btn.getAttribute('data-id');
        
        // Set Action URL ke route Update
        form.action = `/manajemen-produk/${id}`; 
        
        // Inject method PUT untuk Laravel
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
        
        // Isi form dengan data yang ada
        document.getElementById('inputName').value = btn.getAttribute('data-name');
        document.getElementById('inputCategory').value = btn.getAttribute('data-category');
        document.getElementById('inputPrice').value = btn.getAttribute('data-price');
        document.getElementById('inputStock').value = btn.getAttribute('data-stock');
        document.getElementById('inputDesc').value = btn.getAttribute('data-desc');
        
        document.getElementById('imageHelp').classList.remove('d-none'); // Tampilkan teks bantuan gambar
        formModal.show();
    }
</script>
@endpush