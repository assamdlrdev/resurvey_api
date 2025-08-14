<div class="modal fade" id="add_pattadar" tabindex="-1" aria-labelledby="add_pattadar" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="add_pattadar">Add Pattadar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="new_pattadar-tab" data-toggle="tab" data-target="#new_pattadar" type="button" role="tab" aria-controls="new_pattadar" aria-selected="true">New Pattdar</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="exisiting_pattdar-tab" data-toggle="tab" data-target="#exisiting_pattdar" type="button" role="tab" aria-controls="exisiting_pattdar" aria-selected="false">Insert Existing Pattadar</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="new_pattadar" role="tabpanel" aria-labelledby="new_pattadar-tab">
                        <form class='form-horizontal mt-3' id="add_pattadar_form" method="post" action="" enctype="multipart/form-data">
                            <div class="row">
                                <?php $is_govt = in_array($patta_type_code, GovtPattaCode) ?>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="pattadar_id" class="col-sm-3 col-form-label">Pattadar ID:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="pattadar_id" name="pdar_id" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="patta_no" class="col-sm-3 col-form-label">Patta No:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="patta_no_u" placeholder="Patta No" value="<?php echo $new_patta_no ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display: none">
                                        <label for="patta_no" class="col-sm-3 col-form-label">Patta No:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="patta_no" placeholder="Patta No" value="<?php echo $patta_no ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="patta_type" class="col-sm-3 col-form-label">Patta Type</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="patta_type" placeholder="Patta Type" value="<?php echo ($this->utilityclass->getPattaType($new_patta_type_code)) ?>" readonly>
                                            <!-- <input type="text" class="form-control" id="patta_type" placeholder="Patta Type" value="<?php //echo $patta_type_code 
                                                                                                                                            ?>" readonly> -->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pdar_name" class="col-sm-3 col-form-label">Pattadar Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="pdar_name" placeholder="Pattadar Name" name="pdar_name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                        </div>
                                    </div>
                                    <?php if (!$is_govt) : ?>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian's Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_father" placeholder="Guardian's Name" name="pdar_father" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian Relation:</label>
                                            <div class="col-sm-9">
                                                <select name="pdar_relation" class="form-control">
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
                                                <input type="text" class="form-control" id="pdar_add1" placeholder="Address 1" name="pdar_add1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">Address 1:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_add2" placeholder="Address 2" name="pdar_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-3 col-form-label">Address 3:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_add3" placeholder="Address 3" name="pdar_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                            </div>
                                        </div>
                                        <?php if ($this->session->userdata('dist_code') == '21') { ?>
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_b" placeholder="dag_por_b" name="dag_por_b" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_k" placeholder="dag_por_k" name="dag_por_k" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Chatak:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_lc" placeholder="dag_por_lc" name="dag_por_lc" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Ganda:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_g" placeholder="Dag Portion Ganda" name="dag_por_g" value="0">
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_b" placeholder="dag_por_b" name="dag_por_b" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_k" placeholder="dag_por_k" name="dag_por_k" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Poirtion Lessa:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="dag_por_lc" placeholder="dag_por_lc" name="dag_por_lc" value="0">
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
                                                <input type="text" class="form-control" id="pdar_land_revenue" value="0" name="pdar_land_revenue">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-3 col-form-label">Local Rate:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_land_localtax" value="0" name="pdar_land_localtax">
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
                                                <input type="text" class="form-control" id="pdar_land_n" placeholder="North Description" name="pdar_land_n">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">South Description:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_land_s" placeholder="South Description" name="pdar_land_s">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-3 col-form-label">East Description:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_land_e" placeholder="East Description" name="pdar_land_e">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">West Description:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_land_w" placeholder="West Description" name="pdar_land_w">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">Pattadar Strike Out?</label>
                                            <div class="col-sm-9">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="1" name="p_flag">Yes&nbsp;&nbsp;
                                                    </label>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="0" name="p_flag" checked>No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label"> Gender</label>
                                            <div class="col-sm-9">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="m" name="p_gender">Male&nbsp;&nbsp;
                                                    </label>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="f" name="p_gender">Female&nbsp;&nbsp;
                                                    </label>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="o" name="p_gender">Others
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">PAN No:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_pan_no" placeholder="PAN No" name="pdar_pan_no">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputPassword3" class="col-sm-3 col-form-label">Citizen No:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pdar_citizen_no" placeholder="Citizen No" name="pdar_citizen_no">
                                            </div>
                                        </div>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 text-center pb-3">
                                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                <input type="hidden" name="dag_no" id="dag_no" value="<?php echo $dag_no ?>">
                                <input type="hidden" name="patta_type_code" id="patta_type_code" value="<?php echo $patta_type_code ?>">
                                <input type="hidden" name="patta_no" id="patta_no" value="<?php echo $patta_no ?>">
                                <button id="submit_button" class="btn btn-success" type="button" onclick="storePattadar()">SUBMIT</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="exisiting_pattdar" role="tabpanel" aria-labelledby="exisiting_pattdar-tab">
                        <div class="my-3">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    Insert Pattadars from patta no&nbsp;<?php echo $patta_no ?> and any other dag
                                </div>
                                <div>
                                    <select name="pdag" id="pdag" class="form-control" onchange="gpattadar();">
                                        <option value="" selected>All dags</option>
                                        <?php foreach ($other_dags as $row) { ?>
                                            <option value="<?php echo ($row->dag_no); ?>">
                                                <?php echo $row->dag_no; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <form class='form-horizontal mt-3' id="insert_form" action="" enctype="multipart/form-data">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="check_all" onclick="toggleSelectAll(this.value)"><label for="">All</label></th>
                                                <th>Id</th>
                                                <th>Dag</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="filtered_pattadars">
                                            <?php foreach ($other_pattadars as $other_pattadar) : ?>
                                                <tr>
                                                    <td><input type="checkbox" class="p_select" name="chk[]" id="chk[]" value="<?php echo ($other_pattadar['pdar_id'] . ',' . $other_pattadar['pdar_name'] . ',' . $other_pattadar['patta_no'] . ',' . $other_pattadar['patta_type_code'] . ',' . $other_pattadar['dag_no']) ?>"></td>
                                                    <td><?php echo $other_pattadar['pdar_id'] ?></td>
                                                    <td><?php echo $other_pattadar['dag_no'] ?></td>
                                                    <td><?php echo $other_pattadar['pdar_name'] ?></td>
                                                <tr>
                                                <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <input type="hidden" name="dag_no" value="<?php echo $dag_no ?>">
                                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                    <input type="button" class="btn btn-primary" id="insert_button" name="insert_button" value="Insert Selected Pattadars 223" onclick="insertPattadars();"></input>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>