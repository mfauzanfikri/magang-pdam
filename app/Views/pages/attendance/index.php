<?php helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('components/error-alert') ?>
<?= $this->include('components/success-alert') ?>

<div id="attendance-tabs" class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
      <li class="nav-item">
        <a href="#tab-unverified-attendance" class="nav-link active" data-bs-toggle="tab">
          Unverified
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-verified-attendance" class="nav-link" data-bs-toggle="tab">
          Verified
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-rejected-attendance" class="nav-link" data-bs-toggle="tab">
          Rejected
        </a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div id="table-loader" class="text-center my-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="tab-content d-none">
      <!-- unverified attendance tab -->
      <div class="tab-pane active show" id="tab-unverified-attendance">
        <div id="unverified-attendance-table-wrapper">
          <table id="unverified-attendance-table" class="table table-striped">
            <thead>
            <tr>
              <th>Name</th>
              <th>Date</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($attendanceByStatus['unverified'] as $attendance): ?>
              <tr>
                <td><?= $attendance['user']['name'] ?></td>
                <td><?= $attendance['date'] ?></td>
                <td><?= $attendance['check_in'] ?></td>
                <td><?= $attendance['check_out'] ?></td>
                <td>
                  <button class="btn-verification btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-attendance-verification"
                          data-row="<?= encode_row_data($attendance) ?>">
                    Verification
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- verified attendance tab -->
      <div class="tab-pane" id="tab-verified-attendance">
        <div id="verified-attendance-table-wrapper">
          <table id="verified-attendance-table" class="table table-striped">
            <thead>
            <tr>
              <th>Name</th>
              <th>Date</th>
              <th>Check In</th>
              <th>Check Out</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($attendanceByStatus['verified'] as $attendance): ?>
              <tr>
                <td><?= $attendance['user']['name'] ?></td>
                <td><?= $attendance['date'] ?></td>
                <td><?= $attendance['check_in'] ?></td>
                <td><?= $attendance['check_out'] ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- rejected attendance tab -->
      <div class="tab-pane" id="tab-rejected-attendance">
        <div id="rejected-attendance-table-wrapper">
          <table id="rejected-attendance-table" class="table table-striped">
            <thead>
            <tr>
              <th>Name</th>
              <th>Date</th>
              <th>Check In</th>
              <th>Check Out</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($attendanceByStatus['rejected'] as $attendance): ?>
              <tr>
                <td><?= $attendance['user']['name'] ?></td>
                <td><?= $attendance['date'] ?></td>
                <td><?= $attendance['check_in'] ?></td>
                <td><?= $attendance['check_out'] ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
    
    <?= $this->endSection() ?>
    
    <?= $this->section('modals') ?>
    
    <?= $this->include('pages/attendance/verification-modal') ?>
    
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js-libs') ?>
  <script src="/assets/libs/jquery/jquery.min.js"></script>
  <script src="/assets/libs/datatables/datatables.js"></script>
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js') ?>
  <script src="/assets/js/utils/row-data.js"></script>
  <script>
    const verificationModal = $('#modal-attendance-verification');

    function init() {
      $('.btn-verification').off('click').on('click', function() {
        const attendance = decodeRowData($(this).data('row'));

        verificationModal.find('form').attr('action', `/attendance/${attendance.id}/verification`);

        const detailsTable = $('#modal-attendance-verification table');

        // Set basic text fields
        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(attendance.user.name);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(attendance.user.email);
        detailsTable.find('tr:nth-child(4) td:nth-child(2)').text(attendance.date);
        detailsTable.find('tr:nth-child(5) td:nth-child(2)').text(attendance.check_in);
        detailsTable.find('tr:nth-child(6) td:nth-child(2)').text(attendance.check_out);
      });
    }

    $('#table-loader').removeClass('d-none');
    $('#attendance-tabs .tab-content').addClass('d-none');

    // unverified attendance table
    new DataTable('#unverified-attendance-table', {
      order: [],
      initComplete: function() {
        init();
      },
      drawCallback: function() {
        init();
      }
    });

    // verified attendance table
    new DataTable('#verified-attendance-table', {
      order: [],
      initComplete: function() {
        init();
      },
      drawCallback: function() {
        init();
      }
    });

    // rejected attendance table
    new DataTable('#rejected-attendance-table', {
      order: [],
      initComplete: function() {
        $('#table-loader').addClass('d-none');
        $('#attendance-tabs .tab-content').removeClass('d-none');

        init();
      },
      drawCallback: function() {
        init();
      }
    });
  </script>
    <?= $this->endSection() ?>
