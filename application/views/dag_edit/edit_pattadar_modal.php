<div class="modal fade" id="edit_pattadar" tabindex="-1" aria-labelledby="edit_pattadar" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="edit_pattadar">Edit Pattadar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class='form-horizontal mt-3' id="edit_pattadar_form" method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <?php $is_govt = in_array($patta_type_code, GovtPattaCode) ?>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="edit_pattadar_id" class="col-sm-3 col-form-label">Pattadar ID:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="edit_pattadar_id" placeholder="Pattadar ID" name="pdar_id" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_patta_no" class="col-sm-3 col-form-label">Patta No:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="edit_patta_no" value="<?php echo $patta_no ?>" placeholder="Patta No" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="patta_type" class="col-sm-3 col-form-label">Patta Type</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="patta_type" value="<?php echo ($this->utilityclass->getPattaType($patta_type_code)) ?>" placeholder="Patta Type" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Pattadar Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="edit_pdar_name" placeholder="Pattadar Name" name="pdar_name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                </div>
                            </div>
                            <?php if (!$is_govt) : ?>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian's Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_father" placeholder="Guardian's Name" name="pdar_father" value="" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian Relation:</label>
                                    <div class="col-sm-9">
                                        <select name="pdar_relation" id="pdar_relation" class="form-control">
                                            <option selected value="">Select</option>
                                            <?php foreach ($relname as $row) { ?>
                                                <option value="<?php echo ($row->guard_rel); ?>">
                                                    <?php echo $row->guard_rel_desc_as; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Address 1:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add1" placeholder="Address 1" name="pdar_add1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Address 1:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add2" placeholder="Address 2" name="pdar_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Address 3:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add3" placeholder="Address 3" name="pdar_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <?php if ($this->session->userdata('dist_code') == '21') { ?>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_b" placeholder="Dag Portion Bigha" name="dag_por_b">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_k" placeholder="Dag Portion Katha" name="dag_por_k">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Chatak:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_lc" placeholder="Dag Poirtion Chatak" name="dag_por_lc">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Ganda:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_g" placeholder="Dag Portion Ganda" name="dag_por_g">
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_b" placeholder="Dag Portion Bigha" name="dag_por_b">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_k" placeholder="Dag Portion Katha" name="dag_por_k">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Lessa:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_dag_por_lc" placeholder="Dag Poirtion Lessa" name="dag_por_lc">
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="edit_dag_por_g" placeholder="dag_por_lc" name="dag_por_g" value="0">
                                <?php } ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!$is_govt) : ?>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Revenue:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_revenue" name="pdar_land_revenue">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Local Rate:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_localtax" name="pdar_land_localtax">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Land in Acre:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_acre" placeholder="Land in Acre:" value="0" name="pdar_land_acre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">North Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_n" placeholder="North Description" name="pdar_land_n">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">South Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_s" placeholder="South Description" name="pdar_land_s">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">East Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_e" placeholder="East Description" name="pdar_land_e">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">West Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_w" placeholder="West Description" name="pdar_land_w">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Pattadar Strike Out?</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" id="p_flag_yes" class="form-check-input" value="1" name="p_flag">Yes&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" id="p_flag_no" class="form-check-input" value="0" name="p_flag">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Gender</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" id="gender_m" value="m" name="p_gender">Male&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" id="gender_f" value="f" name="p_gender">Female&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" id="gender_o" value="o" name="p_gender">Others
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">PAN No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="id_pdar_pan_no" placeholder="PAN No" name="pdar_pan_no">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Citizen No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_citizen_no" placeholder="Citizen No" name="pdar_citizen_no">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 text-center pb-3">
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                        <input type="hidden" name="new_dag_no" value="<?php echo $new_dag_no ?>">
                        <input type="hidden" name="patta_type_code" value="<?php echo $patta_type_code ?>">
                        <input type="hidden" name="patta_no" value="<?php echo $patta_no ?>">
                        <button id="update_button" class="btn btn-success" type="button" onclick="updatePattadar()">SUBMIT</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>