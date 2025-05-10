<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form class="card" style="width: 36rem">
  <div class="card-header">
    <h1 class="card-title">Magang PDAM</h1>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <p>Please log in to access the system.</p>
    </div>
    <div class="mb-3">
      <label class="form-label required">Email address</label>
      <div>
        <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email" />
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label required">Password</label>
      <div>
        <input type="password" class="form-control" placeholder="Password" />
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-primary">Login</button>
  </div>
</form>
<?= $this->endSection() ?>
