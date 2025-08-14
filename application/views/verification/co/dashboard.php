<?php $this->load->view('header'); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <div class="card-header">
            <div class="row">

                <div class="form-group col-md-2">
                    <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
                    <label for="">Select Mouzas</label>
                    <select id="mouza_pargona_code" class="form-control" onchange="lotData()">
                        <option value="">--Select Mouza--</option>
                        <?php foreach ($mouzas as $mouza) : ?>
                            <option value="<?= $mouza['mouza_pargona_code']; ?>"><?= $mouza['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="">Select Lots</label>
                    <select id="lot_no" class="form-control" onchange="villageData()">
                        <option value="">--Select Lot--</option>
                        <!-- <?php foreach ($lots as $lot) : ?>
                            <option value="<?= $lot['lot_no']; ?>"><?= $lot['loc_name'] ?></option>
                        <?php endforeach; ?> -->
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="inputState">Select Villages</label>
                    <select id="vill_townprt_code" class="form-control" onchange="reloadData()">
                        <option value="">--Select Village--</option>
                        <!-- <?php foreach ($villages as $village) : ?>
                            <option value="<?= $village['mouza_pargona_code'] . '_' . $village['lot_no'] . '_' . $village['vill_townprt_code'] ?>"><?= $village['loc_name'] ?></option>
                        <?php endforeach; ?> -->
                    </select>
                </div>

            </div>
        </div>
        <?php if ($this->session->flashdata('message')) : ?>
            <div class="col-lg-12 ">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong class="rasid" style="color:red !important"><?php echo $this->session->flashdata('message'); ?></strong>
                </div>
                <?php if ($this->session->flashdata('message2')) : ?>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong class="rasid" style="color:red !important"><?php echo $this->session->flashdata('message2'); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="card-body">
            <div class="table-responsive">
                <table id="lm_table" class="table compact text-center table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="text-align:center;width: 100px;">দাগ নং</th>
                            <th style="text-align:center;width: 100px;">পাট্টা নম্বৰ</th>
                            <th style="text-align:center;width: 100px;">বি-কা-লে</th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('patta_type')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('land_class')) ?></th>
                            <th style="text-align:center;width: 100px;">ক্রিয়া</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src=" <?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>


<script>
    var csrfTokenName = '<?php echo $this->security->get_csrf_hash(); ?>';
    var csrfTokenValue = '<?php echo $this->security->get_csrf_hash(); ?>';
    $(document).ajaxSend(function(event, jqxhr, settings) {
        if (settings.type.toLowerCase() === "post") {
            if (settings.data && typeof settings.data === 'string') {
                settings.data += '&' + csrfName + '=' + csrfHash;
            } else {
                settings.data = csrfName + '=' + csrfHash;
            }
        }
    });
    var table = $('#lm_table').DataTable({
        'processing': true,
        'serverMethod': 'post',
        "ordering": true,
        'language': {
            "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>'
        },
        'ajax': {
            'url': '<?= $ajax_url ?>',
            "type": "POST",
            "data": function(d) {
                d.vill_townprt_code = $('#vill_townprt_code').val();
            }
        },
        columns: [{
                "data": "dag_no"
            },
            {
                "data": "patta_no"
            },
            {
                "data": "dag_area"
            },
            {
                "data": "patta_type"
            },
            {
                "data": "land_class"
            },
            {
                "data": "action"
            }
        ]
    });

    function reloadData() {
        table.ajax.reload();
    }

    function lotData() {
        var baseurl = $('#baseurl').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        $('#lot_no').html('');
        $('#vill_townprt_code').html('');
        if(mouza_pargona_code != undefined && mouza_pargona_code != '') {
            $.ajax({
                url: baseurl + 'index.php/verification/COController/getLots',
                method: 'POST',
                data: {mouza_pargona_code:mouza_pargona_code},
                success: function(resp) {
                    var response = JSON.parse(resp);
                    if(response.status == 'FAILED') {
                        alert(response.msg);
                    }
                    else if(response.status == 'SUCCESS') {
                        var data = response.data;
                        if(data.length > 0) {
                            var html = '<option value="">---Select Lot---</option>';
                            data.forEach(element => {
                                html += `<option value="${element.lot_no}">${element.loc_name}</option>`;
                            });
                            $('#lot_no').html(html);
                        }
                        else{
                            var html = '<option value="">---Select Lot---</option>';
                            $('#lot_no').html(html);
                        }
                    }
                }
            });
        }
        else{
            alert('Empty field');
        }
    }

    function villageData() {
        var baseurl = $('#baseurl').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $('#lot_no').val();
        $('#vill_townprt_code').html('');
        if(mouza_pargona_code != undefined && mouza_pargona_code != '' && lot_no != undefined && lot_no != '') {
            $.ajax({
                url: baseurl + 'index.php/verification/COController/getVillages',
                method: 'POST',
                data: {mouza_pargona_code:mouza_pargona_code, lot_no:lot_no},
                success: function(resp) {
                    var response = JSON.parse(resp);
                    if(response.status == 'FAILED') {
                        alert(response.msg);
                    }
                    else if(response.status == 'SUCCESS') {
                        var data = response.data;
                        if(data.length > 0) {
                            var html = '<option value="">---Select Village---</option>';
                            data.forEach(element => {
                                html += `<option value="${element.mouza_pargona_code}_${element.lot_no}_${element.vill_townprt_code}">${element.loc_name}</option>`;
                            });
                            $('#vill_townprt_code').html(html);
                        }
                        else{
                            var html = '<option value="">---Select Village---</option>';
                            $('#vill_townprt_code').html(html);
                        }
                    }
                }
            });
        }
        else{
            alert('Empty field');
        }
    }
</script>