<?php $this->load->view('header'); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="form-group col-md-2">
                    <label for="inputState">Select Villages</label>
                    <select id="vill_townprt_code" class="form-control" onchange="reloadData()">
                        <option value="">--Select Village--</option>
                        <?php foreach ($villages as $village) : ?>
                            <option value="<?= $village['vill_townprt_code'] ?>"><?= $village['loc_name'] ?></option>
                        <?php endforeach; ?>
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
</script>