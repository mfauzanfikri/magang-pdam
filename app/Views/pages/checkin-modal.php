<div class="modal modal-blur fade" id="modal-check-in" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Check in presensi untuk hari ini</div>
        <div>Apakah Anda yakin untuk check in presensi sekarang?</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <form action="<?= base_url('/attendance/check-in') ?>" method="post">
            <?= csrf_field() ?>
          
          <button type="submit" class="btn btn-danger">Ya</button>
        </form>
      </div>
    </div>
  </div>
</div>