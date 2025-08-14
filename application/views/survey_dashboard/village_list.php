<head>
    <?php $this->load->view('header'); ?>
    <link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
</head>

<div class="container">
    <div id="accordion">
        <?php
        if (count($districts_villages)):
            foreach ($districts_villages as $dist_code => $district_villages):
                $dist_name = $district_villages[0]['district_name'];
        ?>
                <div class="card">
                    <div class="card-header" id="heading<?= $dist_code; ?>">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?= $dist_code; ?>" aria-expanded="true" aria-controls="collapse<?= $dist_code; ?>">
                                <?= $dist_name; ?>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse<?= $dist_code; ?>" class="collapse show" aria-labelledby="heading<?= $dist_code; ?>" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table table-hover table-sm table-bordered table-stripe villagetable">
                                <thead>
                                    <tr class="bg-warning">
                                        <th>Sl No</th>
                                        <th>District</th>
                                        <th>Sub-division</th>
                                        <th>Circle</th>
                                        <th>Mouza</th>
                                        <th>Lot</th>
                                        <th>Village</th>
                                        <th>Survey Initiated</th>
                                        <th>Surveyor Final Data Uploaded</th>
                                        <th>GIS QAQC Initiated</th>
                                        <th>GIS QAQC Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($district_villages)):
                                        foreach ($district_villages as $key => $district_village):
                                    ?>
                                            <tr>
                                                <td><?= ($key + 1) ?></td>
                                                <td><?= $district_village['district_name'] ?></td>
                                                <td><?= $district_village['subdivision'] ?></td>
                                                <td><?= $district_village['circle'] ?></td>
                                                <td><?= $district_village['mouza_pargona'] ?></td>
                                                <td><?= $district_village['lot_name'] ?></td>
                                                <td><?= $district_village['loc_name'] ?></td>
                                                <td><?= $district_village['has_survey_started'] ? 'Yes' : 'No' ?></td>
                                                <td><?= $district_village['has_survey_completed'] ? 'Yes' : 'No' ?></td>
                                                <td><?= $district_village['has_gis_qaqc_initiated'] ? 'Yes' : 'No' ?></td>
                                                <td><?= $district_village['has_gis_qaqc_completed'] ? 'Yes' : 'No' ?></td>
                                            </tr>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        <?php
            endforeach;
        else:
        ?>
            <div class="card">
                <h4 class="text-center text-muted p-3">No village found</h4>
            </div>
        <?php
        endif;
        ?>

    </div>
</div>

<!-- <script src="<?= base_url('assets/js/login.js') ?>"></script> -->
<!-- <script src="<?= base_url('assets/js/remark.js') ?>"></script> -->
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>

<script>
    $('.villagetable').DataTable({
        "pagination": true,
        // "pageLength": 10, // Number of records per page
        // "lengthChange": false, // Remove page length dropdown if not needed
        // // other options
    });
</script>