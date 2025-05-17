<div class="modal modal-blur fade" id="modal-add-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New user</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('/masters/users') ?>" method="post">
          <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name" autocomplete="off" required />
          </div>
          <div class="mb-3">
            <label class="form-label required">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" autocomplete="off"
                   required />
          </div>
          <div class="mb-3">
            <label class="form-label required" for="role-select">Role</label>
            <select id="role-select" class="form-select" name="role" required>
              <option value="" selected disabled hidden>Select role</option>
              <option value="supervisor">Supervisor</option>
              <option value="candidate">Candidate</option>
              <option value="intern">Intern</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Cancel</button>
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
            Create new user
          </button>
        </div>
      </form>
    </div>
  </div>
</div>