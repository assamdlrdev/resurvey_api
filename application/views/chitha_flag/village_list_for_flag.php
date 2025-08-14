<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css">
<script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
<div class="row login form-top">
    <div class="col-lg-12 ">
        <div>
            <h5><?php echo $this->lang->line('district'); ?> : <kbd><?php echo $datas['dist_name']; ?></kbd> &nbsp;&nbsp;<?php echo $this->lang->line('subdivision'); ?> : <kbd><?php echo $datas['sub_div_name']; ?></kbd> &nbsp;&nbsp;<?php echo $this->lang->line('circle'); ?> : <kbd><?php echo $datas['cir_name']; ?></kbd> &nbsp;&nbsp;<?php echo $this->lang->line('lot_no'); ?> : <kbd><?php echo $datas['lot_name']; ?></kbd> </h5>
        </div>
        <div class="card card-info">
            <div class="card-header bg-info text-white">
                <h3 class="card-title text-center font-weight-bold">Location Details for Chitha Dag Mapping / Flagging</h3>
            </div>
            <div class="card-body">
                <input type="hidden" class="districtselect" name="dist_code" id="dist_code" value="<?php echo $datas['dist_code']; ?>">
                <input type="hidden" class="subdivselect" name="subdiv_code" id="subdiv_code" value="<?php echo $datas['subdiv_code']; ?>">
                <input type="hidden" class="circleselect" name="cir_code" id="cir_code" value="<?php echo $datas['cir_code']; ?>">
                <input type="hidden" class="mouza_pargona_code" name="mouza_pargona_code" id="mouza_pargona_code" value="<?php echo $datas['mouza_pargona_code']; ?>">
                <input type="hidden" class="lot_no" name="lot_no" id="lot_no" value="<?php echo $datas['lot_no']; ?>">
                <p>
                <h3 class="card-title font-weight-bold" style="text-transform: uppercase;">Dag Mapping with Chitha</h3>
                </p>

                <div class="table-responsive" id="areaMappingDiv">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th width="5%">Serial No.</th>
                                <th width="25%">Village</th>
                                <th width="20%">Forward to CO (Pending)</th>
                                <th width="50%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1;
                            foreach ($villages as $d) :
                                $color = "#000";
                                if ($d->forward_to_co_count > 0) {
                                    $color = "red";
                                }
                            ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><b><?php echo $d->loc_name; ?></b></td>
                                    <td><b style="color: <?= $color ?>"><?= $d->forward_to_co_count; ?></b></td>
                                    <td>
                                        <a href="<?php echo base_url() . 'index.php/ChithaFlag/partialmapping?no=' . $d->vill_townprt_code; ?>" class="btn btn-primary btn-sm" id="<?= $d->vill_townprt_code; ?>"><i class='fa fa-hand-o-right'></i> Click here for Mapping Dag
                                        </a>
                                        <a href="<?php echo base_url() . 'index.php/ChithaFlag/viewMappingDetails?no=' . $d->vill_townprt_code; ?>" class="btn btn-warning btn-sm" id="<?= $d->vill_townprt_code; ?>"><i class='fa fa-refresh'></i> Update Mapping
                                        </a>
                                        <?php if ($d->rejectedCount > 0) { ?>
                                            <a href="<?php echo base_url() . 'index.php/ChithaFlag/partialmappingRejected?no=' . $d->vill_townprt_code; ?>" class="btn btn-danger btn-sm" id="<?= $d->vill_townprt_code; ?>"><i class='fa fa-eye'></i> Reverted Mapping Details
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <a href="<?= base_url() . 'index.php/chithaFlag/FlagIndexLM'; ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i>BACK</a>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="<?php echo base_url(); ?>application/views/js/blockUI.js"></script>
<script type="text/javascript">
    function showSuccessMessage(text) {
        Swal.fire({
            title: "Success !",
            text: text,
            icon: 'success',
            position: 'top',
            showConfirmButton: true,
            timer: 5000,
        }).then(function() {
            window.location.href = baseurl + "Dagflag/locationDetails";
        });

    }

    function showErrorMessage(text) {
        Swal.fire({
            title: "Error!",
            text: text,
            icon: 'error',
            position: 'top',
            timer: 5000,
            showCancelButton: true

        });
    }


    $('.fullMapping').click(function(e) {
        var id = $(this).attr("id");
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();
        var circle_code = $('#cir_code').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $('#lot_no').val();
        var vill_townprt_code = id;
        var areasel = $('#' + id + 'areasel').val();
        $.blockUI({
            message: $('#displayBox'),
            css: {
                border: 'none',
                backgroundColor: 'transparent'
            }
        });
        $.ajax({
            url: baseurl + "Dagflag/updateFullMapping/",
            type: 'post',
            dataType: 'json',
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                circle_code: circle_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code,
                areasel: areasel
            },
            success: function(data) {
                $.unblockUI();
                if (data.status == 'success') {
                    showSuccessMessage(data.msg);
                } else {
                    showErrorMessage(data.msg);
                }
            },
            error: function(error) {
                $.unblockUI();
                showErrorMessage('Something went wrong.');
            }
        });
    });



    //Full Flagging Zonal FLags
    $('.fullFlaggingOtherFlag').click(function(e) {
        var id1 = $(this).attr("id");
        var dist_code1 = $('#dist_code').val();
        var subdiv_code1 = $('#subdiv_code').val();
        var circle_code1 = $('#cir_code').val();
        var mouza_pargona_code1 = $('#mouza_pargona_code').val();
        var lot_no1 = $('#lot_no').val();
        var vill_townprt_code1 = id1;
        var otherflagsel = $('#' + id1 + 'otherflagsel').val();

        Swal.fire({
            title: 'Are you sure?',
            text: "All The Dags of the Village will be sent to CO for Flagging Approval",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseurl + "Dagflag/updateFullFlaggingOtherFlag",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        dist_code: dist_code1,
                        subdiv_code: subdiv_code1,
                        circle_code: circle_code1,
                        mouza_pargona_code: mouza_pargona_code1,
                        lot_no: lot_no1,
                        vill_townprt_code: vill_townprt_code1,
                        otherflagsel: otherflagsel
                    },
                    success: function(data) {
                        $.unblockUI();
                        if (data.status == 'success') {
                            showSuccessMessage(data.msg);
                        } else {
                            showErrorMessage(data.msg);
                        }
                    },
                    error: function(error) {
                        $.unblockUI();
                        showErrorMessage('Something went wrong.');
                    }
                });
            }
        })
    });
</script>