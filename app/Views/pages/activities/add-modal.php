<?php

use App\Libraries\AuthUser;

?>
<div class="modal modal-blur fade" id="modal-add-activity" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kegiatan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="<?= base_url('/activities') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
        
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
            <label class="form-label required" for="add-activity-photo">Unggah Foto</label>
            <input id="add-activity-photo" type="file" class="form-control" name="photo_file" accept="image/png, image/jpeg, image/jpg" required />
            <small>Maksimal 2MB</small>
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
            Tambah Kegiatan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
