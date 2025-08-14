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
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview <?php echo ((current_url() == base_url('index.php/Login/adminindex')) ? 'menu-open' : ''); ?>">
                    <a href="<?= base_url('index.php/Login/adminindex') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/Login/adminindex')) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview <?php echo (in_array(current_url(), [base_url('index.php/reports/DagReportController/newLocation'), base_url('index.php/reports/DagReportController'), base_url('index.php/reports/KhatianReportController/showFormLocation'), base_url('index.php/Chithacontrol/Reportindex'), base_url('index.php/SplittedReportController/location'), base_url('index.php/set-location-for-jamabandi-report'), base_url('index.php/VillageController/cacharreport'), base_url('index.php/SvamitvaCardController/locationForSvamitvaCard'), base_url('index.php/reports/LocationReportController/index')]) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo (in_array(current_url(), [base_url('index.php/reports/DagReportController/newLocation'), base_url('index.php/reports/DagReportController'), base_url('index.php/reports/KhatianReportController/showFormLocation'), base_url('index.php/Chithacontrol/Reportindex'), base_url('index.php/SplittedReportController/location'), base_url('index.php/set-location-for-jamabandi-report'), base_url('index.php/VillageController/cacharreport'), base_url('index.php/SvamitvaCardController/locationForSvamitvaCard'), base_url('index.php/reports/LocationReportController/index')]) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Report
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/Chithacontrol/Reportindex') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/Chithacontrol/Reportindex')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Chitha Report</p>
                            </a>
                        </li>
                        <?php if (in_array($this->session->userdata('dcode'), json_decode(SHOW_DAG_EDIT))) : ?>
                            <li class="nav-item">
                                <a href="<?= base_url('index.php/SplittedReportController/location') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/SplittedReportController/location')) ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Splitted Chitha Report</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/set-location-for-jamabandi-report') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/set-location-for-jamabandi-report')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jamabandi Report</p>
                            </a>
                        </li>
                        <?php if ($this->session->userdata('dcode') == '23') { ?>
                            <li class="nav-item">
                                <a href="<?= base_url('index.php/VillageController/cacharreport') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/VillageController/cacharreport')) ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cacher Village Report</p>
                                </a>
                            </li>
                        <?php } ?>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url('index.php/SvamitvaCardController/locationForSvamitvaCard') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/SvamitvaCardController/locationForSvamitvaCard')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Svamitva Card</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/LocationReportController/index') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/reports/LocationReportController/index')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Locations Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/KhatianReportController/showFormLocation') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/reports/KhatianReportController/showFormLocation')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Khatian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/DagReportController') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/reports/DagReportController')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Old Dag Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/DagReportController/newLocation') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/reports/DagReportController/newLocation')) ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> New Dag Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?php echo (in_array(current_url(), [base_url('index.php/import_vlb/ImportVlbController/select_village'),base_url('index.php/import_chitha/ImportChithaController/select_village_encroent'),base_url('index.php/zip_table/ImportZipController'),base_url('index.php/get-change-password'),base_url('index.php/set-location-for-village-wise-remove-data')]) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo (in_array(current_url(), [base_url('index.php/import_vlb/ImportVlbController/select_village'),base_url('index.php/import_chitha/ImportChithaController/select_village_encroent'),base_url('index.php/zip_table/ImportZipController'),base_url('index.php/get-change-password'),base_url('index.php/set-location-for-village-wise-remove-data')]) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>
                            Utility
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/zip_table/ImportZipController') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/zip_table/ImportZipController')) ? 'active' : ''); ?>">
                                <i class="far fa-file nav-icon"></i>
                                <p>Import Data From Zip</p>
                            </a>
                        </li>
                        
                        <li class="nav-item has-treeview">
                            <a href="<?= base_url('index.php/set-location-for-village-wise-remove-data') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/set-location-for-village-wise-remove-data')) ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-trash"></i>
                                <p>
                                    Village Wise Data Remove
                                </p>
                            </a>
                        </li>
                        <!-- <li class="nav-item has-treeview">
                            <a href="<?= base_url('index.php/import_chitha/ImportChithaController/select_village') ?>" class="nav-link">
                                <i class="nav-icon fas fa-file-import"></i>
                                <p>
                                    Import Chitha
                                </p>
                            </a>
                        </li> -->
                        <li class="nav-item has-treeview">
                            <a href="<?= base_url('index.php/import_chitha/ImportChithaController/select_village_encroent') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/import_chitha/ImportChithaController/select_village_encroent')) ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-file-import"></i>
                                <p>
                                    Import Chitha with Encroachers
                                </p>
                            </a>
                        </li>
                        <?php if (ENABLE_VLB_IMPORT == '1'): ?>
                            <li class="nav-item has-treeview">
                                <a href="<?= base_url('index.php/import_vlb/ImportVlbController/select_village') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/import_vlb/ImportVlbController/select_village')) ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file-import"></i>
                                    <p>
                                        Import VLB
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item has-treeview">
                            <a href="<?= base_url('index.php/get-change-password') ?>" class="nav-link <?php echo ((current_url() == base_url('index.php/get-change-password')) ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Change Password
                                </p>
                            </a>
                        </li>
                    </ul>
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
    </div>
</aside>