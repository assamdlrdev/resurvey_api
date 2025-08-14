<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> PROCESSED CASES</h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr class="text-sm">
                                <th>Sl No</th>
                                <th>District</th>
                                <th>Sub-division</th>
                                <th>Circle</th>
                                <th>Mouza</th>
                                <th>Lot</th>
                                <th>Village</th>
                                <th>Merge Villages Name</th>
                                <th>Request Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($cases)): 
                                    foreach($cases as $key => $case):
                            ?>
                                        <tr class="text-sm">
                                            <td><?= $key + 1 ?>.</td>
                                            <td><?= $case['location']['dist']['loc_name'] ?></td>
                                            <td><?= $case['location']['subdiv']['loc_name'] ?></td>
                                            <td><?= $case['location']['circle']['loc_name'] ?></td>
                                            <td><?= $case['location']['mouza']['loc_name'] ?></td>
                                            <td><?= $case['location']['lot']['loc_name'] ?></td>
                                            <td><?= $case['location']['village']['loc_name'] ?></td>
                                            <td>
                                                <?= (($case['requested_merged_villages_name'] != '') ? $case['requested_merged_villages_name'] : 'N.A.'); ?>
                                            </td>
                                            <td><?= $case['case_type'] ?></td>
                                            <td>
                                                <a href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageCommonController/showDags?application_no=' . $case['application_no'] ?>" class="btn btn-sm btn-info text-white">View <i class="fa fa-chevron-right"></i></a>
                                            </td>
                                        </tr>
                            <?php 
                                    endforeach;
                                else: 
                            ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
