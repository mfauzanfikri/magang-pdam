<div class="modal modal-blur fade" id="modal-edit-activity" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ubah Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required" for="add-activity-title">Judul</label>
            <input id="add-activity-title" type="text" class="form-control" name="title" placeholder="Masukkan judul"
                   maxlength="50" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-description">Deskripsi</label>
            <textarea id="add-activity-description" class="form-control" name="description"
                      placeholder="Masukkan deskripsi" maxlength="300" rows="5" autocomplete="off" required></textarea>
            <small>Maksimal 300 karakter</small>
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-start-date">Tanggal Mulai</label>
            <input id="add-activity-start-date" type="date" class="form-control" name="start_date"
                   placeholder="Pilih tanggal mulai" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-end-date">Tanggal Selesai</label>
            <input id="add-activity-end-date" type="date" class="form-control" name="end_date"
                   placeholder="Pilih tanggal selesai" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label" for="add-activity-photo">Unggah Foto (Opsional)</label>
            <input id="add-activity-photo" type="file" class="form-control" name="photo_file" accept="image/png, image/jpeg, image/jpg" />
            <small>Maksimal 2MB</small>
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
