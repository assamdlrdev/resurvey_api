<style>
    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    th,
    td {
        text-align: left;
        padding: 5px 16px !important;
    }

    tr {
        border-top: hidden;
    }
</style>
<div class="container">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
    <div class="row">
        <div class="col-md-12 my-3 p-0">
            District : <?php echo $this->utilityclass->getDistrictName($this->session->userdata('dag_dist_code')); ?> , Circle : <?php echo $this->utilityclass->getCircleName($this->session->userdata('dag_dist_code'), $this->session->userdata('dag_subdiv_code'), $this->session->userdata('dag_cir_code')); ?> , Mouza : <?php echo $this->utilityclass->getMouzaName($this->session->userdata('dag_dist_code'), $this->session->userdata('dag_subdiv_code'), $this->session->userdata('dag_cir_code'), $this->session->userdata('dag_mouza_pargona_code')); ?> , Lot : <?php echo $this->utilityclass->getLotName($this->session->userdata('dag_dist_code'), $this->session->userdata('dag_subdiv_code'), $this->session->userdata('dag_cir_code'), $this->session->userdata('dag_mouza_pargona_code'), $this->session->userdata('dag_lot_no')); ?> , Village : <?php echo $this->utilityclass->getVillageName($this->session->userdata('dag_dist_code'), $this->session->userdata('dag_subdiv_code'), $this->session->userdata('dag_cir_code'), $this->session->userdata('dag_mouza_pargona_code'), $this->session->userdata('dag_lot_no'), $this->session->userdata('dag_vill_code')); ?>, Patta Type : <?php echo ($this->utilityclass->getPattaType($this->session->userdata('dag_patta_type_code'))) ?>, Patta No : <?php echo ($this->session->userdata('dag_patta_no')) ?>, Dag No : <?php echo ($dag->dag_no) ?>
        </div>
    </div>
    <form id="dag_details_form" class='form-horizontal mt-3' method="post" action="updateDag" enctype="multipart/form-data">
        <div class="row bg-light border border-secondary mb-3">
            <div class="col-12 px-0 pb-3">
                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo ($this->session->flashdata('error')); ?>
                    </div>
                <?php endif; ?>
                <div class="bg-info text-white text-center py-2">
                    <h3>Enter Basic Details(Column 1-6)</h3>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Old Dag No:</td>
                            <td>
                                <input readonly type="text" class="form-control form-control-sm " value="<?php echo $dag->dag_no ?>" name="old_dag_no">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Dag No: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <input id="new_dag_no" type="text" class="form-control form-control-sm" name="dag_no">
                                <span class="text-danger"><?= form_error("dag_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Old Patta Type: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <select disabled name="old_patta_type_code" id="old_patta_type_code" class="form-control">
                                    <option selected value="">Select</option>
                                    <?php foreach ($pattype as $row) { ?>
                                        <option value="<?php echo ($row->type_code); ?>" <?php echo (($row->type_code == $dag->patta_type_code) ? 'selected' : '') ?>>
                                            <?php echo $row->patta_type; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?= form_error("old_patta_type_code") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               Old Patta No: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <input readonly type="text" class="form-control form-control-sm" value="<?php echo ($dag->patta_no) ?>" id="old_patta_no" name="old_patta_no">
                                <span class="text-danger"><?= form_error("old_patta_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                New Patta Type: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <select name="patta_type_code" id="patta_type_code" class="form-control">
                                    <option selected value="">Select</option>
                                    <?php foreach ($pattype as $row) { ?>
                                        <option value="<?php echo ($row->type_code); ?>" <?php echo (($row->type_code == $dag->patta_type_code) ? 'selected' : '') ?>>
                                            <?php echo $row->patta_type; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?= form_error("patta_type_code") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               New Patta No: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->patta_no) ?>" id="patta_no" name="patta_no">
                                <span class="text-danger"><?= form_error("patta_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Grant No(if any):</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_nlrg_no) ?>" id="dag_nlrg_no" name="dag_nlrg_no">
                                <span class="text-danger"><?= form_error("dag_nlrg_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Land class: <span class="text-danger">*</span>
                            </td>
                            <td>
                                <select name="land_class_code" id="land_class_code" class="form-control">
                                    <option selected value="">Select</option>
                                    <?php foreach ($lclass as $row) : ?>
                                        <option value="<?php echo ($row->class_code); ?>" <?php echo ($row->class_code == $dag->land_class_code ? 'selected' : '') ?>><?php echo $row->land_type; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-danger"><?= form_error("land_class_code") ?></span>
                            </td>
                        </tr>
                        <?php if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22') || ($this->session->userdata('dag_dist_code') == '23')) { ?>
                            <tr>
                                <td>
                                    Dag Area Bigha: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_b) ?>" class="form-control form-control-sm bklB" id="dag_area_bigha" name="dag_area_b">
                                    <span class="text-danger"><?= form_error("dag_area_b") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Katha: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_k) ?>" class="form-control form-control-sm bklB" id="dag_area_katha" name="dag_area_k">
                                    <span class="text-danger"><?= form_error("dag_area_k") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Chatak: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_lc) ?>" class="form-control form-control-sm bklB" id="dag_area_lessa" name="dag_area_lc">
                                    <span class="text-danger"><?= form_error("dag_area_lc") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Ganda: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_g) ?>" class="form-control form-control-sm bklB" id="dag_area_ganda" name="dag_area_g">
                                    <span class="text-danger"><?= form_error("dag_area_g") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Are: <font color=red></font>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_are) ?>" class="form-control form-control-sm areB" id="dag_area_are_b" name="dag_area_r">
                                    <span class="text-danger"><?= form_error("dag_area_r") ?></span>
                                </td>
                            </tr>
                        <?php } else { ?>

                            <tr>
                                <td>
                                    Dag Area Bigha: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_b) ?>" class="form-control form-control-sm bkl bigha" id="dag_area_bigha" name="dag_area_b">
                                    <span class="text-danger"><?= form_error("dag_area_b") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Katha: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_k) ?>" class="form-control form-control-sm bkl katha" id="dag_area_katha" name="dag_area_k">
                                    <span class="text-danger"><?= form_error("dag_area_k") ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dag Area Lessa: <span class="text-danger">*</span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_lc) ?>" class="form-control form-control-sm bkl lessa" id="dag_area_lessa" name="dag_area_lc">
                                    <span class="text-danger"><?= form_error("dag_area_lc") ?></span>
                                </td>
                            </tr>
                            <input type="hidden" class="form-control form-control-sm" id="dag_area_ganda" name="dag_area_g">
                            <tr>
                                <td>Dag Area Are: <font color=red></font>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo ($dag->dag_area_are) ?>" class="form-control form-control-sm are" id="dag_area_are" name="dag_area_r">
                                    <span class="text-danger"><?= form_error("dag_area_r") ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="table-responsive">
                    <table class="table" border=0>
                        <tr>
                            <td>Direct Paying ?</td>
                            <td>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" <?php echo ($dag->dp_flag_yn == 'Y' ? 'checked' : '') ?> class="form-check-input" value='Y' name="dp_flag_yn">Yes
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" <?php echo ($dag->dp_flag_yn == 'N' ? 'checked' : '') ?> class="form-check-input" checked="checked" value='N' name="dp_flag_yn">No
                                    </label>
                                </div>
                                <span class="text-danger"><?= form_error("dp_flag_yn") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Dag Land Revenue <span class="text-danger">*</span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_revenue) ?>" id="dag_land_revenue" name="dag_land_revenue" onkeyup="dagamtcal();">
                                <span class="text-danger"><?= form_error("dag_land_revenue") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dag Local Rate</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_local_tax) ?>" id="dag_local_tax" name="dag_local_tax" value="0">
                                <span class="text-danger"><?= form_error("dag_local_tax") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>North Description</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_n_desc) ?>" id="north_descp" name="dag_n_desc">
                                <span class="text-danger"><?= form_error("dag_n_desc") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>South Description</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_s_desc) ?>" id="south_descp" name="dag_s_desc">
                                <span class="text-danger"><?= form_error("dag_s_desc") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>East Description</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_e_desc) ?>" id="east_descp" name="dag_e_desc">
                                <span class="text-danger"><?= form_error("dag_e_desc") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>West Description</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_w_desc) ?>" id="west_descp" name="dag_w_desc">
                                <span class="text-danger"><?= form_error("dag_w_desc") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dag No(North Side)</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_n_dag_no) ?>" id="dag_no_north" name="dag_n_dag_no">
                                <span class="text-danger"><?= form_error("dag_n_dag_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dag No(South Side)</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_s_dag_no) ?>" id="dag_no_south" name="dag_s_dag_no">
                                <span class="text-danger"><?= form_error("dag_s_dag_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dag No(East Side)</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_e_dag_no) ?>" id="dag_no_east" name="dag_e_dag_no">
                                <span class="text-danger"><?= form_error("dag_e_dag_no") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Dag No(West Side)</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ($dag->dag_w_dag_no) ?>" id="dag_no_west" name="dag_w_dag_no">
                                <span class="text-danger"><?= form_error("dag_w_dag_no") ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-12 text-center pb-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <a class="btn btn-warning text-white" href="location">CANCEL</a>
                <button disabled id="update_submit_btn" class="btn btn-primary" type="button" onclick="updateDag()">SUBMIT</button>
            </div>
        </div>
    </form>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function updateDag() {
        $.confirm({
            title: 'Confirm!',
            content: 'Please confirm to update the dag!',
            buttons: {
                confirm: {
                    text: 'Confirm!',
                    btnClass: 'btn-red',
                    action: function(confirm) {
                        $.confirm({
                            title: 'Confirm!',
                            content: 'Once updated.You can not rollback.!',
                            buttons: {
                                confirm: {
                                    text: 'Ok,Proceed!',
                                    btnClass: 'btn-red',
                                    action: function(confirm) {
                                        $("#dag_details_form").submit()
                                    }
                                },
                                cancel: function() {

                                }
                            }
                        });
                    }
                },
                cancel: function() {

                }
            }
        });
    }

    $("#new_dag_no").on("change",function(value){
        checkDagDuplicate($("#new_dag_no").val());
    });
    function checkDagDuplicate(dag_no) {
        var baseurl = $("#base").val();
        $("#update_submit_btn").attr("disabled",true);
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/ChithaDagEditController/isDagExists",
            data: {
                'new_dag_no': dag_no
            },
            type: "POST",
            beforeSend: function() {
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function(data) {
                $.unblockUI();
                if (data.st == 1) {
                    $.confirm({
                        title: 'Dag no already exists !!',
                        content: 'Please Enter a Different Dag No !!',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            Cancel: {
                                text: 'OK',
                                btnClass: 'btn-danger',
                                action: function() {
                                    document.getElementById("new_dag_no").value = "";
                                }
                            },
                            // Edit: {
                            //     text: 'Edit',
                            //     btnClass: 'btn-blue',
                            //     action: function() {
                            //         window.location.href = 'editExistingDag/'+dag_no;
                            //     }
                            // }
                        }
                    });
                } else {
                    $("#update_submit_btn").attr("disabled", false);
                }
            },
            error: function(jqXHR, exception) {
                $("#update_submit_btn").attr("disabled",falses);
                $.unblockUI();
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
    }

    $('.bkl').keyup(function() {
        var b = $('#dag_area_bigha').val();
        var k = $('#dag_area_katha').val();
        var l = $('#dag_area_lessa').val();

        if (b == '') b = 0;
        if (k == '') k = 0;
        if (l == '') l = 0;

        var area_lessa = parseFloat(b) * 100 + parseFloat(k) * 20 + parseFloat(l);

        var area_are = area_lessa * (100 / 747.45);

        var total_area_are = parseFloat(area_are).toFixed(5);

        $("#dag_area_are").val(total_area_are);
    });

    $('.are').keyup(function() {
        var r = $('#dag_area_are').val();
        if (r > 438397.21719) {
            alert('Enter Are area within 0 to 438397.21719');
        }

        var area_B = $('#dag_area_bigha').val();
        var area_K = $('#dag_area_katha').val();
        var area_LC = $('#dag_area_lessa').val();
        var area_Are = $('#dag_area_are').val();

        if (area_Are == '') area_Are = 0;
        if (area_B == '') area_B = 0;
        if (area_K == '') area_K = 0;
        if (area_LC == '') area_LC = 0;
        area_LC = parseFloat(area_Are) * (747.45 / 100);
        area_B = parseInt(area_LC / 100);
        area_LC = area_LC % 100; //Rest area
        area_K = parseInt(area_LC / 20);
        area_LC = area_LC % 20; //Rest area

        var total_area_LC = parseFloat(area_LC).toFixed(4);

        $("#dag_area_bigha").val(area_B);
        $("#dag_area_katha").val(area_K);
        $("#dag_area_lessa").val(total_area_LC);
    });




    $('.bklB').keyup(function() {
        var b = $('#dag_area_bigha').val();
        var k = $('#dag_area_katha').val();
        var l = $('#dag_area_lessa').val();
        var g = $('#dag_area_ganda').val();

        if (b == '') b = 0;
        if (k == '') k = 0;
        if (l == '') l = 0;
        if (g == '') g = 0;

        var area_ganda = parseFloat(b) * 6400 + parseFloat(k) * 320 + parseFloat(l) * 20 + parseFloat(g);
        var area_are_b = area_ganda * (13.37804 / 6400);
        var total_area_are_b = parseFloat(area_are_b).toFixed(4);

        $("#dag_area_are_b").val(total_area_are_b);
    });

    $('.are').keyup(function() {

        var r = $('#dag_area_are_b').val();
        if (r > 133788.21325) {
            alert('Enter Are area within 0 to 133788.21325');
        }

        var area_B = $('#dag_area_bigha').val();
        var area_K = $('#dag_area_katha').val();
        var area_LC = $('#dag_area_lessa').val();
        var area_GN = $('#dag_area_ganda').val();
        var area_Are = $('#dag_area_are').val();

        if (area_Are == '') area_Are = 0;
        if (area_B == '') area_B = 0;
        if (area_K == '') area_K = 0;
        if (area_LC == '') area_LC = 0;
        if (area_G == '') area_G = 0;

        area_G = parseFloat(area_Are) * (6400 / 13.37804);
        area_B = parseInt(area_G / 6400);
        area_G = area_G % 6400; //Rest area
        area_K = parseInt(area_G / 320);
        area_G = area_G % 320
        area_LC = parseInt(area_G / 20);
        G = area_G % 20; //Rest area

        var total_area_g = parseFloat(G).toFixed(4);
        $("#dag_area_bigha").val(area_B);
        $("#dag_area_katha").val(area_K);
        $("#dag_area_lessa").val(area_LC);
        $("#dag_area_ganda").val(total_area_g);
    });
</script>