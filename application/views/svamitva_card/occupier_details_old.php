<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row mb-2">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'] . '/Dag - ' . $this->session->userdata('dag_no'); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" align="right">
            <a href="<?= base_url('index.php/SvamitvaCardController/occupants') ?>" class="btn btn-sm btn-info"><i class="fa fa-users"></i> Occupants</a>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-map" aria-hidden="true"></i> OCCUPIER DETAILS
            </h5>
        </div>
        <form class='form-horizontal' method="post" action="" enctype="mPtipart/form-data">
            <input type="hidden" name="dist_code" id="dcode" value="<?php echo $this->session->userdata('dist_code'); ?>" />
            <input type="hidden" name="subdiv_code" id="scode" value="<?php echo $this->session->userdata('subdiv_code'); ?>" />
            <input type="hidden" name="cir_code" id="ccode" value="<?php echo $this->session->userdata('cir_code'); ?>" />
            <input type="hidden" name="mouza_pargona_code" id="mcode" value="<?php echo $this->session->userdata('mouza_pargona_code'); ?>" />
            <input type="hidden" name="lot_no" id="lcode" value="<?php echo $this->session->userdata('lot_no'); ?>" />
            <input type="hidden" name="vill_townprt_code" id="vcode" value="<?php echo $this->session->userdata('vill_townprt_code'); ?>" />
            <input type="hidden" name="block_code" id="blCode" value="<?php echo $this->session->userdata('block_code'); ?>" />
            <input type="hidden" name="gram_panch_code" id="gpCode" value="<?php echo $this->session->userdata('gram_panch_code'); ?>" />
            <input type="hidden" name="encro_id" id="encroId" value="<?php echo $encroId; ?>" />
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_name">Occupier Name: <font color=red>*</font> </label>
                            <input onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" id="occupier_name" placeholder="Occupier Name" class="form-control form-control-sm" name="occupier_name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_guar_name"> Guardian Name: </label>
                            <input onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" id="occupier_guar_name" placeholder="Father/Mother Name" class="form-control form-control-sm " name="occupier_guar_name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_guar_relation">Relation with Guardian:</label>
                            <select id="occupier_guar_relation" name="occupier_guar_relation" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach ($relname as $row) { ?>
                                    <option value="<?php echo ($row->guard_rel); ?>">
                                        <?php echo $row->guard_rel_desc_as; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="marital_status"> Married/Unmarried: <font color=red>*</font></label>
                            <select id="marital_status" onchange="maritalStatusSelected(this.value)" name="marital_status" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach (MARITAL_STATUS_LIST as $code => $marital_status) : ?>
                                    <option value="<?= $code ?>"><?= $marital_status ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="spouse_name_section" style="display: none;">
                        <div class="form-group">
                            <label for="spouse_name">Spouse Name: <font color=red>*</font> </label>
                            <input onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" id="spouse_name" placeholder="Spouse Name" class="form-control form-control-sm" name="spouse_name">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="gender"> Gender: <font color=red>*</font></label>
                            <select name="gender" id="gender" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach ($master_genders as $master_gender) : ?>
                                    <option value="<?= $master_gender->short_name ?>"><?= $master_gender->gen_name_ass ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="category"> Caste Category: <font color=red>*</font></label>
                            <select name="category" id="category" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach ($master_casts as $caste) : ?>
                                    <option value="<?= $caste->caste_id ?>"><?= $caste->caste_name_ass ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="mobile">Mobile Number: </label>
                            <input type="text" maxlength="10" placeholder="Mobile Number" class="form-control form-control-sm" id="mobile" name="mobile">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dob">DOB: </label>
                            <input type="date" placeholder="DOB" class="form-control form-control-sm" id="dob" name="dob">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="current_occupation"> Current Occupation: <font color=red>*</font></label>
                            <select name="current_occupation" id="current_occupation" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach ($master_occupations as $master_occupation) : ?>
                                    <option value="<?= $master_occupation->id ?>"><?= $master_occupation->name_eng ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="address">Occupier Address: </label>
                            <input type="text" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" placeholder="Occupier Address" class="form-control form-control-sm" id="address" name="address">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_class_code">Nature of Occupier's Land: </label>
                            <select name="occupier_class_code" id="occupier_class_code" class="form-control form-control-sm">
                                <option selected value="">--Select--</option>
                                <?php foreach ($classcode as $value) { ?>
                                    <option value="<?= $value['class_code'] ?>"><?= $value['class'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="property_type_code">Type of Property: </label>
                            <select name="property_type_code" class="form-control form-control-sm">
                                <option value="" selected>--Select--</option>
                                <?php foreach ($master_property_types as $value) { ?>
                                    <option value="<?= $value->code ?>"><?= $value->name_eng ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_land_b">Land Area(Bigha) :</label>
                            <input value="0" type="number" placeholder="Land Area(Bigha)" class="form-control form-control-sm" name="occupier_land_b" id="occupier_land_b">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_land_k">Land Area(Katha) :</label>
                            <input value="0" type="number" placeholder="Land Area(Katha)" class="form-control form-control-sm" name="occupier_land_k" id="occupier_land_k">
                        </div>
                    </div>
                    <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="occupier_land_lc">Land Area (Chatak) :</label>
                                <input value="0" type="number" placeholder="Land Area (Chatak)" class="form-control form-control-sm" name="occupier_land_lc" id="occupier_land_lc">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Dag Area (Ganda): <font color=red>*</font> </label>
                                <input value="0" type="number" placeholder="Dag Area (Ganda)" class="form-control form-control-sm" id="dag_area_ganda" name="occupier_land_lc_g">
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="occupier_land_lc"> Dag Area (Lessa): <font color=red>*</font></label>
                                <input value="0" type="number" placeholder="Dag Area (Lessa)" class="form-control form-control-sm bkl lessa" id="occupier_land_lc" name="occupier_land_lc">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="approx_property_value"> Approximate value of the Property (Rs):</label>
                            <input value="0" type="number" step="0.001" placeholder="Approximate value of the Property" class="form-control form-control-sm" id="approx_property_value" name="approx_property_value">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="occupier_evicted_yn">Occupier Evicted:</label> <br>
                            <input type="radio" name="occupier_evicted_yn" value="Y">Yes &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="occupier_evicted_yn" value="N" checked> No &nbsp;
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="mDate" style="display: none">
                        <div class="form-group" >
                            <label for="occupier_evic_date">Occupier Evic Date:</label>
                            <input type="date" placeholder="Occupier Evic Date" class="form-control form-control-sm" id="occupier_evic_date" name="occupier_evic_date">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="other_description">Any Other Specific Description:</label>
                            <textarea name="other_description" id="other_description" placeholder="Any Other Specific Description" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header rounded-0 text-center bg-info py-1">
                <h5>
                    <i class="fa fa-users" aria-hidden="true"></i> FAMILY DETAILS
                </h5>
            </div>
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Name: </label>
                            <input type="text" class="form-control form-control-sm" id="f_meb_name" placeholder="Family Member Name" name="f_meb_name[]" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Relation: </label>
                            <select name="f_meb_relation[]" class="form-control form-control-sm">
                                <option selected value="">Select</option>
                                <?php foreach ($relname as $row) { ?>
                                    <option value="<?php echo ($row->guard_rel); ?>">
                                        <?php echo $row->guard_rel_desc_as; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Gender: </label>
                            <select name="f_mem_gender[]" class="form-control form-control-sm">
                                <option selected value="">Select</option>
                                <?php foreach ($master_genders as $master_gender) : ?>
                                    <option value="<?= $master_gender->short_name ?>"><?= $master_gender->gen_name_ass ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Occupation </label>
                            <select name="f_meb_occupation[]" class="form-control form-control-sm">
                                <option selected value="">Select</option>
                                <?php foreach ($master_occupations as $master_occupation) : ?>
                                    <option value="<?= $master_occupation->id ?>"><?= $master_occupation->name_eng ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="app" style=""> </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right" style="margin-top: 20px">
                        <button type="button" class="btn btn-sm btn-success" id="addMore"> <i class="fa fa-plus"></i> Add More</button>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right" style="margin-top: 20px">
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <input type="hidden" name="newpatta" id="newpatta" value="" />
                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />

                        <button type='button' class="btn btn-info" name="submit" onclick='occupierEntry();' value="Submit">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
                        </button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function maritalStatusSelected(value){
        if(value == 'm'){
            $("#spouse_name_section").show();
        }else{
            $("#spouse_name_section").hide();
        }
    }
    $("input[name='occupier_evicted_yn']").click(function() {
        var radioValue = $("input[name='occupier_evicted_yn']:checked").val();
        if (radioValue == 'N') {
            $("#mDate").hide();
        }
        if (radioValue == 'Y') {
            $("#mDate").show();
        }

    });

    $("#addMore").on("click", function() {

        $('#app').append(
            '<div class="row border border-info p-3" id="deleteFamilyDetails">\n' +
            '                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right">\n' +
            '                            <button type="button" class="btn btn-sm btn-danger deleteFamilyDetailsButt">\n' +
            '                                <i class="fa fa-trash"></i>\n' +
            '                                Delete\n' +
            '                            </button>\n' +
            '                        </div>' +
            '                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n' +
            '                            <div class="form-group">\n' +
            '                                <label for="sel1"> Name: </label>\n' +
            '                                <input type="text" class="form-control form-control-sm" id="f_meb_name" placeholder="Family Member Name" name="f_meb_name[]" charset="utf-8"    onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"  >\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '\n' +
            '                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n' +
            '                            <div class="form-group">\n' +
            '                                <label for="sel1">Relation: </label>\n' +
            '                                <select name="f_meb_relation[]" class="form-control form-control-sm" >\n' +
            '                                    <option selected value="">Select</option>\n' +
            '                                    <?php foreach ($relname as $row) { ?>\n' +
            '                                        <option value="<?php echo ($row->guard_rel); ?>">' +
            '                                            <?php echo $row->guard_rel_desc_as; ?>\n' +
            '                                        </option>\n' +
            '                                    <?php } ?>\n' +
            '                                </select>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '\n' +
            '                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n' +
            '                            <div class="form-group">\n' +
            '                                <label for="sel1">Gender:  </label>\n' +
            '                                <select name="f_mem_gender[]" class="form-control form-control-sm" >\n' +
            '                                    <option selected value="">Select</option>\n' +
            '                                    <?php foreach ($master_genders as $master_gender) { ?>\n' +
            '                                        <option value="<?php echo ($master_gender->short_name); ?>">' +
            '                                            <?php echo $master_gender->gen_name_ass; ?>\n' +
            '                                        </option>\n' +
            '                                    <?php } ?>\n' +
            '                                </select>\n' +
            '\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '\n' +
            '                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n' +
            '                            <div class="form-group">\n' +
            '                                <label for="sel1">Occupation  </label>\n' +
            '                                <select name="f_meb_occupation[]" class="form-control form-control-sm" >\n' +
            '                                    <option selected value="">Select</option>\n' +
            '                                    <?php foreach ($master_occupations as $master_occupation) { ?>\n' +
            '                                        <option value="<?php echo ($master_occupation->id); ?>">' +
            '                                            <?php echo $master_occupation->name_eng; ?>\n' +
            '                                        </option>\n' +
            '                                    <?php } ?>\n' +
            '                                </select>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '\n' +
            '                    </div>\n'
        );

    });


    $("#app").on('click', '.deleteFamilyDetailsButt', function() {
        $(this).parent().parent().remove();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/location_svamitva.js?v=1.1') ?>"></script>
<script src="<?= base_url('assets/js/common.js') ?>"></script>
<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
<?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
<?php } ?>