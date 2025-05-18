<div class="modal modal-blur fade" id="modal-check-out" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Check Out for Today</div>
        <div>Are you sure you want to check out right now?</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <form action="<?= base_url('/attendance/check-out') ?>" method="post">
            <?= csrf_field() ?>
          
          <button type="submit" class="btn btn-danger">Yes</button>
        </form>
      </div>
    </div>
  </div>
</div>