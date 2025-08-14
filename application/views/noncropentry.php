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
    <?php echo form_open('Chithacontrol/noncrop'); ?>

    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Entry of Non-agricultural Land use details(Col 12-13,18-19,24-25)</h3>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="table-responsive">
                        <table class="table" border=0>

                            <tr>
                                <td>Non-agri use Id:</td>
                                <td><input type="text" value="<?php echo $noncropId; ?>" class="form-control-sm" id="ncropslno" name="ncropslno"></td>
                            </tr>
                            <tr>
                                <td>Year:<font color=red>*</font>
                                </td>
                                <td>
                                    <?php $mm = date('Y') ?>
                                    <select class="form-control-sm" id="yr" name="yearno">
                                        <option value=""> Select </option>
                                        <option><?php echo $mm ?></option>
                                        <option><?php echo $mm - 1 ?></option>
                                        <option><?php echo $mm - 2 ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Type of Usage:<font color=red>*</font>
                                </td>
                                <td>
                                    <select class="form-control" name="ncropcode" id="ncropcode">
                                        <option value="">Select</option>
                                        <?php foreach ($ncropnm as $row) { ?>
                                            <option value="<?php echo $row->used_noncrop_type_code; ?>" <?php if ($row->used_noncrop_type_code == $ncropnm1) { ?> selected <?php } ?>>
                                                <?php echo $row->noncrop_type; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
                                <tr>
                                    <td>Non-agri Land Bigha:</td>
                                    <td><input type="text" class="form-control-sm" id="cropb" name="ncroparea_b" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Non-agri Land Katha:</td>
                                    <td><input type="text" class="form-control-sm" id="cropk" name="ncroparea_k" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Non-agri Land Chatak:</td>
                                    <td><input type="text" class="form-control-sm" id="croplc" name="ncroparea_lc" value="0"></td>
                                </tr>
                                <tr>
                                    <td>Non-agri Land Ganda:</td>
                                    <td><input type="text" class="form-control-sm" id="cropk" name="ncroparea_k" value="0"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        Total Land : <span id="totalLand"><?php echo $landInLessa ?></span> <?php echo ' ' . $landType ?>
                                        <br>Used Land : <span id="totalUsed">0 </span> <?php echo $landType ?>
                                        <br>Remaining Land : <span id="totalRemaining">0 </span> <?php echo $landType ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td>Non-agri Land Bigha:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="cropb" name="ncroparea_b" value="0">
                                        <span style="color: #D32F2F; padding-left: 10px">Max Size : 32767</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Non-agri Land Katha:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="cropk" name="ncroparea_k" value="0">
                                        <span style="color: #D32F2F; padding-left: 10px">Max Size : 4</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Non-agri Land Lessa:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="croplc" name="ncroparea_lc" value="0">
                                        <span style="color: #D32F2F; padding-left: 10px">Max Size : 19.9</span>
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
                                                        ( Bigha : <span id="usedBigha">0 </span>
                                                        &nbsp;&nbsp; Katha : <span id="usedKatha">0 </span>
                                                        &nbsp;&nbsp; Lessa : <span id="usedLessa">0 </span> )
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px!important;"> Remaining Land : <span id="totalRemaining">0 </span> <?php echo $landType ?></td>
                                                    <td style="padding: 0px!important;">
                                                        ( Bigha : <span id="remBigha">0 </span>
                                                        &nbsp;&nbsp; Katha : <span id="remKatha">0 </span>
                                                        &nbsp;&nbsp; Lessa : <span id="remLessa">0 </span> )
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                    </td>
                                </tr>

                            <?php } ?>
                        </table>
                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <?php if ($noncropId > 1) { ?>
                        <input type='button' class="btn btn-primary" id="modten" name="modten" value="Edit Non agricultural" onclick="nonCropList();"></input>
                    <?php } ?>
                    <input type='button' class="btn btn-primary" id="ncrpsubmit" name="ncrpsubmit" value="Submit" onclick="noncropentry();"></input>
                    <input type='button' class="btn btn-primary" id="nnext" name="nnext" value="Next" onclick="frtentry();"></input>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
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