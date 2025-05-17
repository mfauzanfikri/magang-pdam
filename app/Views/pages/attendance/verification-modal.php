<div class="modal modal-blur fade" id="modal-attendance-verification" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Attendance Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th colspan="2">Attendance Detail</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>Name</td>
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
              <td>Date</td>
              <td></td>
            </tr>
            <tr>
              <td>Check In</td>
              <td></td>
            </tr>
            <tr>
              <td>Check Out</td>
              <td></td>
            </tr>
            </tbody>
          </table>
          
          <div class="mb-3">
            <label class="form-label" for="verification">Verification*</label>
            <select id="verification" class="form-select" name="verification" required>
              <option value="" selected disabled hidden>Select verification</option>
              <option value="verified">Verify</option>
              <option value="rejected">Reject</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="notes">Notes (Optional)</label>
            <textarea id="notes" class="form-control" name="notes" rows="5" placeholder="Enter verification notes"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-5 ms-auto">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>