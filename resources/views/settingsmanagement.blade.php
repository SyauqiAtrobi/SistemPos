@extends('layouts.userlayout')

@section('title', 'Pengaturan Sistem')

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

    .settings-card { 
        max-width: 950px; 
        margin: 0 auto; 
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 40px rgba(0, 86, 179, 0.05);
    }

    /* Input Form Glassmorphism */
    .custom-input-glass {
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 123, 255, 0.15);
        color: #334155;
        border-radius: 12px;
        padding: 10px 16px;
        transition: all 0.3s;
        font-weight: 500;
    }
    .custom-input-glass:focus {
        background-color: #fff;
        border-color: #007bff;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        outline: none;
    }

    /* Section Inner Box */
    .setting-section {
        background: #f8fafc;
        border: 1px solid rgba(0, 123, 255, 0.08);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.3s;
    }
    .setting-section:hover {
        background: #ffffff;
        box-shadow: 0 8px 25px rgba(0, 86, 179, 0.05);
        border-color: rgba(0, 123, 255, 0.15);
    }

    /* Image Preview Container */
    .preview-container {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        padding: 10px;
        background: #ffffff;
        border: 1px dashed rgba(0, 123, 255, 0.3);
        min-width: 140px;
        min-height: 120px;
    }
    .preview-img {
        max-height: 100px;
        max-width: 200px;
        border-radius: 8px;
        object-fit: contain;
    }
    
    /* Tombol Delete di Pojok Kanan Atas Preview */
    .btn-remove-preview {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dc2626;
        color: white;
        border-radius: 50%;
        border: 2px solid white;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
        transition: all 0.2s;
    }
    .btn-remove-preview:hover {
        background: #b91c1c;
        transform: scale(1.1);
    }

    .icon-container {
        width: 56px; 
        height: 56px; 
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.05) 100%); 
        border: 1px solid rgba(0, 123, 255, 0.1);
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-12">
        <div class="glass-card p-4 p-md-5 settings-card shadow-sm">
            
            <div class="d-flex align-items-center mb-4 pb-4 border-bottom border-light">
                <div class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-3 icon-container">
                    <i class="fa-solid fa-sliders fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-1 fs-4" style="letter-spacing: -0.5px;">Pengaturan Sistem</h3>
                    <p class="text-secondary small mb-0 fw-medium">Kelola identitas aplikasi, gateway pembayaran, dan server email.</p>
                </div>
            </div>

            <form id="deleteLogoForm" method="POST" action="{{ route('settings.logo.destroy') }}">
                @csrf
                @method('DELETE')
            </form>

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="setting-section">
                    <h6 class="fw-bold text-dark mb-4 fs-5 d-flex align-items-center" style="letter-spacing: -0.3px;">
                        <i class="fa-solid fa-id-card me-2 text-primary opacity-75"></i> Identitas Aplikasi
                    </h6>
                    
                    <div class="mb-4">
                        <label class="form-label text-dark small fw-bold ms-1">Nama Aplikasi (APP_NAME)</label>
                        <input name="APP_NAME" class="form-control custom-input-glass" value="{{ old('APP_NAME', $values['APP_NAME'] ?? '') }}" placeholder="Contoh: BabaPOS">
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark small fw-bold ms-1">Logo Aplikasi (APP_LOGO)</label>
                        
                        @if(!empty($values['APP_LOGO']))
                            <div id="existingLogoContainer" class="mb-3 d-flex flex-column align-items-start">
                                <div class="preview-container shadow-sm border-solid">
                                    <img src="{{ $values['APP_LOGO'] }}" alt="Logo Tersimpan" class="preview-img">
                                    <button type="button" class="btn-remove-preview" onclick="if(confirm('Yakin ingin menghapus logo ini secara permanen dari sistem?')) document.getElementById('deleteLogoForm').submit();" title="Hapus Logo Permanen">
                                        <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                                <div class="small mt-2 text-success fw-bold bg-success bg-opacity-10 px-2 py-1 rounded-pill"><i class="fa-solid fa-check-circle me-1"></i>Logo aktif saat ini</div>
                            </div>
                        @endif

                        <div id="livePreviewContainer" class="mb-3 d-none flex-column align-items-start">
                            <div class="preview-container shadow-sm bg-white border-primary">
                                <img id="livePreviewImg" src="" alt="Preview Logo Baru" class="preview-img">
                                <button type="button" id="clearFileBtn" class="btn-remove-preview" title="Batal Pilih Gambar">
                                    <i class="fa-solid fa-xmark" style="font-size: 14px;"></i>
                                </button>
                            </div>
                            <div class="small mt-2 text-primary fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded-pill"><i class="fa-solid fa-eye me-1"></i>Preview logo baru</div>
                        </div>

                        <div class="position-relative">
                            <input type="file" name="APP_LOGO" id="appLogoInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control custom-input-glass bg-white pe-5">
                            <i class="fa-solid fa-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
                        </div>
                        <div class="form-text text-secondary ms-1 mt-2 small">Format didukung: PNG, JPG, WEBP. Akan menggantikan logo saat ini jika ada.</div>
                    </div>
                </div>

                <div class="setting-section">
                    <h6 class="fw-bold text-dark mb-4 fs-5 d-flex align-items-center" style="letter-spacing: -0.3px;">
                        <i class="fa-solid fa-money-bill-transfer me-2 text-success opacity-75"></i> Pakasir Payment Gateway
                    </h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">Project Slug (PAKASIR_PROJECT_SLUG)</label>
                            <input name="PAKASIR_PROJECT_SLUG" class="form-control custom-input-glass text-primary fw-semibold" value="{{ old('PAKASIR_PROJECT_SLUG', $values['PAKASIR_PROJECT_SLUG'] ?? '') }}" placeholder="Masukkan slug project...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">API Key (PAKASIR_API_KEY)</label>
                            <input type="password" name="PAKASIR_API_KEY" class="form-control custom-input-glass text-primary fw-semibold" value="{{ old('PAKASIR_API_KEY', $values['PAKASIR_API_KEY'] ?? '') }}" placeholder="************************">
                        </div>
                    </div>
                </div>

                <div class="setting-section mb-5">
                    <h6 class="fw-bold text-dark mb-4 fs-5 d-flex align-items-center" style="letter-spacing: -0.3px;">
                        <i class="fa-solid fa-envelope me-2 text-warning opacity-75"></i> Konfigurasi Email (SMTP)
                    </h6>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_MAILER</label>
                            <input name="MAIL_MAILER" class="form-control custom-input-glass" value="{{ old('MAIL_MAILER', $values['MAIL_MAILER'] ?? 'smtp') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_HOST</label>
                            <input name="MAIL_HOST" class="form-control custom-input-glass" value="{{ old('MAIL_HOST', $values['MAIL_HOST'] ?? 'smtp.gmail.com') }}" placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_PORT</label>
                            <input name="MAIL_PORT" type="number" class="form-control custom-input-glass" value="{{ old('MAIL_PORT', $values['MAIL_PORT'] ?? 587) }}" placeholder="587">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_USERNAME</label>
                            <input name="MAIL_USERNAME" class="form-control custom-input-glass" value="{{ old('MAIL_USERNAME', $values['MAIL_USERNAME'] ?? '') }}" placeholder="email@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_PASSWORD</label>
                            <input type="password" name="MAIL_PASSWORD" class="form-control custom-input-glass" value="{{ old('MAIL_PASSWORD', $values['MAIL_PASSWORD'] ?? '') }}" placeholder="********">
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_FROM_ADDRESS</label>
                            <input name="MAIL_FROM_ADDRESS" class="form-control custom-input-glass" value="{{ old('MAIL_FROM_ADDRESS', $values['MAIL_FROM_ADDRESS'] ?? '') }}" placeholder="noreply@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold ms-1">MAIL_FROM_NAME</label>
                            <input name="MAIL_FROM_NAME" class="form-control custom-input-glass" value="{{ old('MAIL_FROM_NAME', $values['MAIL_FROM_NAME'] ?? '${APP_NAME}') }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3">
                    <button type="submit" class="btn btn-custom-primary rounded-pill px-5 shadow-sm py-3 fs-6 fw-bold w-100 w-md-auto transition-smooth">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Semua Pengaturan
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
                if (livePreviewContainer) {
                    livePreviewContainer.classList.add('d-none');
                    livePreviewContainer.classList.remove('d-flex');
                }
                if (existingLogoContainer) {
                    existingLogoContainer.classList.remove('d-none');
                    existingLogoContainer.classList.add('d-flex');
                }
                return;
            }

            if (!file.type.match('image.*')) {
                alert('Harap pilih file gambar (JPG, PNG, WEBP).');
                fileInput.value = '';
                return;
            }

            try {
                const url = URL.createObjectURL(file);
                if (livePreviewImg) {
                    livePreviewImg.src = url;
                }
                if (livePreviewContainer) {
                    livePreviewContainer.classList.remove('d-none');
                    livePreviewContainer.classList.add('d-flex');
                }
                if (existingLogoContainer) {
                    existingLogoContainer.classList.add('d-none');
                    existingLogoContainer.classList.remove('d-flex');
                }
                if (clearFileBtn) clearFileBtn.style.display = 'flex';
            } catch (err) {
                // fallback to FileReader
                console.warn('createObjectURL failed, fallback to FileReader', err);
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (livePreviewImg) livePreviewImg.src = e.target.result;
                    if (livePreviewContainer) {
                        livePreviewContainer.classList.remove('d-none');
                        livePreviewContainer.classList.add('d-flex');
                    }
                    if (existingLogoContainer) {
                        existingLogoContainer.classList.add('d-none');
                        existingLogoContainer.classList.remove('d-flex');
                    }
                    if (clearFileBtn) clearFileBtn.style.display = 'flex';
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
                if (livePreviewContainer) {
                    livePreviewContainer.classList.add('d-none');
                    livePreviewContainer.classList.remove('d-flex');
                }
                if (existingLogoContainer) {
                    existingLogoContainer.classList.remove('d-none');
                    existingLogoContainer.classList.add('d-flex');
                }
                clearFileBtn.style.display = 'none';
            });
        }
    });
</script>
@endpush