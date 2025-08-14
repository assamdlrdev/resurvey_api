<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
</head>

<style>
    .row {
        margin-left: -5px;
        margin-right: -5px;

    }
</style>


<body>
    <!--div class="container bg-light p-0 border border-dark"-->
    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <div class="col-12 px-0 pb-3">
            <div class="bg-info text-white text-center py-2">
                <h3>Enter Order Details(Column 8)</h3>
            </div>
        </div>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row">

                <div class="col-sm-6">

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Order Serial No:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="col8order_cron_no" id="col8order_cron_no" value="<?php echo $ordersno ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Order Passed:</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" checked="checked" value="1" name="order_pass_yn">Yes&nbsp;&nbsp;
                                </label>

                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="0" name="order_pass_yn">No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Order Type(Mut/Part):</label>
                        <div class="col-sm-8">
                            <select name="order_type_code" class="form-control">
                                <option selected value="">Select</option>
                                <?php foreach ($fmutype as $row) { ?>
                                    <option value="<?php echo ($row->order_type_code); ?>" <?php if ($row->order_type_code == $fmutype1) { ?> selected <?php } ?>>
                                        <?php echo $row->order_type; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Nature of Transfer:</label>
                        <div class="col-sm-8">
                            <select name="nature_trans_code" class="form-control">
                                <option selected value="">Select</option>
                                <?php foreach ($ntrcode as $row) { ?>
                                    <option value="<?php echo ($row->trans_code); ?>" <?php if ($row->trans_code == $ntrcode1) { ?> selected <?php } ?>>
                                        <?php echo $row->trans_desc_as; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Mondal's Name:</label>
                        <div class="col-sm-8">
                            <select name="lm_code" class="form-control">
                                <option selected value="">Select</option>
                                <?php foreach ($lmname as $row) { ?>
                                    <option value="<?php echo ($row->lm_code); ?>" <?php if ($row->lm_code == $lmname1) { ?> selected <?php } ?>>
                                        <?php echo $row->lm_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Mondal's Sign:</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" checked="checked" value="1" name="lm_sign_yn">Yes&nbsp;&nbsp;
                                </label>

                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="0" name="lm_sign_yn">No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">LM Note Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="lm_date" name="lm_note_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Circle Officer's Name:</label>
                        <div class="col-sm-8">
                            <select name="co_code" class="form-control">
                                <option selected value="">Select</option>
                                <?php foreach ($coname as $row) { ?>
                                    <option value="<?php echo ($row->user_code); ?>" <?php if ($row->user_code == $coname1) { ?> selected <?php } ?>>
                                        <?php echo $row->username; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">CO's Sign:</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" checked="checked" value="1" name="co_sign_yn">Yes&nbsp;&nbsp;
                                </label>

                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="0" name="co_sign_yn">No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">CO Order Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="co_date" name="co_ord_date">
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Case No:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="case_no" name="case_no" placeholder="Case No">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Deed Registration No:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="deed_reg_no" name="deed_reg_no" placeholder="Deed Registration No">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Deed value:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="deed_reg_no" name="deed_reg_no" placeholder="Deed value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Deed date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="deed_date" name="deed_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center pb-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' /><input type="hidden" name="dag_no" id="dag_no" value='<?php echo $dag_no ?>' />
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="orderent();"></input>
                <input type="button" class="btn btn-primary" id="onext" name="onext" value="Next" onclick="tntentry();"></input>
                <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
            </div>
        </form>
    </div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>