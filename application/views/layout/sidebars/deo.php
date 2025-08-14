<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('index.php/Chithacontrol/index') ?>" class="brand-link">
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
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview menu-open">
                    <a href="<?= base_url('index.php/Chithacontrol/index') ?>" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Data Entry
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/Chithacontrol/index') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Chitha Entry/Edit</p>
                            </a>
                        </li>
                        <?php if (in_array($this->session->userdata('dcode'), json_decode(SHOW_DAG_EDIT))) : ?>
                            <li class="nav-item">
                                <a href="<?= base_url('index.php/ChithaDagEditController/location') ?>" class="nav-link <?php echo (in_array(current_url(), [base_url('index.php/ChithaDagEditController/location'), base_url('index.php/ChithaDagEditController/dagDetails'), base_url('index.php/ChithaDagEditController/viewPattadars')]) ? 'active' : '') ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Edit Dag</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/SvamitvaCardController/location') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> NC Village Chitha Entry/Edit</p>
                            </a>
                        </li>
                    </ul>
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
                        <?php if (in_array($this->session->userdata('dcode'), json_decode(SHOW_DAG_EDIT))) : ?>
                            <li class="nav-item">
                                <a href="<?= base_url('index.php/SplittedReportController/location') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Splitted Chitha Report</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/set-location-for-jamabandi-report') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jamabandi Report</p>
                            </a>
                        </li>
                        <?php if ($this->session->userdata('dcode') == '23') { ?>
                            <li class="nav-item">
                                <a href="<?= base_url('index.php/VillageController/cacharreport') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cacher Village Report</p>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/SvamitvaCardController/locationForSvamitvaCard') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Svamitva Card</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/LocationReportController/index') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Locations Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/KhatianReportController/showFormLocation') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Khatian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/DagReportController') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Old Dag Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/DagReportController/newLocation') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> New Dag Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-word-o"></i>
                        <p>
                            Jamabandi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/set-location-for-jamabandi') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Update Jamabandi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('index.php/set-location-for-jamabandi-remarks') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jamabandi Remarks </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>
                            Utility
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= base_url('index.php/dag/DagController/dagLocation') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dag & Pattadar Delete</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/jamabandi/JamaPattadarController/locationForm') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pattadar SL No( Jamabandi ) </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/svamitva_card/TranslateController/location') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transliteration Encrochers Name </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/zip_table/ExportZipController') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Export Data to Zip</p>
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