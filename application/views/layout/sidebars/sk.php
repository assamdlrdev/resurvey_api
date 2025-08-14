<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=base_url('index.php/Login/dashboard')?>" class="brand-link">
        <center><span class="brand-text font-weight-light"><strong>Data Entry Portal</strong></span></center>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Welcome <?=strtoupper($user)?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview menu-open">
                    <a href="<?=base_url('index.php/Login/dashboard')?>" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            Process
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=base_url('index.php/nc_village/NcVillageSkController/dashboard')?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>NC Village</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url('index.php/nc_village_v2/NcVillageSkController/dashboard')?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>NC Village V2 <span class="ml-2 badge badge-pill badge-danger p-2">New</span></p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-vote-yea"></i>
                        <p>
                            Verification
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/verification/SKController/index') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Chitha Verification</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/verification/SKController/viewSignedPdf') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Signed PDF</p>
                            </a>
                        </li>
                        <?php if(ENABLE_VERIFICATION_CERT=='1'): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/verification/SKController/verificationCertificate') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Verification Certificate</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?=base_url('index.php/get-change-password')?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?=base_url('index.php/Login/logout')?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>