<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('index.php/Login/dashboard') ?>" class="brand-link">
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
                            <a href="<?= base_url('index.php/SplittedReportController/location') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Splitted Chitha Report</p>
                            </a>
                        </li>
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
                                <p> Dag Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/reports/MapCertifiedVillagesController/dashboard') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> NC Village Progress</p>
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
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-copy"></i>
						<p>
							NC Village
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= base_url('index.php/nc_village/NcVillageAdsController/showVillages') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>View NC Village</p>
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