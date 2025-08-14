<?php include(APPPATH . 'views/vlb/deo/view-script.php'); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" x-data="alpineData({'allow_edit':true,'vlb_type':'reverted'})">
    <div class="d-flex justify-content-end align-items-center mb-2">
        <a href="<?= base_url('index.php/vlb/VlbController/dashboard') ?>" class="btn btn-sm btn-info">Land Bank Dashboard</a>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-danger py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Village Land Bank - (Reverted-List)
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-md-2">
                    <select x-model="dist_code" id="dist_code" class="form-control form-control-sm">
                        <option value="">--District--</option>
                        <option selected value="<?= $location_names['district']->dist_code ?>"><?= $location_names['district']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="subdiv_code" id="subdiv_code" class="form-control form-control-sm">
                        <option value="">--Sub Division--</option>
                        <option selected value="<?= $location_names['subdiv']->subdiv_code ?>"><?= $location_names['subdiv']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="cir_code" id="cir_code" class="form-control form-control-sm">
                        <option value="">--Circle--</option>
                        <option selected value="<?= $location_names['circle']->cir_code ?>"><?= $location_names['circle']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="mouza_pargona_code" id="mouza_pargona_code" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Mouza--</option>
                        <?php foreach ($mouzas as $mouza) : ?>
                            <option value="<?= $mouza['mouza_pargona_code'] ?>"><?= $mouza['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="lot_no" id="lot_no" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Lot--</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="vill_townprt_code" id="vill_townprt_code" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Village--</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatable" class="display compact table-bordered">
                    <thead>
                        <tr class="bg-danger">
                            <th>Village Name</th>
                            <th>Dag No</th>
                            <th>Rejected By</th>
                            <th>Rejected Time</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
        <?php include(APPPATH . 'views/vlb/includes/add_details_modal.php'); ?>
        <?php include(APPPATH . 'views/vlb/includes/rejected_remark_modal.php'); ?>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/only_location.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>

<script>
    var cols = [{
            "data": "village"
        },
        {
            "data": "dag_no"
        },
        {
            "data": "rejected_by"
        },
        {
            "data": "rejected_time"
        },
        {
            "data": "remark"
        },
        {
            "data": "action"
        }
    ];
    var table = $('#datatable').DataTable({
        'processing': true,
        'serverMethod': 'post',
        "ordering": false,
        "lengthMenu": [
            [20, 50, 100, 200],
            [20, 50, 100, 200]
        ],
        'language': {
            "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>'
        },
        'ajax': {
            'url': '<?= base_url() ?>index.php/vlb/VlbController/getRevertedListsDeo',
            "type": "POST",
            "data": function(d) {
                d.dist_code = $("#dist_code").val();
                d.subdiv_code = $("#subdiv_code").val();
                d.cir_code = $("#cir_code").val();
                d.mouza_pargona_code = $("#mouza_pargona_code").val();
                d.lot_no = $("#lot_no").val();
                d.vill_townprt_code = $("#vill_townprt_code").val();
            }
        },
        columns: cols
    });

    function reloadData() {
        table.ajax.reload();
    }
</script>