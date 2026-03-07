<style>
    /* Styling khusus Toast Glassmorphism */
    .custom-toast {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 86, 179, 0.15);
    }
    .toast-icon-success { color: #10b981; } /* Hijau pastel modern */
    .toast-icon-error { color: #ef4444; }   /* Merah pastel modern */
    .toast-text-glass { color: rgba(0, 50, 120, 0.8); }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
    
    @if(session('success'))
    <div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex align-items-center p-2">
            <div class="toast-body d-flex align-items-center fw-medium flex-grow-1 toast-text-glass">
                <i class="fa-solid fa-circle-check fs-4 me-3 toast-icon-success"></i>
                <span class="lh-sm">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex align-items-center p-2">
            <div class="toast-body d-flex align-items-center fw-medium flex-grow-1 toast-text-glass">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3 toast-icon-error"></i>
                <span class="lh-sm">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto opacity-75" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

</div>