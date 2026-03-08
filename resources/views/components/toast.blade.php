<style>
    .custom-toast-wrapper {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 1);
        border-radius: 20px;
        box-shadow: 0 15px 45px rgba(0, 86, 179, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .toast-container{
        position: fixed;
        right: 0 !important;
        bottom: 7% !important;
    }
    .toast-icon-box {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        flex-shrink: 0;
    }
    
    .toast-success-box {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .toast-error-box {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .toast-text-glass { 
        color: #1e293b; 
        font-size: 0.95rem;
        letter-spacing: -0.2px;
    }

    @media (min-width: 575.98px) {
        .toast-container {
            right: 0;
            bottom: 0 !important;
        }
    }
</style>

<div class="toast-container p-3 p-md-4 mt-2 mt-md-0" style="z-index: 1090;">
    
    @if(session('success'))
    <div class="toast custom-toast-wrapper border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex align-items-center p-3 gap-3">
            <div class="toast-icon-box toast-success-box">
                <i class="fa-solid fa-check fs-5"></i>
            </div>
            <div class="toast-body p-0 m-0 fw-semibold flex-grow-1 toast-text-glass lh-sm">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close shadow-none opacity-50 flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast custom-toast-wrapper border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex align-items-center p-3 gap-3">
            <div class="toast-icon-box toast-error-box">
                <i class="fa-solid fa-exclamation fs-5"></i>
            </div>
            <div class="toast-body p-0 m-0 fw-semibold flex-grow-1 toast-text-glass lh-sm">
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close shadow-none opacity-50 flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

</div>