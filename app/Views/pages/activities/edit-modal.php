<div class="modal modal-blur fade" id="modal-edit-activity" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit activity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required" for="add-activity-title">Title</label>
            <input id="add-activity-title" type="text" class="form-control" name="title" placeholder="Enter title"
                   maxlength="50" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-description">Description</label>
            <textarea id="add-activity-description" class="form-control" name="description"
                      placeholder="Enter description" maxlength="300" rows="5" autocomplete="off" required></textarea>
            <small>max 300 chars</small>
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-start-date">Start Date</label>
            <input id="add-activity-start-date" type="date" class="form-control" name="start_date"
                   placeholder="Enter start date" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="add-activity-end-date">End Date</label>
            <input id="add-activity-end-date" type="date" class="form-control" name="end_date"
                   placeholder="Enter end date" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label" for="add-activity-photo">Photo File</label>
            <input id="add-activity-photo" type="file" class="form-control" name="photo_file" accept="image/png, image/jpeg, image/jpg" />
            <small>max 2MB</small>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Cancel</button>
          <button type="submit" class="btn btn-primary btn-5 ms-auto">
            Edit activity
          </button>
        </div>
      </form>
    </div>
  </div>
</div>