<style>
    td,
    th {
        border: 1px solid #9ca3af !important;
    }
</style>
<div class="col-md-12">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
    <div class="row">
        <div class="col-md-12 mt-2">
            District : <?php echo $this->utilityclass->getDistrictName($dist_code); ?> , Circle : <?php echo $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code); ?> , Mouza : <?php echo $this->utilityclass->getMouzaName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code); ?> , Lot : <?php echo $this->utilityclass->getLotName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no); ?> , Village : <?php echo $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code); ?>, Patta Type : <?php echo ($this->utilityclass->getPattaType($patta_type_code)) ?>, Patta No : <?php echo ($patta_no) ?>, Dag No : <?php echo ($dag_no) ?>
        </div>
        <div class="col-md-12">
            <div class="card my-2 card-info">
                <div class="card-header">
                    <h5 class="card-title">Pattadars </h5>
                </div>

                <div class="card-body">
                    <div class="mb-2 row">

                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th>Pattadar ID</th>
                                    <th>Pattadar Name</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($pattadars) == 0): ?>
                                    <tr>
                                        <td colspan="2" class="text-center">No pattadars</td>
                                    </tr>
                                <?php  endif; ?>
                                <?php foreach ($pattadars as $pattadar) : ?>
                                    <tr>
                                        <td><?php echo ($pattadar->pdar_id) ?></td>
                                        <td><a title="Click to edit" href="#" onclick="getPattadarDetails(<?php echo ($pattadar->pdar_id) ?>)"><?php echo ($pattadar->pdar_name) ?></a> </td>
                                        <!-- <td><button title="Delete pattadar" onclick="deletePattadar(<?php echo ($pattadar->pdar_id) ?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 text-center pb-3">
                    <a class="btn btn-primary" href="dagDetails">PROCEED DAG EDIT</a>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script>
    
</script>