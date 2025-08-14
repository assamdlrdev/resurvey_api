<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <style>
        .card {
            margin: 0 auto;
            /* Added */
            float: none;
            /* Added */
            margin-bottom: 10px;
            /* Added */
            margin-top: 50px;
        }


        * {
            box-sizing: border-box;
        }

        .row {
            margin-left: -5px;
            margin-right: -5px;
        }

        .column {
            float: left;
            width: 50%;
            padding: 5px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            /* border: 1px solid #ddd;*/
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
</head>

<body>

    <div class="container">
        <?php if ($locationname["dist_name"] != null) {
            echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'];
        }
        ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Entry/Edit Basic Details(Column 1-6)</h3>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table" border=0>
                            <input type="hidden" name="dcode" id="dcode" value="<?php echo $this->session->userdata('dist_code'); ?>" />
                            <input type="hidden" name="scode" id="scode" value="<?php echo $this->session->userdata('subdiv_code'); ?>" />
                            <input type="hidden" name="ccode" id="ccode" value="<?php echo $this->session->userdata('cir_code'); ?>" />
                            <input type="hidden" name="mcode" id="mcode" value="<?php echo $this->session->userdata('mouza_pargona_code'); ?>" />
                            <input type="hidden" name="lcode" id="lcode" value="<?php echo $this->session->userdata('lot_no'); ?>" />
                            <input type="hidden" name="vcode" id="vcode" value="<?php echo $this->session->userdata('vill_townprt_code'); ?>" />


                            <tr>
                                <td>Old Dag No:</td>
                                <td><input type="text" class="form-control form-control-sm " id="old_dag_no" name="old_dag_no"></td>

                            </tr>
                            <tr>
                                <td>Dag No: <font color=red>*</font>
                                </td>
                                <td><input type="text" class="form-control form-control-sm bkl" id="dag_no" name="dag_no" onchange="newdagchk();"></td>

                            </tr>
                            <tr>
                                <td>Patta Type: <font color=red>*</font>
                                </td>
                                <td>

                                    <select name="patta_type_code" id="patta_type_code" class="form-control">
                                        <option selected value="">Select</option>
                                        <?php foreach ($pattype as $row) { ?>
                                            <option value="<?php echo ($row->type_code); ?>" <?php if ($row->type_code == $pcode) { ?> selected <?php } ?>>
                                                <?php echo $row->patta_type; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Patta No: <font color=red>*</font>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" id="patta_no" name="patta_no"></td>
                            </tr>
                            <tr>
                                <td>Grant No(if any):</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_nlrg_no" name="dag_nlrg_no"></td>
                            </tr>
                            <tr>
                                <td>Land class: <font color=red>*</font>
                                </td>
                                <td>
                                    <select name="land_class_code" id="land_class_code" class="form-control">
                                        <option selected value="">Select</option>
                                        <?php foreach ($lclass as $row) { ?>
                                            <option value="<?php echo ($row->class_code); ?>" <?php if ($row->class_code == $lcode) { ?> selected <?php } ?>>
                                                <?php echo $row->land_type; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
                                <tr>
                                    <td>Dag Area Bigha: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bklB" id="dag_area_bigha" name="dag_area_b"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Katha: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bklB" id="dag_area_katha" name="dag_area_k"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Chatak: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bklB" id="dag_area_lessa" name="dag_area_lc"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Ganda: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bklB" id="dag_area_ganda" name="dag_area_g"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Are: <font color=red></font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm areB" id="dag_area_are_b" name="dag_area_r"></td>
                                </tr>
                            <?php } else { ?>

                                <tr>
                                    <td>Dag Area Bigha: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bkl bigha" id="dag_area_bigha" name="dag_area_b"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Katha: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bkl katha" id="dag_area_katha" name="dag_area_k"></td>
                                </tr>
                                <tr>
                                    <td>Dag Area Lessa: <font color=red>*</font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm bkl lessa" id="dag_area_lessa" name="dag_area_lc"></td>
                                </tr>
                                <input type="hidden" class="form-control form-control-sm" id="dag_area_ganda" name="dag_area_g">
                                <tr>
                                    <td>Dag Area Are: <font color=red></font>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm are" id="dag_area_are" name="dag_area_r"></td>
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
                                            <input type="radio" class="form-check-input" value='Y' name="dp_flag_yn">Yes
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked="checked" value='N' name="dp_flag_yn">No
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Dag Land Revenue <font color=red>*</font>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_land_revenue" name="dag_land_revenue" onkeyup="dagamtcal();"></td>
                            </tr>
                            <tr>
                                <td>Dag Local Rate</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_local_tax" name="dag_local_tax" value="0"></td>
                            </tr>
                            <tr>
                                <td>North Description</td>
                                <td><input type="text" class="form-control form-control-sm" id="north_descp" name="dag_n_desc"></td>
                            </tr>
                            <tr>
                                <td>South Description</td>
                                <td><input type="text" class="form-control form-control-sm" id="south_descp" name="dag_s_desc"></td>
                            </tr>
                            <tr>
                                <td>East Description</td>
                                <td><input type="text" class="form-control form-control-sm" id="east_descp" name="dag_e_desc"></td>
                            </tr>
                            <tr>
                                <td>West Description</td>
                                <td><input type="text" class="form-control form-control-sm" id="west_descp" name="dag_w_desc"></td>
                            </tr>
                            <tr>
                                <td>Dag No(North Side)</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_no_north" name="dag_n_dag_no"></td>
                            </tr>
                            <tr>
                                <td>Dag No(South Side)</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_no_south" name="dag_s_dag_no"></td>
                            </tr>
                            <tr>
                                <td>Dag No(East Side)</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_no_east" name="dag_e_dag_no"></td>
                            </tr>
                            <tr>
                                <td>Dag No(West Side)</td>
                                <td><input type="text" class="form-control form-control-sm" id="dag_no_west" name="dag_w_dag_no"></td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" name="newpatta" id="newpatta" value="" />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <input type="button" class="btn btn-primary" id="dsubmit" name="dsubmit" value="Submit" onclick="dagentry();"></input>
                </div>
            </div>
        </form>
    </div>
</body>
<script>
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

        // alert(r)

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

    $('.areB').keyup(function() {

        var r = $('#dag_area_are_b').val();
        if (r > 133788.21325) {
            alert('Enter Are area within 0 to 133788.21325');
        }
        var area_B = $('#dag_area_bigha').val();
        var area_K = $('#dag_area_katha').val();
        var area_LC = $('#dag_area_lessa').val();
        var area_GN = $('#dag_area_ganda').val();
        var area_Are = $('#dag_area_are_b').val();


        if (area_Are == '') area_Are = 0;
        if (area_B == '') area_B = 0;
        if (area_K == '') area_K = 0;
        if (area_LC == '') area_LC = 0;
        if (area_GN == '') area_GN = 0;

        area_GN = parseFloat(area_Are) * (6400 / 13.37804);
        area_B = parseInt(area_GN / 6400);
        area_GN = area_GN % 6400; //Rest area
        area_K = parseInt(area_GN / 320);
        area_GN = area_GN % 320
        area_LC = parseInt(area_GN / 20);
        G = area_GN % 20; //Rest area

        var total_area_g = parseFloat(G).toFixed(4);

        //alert(total_area_g)

        $("#dag_area_bigha").val(area_B);
        $("#dag_area_katha").val(area_K);
        $("#dag_area_lessa").val(area_LC);
        $("#dag_area_ganda").val(total_area_g);

    });
</script>

</html>

<script src="<?= base_url('assets/js/location.js?v=1.2') ?>"></script>