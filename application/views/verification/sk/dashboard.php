<?php $this->load->view('header'); ?>
<style>
    .progress_wrap {
        position: fixed;
        z-index: 9999;
        height: 100%;
        width: 100%;
        overflow: show;
        margin: auto;
        background-color: #001933bf;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    .progress_sec {
        width: 400px;
        height: 20px;
        display: inline-block;

        position: fixed;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>
<div class="progress_wrap" style="display:none;">
    <div class="progress progress_sec auto_esign_progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated certificate_preparation_progress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
</div>
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

                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label for="inputState">Select Villages</label>
                    <select id="vill_townprt_code" class="form-control" onchange="reloadData()">
                        <option value="">--Select Village--</option>

                    </select>
                </div>

                <div class="form-group col-md-3">
                </div>

                <?php if ($enable_auto_esign): ?>
                    <div class="form-group col-md-3 mt-4 pt-2">
                        <button class="btn btn-warning auto_esign" style="display:none;">
                            <i class="fa fa-refresh"></i>
                            Prepare For Certificate Sign
                        </button>
                    </div>
                <?php endif; ?>

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
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('dag_no')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('patta_no')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('bigha')) ?> - <?php echo($this->lang->line('katha')) ?>-<?php echo($this->lang->line('lessa')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('patta_type')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('land_class')) ?></th>
                            <th style="text-align:center;width: 100px;"><?php echo($this->lang->line('action')) ?></th>
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
    let interval;
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    var data = {};
    data[csrfName] = csrfHash;
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
                d[csrfName]  = csrfHash;
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
        $('.auto_esign').show();
    }

    function lotData() {
        var baseurl = $('#baseurl').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        $('#lot_no').html('');
        $('#vill_townprt_code').html('');
        if (mouza_pargona_code != undefined && mouza_pargona_code != '') {
            $.ajax({
                url: baseurl + 'index.php/verification/SKController/getLots',
                method: 'POST',
                data: {
                    mouza_pargona_code: mouza_pargona_code
                },
                success: function(resp) {
                    var response = JSON.parse(resp);
                    if (response.status == 'FAILED') {
                        alert(response.msg);
                    } else if (response.status == 'SUCCESS') {
                        var data = response.data;
                        if (data.length > 0) {
                            var html = '<option value="">---Select Lot---</option>';
                            data.forEach(element => {
                                html += `<option value="${element.lot_no}">${element.loc_name}</option>`;
                            });
                            $('#lot_no').html(html);
                        } else {
                            var html = '<option value="">---Select Lot---</option>';
                            $('#lot_no').html(html);
                        }
                    }
                }
            });
        } else {
            alert('Empty field');
        }
    }

    function villageData() {
        var baseurl = $('#baseurl').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $('#lot_no').val();
        $('#vill_townprt_code').html('');
        if (mouza_pargona_code != undefined && mouza_pargona_code != '' && lot_no != undefined && lot_no != '') {
            $.ajax({
                url: baseurl + 'index.php/verification/SKController/getVillages',
                method: 'POST',
                data: {
                    mouza_pargona_code: mouza_pargona_code,
                    lot_no: lot_no
                },
                success: function(resp) {
                    var response = JSON.parse(resp);
                    if (response.status == 'FAILED') {
                        alert(response.msg);
                    } else if (response.status == 'SUCCESS') {
                        var data = response.data;
                        if (data.length > 0) {
                            var html = '<option value="">---Select Village---</option>';
                            data.forEach(element => {
                                html += `<option value="${element.mouza_pargona_code}_${element.lot_no}_${element.vill_townprt_code}">${element.loc_name}</option>`;
                            });
                            $('#vill_townprt_code').html(html);
                        } else {
                            var html = '<option value="">---Select Village---</option>';
                            $('#vill_townprt_code').html(html);
                        }
                    }
                }
            });
        } else {
            alert('Empty field');
        }
    }

    $(document).on('click', '.auto_esign', function() {
        const $this = $(this);
        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            text: 'You want to prepare for signing certificate!',
            showCancelButton: true,
            confirmButtonText: "Proceed",
        }).then((response) => {
            if (response.isConfirmed) {
                $this.attr('disabled', true);
                var baseurl = $('#baseurl').val();
                const vill_townprt_code = $('#vill_townprt_code').val();
                if (vill_townprt_code == '') {
                    Swal.fire({
                        title: 'Please choose village first',
                        icon: 'error'
                    });
                    return false;
                }

                $('.progress_wrap').show();
                setProgressBar(1);
                $.ajax({
                    url: baseurl + 'index.php/verification/SKController/autoSignChitha',
                    method: 'POST',
                    async: true,
                    data: {
                        vill_townprt_code: vill_townprt_code
                    },
                    success: function(resp) {
                        clearInterval(interval);
                        if (resp.success) {
                            setProgressBar(100);
                            $('.progress_wrap').hide();
                            Swal.fire({
                                title: resp.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire({
                                title: resp.message,
                                icon: 'error'
                            });
                            $this.attr('disabled', false);
                            resetProgressBar();
                        }
                    },
                    error: function() {
                        clearInterval(interval);
                        $this.attr('disabled', false);
                        resetProgressBar();
                        Swal.fire({
                            title: 'Something went wrong. Please try again later',
                            icon: 'error'
                        });
                    }
                });

                interval = setInterval(showProgress, 2000);
            }
        });

    });

    function showProgress() {
        var baseurl = $('#baseurl').val();
        const vill_townprt_code = $('#vill_townprt_code').val();
        $.ajax({
            url: baseurl + 'index.php/verification/SKController/showprogress',
            method: 'POST',
            data: {
                vill_townprt_code: vill_townprt_code
            },
            success: function(resp) {
                setProgressBar(resp.progress_percent);
            }
        });
    }

    function setProgressBar(step) {
        let certificate_preparation_progress = $('.certificate_preparation_progress');
        certificate_preparation_progress.css('width', `${step}%`);
        certificate_preparation_progress.attr('aria-valuenow', step);
        certificate_preparation_progress.text(`${step}%`);
    }

    function resetProgressBar() {
        let certificate_preparation_progress = $('.certificate_preparation_progress');
        certificate_preparation_progress.css('width', '0%');
        certificate_preparation_progress.attr('aria-valuenow', 0);
        certificate_preparation_progress.text('0%');
        $('.progress_wrap').hide();
    }
</script>