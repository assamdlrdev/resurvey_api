<head>
    <?php $this->load->view('header'); ?>
    <link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
</head>

<div class="container">
    <h4>Final Data Uploaded Village List</h4>
    <div id="accordion">
        <?php
        if (count($villages_group_by_dist)):
            foreach ($villages_group_by_dist as $dist_code => $district_villages):
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
                                        <th>District</th>
                                        <th>Sub-division</th>
                                        <th>Circle</th>
                                        <th>Mouza</th>
                                        <th>Lot</th>
                                        <th>Village</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($district_villages)):
                                        foreach ($district_villages as $district_village):
                                    ?>
                                            <tr>
                                                <td><?= $district_village['district_name'] ?></td>
                                                <td><?= $district_village['subdivision'] ?></td>
                                                <td><?= $district_village['circle'] ?></td>
                                                <td><?= $district_village['mouza_pargona'] ?></td>
                                                <td><?= $district_village['lot_name'] ?></td>
                                                <td><?= $district_village['village_name'] ?></td>
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
                <h5 class="text-center text-muted p-3">No village found</h5>
            </div>
        <?php
        endif;
        ?>

    </div>
</div>

<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script>
    $('.villagetable').DataTable({
        "pagination": true,
        // "pageLength": 10, // Number of records per page
        // "lengthChange": false, // Remove page length dropdown if not needed
        // // other options
    });
</script>