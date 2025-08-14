<?php $this->load->view('header'); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between">
                    <div class="form-row">
                        <div class="form-group col-md-2 pt-2">
                            <a href="<?= base_url('index.php/lm/LMController/addLotMondal') ?>">
                                <button type="button" class="btn btn-warning">
                                    Create Lot Mondal <i class="fa fa-user mr-1"></i>
                                </button>
                            </a>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputCity">District</label>
                            <select id="dist_code" class="form-control">
                                <option value="">--District--</option>
                                <option selected value="<?= $district[0]['dist_code'] ?>"><?= $district[0]['loc_name'] ?>
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputState">Sub Division</label>
                            <select id="subdiv_code" class="form-control" onchange="reloadData()">
                                <option value="">--Sub Division--</option>
                                <?php foreach ($sub_divs as $subdiv) : ?>
                                    <option value="<?= $subdiv['subdiv_code'] ?>"><?= $subdiv['loc_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputCity">Circle</label>
                            <select id="cir_code" class="form-control" onchange="reloadData()">
                                <option value="">--Circle--</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputState">Mouza</label>
                            <select id="mouza_pargona_code" class="form-control" onchange="reloadData()">
                                <option value="">--Mouza--</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputCity">Lot</label>
                            <select id="lot_no" class="form-control" onchange="reloadData()">
                                <option value="">--Lot Number--</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="lm_table" class="table compact text-center table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="text-align:center">District</th>
                            <th style="text-align:center">Sub Division</th>
                            <th style="text-align:center">Circle</th>
                            <th style="text-align:center">Mouza</th>
                            <th style="text-align:center">Lot</th>
                            <th style="text-align:center">Lot Mandal</th>
                            <th style="text-align:center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src=" <?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script>
    $('#dist_code').change(function() {
        var baseurl = window.base_url;
        var id = $(this).val();
        $.ajax({
            url: baseurl +
                "index.php/common/LocationController/getSubdivs",
            method: "POST",
            data: {
                id: id
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html +=
                    '<option value="">Select Subdivision</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i]
                        .subdiv_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#subdiv_code').html(html);
            }
        });
        return false;
    });
    $('#subdiv_code').change(function() {
        var baseurl = window.base_url;
        var dist_code = $('#dist_code').val();
        var subdiv_code = $(this).val();
        $.ajax({
            url: baseurl +
                "index.php/common/LocationController/getCircles",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html +=
                    '<option value="">Select Circle</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i]
                        .cir_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#cir_code').html(html);
            }
        });
        return false;
    });
    $('#cir_code').change(function() {
        var baseurl = window.base_url;
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();;
        var cir_code = $(this).val();

        $.ajax({
            url: baseurl +
                "index.php/common/LocationController/getMouzas",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html +=
                    '<option value="">Select Mouza</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i]
                        .mouza_pargona_code + '>' + data[i]
                        .loc_name +
                        '</option>';
                }
                $('#mouza_pargona_code').html(html);
            }
        });
        return false;
    });
    $('#mouza_pargona_code').change(function() {
        var baseurl = window.base_url;
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();;
        var cir_code = $('#cir_code').val();
        var mouza_pargona_code = $(this).val();
        $.ajax({
            url: baseurl +
                "index.php/common/LocationController/getLots",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Lot</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lot_no +
                        '>' + data[i].loc_name + '</option>';
                }
                $('#lot_no').html(html);
            }
        });
        return false;
    });
    $('#lot_no').change(function() {
        var baseurl = window.base_url;
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();;
        var cir_code = $('#cir_code').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $(this).val();
        $.ajax({
            url: baseurl +
                "index.php/common/LocationController/getVillages",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html +=
                    '<option value="">Select Village</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i]
                        .vill_townprt_code + '>' + data[i]
                        .loc_name +
                        '</option>';
                }
                $('#vill_townprt_code').html(html);
            }
        });
        return false;
    });
</script>
<script>
    var table = $('#lm_table').DataTable({
        'processing': true,
        'serverMethod': 'post',
        "ordering": false,
        'language': {
            "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>'
        },
        'ajax': {
            'url': '<?= base_url() ?>index.php/lm/LMController/getAllDetails',
            "type": "POST",
            "data": function(d) {
                d.dist_code = $("#dist_code").val();
                d.subdiv_code = $("#subdiv_code").val();
                d.cir_code = $("#cir_code").val();
                d.mouza_pargona_code = $("#mouza_pargona_code").val();
                d.lot_no = $("#lot_no").val();
            }
        },
        columns: [{
                "data": "dist_name"
            },
            {
                "data": "subdiv_name"
            },
            {
                "data": "circle_name"
            },
            {
                "data": "mouza_name"
            },
            {
                "data": "lot_name"
            },
            {
                "data": "lm_name"
            },
            {
                "data": "action"
            }
        ]
    });

    function reloadData() {
        table.ajax.reload();
    }
</script>