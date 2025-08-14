<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Location Report
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-md-3 d-flex justify-content-start align-items-center">
                    <select id="dist_code" class="mr-2">
                        <option value="">--District--</option>
                        <option selected value="<?= $district[0]['dist_code'] ?>"><?= $district[0]['loc_name'] ?></option>
                    </select>
                    <select id="subdiv_code" class="mr-2" onchange="reloadData()">
                        <option value="">--Sub Division--</option>
                        <?php foreach ($sub_divs as $subdiv) : ?>
                            <option value="<?= $subdiv['subdiv_code'] ?>"><?= $subdiv['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="cir_code" class="mr-2" onchange="reloadData()">
                        <option value="">--Circle--</option>
                    </select>
                    <select id="mouza_pargona_code" onchange="reloadData()">
                        <option value="">--Mouza--</option>
                    </select>
                    <select id="lot_no" onchange="reloadData()">
                        <option value="">--Lot No--</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatable" class="display compact">
                    <thead>
                        <tr>
                            <th>District</th>
                            <th>Sub Division</th>
                            <th>Circle</th>
                            <th>Mouza</th>
                            <th>Lot</th>
                            <th>Village</th>
                            <th>Village Eng</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/only_location.js') ?>"></script>
<script>
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
            'url': '<?= base_url() ?>index.php/reports/LocationReportController/getLocations',
            "type": "POST",
            "data": function(d) {
                d.subdiv_code = $("#subdiv_code").val();
                d.cir_code = $("#cir_code").val();
                d.mouza_pargona_code = $("#mouza_pargona_code").val();
                d.lot_no = $("#lot_no").val();
            }
        },
        columns: [{
                "data": "district"
            },
            {
                "data": "subdiv"
            },
            {
                "data": "circle"
            },
            {
                "data": "mouza"
            },
            {
                "data": "lot"
            },
            {
                "data": "village"
            },
            {
                "data": "villageeng"
            },
        ]
    });

    function reloadData() {
        table.ajax.reload();
    }
</script>