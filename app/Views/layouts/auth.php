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
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <?= $this->renderSection('page-styles') ?>
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/assets/dist/css/tabler.css?1744816593" rel="stylesheet" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PLUGINS STYLES -->
    <?= $this->renderSection('page-style-libs') ?>
    <!-- END PLUGINS STYLES -->
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
    <div class="page-wrapper">
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
            <div class="container-xl my-auto d-flex align-items-center justify-content-center">
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
