<div class="modal modal-blur fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit user</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
          <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name" autocomplete="off" />
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" autocomplete="off" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="role-select">Role</label>
            <select id="role-select" class="form-select" name="role">
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
            Edit user
          </button>
        </div>
      </form>
    </div>
  </div>
</div>