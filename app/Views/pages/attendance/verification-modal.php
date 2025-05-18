<div class="modal modal-blur fade" id="modal-attendance-verification" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verifikasi Presensi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th colspan="2">Detail Presensi</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>Nama</td>
              <td></td>
            </tr>
            <tr>
              <td>Email</td>
              <td></td>
            </tr>
            <tr>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td></td>
            </tr>
            <tr>
              <td>Masuk</td>
              <td></td>
            </tr>
            <tr>
              <td>Pulang</td>
              <td></td>
            </tr>
            </tbody>
          </table>
          
          <div class="mb-3">
            <label class="form-label required" for="verification">Verifikasi</label>
            <select id="verification" class="form-select" name="verification" required>
              <option value="" selected disabled hidden>Pilih status verifikasi</option>
              <option value="verified">Setujui</option>
              <option value="rejected">Tolak</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="notes">Catatan (Opsional)</label>
            <textarea id="notes" class="form-control" name="notes" rows="5" placeholder="Masukkan catatan verifikasi"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-5 ms-auto">
            Kirim
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
