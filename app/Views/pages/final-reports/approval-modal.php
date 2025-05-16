<div class="modal modal-blur fade" id="modal-final-report-approval" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Final Report Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th colspan="2">Final Report Detail</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>Title</td>
              <td></td>
            </tr>
            <tr>
              <td>Institution</td>
              <td></td>
            </tr>
            <tr>
              <td>Leader Name</td>
              <td></td>
            </tr>
            <tr class="d-none">
              <td>Members</td>
              <td></td>
            </tr>
            <tr>
              <td>Final Report File</td>
              <td>
              </td>
            </tr>
            </tbody>
          </table>
          
          <div class="mb-3">
            <label class="form-label" for="approval">Approval</label>
            <select id="approval" class="form-select" name="approval" required>
              <option value="" selected disabled hidden>Select approval</option>
              <option value="approved">Approve</option>
              <option value="rejected">Reject</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="notes">Notes (Optional)</label>
            <textarea id="notes" class="form-control" name="notes" rows="5" placeholder="Enter approval notes"></textarea>
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