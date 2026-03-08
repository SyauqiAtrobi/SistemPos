@extends('layouts.userlayout')

@section('title', 'Pengaturan Sistem')

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

    .settings-card { 
        max-width: 900px; 
        margin: 0 auto; 
    }

    /* Input Form Glassmorphism */
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

    /* Section Inner Box */
    .setting-section {
        background: rgba(0, 86, 179, 0.03);
        border: 1px solid rgba(0, 86, 179, 0.1);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 24px;
    }

    /* Image Preview Container */
    .preview-container {
        position: relative;
        display: inline-block;
        border-radius: 12px;
        padding: 5px;
        background: rgba(255, 255, 255, 0.6);
        border: 1px dashed rgba(0, 123, 255, 0.4);
    }
    .preview-img {
        max-height: 100px;
        border-radius: 8px;
        object-fit: contain;
    }
    
    /* Tombol Delete di Pojok Kanan Atas Preview */
    .btn-remove-preview {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        border: 2px solid white;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }
    .btn-remove-preview:hover {
        background: #bb2d3b;
        transform: scale(1.1);
    }

    /* Tombol Hapus Logo Database (Beda dengan Live Preview) */
    .btn-glass-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: 8px;
        transition: all 0.3s;
    }
    .btn-glass-danger:hover {
        background: #dc3545;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-12">
        <div class="glass-card p-4 p-md-5 settings-card shadow-sm">
            
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-light">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-sliders fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-gradient-blue mb-0">Pengaturan Sistem</h4>
                    <p class="text-glass-blue small mb-0">Kelola identitas aplikasi, gateway pembayaran, dan server email.</p>
                </div>
            </div>

            <form id="deleteLogoForm" method="POST" action="{{ route('settings.logo.destroy') }}">
                @csrf
                @method('DELETE')
            </form>

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="setting-section">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card me-2 text-primary"></i>Identitas Aplikasi</h6>
                    
                    <div class="mb-4">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Nama Aplikasi (APP_NAME)</label>
                        <input name="APP_NAME" class="form-control custom-input-glass" value="{{ old('APP_NAME', $values['APP_NAME'] ?? '') }}" placeholder="Contoh: BabaPOS">
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-glass-blue small fw-semibold ms-1">Logo Aplikasi (APP_LOGO)</label>
                        
                        @if(!empty($values['APP_LOGO']))
                            <div id="existingLogoContainer" class="mb-3">
                                <div class="preview-container shadow-sm border-solid">
                                    <img src="{{ $values['APP_LOGO'] }}" alt="Logo Tersimpan" class="preview-img">
                                    <button type="button" class="btn-remove-preview" onclick="if(confirm('Yakin ingin menghapus logo ini dari sistem?')) document.getElementById('deleteLogoForm').submit();" title="Hapus Logo Permanen">
                                        <i class="fa-solid fa-trash-can" style="font-size: 10px;"></i>
                                    </button>
                                </div>
                                <div class="small mt-1 text-success fw-medium"><i class="fa-solid fa-check-circle me-1"></i>Logo aktif saat ini</div>
                            </div>
                        @endif

                        <div id="livePreviewContainer" class="mb-3 d-none">
                            <div class="preview-container shadow-sm">
                                <img id="livePreviewImg" src="" alt="Preview Logo Baru" class="preview-img">
                                <button type="button" id="clearFileBtn" class="btn-remove-preview" title="Batal Pilih Gambar">
                                    <i class="fa-solid fa-xmark" style="font-size: 12px;"></i>
                                </button>
                            </div>
                            <div class="small mt-1 text-primary fw-medium"><i class="fa-solid fa-eye me-1"></i>Preview logo baru</div>
                        </div>

                        <input type="file" name="APP_LOGO" id="appLogoInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control custom-input-glass">
                        <div class="form-text text-glass-blue ms-1 small">Format didukung: PNG, JPG. Akan menggantikan logo saat ini jika ada.</div>
                    </div>
                </div>

                <div class="setting-section">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-money-bill-transfer me-2 text-success"></i>Pakasir Payment Gateway</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">Project Slug (PAKASIR_PROJECT_SLUG)</label>
                            <input name="PAKASIR_PROJECT_SLUG" class="form-control custom-input-glass" value="{{ old('PAKASIR_PROJECT_SLUG', $values['PAKASIR_PROJECT_SLUG'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">API Key (PAKASIR_API_KEY)</label>
                            <input type="password" name="PAKASIR_API_KEY" class="form-control custom-input-glass" value="{{ old('PAKASIR_API_KEY', $values['PAKASIR_API_KEY'] ?? '') }}" placeholder="********">
                        </div>
                    </div>
                </div>

                <div class="setting-section mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-envelope me-2 text-warning"></i>Konfigurasi Email (SMTP)</h6>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_MAILER</label>
                            <input name="MAIL_MAILER" class="form-control custom-input-glass" value="{{ old('MAIL_MAILER', $values['MAIL_MAILER'] ?? 'smtp') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_HOST</label>
                            <input name="MAIL_HOST" class="form-control custom-input-glass" value="{{ old('MAIL_HOST', $values['MAIL_HOST'] ?? 'smtp') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_PORT</label>
                            <input name="MAIL_PORT" type="number" class="form-control custom-input-glass" value="{{ old('MAIL_PORT', $values['MAIL_PORT'] ?? 587) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_USERNAME</label>
                            <input name="MAIL_USERNAME" class="form-control custom-input-glass" value="{{ old('MAIL_USERNAME', $values['MAIL_USERNAME'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_PASSWORD</label>
                            <input type="password" name="MAIL_PASSWORD" class="form-control custom-input-glass" value="{{ old('MAIL_PASSWORD', $values['MAIL_PASSWORD'] ?? '') }}">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_FROM_ADDRESS</label>
                            <input name="MAIL_FROM_ADDRESS" class="form-control custom-input-glass" value="{{ old('MAIL_FROM_ADDRESS', $values['MAIL_FROM_ADDRESS'] ?? '') }}" placeholder="noreply@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-glass-blue small fw-semibold ms-1">MAIL_FROM_NAME</label>
                            <input name="MAIL_FROM_NAME" class="form-control custom-input-glass" value="{{ old('MAIL_FROM_NAME', $values['MAIL_FROM_NAME'] ?? '${APP_NAME}') }}">
                        </div>
                    </div>
                </div>

                <div class="text-end pt-3 border-top border-light">
                    <button type="submit" class="btn btn-custom-primary rounded-pill px-5 shadow-sm py-2">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('appLogoInput');
        const livePreviewContainer = document.getElementById('livePreviewContainer');
        const livePreviewImg = document.getElementById('livePreviewImg');
        const clearFileBtn = document.getElementById('clearFileBtn');
        const existingLogoContainer = document.getElementById('existingLogoContainer');

        if (!fileInput) {
            console.warn('appLogoInput not found');
            return;
        }

        // Use createObjectURL for faster preview; fallback to FileReader if needed
        fileInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                // no file selected
                if (livePreviewContainer) livePreviewContainer.classList.add('d-none');
                if (existingLogoContainer) existingLogoContainer.classList.remove('d-none');
                return;
            }

            if (!file.type.match('image.*')) {
                alert('Harap pilih file gambar (JPG, PNG).');
                fileInput.value = '';
                return;
            }

            try {
                const url = URL.createObjectURL(file);
                if (livePreviewImg) {
                    livePreviewImg.src = url;
                }
                if (livePreviewContainer) livePreviewContainer.classList.remove('d-none');
                if (existingLogoContainer) existingLogoContainer.classList.add('d-none');
                if (clearFileBtn) clearFileBtn.style.display = 'inline-block';
            } catch (err) {
                // fallback to FileReader
                console.warn('createObjectURL failed, fallback to FileReader', err);
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (livePreviewImg) livePreviewImg.src = e.target.result;
                    if (livePreviewContainer) livePreviewContainer.classList.remove('d-none');
                    if (existingLogoContainer) existingLogoContainer.classList.add('d-none');
                    if (clearFileBtn) clearFileBtn.style.display = 'inline-block';
                }
                reader.readAsDataURL(file);
            }
        });

        if (clearFileBtn) {
            clearFileBtn.addEventListener('click', function() {
                if (fileInput) fileInput.value = '';
                if (livePreviewImg) {
                    // revoke blob url if used
                    if (livePreviewImg.src && livePreviewImg.src.startsWith('blob:')) {
                        try { URL.revokeObjectURL(livePreviewImg.src); } catch (e) {}
                    }
                    livePreviewImg.src = '';
                }
                if (livePreviewContainer) livePreviewContainer.classList.add('d-none');
                if (existingLogoContainer) existingLogoContainer.classList.remove('d-none');
                clearFileBtn.style.display = 'none';
            });
        }
    });
</script>
@endpush