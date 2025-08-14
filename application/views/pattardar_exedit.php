<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <style>
        .row {
            margin-left: -5px;
            margin-right: -5px;

        }
    </style>
    <script src="<?= base_url('assets/js/common.js') ?>"></script>
    <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>
</head>

<body>
    <!--div class="container bg-light p-0 border border-dark"-->
    <div class="container p-0 border">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <div class="col-12 px-0 pb-3">
            <div class="bg-info text-white text-center py-2">
                <h3>Edit Pattadar Details(Column 7)</h3>
            </div>
        </div>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row">
                <?php $is_govt = in_array($ptype, GovtPattaCode) ?>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Pattadar ID:</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputEmail3" name="pdar_id" value="<?php echo $pdarid; ?>" readonly>
                        </div>


                    </div>


                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Patta No:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputEmail3" placeholder="Patta No" value="<?php echo $pattano ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Patta Type</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPassword3" placeholder="Password" value="<?php echo $patta_type_name ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Pattadar Name:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="pdar_name" placeholder="Pattadar Name" name="pdar_name" value="<?php echo $pname ?>" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                        </div>
                    </div>
                    <?php if (!$is_govt) : ?>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian's Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_father" placeholder="Guardian's Name" name="pdar_father" value="<?php echo $pfname ?>" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian Relation:</label>
                            <div class="col-sm-9">
                                <select name="pdar_relation" class="form-control">
                                    <option selected value="">Select</option>
                                    <?php foreach ($relname as $row) { ?>
                                        <option value="<?php echo ($row->guard_rel); ?>" <?php if ($row->guard_rel == $relname1) { ?> selected <?php } ?>>
                                            <?php echo $row->guard_rel_desc_as; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Address 1:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_add1" placeholder="Address 1" name="pdar_add1" value="<?php echo $padd1 ?>" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Address 1:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_add2" placeholder="Address 2" value="<?php echo $padd2 ?>" name="pdar_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Address 3:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_add3" placeholder="Address 3" value="<?php echo $padd3 ?>" name="pdar_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                            </div>
                        </div>
                        <?php if ($this->session->userdata('dist_code') == '21') { ?>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_b" placeholder="dag_por_b" name="dag_por_b" value="<?php echo $bigha ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_k" placeholder="dag_por_k" name="dag_por_k" value="<?php echo $katha ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Chatak:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_lc" placeholder="dag_por_lc" name="dag_por_lc" value="<?php echo $lessa ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Ganda:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_g" placeholder="Dag Portion Ganda" name="dag_por_g" value="<?php echo $ganda ?>">
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_b" placeholder="dag_por_b" name="dag_por_b" value="<?php echo $bigha ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_k" placeholder="dag_por_k" name="dag_por_k" value="<?php echo $katha ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Lessa:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="dag_por_lc" placeholder="dag_por_lc" name="dag_por_lc" value="<?php echo $lessa ?>">
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="dag_por_g" placeholder="dag_por_lc" name="dag_por_g" value="0">
                        <?php } ?>
                    <?php endif; ?>
                </div>
                <?php if (!$is_govt) : ?>
                    <div class="col-sm-6">


                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Revenue:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_revenue" name="pdar_land_revenue" value="<?php echo $lrev ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Local Rate:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_localtax" name="pdar_land_localtax" value="<?php echo $ltax ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Land in Acre:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_acre" placeholder="Land in Acre:" value="0" name="pdar_land_acre">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">North Description:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_n" placeholder="North Description" name="pdar_land_n" value="<?php echo $landn ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">South Description:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_s" placeholder="South Description" name="pdar_land_s" value="<?php echo $lands ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">East Description:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_e" placeholder="East Description" name="pdar_land_e" value="<?php echo $lande ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">West Description:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_land_w" placeholder="West Description" name="pdar_land_w" value="<?php echo $landw ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Pattadar Strike Out?</label>
                            <div class="col-sm-9">
                                <div class="form-check-inline">
                                    <?php if ($pflag == 1) { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked="checked" value="1" name="p_flag">Yes&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="0" name="p_flag">No
                                        </label>
                                    <?php } else { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="1" name="p_flag">Yes&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked="checked" value="0" name="p_flag">No
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Gender</label>
                            <div class="col-sm-9">
                                <div class="form-check-inline">
                                    <?php if ($pGender == 'm') { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked="checked" value="m" name="p_gender">Male&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="f" name="p_gender">Female&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="o" name="p_gender">Others
                                        </label>
                                    <?php } else if ($pGender == 'f') { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="m" name="p_gender">Male&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked value="f" name="p_gender">Female&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="o" name="p_gender">Others
                                        </label>
                                    <?php } else if ($pGender == 'o') { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="m" name="p_gender">Male&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="f" name="p_gender">Female&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" checked value="o" name="p_gender">Others
                                        </label>
                                    <?php } else { ?>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="m" name="p_gender">Male&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="f" name="p_gender">Female&nbsp;&nbsp;
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="o" name="p_gender">Others
                                        </label>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">PAN No:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_pan_no" placeholder="PAN No" name="pdar_pan_no" value="<?php echo $ppan ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Citizen No:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pdar_citizen_no" placeholder="Citizen No" name="pdar_citizen_no" value="<?php echo $pcit ?>">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-12 text-center pb-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' /><input type="hidden" name="dag_no" id="dag_no" value='<?php echo $dag_no ?>' /><input type="hidden" name="pdar_id" id="pdar_id" value='<?php echo $pdar_id ?>' /><input type="hidden" name="patta_no" id="patta_no" value='<?php echo $pattano ?>' /><input type="hidden" name="patta_type_code" id="patta_type_code" value='<?php echo $ptype ?>' />
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <input type="button" class="btn btn-primary" id="pusubmit" name="pusubmit" value="Submit" onclick="pdarexupdate();"></input>
                <?php if ($is_govt) : ?>
                    <input type="button" class="btn btn-primary" onclick="rmkentry()" value="Next"></input>
                <?php else : ?>
                    <input type="button" class="btn btn-primary" id="orddet" name="orddet" value="Next" onclick="gorder();"></input>
                <?php endif; ?>
                <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
            </div>
        </form>
    </div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>