<?php helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('components/error-alert') ?>
<?= $this->include('components/success-alert') ?>

<div id="final-report-tabs" class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
      <li class="nav-item">
        <a href="#tab-pending-final-reports" class="nav-link active" data-bs-toggle="tab">
          Pending
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-approved-final-reports" class="nav-link" data-bs-toggle="tab">
          Approved
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-rejected-final-reports" class="nav-link" data-bs-toggle="tab">
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
      <!-- pending final reports tab -->
      <div class="tab-pane active show" id="tab-pending-final-reports">
        <div id="pending-final-reports-table-wrapper">
          <table id="pending-final-reports-table" class="table table-striped">
            <thead>
            <tr>
              <th>Title</th>
              <th>Institution</th>
              <th>Name</th>
              <th>Group</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($finalReportsByStatus['pending'] as $finalReport): ?>
              <tr>
                <td><?= $finalReport['title'] ?></td>
                <td><?= $finalReport['proposal']['institution'] ?></td>
                <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                <td><?= $finalReport['proposal']['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-approval btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-final-report-approval"
                          data-row="<?= encode_row_data($finalReport) ?>">
                    Approval
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- approved final report tab -->
      <div class="tab-pane" id="tab-approved-final-reports">
        <div id="approved-final-reports-table-wrapper">
          <table id="approved-final-reports-table" class="table table-striped">
            <thead>
            <tr>
              <th>Title</th>
              <th>Institution</th>
              <th>Name</th>
              <th>Group</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($finalReportsByStatus['approved'] as $finalReport): ?>
              <tr>
                <td><?= $finalReport['title'] ?></td>
                <td><?= $finalReport['proposal']['institution'] ?></td>
                <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                <td><?= $finalReport['proposal']['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-final-report-detail"
                          data-row="<?= encode_row_data($finalReport) ?>">
                    Detail
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- rejected final report tab -->
      <div class="tab-pane" id="tab-rejected-final-reports">
        <div id="rejected-final-reports-table-wrapper">
          <table id="rejected-final-reports-table" class="table table-striped">
            <thead>
            <tr>
              <th>Title</th>
              <th>Institution</th>
              <th>Name</th>
              <th>Group</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($finalReportsByStatus['rejected'] as $finalReport): ?>
              <tr>
                <td><?= $finalReport['title'] ?></td>
                <td><?= $finalReport['proposal']['institution'] ?></td>
                <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                <td><?= $finalReport['proposal']['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-final-report-detail"
                          data-row="<?= encode_row_data($finalReport) ?>">
                    Detail
                  </button>
                </td>
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
    
    <?= $this->include('pages/final-reports/approval-modal') ?>
    <?= $this->include('pages/final-reports/detail-modal') ?>
    
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js-libs') ?>
  <script src="/assets/libs/jquery/jquery.min.js"></script>
  <script src="/assets/libs/datatables/datatables.js"></script>
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js') ?>
  <script src="/assets/js/utils/row-data.js"></script>
  <script>
    const approvalModal = $('#modal-final-report-approval');

    function init() {
      $('.btn-approval').off('click').on('click', function () {
        const finalReport = decodeRowData($(this).data('row'));

        approvalModal.find('form').attr('action', `/final-reports/${finalReport.id}/approval`);

        const detailsTable = $('#modal-final-report-approval table');

        // Set basic fields from final report and nested proposal
        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(finalReport.title); // final report title
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(finalReport.proposal.institution);

        if (finalReport.proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(
            finalReport.proposal.leader.name + ' / ' + finalReport.proposal.leader.email
          );

          // Members list
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          if (finalReport.proposal.members && finalReport.proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            finalReport.proposal.members.forEach(member => {
              ol.append(`<li>${member.name} / ${member.email}</li>`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(
            finalReport.proposal.leader.name + ' / ' + finalReport.proposal.leader.email
          );
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/final-reports/${finalReport.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });

      $('.btn-detail').off('click').on('click', function () {
        const finalReport = decodeRowData($(this).data('row'));

        const detailsTable = $('#modal-final-report-detail table');

        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(finalReport.title);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(finalReport.proposal.institution);

        if (finalReport.proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(
            finalReport.proposal.leader.name + ' / ' + finalReport.proposal.leader.email
          );

          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          if (finalReport.proposal.members && finalReport.proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            finalReport.proposal.members.forEach(member => {
              ol.append(`<li>${member.name} / ${member.email}</li>`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(
            finalReport.proposal.leader.name + ' / ' + finalReport.proposal.leader.email
          );
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/final-reports/${finalReport.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });
    }

    $('#table-loader').removeClass('d-none');
    $('#final-report-tabs .tab-content').addClass('d-none');

    // pending final reports table
    new DataTable('#pending-final-reports-table', {
      order: [],
      columnDefs: [
        {
          targets: -1,
          orderable: false,
          searchable: false
        }
      ],
      initComplete: function() {
        init();
      },
      drawCallback: function() {
        init();
      }
    });

    // approved final reports table
    new DataTable('#approved-final-reports-table', {
      order: [],
      columnDefs: [
        {
          targets: -1,
          orderable: false,
          searchable: false
        }
      ],
      initComplete: function() {
        init();
      },
      drawCallback: function() {
        init();
      }
    });

    // rejected final reports table
    new DataTable('#rejected-final-reports-table', {
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
        $('#final-report-tabs .tab-content').removeClass('d-none');

        init();
      },
      drawCallback: function() {
        init();
      }
    });
  </script>
    <?= $this->endSection() ?>
