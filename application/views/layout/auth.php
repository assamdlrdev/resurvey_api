<!DOCTYPE html>
<html>

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token-name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
    <meta name="csrf-token-hash" content="<?php echo $this->security->get_csrf_hash(); ?>">
    <title><?php echo (isset($page_title) ? $page_title : 'Dharitee Data Entry') ?> </title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css') ?>">
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js') ?>"></script>
    <?php
    if (!$this->session->userdata('usercode')) {
        redirect('/');
    }
    ?>
<body class="hold-transition login-page">
    <?php
    if (isset($_view) && $_view) {
        $this->load->view($_view);
    }

    ?>

    <script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets/dist/js/adminlte.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/block-ui/blockUI.js') ?>"></script>

</body>

</html>