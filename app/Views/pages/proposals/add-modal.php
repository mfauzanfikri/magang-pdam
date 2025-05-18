<div class="modal modal-blur fade" id="modal-add-proposal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Proposal Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="<?= base_url('/proposals') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required" for="add-proposal-title">Judul</label>
            <input id="add-proposal-title" type="text" class="form-control" name="title" placeholder="Masukkan judul" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-proposal-institution">Institusi</label>
            <input id="add-proposal-institution" type="text" class="form-control" name="institution" placeholder="Masukkan institusi" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-check">
              <input class="form-check-input" type="checkbox" name="is_group" />
              <span class="form-check-label">Magang Kelompok</span>
            </label>
          </div>
          <div id="members-wrapper" class="mb-3 d-none">
            <label class="form-label required" for="add-proposal-members">Email Terdaftar Anggota Kelompok</label>
            <input id="add-proposal-members" type="text" class="form-control" name="members" placeholder="Masukkan email anggota" autocomplete="off" />
            <small>Dipisahkan dengan titik koma (;), contoh: 'anggota1@mail.com;anggota2@mail.com'</small>
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-proposal-file">File</label>
            <input id="add-proposal-file" type="file" class="form-control" name="file" accept="application/pdf" required />
            <small>.pdf</small>
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
            Buat Proposal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>