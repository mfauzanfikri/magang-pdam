<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.2.0
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
  <!-- BEGIN PAGE LEVEL STYLES -->
  <link href="/assets/libs/jsvectormap/dist/jsvectormap.css?1744816593" rel="stylesheet" />
  <!-- END PAGE LEVEL STYLES -->
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="/assets/dist/css/tabler.css?1744816593" rel="stylesheet" />
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PLUGINS STYLES -->
  <link href="/assets/dist/css/tabler-flags.css?1744816593" rel="stylesheet" />
  <link href="/assets/dist/css/tabler-socials.css?1744816593" rel="stylesheet" />
  <link href="/assets/dist/css/tabler-payments.css?1744816593" rel="stylesheet" />
  <link href="/assets/dist/css/tabler-vendors.css?1744816593" rel="stylesheet" />
  <link href="/assets/dist/css/tabler-marketing.css?1744816593" rel="stylesheet" />
  <link href="/assets/dist/css/tabler-themes.css?1744816593" rel="stylesheet" />
  <!-- END PLUGINS STYLES -->
  <!-- BEGIN DEMO STYLES -->
  <link href="./preview/css/demo.css?1744816593" rel="stylesheet" />
  <!-- END DEMO STYLES -->
  <!-- BEGIN CUSTOM FONT -->
  <style>
      @import url("https://rsms.me/inter/inter.css");
  </style>
  <!-- END CUSTOM FONT -->
</head>
<body>
<!-- BEGIN GLOBAL THEME SCRIPT -->
<script src="/assets/dist/js/tabler-theme.min.js?1744816593"></script>
<!-- END GLOBAL THEME SCRIPT -->
<div class="page">
  <!--  BEGIN SIDEBAR  -->
    <?= $this->include('layouts/sidebar') ?>
  <!--  END SIDEBAR  -->
  <div class="page-wrapper">
    <!-- BEGIN PAGE HEADER -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <!-- Page pre-title -->
              <?php if(isset($subtitle)): ?>
                <div class="page-pretitle"><?= $subtitle ?></div>
              <?php endif; ?>
            <h2 class="page-title"><?= $title ?></h2>
          </div>
        </div>
      </div>
    </div>
    <!-- END PAGE HEADER -->
    <div class="page-body">
      <div class="container-xl">
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
          <div class="container-xl">
              <?= $this->renderSection('content') ?>
          </div>
        </div>
        <!-- END PAGE BODY -->
        <!--  BEGIN FOOTER  -->
          <?= $this->include('layouts/footer') ?>
        <!--  END FOOTER  -->
      </div>
    </div>
    
    <!-- BEGIN PAGE MODALS -->
      <?= $this->renderSection('modals') ?>
    <!-- END PAGE MODALS -->
    <!-- BEGIN PAGE LIBRARIES -->
      <?= $this->renderSection('page-js-libs') ?>
    <!-- END PAGE LIBRARIES -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="/assets/dist/js/tabler.min.js?1744816593" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN PAGE SCRIPTS -->
      <?= $this->renderSection('page-js') ?>
    <!-- END PAGE SCRIPTS -->
</body>
</html>
