<div class="modal modal-blur fade" id="modal-check-out" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Check out absesi untuk hari ini</div>
        <div>Apakah Anda yakin untuk check out presensi sekarang?</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <form action="<?= base_url('/attendance/check-out') ?>" method="post">
            <?= csrf_field() ?>
          
          <button type="submit" class="btn btn-danger">Ya</button>
        </form>
      </div>
    </div>
  </div>
</div>