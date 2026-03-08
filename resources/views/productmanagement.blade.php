@extends('layouts.userlayout')

@section('title', 'Manajemen Produk')

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
    
    .product-thumb {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 86, 179, 0.15);
    }
    .product-thumb-lg {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 86, 179, 0.12);
        display: block;
    }

    .image-preview-wrapper { position: relative; display: inline-block; }
    .preview-remove-icon {
        position: absolute; top: 6px; right: 6px; z-index: 5;
        width: 34px; height: 34px; border-radius: 8px; display:flex; align-items:center; justify-content:center;
        background: rgba(255,255,255,0.9); border: 1px solid rgba(0,0,0,0.06); color: #dc3545;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .preview-remove-icon:hover { transform: translateY(-1px); }

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
    .badge-glass-category { background: rgba(0, 123, 255, 0.1); border: 1px solid rgba(0, 123, 255, 0.2); color: #0056b3; }
    .badge-glass-success { background: rgba(25, 135, 84, 0.15); border: 1px solid rgba(25, 135, 84, 0.3); color: #157347; }
    .badge-glass-warning { background: rgba(255, 193, 7, 0.15); border: 1px solid rgba(255, 193, 7, 0.3); color: #b08d00; }
    .badge-glass-danger { background: rgba(220, 53, 69, 0.15); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }

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
            <i class="fa-solid fa-boxes-stacked me-2"></i> Manajemen Produk
        </h3>
        <p class="text-glass-blue small mt-1 mb-0">Kelola katalog parfum, harga, dan stok Anda di sini.</p>
    </div>
    <button class="btn btn-custom-primary rounded-pill shadow-sm" onclick="openCreateModal()">
        <i class="fa-solid fa-plus me-1"></i> <span class="d-none d-md-inline">Tambah Produk</span>
    </button>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up shadow-sm" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover align-middle">
            <thead>
                <tr>
                    <th class="ps-4 py-3">Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td class="ps-4 py-3 d-flex align-items-center gap-3">
                        <img src="{{ $p->image ? '/storage/'.$p->image : 'https://placehold.co/400' }}" alt="{{ $p->name }}" class="product-thumb" onerror="this.onerror=null;this.src='https://placehold.co/400';">
                        <div>
                            <div class="fw-bold" style="color: rgba(0, 50, 120, 0.9);">{{ $p->name }}</div>
                            <small class="text-glass-blue">{{ Str::limit($p->description, 30) }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-glass-category rounded-pill px-3">{{ $p->category->name ?? '-' }}</span>
                    </td>
                    <td class="fw-bold text-gradient-blue">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $stockClass = $p->stock > 10 ? 'badge-glass-success' : ($p->stock > 0 ? 'badge-glass-warning' : 'badge-glass-danger');
                        @endphp
                        <span class="badge {{ $stockClass }} rounded-pill px-3">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="action-btn text-primary me-1" 
                            onclick="openEditModal(this)"
                            data-id="{{ $p->id }}" data-name="{{ $p->name }}" 
                            data-category="{{ $p->category_id }}" data-price="{{ $p->price }}" 
                                data-stock="{{ $p->stock }}" data-desc="{{ $p->description }}"
                                data-image="{{ $p->image ? '/storage/'.$p->image : '' }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('product.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="action-btn text-danger"
                                    onclick="showConfirmModal('Hapus Produk', 'Yakin ingin menghapus parfum {{ $p->name }}? Data tidak dapat dikembalikan.', () => this.form.submit())">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fa-solid fa-box-open fa-3x mb-3 text-glass-blue opacity-50"></i>
                        <p class="text-glass-blue mb-0">Belum ada data produk.</p>
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

        @php
            $p = $products;
        @endphp

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

    @push('scripts')
    <script>
        // nothing (placeholder to keep stack consistent)
    </script>
    @endpush

    @section('paginationControls')
    @endsection

    @php
        // nothing
    @endphp


@push('modals')
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-box fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0" id="modalTitle">Tambah Produk Baru</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="productForm" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div> 
                
                <div class="modal-body pt-4 px-4 ms-2">
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Nama Parfum</label>
                        <input type="text" class="form-control custom-input-glass" name="name" id="inputName" placeholder="Contoh: Baccarat Rouge" required>
                    </div>
                    
                    <div class="row mb-3 g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Kategori Aroma</label>
                            <select class="form-select custom-input-glass" name="category_id" id="inputCategory" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Stok Gudang</label>
                            <input type="number" class="form-control custom-input-glass" name="stock" id="inputStock" min="0" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Harga (Rp)</label>
                        <input type="number" class="form-control custom-input-glass" name="price" id="inputPrice" min="0" placeholder="Contoh: 150000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Deskripsi Produk</label>
                        <textarea class="form-control custom-input-glass" name="description" id="inputDesc" rows="3" placeholder="Jelaskan karakteristik aroma parfum ini..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Gambar Produk (Opsional)</label>
                        <input type="file" class="form-control custom-input-glass" name="image" id="inputImage" accept="image/*">
                        <div class="form-text text-glass-blue ms-1 mt-1 small" id="imageHelp">Biarkan kosong jika tidak ingin mengubah gambar.</div>

                        <div id="imagePreviewWrapper" class="mt-3" style="display:none;">
                            <div class="image-preview-wrapper">
                                <button type="button" id="removeImageBtn" class="preview-remove-icon" title="Hapus gambar"><i class="fa-solid fa-trash"></i></button>
                                <img id="imagePreview" src="" alt="Preview" class="product-thumb-lg">
                            </div>
                        </div>
                        <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-2 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary rounded-pill px-4 shadow-sm" id="btnSubmit">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Produk
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
    const form = document.getElementById('productForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');

    // Fungsi membuka modal untuk TAMBAH data
    function openCreateModal() {
        modalTitle.innerText = 'Tambah Produk Baru';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Simpan Produk';
        form.action = "{{ route('product.store') }}"; 
        methodContainer.innerHTML = ''; 
        form.reset(); 
        
        document.getElementById('imageHelp').classList.add('d-none'); 
        formModal.show();
    }

    // Fungsi membuka modal untuk EDIT data
    function openEditModal(btn) {
        modalTitle.innerText = 'Edit Data Produk';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data';
        
        const id = btn.getAttribute('data-id');
        form.action = `/manajemen-produk/${id}`; 
        
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
        
        document.getElementById('inputName').value = btn.getAttribute('data-name');
        document.getElementById('inputCategory').value = btn.getAttribute('data-category');
        document.getElementById('inputPrice').value = btn.getAttribute('data-price');
        document.getElementById('inputStock').value = btn.getAttribute('data-stock');
        document.getElementById('inputDesc').value = btn.getAttribute('data-desc');
        
        // Image preview handling for edit
        const imgUrl = btn.getAttribute('data-image') || '';
        const previewWrap = document.getElementById('imagePreviewWrapper');
        const previewImg = document.getElementById('imagePreview');
        const removeInput = document.getElementById('removeImageInput');
        const imageHelp = document.getElementById('imageHelp');
        const fileInput = document.getElementById('inputImage');
        fileInput.value = null;
        removeInput.value = '0';
        if (imgUrl) {
            previewImg.src = imgUrl;
            previewWrap.style.display = 'block';
            imageHelp.classList.remove('d-none');
        } else {
            previewImg.src = '';
            previewWrap.style.display = 'none';
            imageHelp.classList.add('d-none');
        }

        formModal.show();
    }

    // File input preview and remove handlers
    (function () {
        const fileInput = document.getElementById('inputImage');
        const previewWrap = document.getElementById('imagePreviewWrapper');
        const previewImg = document.getElementById('imagePreview');
        const removeBtn = document.getElementById('removeImageBtn');
        const removeInput = document.getElementById('removeImageInput');
        const imageHelp = document.getElementById('imageHelp');

        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                const file = this.files && this.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                previewImg.src = url;
                previewWrap.style.display = 'block';
                removeInput.value = '0';
                imageHelp.classList.remove('d-none');
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                // Clear file input and mark remove flag
                if (fileInput) fileInput.value = null;
                previewImg.src = '';
                previewWrap.style.display = 'none';
                if (removeInput) removeInput.value = '1';
                if (imageHelp) imageHelp.classList.remove('d-none');
            });
            // allow pressing Enter/Space when focused
            removeBtn.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
            });
        }
    })();
</script>
@endpush