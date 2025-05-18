<div class="modal modal-blur fade" id="modal-proposal-approval" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Persetujuan Proposal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th colspan="2">Detail Proposal</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>Judul</td>
              <td></td>
            </tr>
            <tr>
              <td>Institusi</td>
              <td></td>
            </tr>
            <tr>
              <td>Nama Ketua</td>
              <td></td>
            </tr>
            <tr class="d-none">
              <td>Anggota</td>
              <td></td>
            </tr>
            <tr>
              <td>File Proposal</td>
              <td></td>
            </tr>
            </tbody>
          </table>
          
          <div class="mb-3">
            <label class="form-label" for="approval">Persetujuan*</label>
            <select id="approval" class="form-select" name="approval" required>
              <option value="" selected disabled hidden>Pilih keputusan</option>
              <option value="approved">Setujui</option>
              <option value="rejected">Tolak</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="notes">Catatan (Opsional)</label>
            <textarea id="notes" class="form-control" name="notes" rows="5" placeholder="Masukkan catatan persetujuan"></textarea>
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