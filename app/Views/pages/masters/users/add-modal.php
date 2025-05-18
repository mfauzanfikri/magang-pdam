<div class="modal modal-blur fade" id="modal-add-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pengguna Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="<?= base_url('/masters/users') ?>" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama</label>
            <input type="text" class="form-control" name="name" placeholder="Masukkan nama" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Masukkan email" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="role-select">Peran</label>
            <select id="role-select" class="form-select" name="role" required>
              <option value="" selected disabled hidden>Pilih peran</option>
              <option value="supervisor">Pembimbing</option>
              <option value="candidate">Kandidat</option>
              <option value="intern">Peserta Magang</option>
            </select>
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
            Tambah Pengguna
          </button>
        </div>
      </form>
    </div>
  </div>
</div>