<?php

use App\Libraries\AuthUser;
use App\Libraries\Authz;

?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?= $this->include('components/success-alert') ?>

<div class="row">
    <?php if(Authz::is('intern')): ?>
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <h2 class="fw-bold text-md">Welcome to Magang PDAM</h2>
                    <div class="text-secondary">A place to start your dream</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-2">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar"
                    ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                           class="icon icon-tabler icons-tabler-outline icon-tabler-file-import">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                      </svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="font-weight-medium">Proposal</div>
                    <div class="text-secondary"><?= $proposal['status'] ?? 'not submitted' ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-2">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar"
                    ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                           class="icon icon-tabler icons-tabler-outline icon-tabler-file-isr">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M6 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                        <path d="M3 15l3 -3l3 3" />
                      </svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="font-weight-medium">Final Report</div>
                    <div class="text-secondary"><?= $finalReport['status'] ?? 'not submitted' ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-2">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar"
                    ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                           class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                      </svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="font-weight-medium"><?= $activityCount ?> Activities</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-2">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar"
                    ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                           class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                        <path d="M16 3v4" />
                        <path d="M8 3v4" />
                        <path d="M4 11h16" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                      </svg>
                    </span>
                  </div>
                  <div class="col">
                    <div>Attendance</div>
                      <?php if(!$attendanceToday || !$attendanceToday['check_in']): ?>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-check-in">Check In
                        </button>
                      <?php elseif($attendanceToday && !$attendanceToday['check_out']): ?>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-check-out">Check Out
                        </button>
                      <?php else: ?>
                        <div class="text-secondary">Completed</div>
                      <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-12 mt-4">
        <div id="calendar"></div>
      </div>
    <?php endif ?>
    
    <?php if(Authz::any(['candidate', 'graduate'])): ?>
      <div class="col-12">
        <div class="row">
          <div class="col-6">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <h2 class="fw-bold text-md">Welcome to Magang PDAM</h2>
                    <div class="text-secondary">Please wait, your proposal is still under review.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card card-sm">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar"
                    ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                           class="icon icon-tabler icons-tabler-outline icon-tabler-file-import">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                      </svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="font-weight-medium">Proposal</div>
                    <div class="text-secondary"><?= $proposal['status'] ?? 'not submitted' ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif ?>
  
  <?php if(Authz::any(['admin', 'supervisor'])): ?>
    <div class="col-12">
      <div class="row">
        <div class="col-4">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col">
                  <h2 class="fw-bold text-md">Welcome to Magang PDAM</h2>
                  <div class="text-secondary">A place to start your dream</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-primary text-white avatar"
                  ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-file-import">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                      <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                    </svg>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">Proposals</div>
                  <div class="text-secondary"><?= $proposalCounts['pending'] ?> pending</div>
                  <div class="text-secondary"><?= $proposalCounts['approved'] ?> approved</div>
                  <div class="text-secondary"><?= $proposalCounts['rejected'] ?> rejected</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-primary text-white avatar"
                  ><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-file-isr">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                      <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                      <path d="M6 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                      <path d="M3 15l3 -3l3 3" />
                    </svg>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">Final Reports</div>
                  <div class="text-secondary"><?= $finalReportCounts['pending'] ?> pending</div>
                  <div class="text-secondary"><?= $finalReportCounts['approved'] ?> approved</div>
                  <div class="text-secondary"><?= $finalReportCounts['rejected'] ?> rejected</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  <div class="col-12 mt-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="card-title">Proposals <?= date('Y') ?></h3>
        </div>
        <div class="row">
          <div class="col">
            <div id="chart-proposals-this-month"></div>
          </div>
          <div class="col-md-auto">
            <div class="divide-y divide-y-fill">
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-primary"></span> Pending</div>
                <div class="h2"><?= $proposalsThisYearCount['pending'] ?></div>
              </div>
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-green"></span> Approved</div>
                <div class="h2"><?= $proposalsThisYearCount['approved'] ?></div>
              </div>
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-red"></span> Rejected</div>
                <div class="h2"><?= $proposalsThisYearCount['rejected'] ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-12 mt-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex">
          <h3 class="card-title">Final Reports <?= date('Y') ?></h3>
        </div>
        <div class="row">
          <div class="col">
            <div id="chart-final-reports-this-month"></div>
          </div>
          <div class="col-md-auto">
            <div class="divide-y divide-y-fill">
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-primary"></span> Pending</div>
                <div class="h2"><?= $finalReportsThisYearCount['pending'] ?></div>
              </div>
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-green"></span> Approved</div>
                <div class="h2"><?= $finalReportsThisYearCount['approved'] ?></div>
              </div>
              <div class="px-3">
                <div class="text-secondary"><span class="status-dot bg-red"></span> Rejected</div>
                <div class="h2"><?= $finalReportsThisYearCount['rejected'] ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif ?>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->include('pages/checkin-modal') ?>
<?= $this->include('pages/checkout-modal') ?>
<?= $this->endSection() ?>

<?= $this->section('page-js-libs') ?>
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/fullcalendar/fullcalendar.min.js"></script>
<script src="/assets/libs/apexcharts/dist/apexcharts.js"></script>
<?= $this->endSection() ?>

<?= $this->section('page-js') ?>
<script>
    <?php if(Authz::is('intern')): ?>
    const calendarEl = $('#calendar');
    const calendar = new FullCalendar.Calendar(calendarEl[0], {
      initialView: 'dayGridMonth',
      height: 600,
      headerToolbar: {
        left: 'title',
        center: '',
        right: 'today'
      },
      events: <?= json_encode($events, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      }
    });
    calendar.render();
    <?php endif ?>
    
    <?php if(Authz::any(['admin', 'supervisor'])): ?>
    // proposals chart
    new ApexCharts($('#chart-proposals-this-month')[0], {
      chart: {
        type: "line",
        fontFamily: "inherit",
        height: 288,
        parentHeightOffset: 0,
        toolbar: {
          show: false,
        },
        animations: {
          enabled: false,
        },
      },
      stroke: {
        width: 2,
        lineCap: "round",
        curve: "smooth",
      },
      series: [
        {
          name: "Pending",
          data: <?= json_encode($proposalsThisYearPerMonthCount['pending'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
        {
          name: "Approved",
          data: <?= json_encode($proposalsThisYearPerMonthCount['approved'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
        {
          name: "Rejected",
          data: <?= json_encode($proposalsThisYearPerMonthCount['rejected'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
      ],
      tooltip: {
        theme: "dark",
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          left: -4,
          bottom: -4,
        },
        strokeDashArray: 4,
      },
      xaxis: {
        labels: {
          padding: 0,
        },
        tooltip: {
          enabled: false,
        },
        type: "category",
      },
      yaxis: {
        labels: {
          padding: 4,
        },
      },
      labels: <?= json_encode($labelsMonths, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
      colors: [
        "color-mix(in srgb, transparent, var(--tblr-primary) 100%)",
        "color-mix(in srgb, transparent, var(--tblr-azure) 100%)",
        "color-mix(in srgb, transparent, var(--tblr-green) 100%)",
      ],
      legend: {
        show: false,
      },
    }).render();
    
    // final reports chart
    new ApexCharts($('#chart-final-reports-this-month')[0], {
      chart: {
        type: "line",
        fontFamily: "inherit",
        height: 288,
        parentHeightOffset: 0,
        toolbar: {
          show: false,
        },
        animations: {
          enabled: false,
        },
      },
      stroke: {
        width: 2,
        lineCap: "round",
        curve: "smooth",
      },
      series: [
        {
          name: "Pending",
          data: <?= json_encode($finalReportsThisYearPerMonthCount['pending'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
        {
          name: "Approved",
          data: <?= json_encode($finalReportsThisYearPerMonthCount['approved'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
        {
          name: "Rejected",
          data: <?= json_encode($finalReportsThisYearPerMonthCount['rejected'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        },
      ],
      tooltip: {
        theme: "dark",
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          left: -4,
          bottom: -4,
        },
        strokeDashArray: 4,
      },
      xaxis: {
        labels: {
          padding: 0,
        },
        tooltip: {
          enabled: false,
        },
        type: "category",
      },
      yaxis: {
        labels: {
          padding: 4,
        },
      },
      labels: <?= json_encode($labelsMonths, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
      colors: [
        "color-mix(in srgb, transparent, var(--tblr-primary) 100%)",
        "color-mix(in srgb, transparent, var(--tblr-azure) 100%)",
        "color-mix(in srgb, transparent, var(--tblr-green) 100%)",
      ],
      legend: {
        show: false,
      },
    }).render();
    <?php endif ?>
</script>
<?= $this->endSection() ?>
