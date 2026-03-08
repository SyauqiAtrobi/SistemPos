@extends('layouts.userlayout')

@section('title', 'Manajemen Pengguna')

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

    /* Avatar & Badges Glassmorphism */
    .avatar-glass {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 61, 153, 0.05) 100%);
        color: #0056b3;
        border: 1px solid rgba(0, 123, 255, 0.15);
    }
    .badge-glass-admin { background: rgba(0, 123, 255, 0.1); border: 1px solid rgba(0, 123, 255, 0.2); color: #0056b3; }
    .badge-glass-customer { background: rgba(108, 117, 125, 0.08); border: 1px solid rgba(108, 117, 125, 0.2); color: rgba(0, 50, 120, 0.6); }

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
            <i class="fa-solid fa-users-gear me-2"></i> Manajemen Pengguna
        </h3>
        <p class="text-glass-blue small mt-1 mb-0">Lihat dan kelola akun pengguna aplikasi.</p>
    </div>
    <button class="btn btn-custom-primary rounded-pill shadow-sm" onclick="openCreateModal()">
        <i class="fa-solid fa-user-plus me-1"></i> <span class="d-none d-md-inline">Tambah Pengguna</span>
    </button>
</div>

<div class="glass-card p-0 overflow-hidden border-0 fade-in-up shadow-sm" style="animation-delay: 0.1s;">
    <div class="table-responsive">
        <table class="table table-glass table-hover align-middle">
            <thead>
                <tr>
                    <th class="ps-4 py-3">Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Telepon</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-glass rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold" style="color: rgba(0, 50, 120, 0.9);">{{ $user->name }}</div>
                                <div class="small text-glass-blue">Terdaftar: {{ $user->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-glass-blue fw-medium">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-glass-admin' : 'badge-glass-customer' }} rounded-pill px-3">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-glass-blue">{{ $user->phone ?? '-' }}</td>
                    <td class="text-end pe-4">
                        <button class="action-btn text-primary me-1" 
                                onclick="openEditModal({{ $user->id }}, '{{ e($user->name) }}', '{{ e($user->email) }}', '{{ $user->role }}', '{{ e($user->phone) }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        
                        <form method="POST" action="{{ route('user.destroy', $user->id) }}" class="d-inline">
                            @csrf 
                            @method('DELETE')
                            <button type="button" class="action-btn text-danger" 
                                    onclick="showConfirmModal('Hapus Pengguna', 'Yakin ingin menghapus pengguna {{ $user->name }}? Data yang terkait mungkin akan hilang.', () => this.form.submit())">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fa-solid fa-users-slash fa-3x mb-3 text-glass-blue opacity-50"></i>
                        <p class="text-glass-blue mb-0">Belum ada data pengguna.</p>
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

        @php $p = $users; @endphp

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
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-user fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0" id="modalTitle">Tambah Pengguna</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="userForm" method="POST" action="{{ route('user.store') }}">
                @csrf
                <div id="methodContainer"></div>
                <input type="hidden" name="id" id="userId">
                
                <div class="modal-body pt-4 px-4 ms-2">
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Nama Lengkap</label>
                        <input type="text" class="form-control custom-input-glass" name="name" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Email</label>
                        <input type="email" class="form-control custom-input-glass" name="email" id="userEmail" required>
                    </div>
                    <div class="row mb-3 g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Role Akun</label>
                            <select name="role" id="userRole" class="form-select custom-input-glass">
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">No. Telepon (Opsional)</label>
                            <input type="text" class="form-control custom-input-glass" name="phone" id="userPhone">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-2 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary rounded-pill px-4 shadow-sm" id="btnSubmit">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
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
    const form = document.getElementById('userForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');

    function openCreateModal() {
        modalTitle.innerText = 'Tambah Pengguna Baru';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengguna';
        form.action = "{{ route('user.store') }}";
        methodContainer.innerHTML = ''; 
        form.reset(); 
        document.getElementById('userId').value = '';
        document.getElementById('userRole').value = 'customer';
        formModal.show();
    }

    function openEditModal(id, name, email, role, phone) {
        modalTitle.innerText = 'Edit Data Pengguna';
        btnSubmit.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data';
        
        form.action = `/manajemen-pengguna/${id}`; 
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
        
        document.getElementById('userId').value = id;
        document.getElementById('userName').value = name;
        document.getElementById('userEmail').value = email;
        document.getElementById('userRole').value = role;
        document.getElementById('userPhone').value = phone || '';
        
        formModal.show();
    }
</script>
@endpush