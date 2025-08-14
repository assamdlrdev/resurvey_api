<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>


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
    <?php echo form_open('Chithacontrol/crop'); ?>
    <!--<div class="card-title font-weight-bold">Chitha Data Entry</div>-->

    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $daghd ?><?php echo $landhd ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="<?php echo base_url() . 'index.php/Chithacontrol/BasicDetailsSubmit' ?>" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Edit of Crop Details (Col 14-17,20-23,26-29)</h3>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="table-responsive">
                        <table class="table" border=0>

                            <tr>
                                <td>Crop Id:</td>
                                <td>
                                    <input type="text" value="<?php echo $cropId ?>" class="form-control form-control-sm" id="cropslno" name="cropslno" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Year:<font color=red>*</font>
                                </td>
                                <td>
                                    <?php $mm = date('Y') ?>
                                    <select class="form-control-sm" id="yr" name="yearno">
                                        <option><?php echo $mm ?></option>
                                        <option><?php echo $mm - 1 ?></option>
                                        <option><?php echo $mm - 2 ?></option>
                                        <option value="<?php echo ($cropD->yearno); ?>" <?php if ($cropD->yearno) { ?> selected <?php } ?>>
                                            <?php echo $cropD->yearno; ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Crop Name:<font color=red>*</font>
                                </td>
                                <td>
                                    <select class="form-control" name="cropname" id="crop_code">
                                        <option value="">Select</option>
                                        <?php foreach ($cropnm as $row) { ?>
                                            <option value="<?php echo $row->crop_code; ?>" <?php if ($row->crop_code == $cropD->crop_code) { ?> selected <?php } ?>>
                                                <?php echo $row->crop_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Crop Category:</td>
                                <td>
                                    <select class="form-control" name="cropcatg" id="cropcatg">
                                        <option value="">Select</option>
                                        <?php foreach ($cropcat as $row) { ?>
                                            <option value="<?php echo $row->crop_categ_code; ?>" <?php if ($row->crop_categ_code == $cropD->crop_categ_code) { ?> selected <?php } ?>>
                                                <?php echo $row->crop_categ_desc; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Crop Season:</td>
                                <td>
                                    <select class="form-control" name="cropseason" id="cropsn">
                                        <option value="">Select</option>
                                        <?php foreach ($cropsn as $row) { ?>
                                            <option value="<?php echo $row->season_code; ?>" <?php if ($row->season_code == $cropD->crop_season) { ?> selected <?php } ?>>
                                                <?php echo $row->crop_season; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Water Source:</td>
                                <td>
                                    <select class="form-control" name="sourcewater" id="watersrc">
                                        <option value="">Select</option>
                                        <?php foreach ($watersrc as $row) { ?>
                                            <option value="<?php echo $row->water_source_code; ?>" <?php if ($row->water_source_code == $cropD->source_of_water) { ?> selected <?php } ?>>
                                                <?php echo $row->source; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php if (($this->session->userdata('dist_code') == '21') or ($this->session->userdata('dist_code') == '22') or ($this->session->userdata('dist_code') == '23')) { ?>
                                <tr>
                                    <td>Crop Land Bigha:</td>
                                    <td><input type="text" class="form-control-sm" id="cropb" name="croparea_b" value="<?php echo $cropD->crop_land_area_b; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Crop Land Katha:</td>
                                    <td><input type="text" class="form-control-sm" id="cropk" name="croparea_k" value="<?php echo $cropD->crop_land_area_k; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Crop Land Chatak:</td>
                                    <td><input type="text" class="form-control-sm" id="croplc" name="croparea_lc" value="<?php echo $cropD->crop_land_area_lc; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Crop Land Ganda:</td>
                                    <td><input type="text" class="form-control-sm" id="cropg" name="croparea_g" value="<?php echo $cropD->crop_land_area_g; ?>"></td>
                                </tr>

                            <?php } else { ?>
                                <tr>
                                    <td>Crop Land Bigha:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="cropb" name="croparea_b" value="<?php echo $cropD->crop_land_area_b; ?>">
                                        <span style="color: #D32F2F">Max Size : 32767</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Crop Land Katha:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="cropk" name="croparea_k" value="<?php echo $cropD->crop_land_area_k; ?>">
                                        <span style="color: #D32F2F">Max Size : 4</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Crop Land Lessa:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="croplc" name="croparea_lc" value="<?php echo $cropD->crop_land_area_lc; ?>">
                                        <span style="color: #D32F2F">Max Size : 19.9</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td>
                                        <table style="width: 100%!important;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 0px!important;">
                                                        Total Land : <span id="totalLand"><?php echo $landInLessa ?></span> <?php echo ' ' . $landType ?>
                                                    </td>
                                                    <td style="padding: 0px!important;">
                                                        ( Bigha : <span id="totalBigha"><?php echo $this->session->userdata('bigha') ?> </span>
                                                        &nbsp;&nbsp; Katha : <span id="totalKatha"><?php echo $this->session->userdata('katha') ?> </span>
                                                        &nbsp;&nbsp; Lessa : <span id="totalLessa"><?php echo $this->session->userdata('lessa') ?> </span> )
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px!important;">Used Land : <span id="totalUsed">0 </span> <?php echo $landType ?></td>
                                                    <td style="padding: 0px!important;">
                                                        ( Bigha : <span id="usedBigha">0</span>
                                                        &nbsp;&nbsp; Katha : <span id="usedKatha">0 </span>
                                                        &nbsp;&nbsp; Lessa : <span id="usedLessa">0</span> )
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px!important;"> Remaining Land : <span id="totalRemaining">0 </span> <?php echo $landType ?></td>
                                                    <td style="padding: 0px!important;">
                                                        ( Bigha : <span id="remBigha">0</span>
                                                        &nbsp;&nbsp; Katha : <span id="remKatha">0 </span>
                                                        &nbsp;&nbsp; Lessa : <span id="remLessa">0 </span> )
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <input type='button' class="btn btn-primary" id="modten" name="modten" value="Edit Crop" onclick="cropList();"></input>
                    <input type='button' class="btn btn-primary" id="cropsubmit" name="cropsubmit" value="Submit" onclick="updateCropDetails();"></input>
                    <input type='button' class="btn btn-primary" id="cnext" name="cnext" value="Next" onclick="ncrpentry();"></input>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    var year = $("#yr option:selected").val();
    var baseurl = $("#base").val();
    var nn = $("#totalLand").html();
    $.ajax({
        url: baseurl + 'index.php/Chithacontrol/getLandDetailsInCrop',
        type: 'POST',
        data: {
            year: year
        },
        error: function() {
            alert('Something is wrong');
        },
        success: function(data) {
            var kk = $.trim(data);
            var rr = nn - kk;

            var bigha = parseFloat(kk / 100).toFixed(2);
            var onlyBigha = Math.floor(bigha);

            var rem_lessa = 100 * parseFloat(bigha - Math.floor(bigha));
            var katha = parseFloat(rem_lessa / 20).toFixed(2);
            var onlyKatha = Math.floor(katha);

            var lessa = 100 * parseFloat(katha - Math.floor(katha));
            var onlyLessa = parseFloat(lessa / 5).toFixed(2);


            var remBigha = parseFloat(rr / 100).toFixed(2);
            var remOnlyBigha = Math.floor(remBigha);

            var rem_lessa1 = 100 * parseFloat(remBigha - Math.floor(remBigha));
            var rKatha = parseFloat(rem_lessa1 / 20).toFixed(2);
            var remOnlyKatha = Math.floor(rKatha);

            var remLessa = 100 * parseFloat(rKatha - Math.floor(rKatha));
            var remOnlyLessa = parseFloat(remLessa / 5).toFixed(2);

            $("#totalUsed").html(kk);
            $("#totalRemaining").html(rr);

            $("#usedBigha").html(onlyBigha);
            $("#usedKatha").html(onlyKatha);
            $("#usedLessa").html(onlyLessa);

            $("#remBigha").html(remOnlyBigha);
            $("#remKatha").html(remOnlyKatha);
            $("#remLessa").html(remOnlyLessa);

        }
    });



    $('#yr').on('change', function() {
        var year = this.value;
        var baseurl = $("#base").val();
        var nn = $("#totalLand").html();
        $.ajax({
            url: baseurl + 'index.php/Chithacontrol/getLandDetailsInCrop',
            type: 'POST',
            data: {
                year: year
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {
                var kk = $.trim(data);
                var rr = nn - kk;

                var bigha = parseFloat(kk / 100).toFixed(2);
                var onlyBigha = Math.floor(bigha);

                var rem_lessa = 100 * parseFloat(bigha - Math.floor(bigha));
                var katha = parseFloat(rem_lessa / 20).toFixed(2);
                var onlyKatha = Math.floor(katha);

                var lessa = 100 * parseFloat(katha - Math.floor(katha));
                var onlyLessa = parseFloat(lessa / 5).toFixed(2);


                var remBigha = parseFloat(rr / 100).toFixed(2);
                var remOnlyBigha = Math.floor(remBigha);

                var rem_lessa1 = 100 * parseFloat(remBigha - Math.floor(remBigha));
                var rKatha = parseFloat(rem_lessa1 / 20).toFixed(2);
                var remOnlyKatha = Math.floor(rKatha);

                var remLessa = 100 * parseFloat(rKatha - Math.floor(rKatha));
                var remOnlyLessa = parseFloat(remLessa / 5).toFixed(2);

                $("#totalUsed").html(kk);
                $("#totalRemaining").html(rr);

                $("#usedBigha").html(onlyBigha);
                $("#usedKatha").html(onlyKatha);
                $("#usedLessa").html(onlyLessa);

                $("#remBigha").html(remOnlyBigha);
                $("#remKatha").html(remOnlyKatha);
                $("#remLessa").html(remOnlyLessa);
            }
        });

    });
</script>

</html>
<script src="<?= base_url('assets/js/location.js') ?>"></script>