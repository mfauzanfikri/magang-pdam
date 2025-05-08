<?php if(session()->has('errors')): ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
    <div class="alert-icon">
      <!-- SVG Icon -->
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
        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
        <path d="M12 8v4" />
        <path d="M12 16h.01" />
      </svg>
    </div>
    <div>
        <?php if(is_array(session('errors'))): ?>
          <strong>There were some errors:</strong>
          <ul class="mb-0 mt-1">
              <?php foreach(session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
              <?php endforeach; ?>
          </ul>
        <?php else: ?>
            <?= esc(session('errors')) ?>
        <?php endif ?>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
<?php endif; ?>
