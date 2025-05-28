<?php use App\Libraries\AuthUser;
use App\Libraries\Authz;

helper('row_data') ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('page-style-libs') ?>
<link rel="stylesheet" href="/assets/libs/datatables/datatables.css">
<?= $this->endSection() ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate']) && !$userHasPendingFinalReports && $userHasActiveProposal && $isLeader): ?>
    <?= $this->section('title-actions') ?>
  <button class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
          data-bs-target="#modal-add-final-report">
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
    Tambah laporan akhir
  </button>
  <button
    class="btn btn-primary btn-6 d-sm-none btn-icon"
    data-bs-toggle="modal"
    data-bs-target="#modal-add-final-report"
    aria-label="Create new final report"
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

<?php if(Authz::any(['supervisor', 'admin'])): ?>
  <div id="final-report-tabs" class="card">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
        <li class="nav-item">
          <a href="#tab-pending-final-reports" class="nav-link active" data-bs-toggle="tab">
            Menunggu Persetujuan
          </a>
        </li>
        <li class="nav-item">
          <a href="#tab-approved-final-reports" class="nav-link" data-bs-toggle="tab">
            Disetujui
          </a>
        </li>
        <li class="nav-item">
          <a href="#tab-rejected-final-reports" class="nav-link" data-bs-toggle="tab">
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
        <!-- Tab: Menunggu Persetujuan -->
        <div class="tab-pane active show" id="tab-pending-final-reports">
          <div id="pending-final-reports-table-wrapper">
            <table id="pending-final-reports-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Instansi</th>
                <th>Nama Ketua</th>
                <th>Kelompok</th>
                  <?php if(Authz::is('supervisor')): ?>
                    <th>Aksi</th>
                  <?php endif; ?>
              </tr>
              </thead>
              <tbody>
              <?php foreach($finalReportsByStatus['pending'] as $finalReport): ?>
                <tr>
                  <td><?= $finalReport['title'] ?></td>
                  <td><?= $finalReport['proposal']['institution'] ?></td>
                  <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                  <td><?= $finalReport['proposal']['is_group'] ? 'Ya' : 'Tidak' ?></td>
                    <?php if(Authz::is('supervisor')): ?>
                      <td>
                        <button class="btn-approval btn btn-warning btn-5 d-none d-sm-inline-block"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-final-report-approval"
                                data-row="<?= encode_row_data($finalReport) ?>">
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
        
        <!-- Tab: Disetujui -->
        <div class="tab-pane" id="tab-approved-final-reports">
          <div id="approved-final-reports-table-wrapper">
            <table id="approved-final-reports-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Instansi</th>
                <th>Nama Ketua</th>
                <th>Kelompok</th>
                <th>Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($finalReportsByStatus['approved'] as $finalReport): ?>
                <tr>
                  <td><?= $finalReport['title'] ?></td>
                  <td><?= $finalReport['proposal']['institution'] ?></td>
                  <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                  <td><?= $finalReport['proposal']['is_group'] ? 'Ya' : 'Tidak' ?></td>
                  <td>
                    <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modal-final-report-detail"
                            data-row="<?= encode_row_data($finalReport) ?>">
                      Detail
                    </button>
                      <?php if(Authz::is('admin')): ?>
                          <?php if(!$finalReport['is_certificate_issued']): ?>
                          <button class="btn-issue-certificate btn btn-primary btn-5 d-none d-sm-inline-block"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal-issue-certificate"
                                  data-row="<?= encode_row_data($finalReport) ?>">
                            Terbitkan Sertifikat
                          </button>
                          <?php endif ?>
                      <?php endif ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Tab: Ditolak -->
        <div class="tab-pane" id="tab-rejected-final-reports">
          <div id="rejected-final-reports-table-wrapper">
            <table id="rejected-final-reports-table" class="table table-striped">
              <thead>
              <tr>
                <th>Judul</th>
                <th>Instansi</th>
                <th>Nama Ketua</th>
                <th>Kelompok</th>
                <th>Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($finalReportsByStatus['rejected'] as $finalReport): ?>
                <tr>
                  <td><?= $finalReport['title'] ?></td>
                  <td><?= $finalReport['proposal']['institution'] ?></td>
                  <td><?= $finalReport['proposal']['leader']['name'] ?></td>
                  <td><?= $finalReport['proposal']['is_group'] ? 'Ya' : 'Tidak' ?></td>
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
  </div>
<?php endif ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
  <div class="card">
    <div class="card-body">
      <table id="user-final-reports-table" class="table table-striped">
        <thead>
        <tr>
          <th>Judul</th>
          <th>Instansi</th>
          <th>Kelompok</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($finalReports as $finalReport): ?>
          <tr>
            <td><?= $finalReport['title'] ?></td>
            <td><?= $finalReport['proposal']['institution'] ?></td>
            <td><?= $finalReport['proposal']['is_group'] ? 'Ya' : 'Tidak' ?></td>
            <td><?= $finalReport['status'] ?></td>
            <td>
              <button class="btn-detail btn btn-warning btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                      data-bs-target="#modal-final-report-detail"
                      data-row="<?= encode_row_data($finalReport) ?>">
                Detail
              </button>
                <?php if($finalReport['status'] === 'pending' && $finalReport['proposal']['leader']['id'] === AuthUser::id()): ?>
                  <button class="btn-delete btn btn-danger btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                          data-bs-target="#modal-delete-final-report"
                          data-id="<?= $finalReport['id'] ?>">
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

<?= $this->include('pages/final-reports/detail-modal') ?>

<?php if(Authz::is('supervisor')): ?>
    <?= $this->include('pages/final-reports/approval-modal') ?>
    <?= $this->include('pages/final-reports/issue-certificate-modal') ?>
<?php endif ?>

<?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
    <?= $this->include('pages/final-reports/add-modal') ?>
    <?= $this->include('pages/final-reports/delete-modal') ?>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/datatables/datatables.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<script src="/assets/js/utils/row-data.js"></script>
<script>
    <?php if(Authz::any(['supervisor', 'admin'])): ?>
    const approvalModal = $('#modal-final-report-approval');

    function init() {
      $('.btn-approval').off('click').on('click', function() {
        const finalReport = decodeRowData($(this).data('row'));

        approvalModal.find('form').attr('action', `/final-reports/${finalReport.id}/approval`);

        const detailsTable = $('#modal-final-report-approval table');

        // Set basic fields from final report and nested proposal
        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(finalReport.title); // final report title
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(finalReport.proposal.institution);

        if(finalReport.proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').text(
            finalReport.proposal.leader.name + ' / ' + finalReport.proposal.leader.email
          );

          // Members list
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          if(finalReport.proposal.members && finalReport.proposal.members.length > 0) {
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

      $('.btn-detail').off('click').on('click', function() {
        const finalReport = decodeRowData($(this).data('row'));
        const isCertificateIssued = finalReport.is_certificate_issued;
        const detailsTable = $('#modal-final-report-detail table');

        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(finalReport.title);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(finalReport.proposal.institution);

        if(finalReport.proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').html(
            `${finalReport.proposal.leader.name} / ${finalReport.proposal.leader.email}${finalReport.is_certificate_issued ? ` <a href="/final-reports/${finalReport.id}/user/${finalReport.proposal.leader.id}/certificate" target="_blank">Download certificate</a>` : ''}`
          );

          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          if(finalReport.proposal.members && finalReport.proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            finalReport.proposal.members.forEach(member => {
              ol.append(`<li>${member.name} / ${member.email} ${isCertificateIssued ? `<a href="/final-reports/${finalReport.id}/user/${member.id}/certificate" target="_blank">Download certificate</a></li>` : ''}`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').html(
            `${finalReport.proposal.leader.name} / ${finalReport.proposal.leader.email}${finalReport.is_certificate_issued ? ` <a href="/final-reports/${finalReport.id}/user/${finalReport.proposal.leader.id}/certificate" target="_blank">Download certificate</a>` : ''}`
          );
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/final-reports/${finalReport.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });

      $('.btn-issue-certificate').off('click').on('click', function() {
        const row = decodeRowData($(this).data('row'));
        const modal = $('#modal-issue-certificate');
        const form = modal.find('form');

        // Set form action
        form.attr('action', `/final-reports/${row.id}/certificate`);

        // Clear previous inputs
        const modalBody = modal.find('.modal-body');
        modalBody.empty();

        // Function to render file input
        const renderFileInput = (user) => {
          const id = user.id;
          const name = user.name;

          return `
            <div class="mb-3">
              <label class="form-label required" for="issue-certificate-file-${id}">${name}</label>
              <input id="issue-certificate-file-${id}" type="file" class="form-control" name="file_${id}" accept="application/pdf" required />
              <small>.pdf</small>
            </div>
          `;
        };

        // Add leader
        if(row.proposal.leader) {
          modalBody.append(renderFileInput(row.proposal.leader));
        }

        // Add members
        if(Array.isArray(row.proposal.members)) {
          row.proposal.members.forEach((member) => {
            modalBody.append(renderFileInput(member));
          });
        }
      });
    }

    $('#table-loader').removeClass('d-none');
    $('#final-report-tabs .tab-content').addClass('d-none');

    // pending final reports table
    new DataTable('#pending-final-reports-table', {
      order: [],
      columnDefs: <?= Authz::is('supervisor') ? '[
        {
          targets: -1,
          orderable: false,
          searchable: false
        }
      ]' : 'undefined' ?>,
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
    <?php endif ?>
    
    <?php if(Authz::any(['candidate', 'intern', 'graduate'])): ?>
    function initUser() {
      $('.btn-detail').off('click').on('click', function() {
        const finalReport = decodeRowData($(this).data('row'));
        const isCertificateIssued = finalReport.is_certificate_issued;
        const detailsTable = $('#modal-final-report-detail table');

        detailsTable.find('tr:nth-child(1) td:nth-child(2)').text(finalReport.title);
        detailsTable.find('tr:nth-child(2) td:nth-child(2)').text(finalReport.proposal.institution);

        if(finalReport.proposal.is_group) {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Leader Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').html(
            `${finalReport.proposal.leader.name} / ${finalReport.proposal.leader.email}${finalReport.is_certificate_issued ? ` <a href="/final-reports/${finalReport.id}/user/${finalReport.proposal.leader.id}/certificate" target="_blank">Download certificate</a>` : ''}`
          );

          const membersTd = detailsTable.find('tr:nth-child(4) td:nth-child(2)');
          detailsTable.find('tr:nth-child(4)').removeClass('d-none');
          if(finalReport.proposal.members && finalReport.proposal.members.length > 0) {
            const ol = $('<ol></ol>');
            finalReport.proposal.members.forEach(member => {
              ol.append(`<li>${member.name} / ${member.email} ${isCertificateIssued ? `<a href="/final-reports/${finalReport.id}/user/${member.id}/certificate" target="_blank">Download certificate</a></li>` : ''}`);
            });
            membersTd.empty().append(ol);
          } else {
            membersTd.html('-');
          }
        } else {
          detailsTable.find('tr:nth-child(3) td:nth-child(1)').text('Name');
          detailsTable.find('tr:nth-child(3) td:nth-child(2)').html(
            `${finalReport.proposal.leader.name} / ${finalReport.proposal.leader.email}${finalReport.is_certificate_issued ? ` <a href="/final-reports/${finalReport.id}/user/${finalReport.proposal.leader.id}/certificate" target="_blank">Download certificate</a>` : ''}`
          );
          detailsTable.find('tr:nth-child(4)').addClass('d-none');
        }

        const fileTd = detailsTable.find('tr:nth-child(5) td:nth-child(2)');
        const fileUrl = `/final-reports/${finalReport.id}/file`;
        fileTd.html(`<a href="${fileUrl}" target="_blank">Download</a>`);
      });

      $('.btn-delete').off('click').on('click', function() {
        const id = $(this).data('id');
        $('#modal-delete-final-report').find('form').attr('action', `/final-reports/${id}`);
      });
    }

    // user final reports table
    new DataTable('#user-final-reports-table', {
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
