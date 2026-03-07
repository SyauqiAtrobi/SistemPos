@extends('layouts.userlayout')

@section('title', 'Manajemen Pengguna')

@push('styles')
<style>
    .table-users th { background: rgba(0,86,179,0.05); }
    .role-badge { font-size: 0.85rem; }
    .action-btn { width:36px; height:36px; }
    .user-thumb { width:40px; height:40px; object-fit:cover; border-radius:8px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Manajemen Pengguna</h3>
        <p class="small text-muted mb-0">Lihat dan kelola akun pengguna aplikasi.</p>
    </div>
    <div>
        <a href="#" class="btn btn-custom-primary" onclick="openCreateUserModal()"><i class="fa-solid fa-user-plus me-1"></i>Tambah Pengguna</a>
    </div>
</div>

<div class="glass-card p-3">
    <div class="table-responsive">
        <table class="table table-users table-hover align-middle">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Telepon</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="small text-muted">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }} role-badge">{{ ucfirst($user->role) }}</span></td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-secondary action-btn me-2" onclick="openEditUserModal({{ $user->id }}, '{{ e($user->name) }}', '{{ e($user->email) }}', '{{ $user->role }}', '{{ $user->phone ?? '' }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form method="POST" action="{{ route('user.destroy', $user->id) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger action-btn" onclick="showConfirmModal('Hapus pengguna', 'Hapus pengguna {{ $user->name }}?', () => this.form.submit())"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: create/edit user -->
<div class="modal fade" id="userFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="userFormTitle">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST" action="{{ route('user.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId">
                    <div class="mb-3">
                        <label class="form-label small">Nama</label>
                        <input type="text" class="form-control" name="name" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Email</label>
                        <input type="email" class="form-control" name="email" id="userEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Role</label>
                        <select name="role" id="userRole" class="form-select">
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Telepon</label>
                        <input type="text" class="form-control" name="phone" id="userPhone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const userFormModal = new bootstrap.Modal(document.getElementById('userFormModal'));
    function openCreateUserModal() {
        document.getElementById('userForm').action = '{{ route('user.store') }}';
        document.getElementById('userFormTitle').innerText = 'Tambah Pengguna';
        document.getElementById('userId').value = '';
        document.getElementById('userName').value = '';
        document.getElementById('userEmail').value = '';
        document.getElementById('userRole').value = 'customer';
        document.getElementById('userPhone').value = '';
        userFormModal.show();
    }
    function openEditUserModal(id, name, email, role, phone) {
        document.getElementById('userForm').action = `/manajemen-pengguna/${id}`;
        document.getElementById('userFormTitle').innerText = 'Edit Pengguna';
        document.getElementById('userId').value = id;
        document.getElementById('userName').value = name;
        document.getElementById('userEmail').value = email;
        document.getElementById('userRole').value = role;
        document.getElementById('userPhone').value = phone;
        // add spoof _method input
        const mc = document.getElementById('userForm').querySelector('input[name="_method"]');
        if (!mc) {
            const hm = document.createElement('input'); hm.type='hidden'; hm.name='_method'; hm.value='PUT';
            document.getElementById('userForm').appendChild(hm);
        }
        userFormModal.show();
    }
</script>
@endpush
