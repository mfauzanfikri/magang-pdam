<div class="modal modal-blur fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ubah Data Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" placeholder="Masukkan nama" autocomplete="off" />
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Masukkan email" autocomplete="off" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="role-select">Peran</label>
            <select id="role-select" class="form-select" name="role">
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
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>