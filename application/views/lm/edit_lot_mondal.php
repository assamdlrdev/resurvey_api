<?php $this->load->view('header'); ?>
<script src="<?= base_url('assets/js/common.js') ?>"></script>
<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
<?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
<?php } ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <form action="<?php echo $base ?>index.php/lm/LMController/updateLotMondal">
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>District: <span style="color:red">*</span></label>
                            <select class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select District</option>
                                <?php foreach ($districts as $value) : ?>
                                    <option value="<?= $value['dist_code'] ?>" <?= ($value['dist_code'] == $userDetail->dist_code) ? 'selected="selected"' : '' ?>>
                                        <?= $value['loc_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Sub-Division: <span style="color:red">*</span></label>
                            <select class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select District First</option>
                                <?php foreach ($subdistricts as $value) : ?>
                                    <option value="<?= $value['subdiv_code'] ?>" <?= ($value['subdiv_code'] == $userDetail->subdiv_code) ? 'selected="selected"' : '' ?>>
                                        <?= $value['loc_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="c">Circle: <span style="color:red">*</span></label>
                            <select class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select Sub Division First</option>
                                <?php foreach ($circles as $value) : ?>
                                    <option value="<?= $value['cir_code'] ?>" <?= ($value['cir_code'] == $userDetail->cir_code) ? 'selected="selected"' : '' ?>>
                                        <?= $value['loc_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="m">Mouza/Porgona:</label>
                            <select class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select Circle First</option>
                                <?php foreach ($mouzas as $value) : ?>
                                    <option value="<?= $value['mouza_pargona_code'] ?>" <?= ($value['mouza_pargona_code'] == $userDetail->mouza_pargona_code) ? 'selected="selected"' : '' ?>>
                                        <?= $value['loc_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="l">Lot: <span style="color:red">*</span></label>
                            <select class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select Mouza First</option>
                                <?php foreach ($lots as $value) : ?>
                                    <option value="<?= $value['lot_no'] ?>" <?= ($value['lot_no'] == $userDetail->lot_no) ? 'selected="selected"' : '' ?>>
                                        <?= $value['loc_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="lm_name">Lot Mondal: <span style="color:red">*</span></label>
                            <input type="text" name="lm_name" maxlength="50" id="lm_name" placeholder="Enter Mondal Name" value="<?php echo $userDetail->lm_name; ?>" class=" form-control form-control-sm" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="status">Status: <span style="color:red">*</span></label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="">Select Status</option>
                                <?php
                                $statusOptions = ['E' => 'Enable', 'D' => 'Disabled', 'S' => 'Substitute', 'O' => 'Original'];
                                foreach ($statusOptions as $value => $label) {
                                    $selected = ($userDetail->status == $value) ? 'selected="selected"' : '';
                                    echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="date_of_joining">Date of Joining <span style="color:red">*</span></label>
                            <input type="date" value="<?php echo date("Y-m-d", strtotime($userDetail->dt_from)); ?>" class="form-control " name="date_of_joining" id="date_of_joining">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="corres_sk_code">Superviser Kanungoo: <span style="color:red">*</span></label>
                            <select name="corres_sk_code" id="corres_sk_code" class="form-control form-control-sm">
                                <option value="">Select Lot First</option>
                                <?php foreach ($sks as $value) : ?>
                                    <option value="<?= $value['user_code'] ?>" <?= ($value['user_code'] == $userDetail->corres_sk_code) ? 'selected="selected"' : '' ?>>
                                        <?= $value['username'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="phone_no">Phone Number: <span style="color:red">*</span></label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control form-control-sm" maxlength="10" minlength="10" onkeyup="this.value=this.value.replace(/[^\d]/,'')" placeholder="Enter Phone Number" value="<?php echo $userDetail->phone_no; ?>">
                            <small>Only numbers allowed.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div id="user_hidden_details" style="display: none;">
                <input type="hidden" name="dist_code" id="dist_code" value='<?php echo $userDetail->dist_code ?>' />
                <input type="hidden" name="subdiv_code" id="subdiv_code" value='<?php echo $userDetail->subdiv_code ?>' />
                <input type="hidden" name="cir_code" id="cir_code" value='<?php echo $userDetail->cir_code ?>' />
                <input type="hidden" name="mouza_pargona_code" id="mouza_pargona_code" value='<?php echo $userDetail->mouza_pargona_code ?>' />
                <input type="hidden" name="lot_no" id="lot_no" value='<?php echo $userDetail->lot_no ?>' />
                <input type="hidden" name="lm_code" id="lm_code" value='<?php echo $userDetail->lm_code; ?>' />
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
            </div>


            <div class="card-footer">
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <a href="<?= base_url('index.php/lm/LMController/index') ?>">
                    <button type="button" class="btn btn-default ">Cancel <i class="fa fa-times" aria-hidden="true"></i></button>
                </a>
                <button type="button" class="btn btn-primary float-right" id="psubmit" name="psubmit" onclick="userDetailsSubmit();">Submit <i class="fas fa-save"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    function userDetailsSubmit() {
        var baseurl = $("#base").val();
        var lmCode = $("#lm_code").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/lm/LMController/updateLotMondal",
            data: $('form').serialize(),
            type: "POST",
            success: function(data) {
                if (data.st == 1) {
                    swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/lm/LMController/index";
                        });
                } else {
                    swal.fire("", data.msg, "info");

                }
            }
        });
    }
</script>