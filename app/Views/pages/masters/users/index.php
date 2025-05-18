<?php helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('title-actions') ?>
<button class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-user">
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
  Tambah user
</button>
<button
  class="btn btn-primary btn-6 d-sm-none btn-icon"
  data-bs-toggle="modal"
  data-bs-target="#modal-add-user"
  aria-label="Create new user"
>
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
</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('components/error-alert') ?>
<?= $this->include('components/success-alert') ?>

<div class="card">
  <div class="card-body">
    <div id="table-loader" class="text-center my-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Memuat...</span>
      </div>
    </div>
    <div id="users-table-wrapper" class="d-none">
      <table id="users-table" class="table table-striped">
        <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user): ?>
            <?php if($user['role'] === 'admin') continue ?>
          <tr>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
              <button class="btn-edit btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                      data-bs-target="#modal-edit-user"
                      data-row="<?= encode_row_data($user) ?>">
                Ubah
              </button>
              <button class="btn-delete btn btn-danger btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                      data-bs-target="#modal-delete-user"
                      data-id="<?= $user['id'] ?>">
                Hapus
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<?= $this->include('pages/masters/users/add-modal') ?>
<?= $this->include('pages/masters/users/edit-modal') ?>
<?= $this->include('pages/masters/users/delete-modal') ?>

<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/datatables/datatables.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<script src="/assets/js/utils/row-data.js"></script>
<script>
  const editModal = $('#modal-edit-user');
  const deleteModal = $('#modal-delete-user');

  function init() {
    $('.btn-edit').off('click').on('click', function() {
      const user = decodeRowData($(this).data('row'));
      editModal.find('form').attr('action', `/masters/users/${user.id}`)
      
      editModal.find('input[name=name]').val(user.name);
      editModal.find('input[name=email]').val(user.email);
      editModal.find('select[name=role]').val(user.role);
    });

    $('.btn-delete').off('click').on('click', function() {
      const id = $(this).data('id');
      deleteModal.find('form').attr('action', `/masters/users/${id}`)
    });
  }

  $('#table-loader').removeClass('d-none');
  $('#users-table-wrapper').addClass('d-none');

  const table = new DataTable('#users-table', {
    order: [],
    columnDefs: [
      {
        targets: -1,
        orderable: false,
        searchable: false
      }
    ],
    initComplete: function() {
      $('#table-loader').addClass('d-none');
      $('#users-table-wrapper').removeClass('d-none');

      init();
    },
    drawCallback: function() {
      init();
    }
  });
</script>
<?= $this->endSection() ?>
