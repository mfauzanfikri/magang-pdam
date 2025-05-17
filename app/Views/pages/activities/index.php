<?php helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('title-actions') ?>
<button class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
        data-bs-target="#modal-add-activity">
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
<button
  class="btn btn-primary btn-6 d-sm-none btn-icon"
  data-bs-toggle="modal"
  data-bs-target="#modal-add-activity"
  aria-label="Create new activity"
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
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div id="activities-table-wrapper" class="d-none">
      <table id="activities-table" class="table table-striped">
        <thead>
        <tr>
          <th>Name</th>
          <th>Title</th>
          <th>Description</th>
          <th>Date</th>
          <th style="max-width: 200px; min-width: 100px">Photo</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($activities as $activity): ?>
            <?php
            $id = $activity['id'];
            ?>
          <tr>
            <td><?= $activity['user']['name'] ?></td>
            <td><?= $activity['title'] ?></td>
            <td><?= $activity['description'] ?></td>
            <td><?= $activity['start_date'] ?> - <?= $activity['end_date'] ?></td>
            <td style="max-width: 200px; min-width: 100px">
              <img class="img-fluid rounded"
                   style="max-width: 100%; height: auto;"
                   src="<?= base_url("/activities/$id/file") ?>"
                   alt="Activity Image">
            </td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn-edit btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#modal-edit-activity"
                        data-row="<?= encode_row_data($activity) ?>">
                  Edit
                </button>
                <button class="btn-delete btn btn-danger btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#modal-delete-activity"
                        data-id="<?= $id ?>">
                  Delete
                </button>
              </div>
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

<?= $this->include('pages/activities/add-modal') ?>
<?= $this->include('pages/activities/edit-modal') ?>
<?= $this->include('pages/activities/delete-modal') ?>

<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/datatables/datatables.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<script src="/assets/js/utils/row-data.js"></script>
<script>
  const editModal = $('#modal-edit-activity');
  const deleteModal = $('#modal-delete-activity');

  function init() {
    $('.btn-edit').off('click').on('click', function() {
      const activity = decodeRowData($(this).data('row'));
      console.log(activity);
      editModal.find('form').attr('action', `/activities/${activity.id}`);
      
      editModal.find('input[name=title]').val(activity.title);
      editModal.find('textarea[name=description]').html(activity.description);
      editModal.find('input[name=start_date]').val(activity.start_date);
      editModal.find('input[name=end_date]').val(activity.end_date);
    });

    $('.btn-delete').off('click').on('click', function() {
      const id = $(this).data('id');
      deleteModal.find('form').attr('action', `/activities/${id}`);
    });
  }

  $('#table-loader').removeClass('d-none');
  $('#activities-table-wrapper').addClass('d-none');

  const table = new DataTable('#activities-table', {
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
      $('#activities-table-wrapper').removeClass('d-none');

      init();
    },
    drawCallback: function() {
      init();
    }
  });
</script>
<?= $this->endSection() ?>
