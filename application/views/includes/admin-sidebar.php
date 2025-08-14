<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('index.php/Login/adminindex') ?>" class="brand-link">
        <center><span class="brand-text font-weight-light"><strong>Data Entry Portal</strong></span></center>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Welcome <?= strtoupper($user) ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview menu-open">
                    <a href="<?= base_url('index.php/Login/adminindex') ?>" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Report
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/Chithacontrol/Reportindex') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Chitha Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/set-location-for-jamabandi-report') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jamabandi Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-word-o"></i>
                        <p>
                            Patta
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/patta-select-location') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Generate Patta</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('index.php/patta-view-form') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Patta </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/UserManagement/createUser') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('index.php/UserManagement') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enable/Disable </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/get-change-password') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/Login/logout') ?>" class="nav-link">
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