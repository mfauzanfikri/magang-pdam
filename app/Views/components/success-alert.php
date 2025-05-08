<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <div class="alert-icon">
            <!-- SVG icon -->
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="icon alert-icon icon-2"
            >
                <path d="M5 12l5 5l10 -10" />
            </svg>
        </div>
        <?= esc(session('message')) ?>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>
