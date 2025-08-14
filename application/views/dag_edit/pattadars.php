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
            District : <?php echo $this->utilityclass->getDistrictName($dist_code); ?> , Circle : <?php echo $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code); ?> , Mouza : <?php echo $this->utilityclass->getMouzaName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code); ?> , Lot : <?php echo $this->utilityclass->getLotName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no); ?> , Village : <?php echo $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code); ?>, Patta Type : <?php echo ($this->utilityclass->getPattaType($new_patta_type_code)) ?>, Patta No : <?php echo ($new_patta_no) ?>, Dag No : <?php echo ($new_dag_no) ?>
        </div>
        <div class="col-md-12">
            <div class="card my-2 card-info">
                <div class="card-header">
                    <h5 class="card-title">Pattadars of DAG NO <?php echo ($new_dag_no) ?> </h5>
                </div>

                <div class="card-body">
                    <div class="mb-2 row">

                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3 d-flex flex-row-reverse">
                            <button class="btn btn-sm btn-info" data-toggle="modal" onclick="getNewPattadarId()" data-target="#add_pattadar">ADD PATTADAR</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th>Pattadar ID</th>
                                    <th>Pattadar Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pattadars as $pattadar) : ?>
                                    <tr>
                                        <td><?php echo ($pattadar->pdar_id) ?></td>
                                        <td><a title="Click to edit" href="#" onclick="getPattadarDetails(<?php echo ($pattadar->pdar_id) ?>)"><?php echo ($pattadar->pdar_name) ?></a> </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 text-center pb-3">
                    <a class="btn btn-primary" onclick="finishProcess();">FINISH</a>
                </div>
            </div>
        </div>

        <?php include(APPPATH . 'views/dag_edit/add_pattdar_modal.php'); ?>
        <?php include(APPPATH . 'views/dag_edit/edit_pattadar_modal.php'); ?>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>

<script>
    function toggleSelectAll(value){
        if($('#check_all').is(":checked")){
            $('.p_select').prop('checked', true);
        }else{
            $('.p_select').prop('checked', false);
        }
    }
    function deletePattadar(pdar_id) {
        var baseurl = $("#base").val();
        $.confirm({
            title: 'Please confirm to delete dag!',
            content: 'Once deleted. Can\'t be rolled back.',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Delete',
                    btnClass: 'btn-danger',
                    action: function() {
                        $.ajax({
                            dataType: "json",
                            url: baseurl + "index.php/ChithaDagEditController/deleteExistingPattadar",
                            data: {
                                'pdar_id': pdar_id
                            },
                            type: "POST",
                            success: function(data) {
                                $.confirm({
                                    title: 'Success!',
                                    content: data.msg,
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        close: function() {
                                            window.location.reload();
                                        }
                                    }
                                });
                            },
                            error: function(err) {
                                $.confirm({
                                    title: 'Encountered an error!',
                                    content: data.msg,
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        close: function() {}
                                    }
                                });
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    action: function() {

                    }
                }
            }
        });
    }

    function getPattadarDetails(pdar_id) {
        var baseurl = $("#base").val();
        var patta_type_code = $("#patta_type_code").val();
        var patta_no = $("#patta_no").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/editPattadarGetDetails",
            data: {
                'patta_no': patta_no,
                'patta_type_code': patta_type_code,
                'pdar_id': pdar_id
            },
            type: "POST",
            success: function(data) {
                if (data.pdar_id) {
                    $("#edit_pattadar_id").val(data.pdar_id);
                    $("#edit_pdar_name").val(data.pdar_name);
                    $("#edit_pdar_father").val(data.pdar_father);
                    $("#pdar_relation").val(data.pdar_guard_reln);
                    $("#edit_pdar_add1").val(data.pdar_add1);
                    $("#edit_pdar_add2").val(data.pdar_add2);
                    $("#edit_pdar_add3").val(data.pdar_add3);
                    $("#edit_dag_por_b").val(data.dag_por_b);
                    $("#edit_dag_por_k").val(data.dag_por_k);
                    $("#edit_dag_por_lc").val(data.dag_por_lc);
                    $("#edit_dag_por_g").val(data.dag_por_g);
                    $("#edit_pdar_land_revenue").val(data.pdar_land_revenue);
                    $("#edit_pdar_land_localtax").val(data.pdar_land_localtax);
                    $("#edit_pdar_land_acre").val(data.pdar_land_acre);
                    $("#edit_pdar_land_n").val(data.pdar_land_n);
                    $("#edit_pdar_land_s").val(data.pdar_land_s);
                    $("#edit_pdar_land_e").val(data.pdar_land_e);
                    $("#edit_pdar_land_w").val(data.pdar_land_w);
                    if (data.p_flag == '1') {
                        $("#p_flag_yes").prop("checked", true);
                    } else {
                        $("#p_flag_no").prop("checked", true);
                    }
                    if (data.pdar_gender == 'm') {
                        $("#gender_m").prop("checked", true);
                    } else if (data.pdar_gender == 'f') {
                        $("#gender_f").prop("checked", true);
                    } else if (data.pdar_gender == 'o') {
                        $("#gender_o").prop("checked", true);
                    }
                    $("#id_pdar_pan_no").val(data.pdar_pan_no);
                    $("#edit_pdar_citizen_no").val(data.pdar_citizen_no);
                    $('#edit_pattadar').modal('show');
                } else {
                    $.confirm({
                        title: 'Encountered an error!',
                        content: data.msg,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function() {}
                        }
                    });
                }
            },
            error: function() {
                $.confirm({
                    title: 'Encountered an error!',
                    content: data.msg,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        close: function() {}
                    }
                });
            }
        });

    }

    function insertPattadars() {
        $.confirm({
            title: 'Please confirm to Insert pattadar!',
            content: 'Once inserted. Can\'t be rolled back.',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Confirm',
                    btnClass: 'btn-warning',
                    action: function() {
                        var baseurl = $("#base").val();
                        $("#insert_button").attr("disabled", true);
                        $.ajax({
                            dataType: "json",
                            url: baseurl + "index.php/ChithaDagEditController/insertPattadars",
                            data: $('#insert_form').serialize(),
                            type: "POST",
                            success: function(data) {
                                $("#insert_button").attr("disabled", false);
                                if (data.st == 1) {
                                    $.confirm({
                                        title: 'Pattdar inserted successfully.',
                                        content: data.msg,
                                        type: 'green',
                                        typeAnimated: true,
                                        buttons: {
                                            Ok: {
                                                text: 'Ok',
                                                btnClass: 'btn-green',
                                                action: function() {
                                                    location.reload()
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    $.confirm({
                                        title: 'Encountered an error!',
                                        content: data.msg,
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            close: function() {}
                                        }
                                    });
                                }
                            },
                            error: function(e) {
                                $("#insert_button").attr("disabled", false);
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    action: function() {

                    }
                }
            }
        });

    }

    function gpattadar() {
        var baseurl = $("#base").val();
        var dag_no = $("#pdag").val();
        var current_dag_no = $("#dag_no").val();
        var patta_type_code = $("#patta_type_code").val();
        var patta_no = $("#patta_no").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/get_filtered_pattadars",
            data: {
                'dag_no': dag_no,
                'patta_no': patta_no,
                'patta_type_code': patta_type_code,
                'current_dag_no': current_dag_no
            },
            type: "POST",
            success: function(data) {
                $("#filtered_pattadars").html(data.msg);
            }
        });
    }

    function getNewPattadarId() {
        var baseurl = $("#base").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/getNewPattadarId",
            data: $('form').serialize(),
            type: "POST",
            success: function(data) {
                $("#pattadar_id").val(data);
            }
        });
    }

    function storePattadar() {
        $("#submit_button").attr('disabled', true);
        var baseurl = $("#base").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/storePattadar",
            data: $('#add_pattadar_form').serialize(),
            type: "POST",
            success: function(data) {
                $("#submit_button").attr('disabled', false);
                if (data.st == 1) {
                    $.confirm({
                        title: 'Pattdar Added successfully.',
                        content: data.msg,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            Ok: {
                                text: 'Ok',
                                btnClass: 'btn-green',
                                action: function() {
                                    location.reload()
                                }
                            }
                        }
                    });
                } else {
                    $.confirm({
                        title: 'Encountered an error!',
                        content: data.msg,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function() {}
                        }
                    });
                }
            },
            error: function(err) {
                $("#submit_button").attr('disabled', false);
                $.confirm({
                    title: 'Encountered an error!',
                    content: err,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        close: function() {}
                    }
                });
            }
        });
    }

    function updatePattadar() {
        $("#update_button").attr('disabled', true);
        var baseurl = $("#base").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/updatePattadar",
            data: $('#edit_pattadar_form').serialize(),
            type: "POST",
            success: function(data) {
                $("#update_button").attr('disabled', false);
                if (data.st == 1) {
                    $.confirm({
                        title: 'Pattdar Added successfully.',
                        content: data.msg,
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            Ok: {
                                text: 'Ok',
                                btnClass: 'btn-green',
                                action: function() {
                                    location.reload()
                                }
                            }
                        }
                    });
                } else {
                    $.confirm({
                        title: 'Encountered an error!',
                        content: data.msg,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function() {}
                        }
                    });
                }
            },
            error: function(err) {
                $("#update_button").attr('disabled', false);
                $.confirm({
                    title: 'Encountered an error!',
                    content: err,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        close: function() {}
                    }
                });
            }
        });
    }

    function finishProcess(){
        swal.fire("", "Dag has been splitted successfully !!", "success")
                        .then((value) => {
                            window.location = "location";
                        });
    }
</script>