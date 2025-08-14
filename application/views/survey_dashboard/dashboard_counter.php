<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card-box bg-blue">
            <div class="inner">
                <h3> <?= $total_villages; ?> </h3>
                <p> Total No of Villages </p>
            </div>
            <div class="icon">
                <i class="fas fa-home" aria-hidden="true"></i>
            </div>
            <?php if($enable_report_link): ?> 
                <a href="<?= base_url('index.php/survey/home/village-list') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            <?php endif; ?> 
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card-box bg-green">
            <div class="inner">
                <h3> <?= $survey_completed_count; ?> </h3>
                <p> Detail Survey Completed </p>
            </div>
            <div class="icon">
                <i class="fa fa-pie-chart" aria-hidden="true"></i>
            </div>
            <?php if($enable_report_link): ?> 
                <a href="<?= base_url('index.php/survey/home/complete-village-list') ?>" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            <?php endif; ?> 
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card-box bg-orange">
            <div class="inner">
                <h3> <?= $qc_completed_count; ?> </h3>
                <p> QC Completed </p>
            </div>
            <div class="icon">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
            </div>
            <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card-box bg-red">
            <div class="inner">
                <h3> <?= $draft_map_prepared_count; ?> </h3>
                <p> Draft Map Prepared </p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-sm-6">
        <div class="card-box bg-green">
            <div class="inner">
                <h3> <?= $final_map_prepared_count; ?> </h3>
                <p> Final Map Prepared </p>
            </div>
            <div class="icon">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
            </div>
            <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="card-box bg-orange">
            <div class="inner">
                <h3> <span><?= $total_completed_village_area_count; ?></span> / <span><?= ($total_village_area_count * 1000); ?></span> </h3>
                <p> Total Village Area (in Mtr.)</p>
            </div>
            <div class="icon">
                <i class="fa fa-area-chart" aria-hidden="true"></i>
            </div>
            <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="card-box bg-red">
            <div class="inner">
                <h3> <?= $total_land_parcel_count; ?> </h3>
                <p> Total Land Parcel </p>
            </div>
            <div class="icon">
                <i class="fa fa-area-chart"></i>
            </div>
            <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
        </div>
    </div>
    <!-- <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-blue">
                <div class="inner">
                    <h3> <?= $team_count; ?> </h3>
                    <p> Total Teams </p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                </div>
                <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div> -->

</div>

<?php if ($show_allotment_section): ?>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-red">
                <div class="inner">
                    <h3> <?= $supervisor_total_completed_count ?>/<?= $supervisor_total_assigned_count; ?> </h3>
                    <p> Supervisor Allotment Details </p>
                </div>
                <div class="icon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-blue">
                <div class="inner">
                    <h3> <?= $surveyor_total_completed_count; ?>/<?= $surveyor_total_assigned_count; ?> </h3>
                    <p> Surveyor Progress Details </p>
                </div>
                <div class="icon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-green">
                <div class="inner">
                    <h3> 0/5 </h3>
                    <p> SPMU QC Status</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-orange">
                <div class="inner">
                    <h3> 0/2 </h3>
                    <p> SPMU Map Preparation</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($show_user_counter_section): ?>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-orange">
                <div class="inner">
                    <h3> <?= $supervisor_count; ?> </h3>
                    <p> Total Supervisor </p>
                </div>
                <div class="icon">
                    <i class="fa fa-users" aria-hidden="true"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-red">
                <div class="inner">
                    <h3> <?= $surveyor_count; ?> </h3>
                    <p> Total Surveyor </p>
                </div>
                <div class="icon">
                    <i class="fa fa-users" aria-hidden="true"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-blue">
                <div class="inner">
                    <h3> <?= $spmu_count; ?> </h3>
                    <p> Total SPMU </p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-green">
                <div class="inner">
                    <h3> <?= $team_count; ?> </h3>
                    <p> Total Teams </p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                </div>
                <!-- <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>

    </div>
<?php endif; ?>