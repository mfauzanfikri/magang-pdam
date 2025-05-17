<?php

use App\Libraries\AuthUser;

?>
<div class="modal modal-blur fade" id="modal-add-activity" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New activity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('/activities') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
        
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
            <label class="form-label required" for="add-activity-photo">Photo File</label>
            <input id="add-activity-photo" type="file" class="form-control" name="photo_file" accept="image/png, image/jpeg, image/jpg" required />
            <small>max 2MB</small>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Cancel</button>
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
            Create new activity
          </button>
        </div>
      </form>
    </div>
  </div>
</div>