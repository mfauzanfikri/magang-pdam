<div class="modal modal-blur fade" id="modal-check-in" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Check In for Today</div>
        <div>Are you sure you want to check in right now?</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <form action="<?= base_url('/attendance/check-in') ?>" method="post">
            <?= csrf_field() ?>
          
          <button type="submit" class="btn btn-danger">Yes</button>
        </form>
      </div>
    </div>
  </div>
</div>