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
    <script src="<?= base_url('assets/js/common.js') ?>"></script>
    <?php if ($this->session->userdata('dist_code') == '21') { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>

</head>

<body>
    <?php echo form_open('Chithacontrol/subtenant'); ?>

    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="<?php echo base_url() . 'index.php/Chithacontrol/tenantentry' ?>" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Entry of Subtenant Details(Column 11)</h3>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table" border=0>

                            <tr>
                                <td>Subtenant Id:</td>
                                <td><input type="text" value="<?php echo $subtenantId; ?>" class="form-control form-control-sm" id="subtenant_id" name="subtenant_id"></td>
                            </tr>
                            <tr>
                                <td>Corresponding Tenant Name:<font color=red>*</font>
                                </td>
                                <td>
                                    <select class="form-control relation-type" name="tenantid" id="tenantid">
                                        <option value="">Select</option>
                                        <?php foreach ($tntsub as $row) { ?>
                                            <option value="<?php echo $row->tenant_id; ?>" <?php if ($row->tenant_id == $tidsub) { ?> selected <?php } ?>>
                                                <?php echo $row->tenant_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Subtenant Name:<font color=red>*</font>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" id="subtname" name="subtennm" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
                            </tr>
                            <tr>
                                <td>Guardian's Name:<font color=red>*</font>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="subtg" name="subtenants_father" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                </td>
                                </td>
                            </tr>
                            <tr>
                                <td>Guardian's relation:<font color=red>*</font>
                                </td>
                                <td><select class="form-control relation-type" name="guard_rel" id="relation">
                                        <option value="">Select</option>
                                        <?php foreach ($relanm as $row) { ?>
                                            <option value="<?php echo $row->guard_rel; ?>" <?php if ($row->guard_rel == $relnm) { ?> selected <?php } ?>>
                                                <?php echo $row->guard_rel_desc_as; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Address line1:</td>
                                <td><input type="text" class="form-control form-control-sm" id="sadd1" name="subtenants_add1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
                            </tr>
                            <tr>
                                <td>Address line2:</td>
                                <td><input type="text" class="form-control form-control-sm" id="sadd2" name="subtenants_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
                            </tr>
                            <tr>
                                <td>Address line3:</td>
                                <td><input type="text" class="form-control form-control-sm" id="sadd3" name="subtenants_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <?php if ($subtenantId > 1) { ?>
                        <input type='button' class="btn btn-primary" id="modten" name="modten" value="Edit Subtenant" onclick="subTenantList();"></input>
                    <?php } ?>
                    <input type='button' class="btn btn-primary" id="stsubmit" name="stsubmit" value="Submit" onclick="subtenantentry();"></input>
                    <input type='button' class="btn btn-primary" id="tnext" name="tnext" value="Next" onclick="crpentry();"></input>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
<script src="<?= base_url('assets/js/location.js') ?>"></script>