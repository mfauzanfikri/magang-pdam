<?php helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('components/error-alert') ?>
<?= $this->include('components/success-alert') ?>

<div id="proposal-tabs" class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
      <li class="nav-item">
        <a href="#tab-pending-proposals" class="nav-link active" data-bs-toggle="tab">
          Pending
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-approved-proposals" class="nav-link" data-bs-toggle="tab">
          Approved
        </a>
      </li>
      <li class="nav-item">
        <a href="#tab-rejected-proposals" class="nav-link" data-bs-toggle="tab">
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
      <!-- pending proposals tab -->
      <div class="tab-pane active show" id="tab-pending-proposals">
        <div id="pending-proposals-table-wrapper">
          <table id="pending-proposals-table" class="table table-striped">
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
            <?php foreach($proposalsByStatus['pending'] as $proposal): ?>
              <tr>
                <td><?= $proposal['title'] ?></td>
                <td><?= $proposal['institution'] ?></td>
                <td><?= $proposal['leader']['name'] ?></td>
                <td><?= $proposal['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-approval btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-proposal-approval"
                          data-row="<?= encode_row_data($proposal) ?>">
                    Approval
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- approved proposal tab -->
      <div class="tab-pane" id="tab-approved-proposals">
        <div id="approved-proposals-table-wrapper">
          <table id="approved-proposals-table" class="table table-striped">
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
            <?php foreach($proposalsByStatus['approved'] as $proposal): ?>
              <tr>
                <td><?= $proposal['title'] ?></td>
                <td><?= $proposal['institution'] ?></td>
                <td><?= $proposal['leader']['name'] ?></td>
                <td><?= $proposal['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-proposal-detail"
                          data-row="<?= encode_row_data($proposal) ?>">
                    Detail
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- rejected proposal tab -->
      <div class="tab-pane" id="tab-rejected-proposals">
        <div id="rejected-proposals-table-wrapper">
          <table id="rejected-proposals-table" class="table table-striped">
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
            <?php foreach($proposalsByStatus['rejected'] as $proposal): ?>
              <tr>
                <td><?= $proposal['title'] ?></td>
                <td><?= $proposal['institution'] ?></td>
                <td><?= $proposal['leader']['name'] ?></td>
                <td><?= $proposal['is_group'] ? 'Yes' : 'No' ?></td>
                <td>
                  <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-proposal-detail"
                          data-row="<?= encode_row_data($proposal) ?>">
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
    
    <?= $this->include('pages/proposals/approval-modal') ?>
    <?= $this->include('pages/proposals/detail-modal') ?>
    
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js-libs') ?>
  <script src="/assets/libs/jquery/jquery.min.js"></script>
  <script src="/assets/libs/datatables/datatables.js"></script>
    <?= $this->endSection() ?>
    
    <?= $this->section('page-js') ?>
  <script src="/assets/js/utils/row-data.js"></script>
  <script>
    const approvalModal = $('#modal-proposal-approval');

    function init() {
      $('.btn-approval').off('click').on('click', function() {
        const proposal = decodeRowData($(this).data('row'));
        
        approvalModal.find('form').attr('action', `/proposals/${proposal.id}/approval`);

        const detailsTable = $('#modal-proposal-approval table');

        // Set basic text fields
        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(proposal.title);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(proposal.institution);

        if(proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(proposal.leader.name + ' / ' + proposal.leader.email);

          // Set members as <ol>
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          if(proposal.members && proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            proposal.members.forEach(member => {
              ol.append(`<li>${member.name + ' / ' + member.email}</li>`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(proposal.leader.name + ' / ' + proposal.leader.email);
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        // Set proposal file link
        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/proposals/${proposal.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });

      $('.btn-detail').off('click').on('click', function() {
        const proposal = decodeRowData($(this).data('row'));

        const detailsTable = $('#modal-proposal-detail table');

        // Set basic text fields
        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(proposal.title);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(proposal.institution);

        if(proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(proposal.leader.name + ' / ' + proposal.leader.email);

          // Set members as <ol>
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          if(proposal.members && proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            proposal.members.forEach(member => {
              ol.append(`<li>${member.name + ' / ' + member.email}</li>`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(proposal.leader.name + ' / ' + proposal.leader.email);
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        // Set proposal file link
        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/proposals/${proposal.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });
    }

    $('#table-loader').removeClass('d-none');
    $('#proposal-tabs .tab-content').addClass('d-none');

    // pending proposals table
    new DataTable('#pending-proposals-table', {
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

    // approved proposals table
    new DataTable('#approved-proposals-table', {
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

    // rejected proposals table
    new DataTable('#rejected-proposals-table', {
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
        $('#proposal-tabs .tab-content').removeClass('d-none');

        init();
      },
      drawCallback: function() {
        init();
      }
    });
  </script>
    <?= $this->endSection() ?>
