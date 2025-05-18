<div class="modal modal-blur fade" id="modal-change-password" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ubah Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="<?= base_url('/change-password') ?>" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required" for="password">Password Saat Ini</label>
            <input id="password" name="password" type="password" class="form-control" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="new-password">Password Baru</label>
            <input id="new-password" name="new_password" type="password" class="form-control" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="confirm-password">Konfirmasi Password Baru</label>
            <input id="confirm-password" name="confirm_password" type="password" class="form-control" required />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-5 ms-auto">
            <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
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
              class="icon icon-2"
            >
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>