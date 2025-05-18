<?php

use App\Libraries\AuthUser;

?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?= $this->include('components/success-alert') ?>
<?= $this->include('components/error-alert') ?>

<div class="card">
  <div class="card-body">
    <h2 class="mb-4">Akun Saya</h2>
    <h3 class="card-title">Detail Profil</h3>
    <div class="row g-3">
      <div class="col-4">
        <h3 class="card-title">Nama</h3>
        <input type="text" class="form-control" value="<?= AuthUser::name() ?>" readonly />
      </div>
    </div>
    <h3 class="card-title mt-4">Email</h3>
    <div>
      <div class="row g-2">
        <div class="col-4">
          <input type="text" class="form-control" value="<?= AuthUser::email() ?>" readonly />
        </div>
      </div>
    </div>
    <h3 class="card-title mt-4">Password</h3>
    <div>
      <button class="btn btn-1" data-bs-toggle="modal" data-bs-target="#modal-change-password">Atur password baru</button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->include('pages/profile/change-password-modal') ?>
<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<?= $this->endSection() ?>
