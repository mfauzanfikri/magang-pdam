<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form class="card" style="width: 36rem" action="<?= base_url('/login') ?>" method="post">
  <div class="card-header">
    <h1 class="card-title">Magang PDAM</h1>
  </div>
  <div class="card-body">
      <?= $this->include('components/success-alert') ?>
      <?= $this->include('components/error-alert') ?>
    
    <div class="mb-3">
      <p>Silakan masuk untuk mengakses sistem.</p>
    </div>
    <div class="mb-3">
      <label class="form-label required" for="login-email">Email</label>
      <div>
        <input id="login-email" name="email" type="email" class="form-control" aria-describedby="emailHelp"
               placeholder="Masukkan email" autocomplete="off" required />
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label required" for="login-password">Kata Sandi</label>
      <div>
        <input id="login-password" name="password" type="password" class="form-control" placeholder="Masukkan kata sandi"
               required />
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-primary">Masuk</button>
  </div>
</form>
<?= $this->endSection() ?>
