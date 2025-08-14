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
    <script>
        window.base_url = '<?php echo (base_url()); ?>';
    </script>
    <style type="text/css" media="print">
        #generatePdf {
            display: none;
        }
    </style>
    <style>
        .no-wrap {
            white-space: nowrap;
        }

        .min-width-td {
            min-width: 120px !important;
        }

        ul {
            background-color: transparent !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <script>
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

        $(document).ready(function() {
            $(document).ajaxSend(function(event, jqxhr, settings) {
                if (settings.type.toLowerCase() === "post") { // Add CSRF token to POST requests only
                    if (settings.data && typeof settings.data === 'string') {
                        // If data is already a string, append the CSRF token
                        settings.data += '&' + csrfName + '=' + csrfHash;
                    } else if (settings.data && typeof settings.data === 'object'){
                        // Added by Abhijit -- 2024-11-01
                        if(settings.data instanceof FormData){
                            settings.data.append(csrfName, csrfHash);
                        }else{
                            settings.data = {...settings.data, [csrfName]: csrfHash};
                        }
                    } else {
                        // Otherwise, initialize data as a string with the CSRF token
                        settings.data = csrfName + '=' + csrfHash;
                    }
                }
            });
        });
    </script>
    <?php
    if ($this->session->userdata('loggedin') == false) {
        redirect('/');
    }
    if (ENABLE_MOBILE_VERIFICATION == '1') {
        if($this->session->userdata('is_set_mobile') != '1'){
            redirect('/reset-mobile');
        }
        if($this->session->userdata('is_otp_verified') != '1'){
            redirect('/enter-otp');
        }
    }
    if ($this->session->userdata('is_password_changed') != '1') {
        redirect('/reset-password');
    }

    ?>
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Home</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <?php $user = $this->session->userdata('usercode') ?>
        <?php if ($this->session->userdata('usertype') == 00) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/deo.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 01) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/admin.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 02) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/superadmin.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 3) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/lm.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 4) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/co.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 5) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/sk.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 6) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/adc.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 7) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/dc.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 9) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/guest.php';
            ?>
        <?php } ?>
        <?php if ($this->session->userdata('usertype') == 13) { ?>
            <?php
            include APPPATH . '/views/layout/sidebars/usercreation.php';
            ?>
        <?php } ?>
        <?php 
            if ($this->session->userdata('usertype') == 10) { 
                include APPPATH . '/views/layout/sidebars/supervisor.php';
            }else if($this->session->userdata('usertype') == 11){
                include APPPATH . '/views/layout/sidebars/surveyor.php';
            }else if($this->session->userdata('usertype') == 12){
                include APPPATH . '/views/layout/sidebars/spmu.php';
            }else if($this->session->userdata('usertype') == 14){
                include APPPATH . '/views/layout/sidebars/gis_assistant.php';
            }
        ?>
        <div class="content-wrapper">
            <?php if ((isset($page_header) && $page_header) || (isset($breadcrumbs) && $breadcrumbs)) : ?>
                <div class="content-header py-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="m-2">
                                    <?php
                                    if (isset($page_header) && $page_header) {
                                        echo $page_header;
                                    }

                                    ?>
                                </h5>
                            </div><!-- /.col -->
                            <div class="col-sm-8">
                                <ol class="breadcrumb float-sm-right">
                                    <?php
                                    if (isset($breadcrumbs) && $breadcrumbs) {
                                        echo $breadcrumbs;
                                    }

                                    ?>
                                </ol>
                            </div><!-- /.col -->
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <section class="content pt-2">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <?php
                        if (isset($_view) && $_view) {
                            $this->load->view($_view);
                        }

                        ?>
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2021</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b>1.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery UI 1.11.4 -->
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