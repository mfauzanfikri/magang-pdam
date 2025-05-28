<?php use App\Libraries\Authz;

helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate']) && !$userHasActiveProposal): ?>
    <?= $this->section('title-actions') ?>
  <button class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
          data-bs-target="#modal-add-proposal">
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
    Ajukan proposal
  </button>
  <button
    class="btn btn-primary btn-6 d-sm-none btn-icon"
    data-bs-toggle="modal"
    data-bs-target="#modal-add-proposal"
    aria-label="Create new proposal"
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
<?php endif ?>

<?= $this->section('content') ?>
<?= $this->include('components/error-alert') ?>
<?= $this->include('components/success-alert') ?>

<?php if(Authz::any(['admin', 'supervisor'])): ?>
  <div id="proposal-tabs" class="card">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
        <li class="nav-item">
          <a href="#tab-pending-proposals" class="nav-link active" data-bs-toggle="tab">
            Menunggu
          </a>
        </li>
        <li class="nav-item">
          <a href="#tab-approved-proposals" class="nav-link" data-bs-toggle="tab">
            Disetujui
          </a>
        </li>
        <li class="nav-item">
          <a href="#tab-rejected-proposals" class="nav-link" data-bs-toggle="tab">
            Ditolak
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div id="table-loader" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Memuat...</span>
        </div>
      </div>
      <div class="tab-content d-none">
        <!-- Tab proposal menunggu -->
        <div class="tab-pane active show" id="tab-pending-proposals">
          <div id="pending-proposals-table-wrapper">
            <table id="pending-proposals-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Institusi</th>
                <th>Nama</th>
                <th>Kelompok</th>
                  <?php if(Authz::is('supervisor')): ?>
                    <th>Aksi</th>
                  <?php endif ?>
              </tr>
              </thead>
              <tbody>
              <?php foreach($proposalsByStatus['pending'] as $proposal): ?>
                <tr>
                  <td><?= $proposal['title'] ?></td>
                  <td><?= $proposal['institution'] ?></td>
                  <td><?= $proposal['leader']['name'] ?></td>
                  <td><?= $proposal['is_group'] ? 'Ya' : 'Tidak' ?></td>
                    <?php if(Authz::is('supervisor')): ?>
                      <td>
                        <button class="btn-approval btn btn-warning btn-5 d-none d-sm-inline-block"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-proposal-approval"
                                data-row="<?= encode_row_data($proposal) ?>">
                          Persetujuan
                        </button>
                      </td>
                    <?php endif ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Tab proposal disetujui -->
        <div class="tab-pane" id="tab-approved-proposals">
          <div id="approved-proposals-table-wrapper">
            <table id="approved-proposals-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Institusi</th>
                <th>Nama</th>
                <th>Kelompok</th>
                <th>Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($proposalsByStatus['approved'] as $proposal): ?>
                <tr>
                  <td><?= $proposal['title'] ?></td>
                  <td><?= $proposal['institution'] ?></td>
                  <td><?= $proposal['leader']['name'] ?></td>
                  <td><?= $proposal['is_group'] ? 'Ya' : 'Tidak' ?></td>
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
        <!-- Tab proposal ditolak -->
        <div class="tab-pane" id="tab-rejected-proposals">
          <div id="rejected-proposals-table-wrapper">
            <table id="rejected-proposals-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Institusi</th>
                <th>Nama</th>
                <th>Kelompok</th>
                <th>Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($proposalsByStatus['rejected'] as $proposal): ?>
                <tr>
                  <td><?= $proposal['title'] ?></td>
                  <td><?= $proposal['institution'] ?></td>
                  <td><?= $proposal['leader']['name'] ?></td>
                  <td><?= $proposal['is_group'] ? 'Ya' : 'Tidak' ?></td>
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
  </div>
<?php endif ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
  <div class="card">
    <div class="card-body">
      <table id="user-proposals-table" class="table table-striped">
        <thead>
        <tr>
          <th>Judul</th>
          <th>Institusi</th>
          <th>Nama</th>
          <th>Kelompok</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($proposals as $proposal): ?>
          <tr>
            <td><?= $proposal['title'] ?></td>
            <td><?= $proposal['institution'] ?></td>
            <td><?= $proposal['leader']['name'] ?></td>
            <td><?= $proposal['is_group'] ? 'Ya' : 'Tidak' ?></td>
            <td><?= $proposal['status'] ?></td>
            <td>
              <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                      data-bs-target="#modal-proposal-detail"
                      data-row="<?= encode_row_data($proposal) ?>">
                Detail
              </button>
                <?php if($proposal['status'] === 'pending'): ?>
                  <button class="btn-delete btn btn-danger btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-delete-proposal"
                          data-id="<?= $proposal['id'] ?>">
                    Hapus
                  </button>
                <?php endif ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<?= $this->include('pages/proposals/detail-modal') ?>

<?php if(Authz::any(['admin', 'supervisor'])): ?>
    <?= $this->include('pages/proposals/approval-modal') ?>
<?php endif ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
    <?= $this->include('pages/proposals/add-modal') ?>
    <?= $this->include('pages/proposals/delete-modal') ?>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/datatables/datatables.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<script src="/assets/js/utils/row-data.js"></script>
<script>
    <?php if(Authz::any(['admin', 'supervisor'])): ?>
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
    <?php endif ?>
    
    <?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
    $('#modal-add-proposal').on('change', 'input[name="is_group"]', function() {
      const isChecked = $(this).is(':checked');
      const $wrapper = $('#modal-add-proposal #members-wrapper');
      const $input = $('#modal-add-proposal #add-proposal-members');

      if(isChecked) {
        $wrapper.removeClass('d-none');
        $input.prop('required', true);
      } else {
        $wrapper.addClass('d-none');
        $input.prop('required', false);
      }
    });

    // Optional: trigger once on modal show to initialize state
    $('#modal-add-proposal').on('shown.bs.modal', function() {
      $('input[name="is_group"]', this).trigger('change');
    });

    function initUser() {
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

      $('.btn-delete').off('click').on('click', function() {
        const id = $(this).data('id');
        $('#modal-delete-proposal').find('form').attr('action', `/proposals/${id}`);
      });
    }

    // user proposals table
    new DataTable('#user-proposals-table', {
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

        initUser();
      },
      drawCallback: function() {
        initUser();
      }
    });
    <?php endif ?>
</script>
<?= $this->endSection() ?>
