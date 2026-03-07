<style>
    /* Styling Tombol Batal Glassmorphism */
    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(0, 86, 179, 0.2);
        color: rgba(0, 50, 120, 0.8);
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-glass-cancel:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(0, 86, 179, 0.4);
        color: #0056b3;
    }
</style>

@push('modals')
<style>
    /* Make modal full-width on small screens but keep height constrained */
    .modal-fullwidth-sm-down {
        max-width: 720px;
        margin: 1.5rem auto;
    }

    @media (max-width: 575.98px) {
        .modal-fullwidth-sm-down {
            max-width: calc(100% - 32px);
            margin: 12px auto;
        }
        .modal-fullwidth-sm-down .modal-content {
            max-height: calc(90vh);
            overflow-y: auto;
            border-radius: 12px;
        }
    }
</style>

<div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullwidth-sm-down">
        <div class="modal-content glass-card border-0 shadow-lg">
            
            <div class="modal-header border-0 pb-0 align-items-center mt-2 mx-2">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-circle-question fs-4"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-gradient-blue mb-0" id="customModalTitle">Konfirmasi</h5>
                </div>
                <button type="button" class="btn-close opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body text-glass-blue pt-4 pb-4 px-4 ms-2" id="customModalBody" style="font-size: 0.95rem; line-height: 1.6;">
                Apakah Anda yakin ingin melakukan tindakan ini?
            </div>
            
            <div class="modal-footer border-0 pt-0 pb-3 px-4">
                <button type="button" class="btn btn-glass-cancel rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-custom-primary rounded-pill px-4 shadow-sm" id="customModalConfirmBtn">
                    Ya, Lanjutkan
                </button>
            </div>
            
        </div>
    </div>
</div>
@endpush