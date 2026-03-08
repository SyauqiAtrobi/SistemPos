<style>
    .btn-glass-cancel {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 86, 179, 0.15);
        color: #64748b;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .btn-glass-cancel:hover {
        background: rgba(241, 245, 249, 0.9);
        border-color: rgba(0, 86, 179, 0.3);
        color: #334155;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 86, 179, 0.05);
    }
</style>

@push('modals')
<style>
    .modal-glass-content {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 86, 179, 0.12);
    }
    
    .icon-circle-confirm {
        width: 54px;
        height: 54px;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.04) 100%);
        color: var(--primary-blue, #0056b3);
        border: 1px solid rgba(0, 123, 255, 0.15);
        box-shadow: 0 8px 20px rgba(0, 86, 179, 0.06);
    }

    .modal-fullwidth-sm-down {
        max-width: 460px;
        margin: 1.5rem auto;
    }

    @media (max-width: 575.98px) {
        .modal-fullwidth-sm-down {
            max-width: calc(100% - 32px);
            margin: auto;
            display: flex;
            align-items: center;
            min-height: 100%;
        }
        .modal-fullwidth-sm-down .modal-content {
            border-radius: 20px;
        }
    }
</style>

<div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullwidth-sm-down">
        <div class="modal-content modal-glass-content border-0">
            
            <div class="modal-header border-0 pb-0 pt-4 px-4 align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle icon-circle-confirm flex-shrink-0">
                        <i class="fa-solid fa-circle-question fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0 fs-5" id="customModalTitle" style="letter-spacing: -0.3px;">Konfirmasi</h5>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none mt-1 opacity-50" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body px-4 pt-3 pb-4" id="customModalBody" style="color: #475569; font-size: 0.95rem; line-height: 1.6;">
                Apakah Anda yakin ingin melakukan tindakan ini?
            </div>
            
            <div class="modal-footer border-0 pt-0 pb-4 px-4 gap-2 flex-nowrap">
                <button type="button" class="btn btn-glass-cancel rounded-pill w-50 py-2 m-0" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-custom-primary rounded-pill w-50 py-2 m-0 shadow-sm" id="customModalConfirmBtn">
                    Ya, Lanjutkan
                </button>
            </div>
            
        </div>
    </div>
</div>
@endpush