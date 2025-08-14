<aside class="main-sidebar sidebar-dark-primary elevation-2">
    <!-- Brand Logo -->
    <a href="<?= base_url('index.php/Login/superadminindex') ?>" class="brand-link">
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
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview <?php echo((current_url() == base_url('index.php/Login/superadminindex')) ? 'menu-open' : ''); ?>">
                    <a href="<?= base_url('index.php/Login/superadminindex') ?>" class="nav-link <?php echo((current_url() == base_url('index.php/Login/superadminindex')) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/set-location-for-jama-pattadar-bulk-update') ?>" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Jama Pattadar Bulk Update
                        </p>
                    </a>

                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/svamitva_card/TranslateController/location') ?>" class="nav-link">
                        <i class="nav-icon fas fa-recycle"></i>
                        <p>
                            Transliteration Encrochers Name
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/set-removable-database') ?>" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Remove Data
                        </p>
                    </a>

                </li> -->
                <li class="nav-item has-treeview <?php echo(in_array(current_url(),[base_url('index.php/nc_village/NcVillagePortingController'),base_url('index.php/nc_village/NcVillagePortingController/index_vlb')]) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo(in_array(current_url(),[base_url('index.php/nc_village/NcVillagePortingController'),base_url('index.php/nc_village/NcVillagePortingController/index_vlb')]) ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-exchange"></i>
                        <p>
                            Data Porting to Dharitree
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="<?= base_url('index.php/nc_village/NcVillagePortingController') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc_village/NcVillagePortingController')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Port Chitha Data
                                </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="<?= base_url('index.php/nc_village/NcVillagePortingController/index_nc') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc_village/NcVillagePortingController')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Port Chitha (NC) Data
                                </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="<?= base_url('index.php/nc_village/NcVillagePortingController/index_vlb') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc_village/NcVillagePortingController/index_vlb')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Port VLB Data
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?php echo(in_array(current_url(),[base_url('index.php/port-dhar-data'),base_url('index.php/port-dhar-data/index_vlb')]) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo(in_array(current_url(),[base_url('index.php/port-dhar-data'),base_url('index.php/port-dhar-data/index_vlb')]) ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-exchange"></i>
                        <p>
                            Port Data from Dharitree
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="<?= base_url('index.php/port-dhar-data') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/port-dhar-data')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Port Chitha Data
                                </p>
                            </a>
                        </li>
                        <!-- <li class="nav-item ">
                            <a href="<?= base_url('index.php/port-dhar-data/index_vlb') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/port-dhar-data/index_vlb')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Port VLB Data
                                </p>
                            </a>
                        </li> -->
                    </ul>
                </li>
                <li class="nav-item has-treeview <?php echo(in_array(current_url(),[base_url('index.php/nc_village/NcVillageNameController/finalNotifications'),base_url('index.php/nc_village/NcVillageNameController/notifications'),base_url('index.php/nc-chitha-basic-sync'),base_url('index.php/nc-chitha-basic-sync-bhunaksa'),base_url('index.php/nc_process_track')]) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo(in_array(current_url(),[base_url('index.php/nc_village/NcVillageNameController/finalNotifications'),base_url('index.php/nc_village/NcVillageNameController/notifications'),base_url('index.php/nc-chitha-basic-sync'),base_url('index.php/nc-chitha-basic-sync-bhunaksa'),base_url('index.php/nc_process_track')]) ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-exchange"></i>
                        <p>
                            NC Process
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ">
                            <a href="<?= base_url('index.php/nc_village/NcVillageNameController/notifications') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc_village/NcVillageNameController/finalNotifications')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                   All Notifications
                                </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a title="Sync Chitha Basic with Chitha Basic NC" href="<?= base_url('index.php/nc-chitha-basic-sync') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc-chitha-basic-sync')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                   Sync Chitha Basic with Chitha Basic NC
                                </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a title="Sync Chitha Basic NC with Bhunaksa" href="<?= base_url('index.php/nc-chitha-basic-sync-bhunaksa') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc-chitha-basic-sync-bhunaksa')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                   Sync Chitha Basic NC with Bhunaksa
                                </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a title="NC Village Progress Track" href="<?= base_url('index.php/nc_process_track') ?>" class="nav-link  <?php echo ((current_url() == base_url('index.php/nc_process_track')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                   NC Village Progress Track
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/get-change-password') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            My Account
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/Login/logout') ?>" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
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