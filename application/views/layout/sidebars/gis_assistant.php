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
                    <a href="<?= base_url('index.php/survey/home') ?>" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="<?= base_url('index.php/gis/qa_qc/villages') ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            QA/QC
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item has-treeview">
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
                </li> -->
                <!-- <li class="nav-item">
                    <a href="<?= base_url('index.php/zip_table/ImportZipController') ?>" class="nav-link">
                        <i class="far fa-file nav-icon"></i>
                        <p>Import Data From Zip</p>
                    </a>
                </li> -->
                <!-- <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/get-change-password') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li> -->
                <!-- <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/set-location-for-village-wise-remove-data') ?>" class="nav-link">
                        <i class="nav-icon fas fa-trash"></i>
                        <p>
                            Village Wise Data Remove
                        </p>
                    </a>
                </li> -->
                <!-- <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/import_chitha/ImportChithaController/select_village') ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p>
                            Import Chitha
                        </p>
                    </a>
                </li> -->
                <!-- <li class="nav-item has-treeview">
                    <a href="<?= base_url('index.php/import_chitha/ImportChithaController/select_village_encroent') ?>" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p>
                            Import Chitha with Encroachers
                        </p>
                    </a>
                </li> -->
                <?php if (ENABLE_VLB_IMPORT == '1'): ?>
                    <!-- <li class="nav-item has-treeview">
                        <a href="<?= base_url('index.php/import_vlb/ImportVlbController/select_village') ?>" class="nav-link">
                            <i class="nav-icon fas fa-file-import"></i>
                            <p>
                                Import VLB
                            </p>
                        </a>
                    </li> -->
                <?php endif; ?>
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