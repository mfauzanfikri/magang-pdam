<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form class="card" style="width: 36rem" action="<?= base_url('/register') ?>" method="post">
  <div class="card-header">
    <h1 class="card-title">Magang PDAM</h1>
  </div>
  <div class="card-body">
      <?= $this->include('components/error-alert') ?>
    
    <div class="mb-3">
      <p>Daftar untuk mengajukan magang.</p>
    </div>
    <div class="mb-3">
      <label class="form-label required" for="login-name">Nama</label>
      <div>
        <input id="login-name" name="name" type="text" class="form-control" aria-describedby="nameHelp"
               placeholder="Masukkan nama" autocomplete="off" required />
      </div>
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
    <button type="submit" class="btn btn-primary">Daftar</button>
  </div>
</form>
<?= $this->endSection() ?>
