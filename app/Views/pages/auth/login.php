<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form class="card" style="width: 36rem" action="<?= base_url('/login') ?>" method="post">
  <div class="card-header">
    <h1 class="card-title">Magang PDAM</h1>
  </div>
  <div class="card-body">
    <?= $this->include('components/error-alert') ?>
    
    <div class="mb-3">
      <p>Please log in to access the system.</p>
    </div>
    <div class="mb-3">
      <label class="form-label required" for="login-email">Email address</label>
      <div>
        <input id="login-email" name="email" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email" required />
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label required" for="login-password">Password</label>
      <div>
        <input id="login-password" name="password" type="password" class="form-control" placeholder="Password" required />
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-primary">Login</button>
  </div>
</form>
<?= $this->endSection() ?>
