<?php $this->load->view('header'); ?>
<script src="<?= base_url('assets/js/common.js') ?>"></script>
<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
<?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
<?php } ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <?php echo form_open('lm/LMController/createLotMondal'); ?>
        <div class="card-body">
            <div class="row border border-info p-3">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="d">District: <span style="color:red">*</span></label>
                        <select name="dist_code" class="form-control" id="d">
                            <option selected value="">Select District</option>
                            <?php foreach ($districts as $value) { ?>
                                <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sd">Sub-Division: <span style="color:red">*</span></label>
                        <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                            <option value="">Select District First </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="c">Circle: <span style="color:red">*</span></label>
                        <select name="cir_code" class="form-control form-control-sm" id="c">
                            <option value="">Select Sub Division First </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="m">Mouza/Porgona:</label>
                        <select name="mouza_pargona_code" id="m" class="form-control form-control-sm">
                            <option value="">Select Circle First</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="l">Lot: <span style="color:red">*</span></label>
                        <select name="lot_no" id="l" class="form-control form-control-sm">
                            <option value="">Select Mouza First</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="lm_name">Lot Mondal: <span style="color:red">*</span></label>
                        <input type="text" name="lm_name" maxlength="50" id="lm_name" placeholder="Enter Mondal Name" value=" <?php echo set_value('lm_name'); ?>" class=" form-control form-control-sm" placeholder="Enter Phone Number" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="status">Status: <span style="color:red">*</span></label>
                        <select name="status" id="status" class="form-control form-control-sm">
                            <option value="">Select Status</option>
                            <option value="E">Enable</option>
                            <option value="D">Disabled</option>
                            <option value="S">Substitute</option>
                            <option value="O">Original</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="date_of_joining">Date of Joining <span style="color:red">*</span></label>
                        <input type="date" value="<?php echo (isset($_POST['date_of_joining']) ? $_POST['date_of_joining'] : '') ?>" class="form-control " name="date_of_joining" id="date_of_joining">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="corres_sk_code">Superviser Kanungoo: <span style="color:red">*</span></label>
                        <select name="corres_sk_code" id="corres_sk_code" class="form-control form-control-sm">
                            <option value="">Select Lot First</option>
                            <?php foreach ($sks as $value) { ?>
                                <option value="<?= $value['usernm'] ?>"><?= $value['username'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="phone_no">Phone Number: <span style="color:red">*</span></label>
                        <input type="text" name="phone_no" id="phone_no" class="form-control form-control-sm" maxlength="10" minlength="10" onkeyup="this.value=this.value.replace(/[^\d]/,'')" placeholder="Enter Phone Number" value="<?php echo set_value('phone_no'); ?>">
                        <small>Only numbers allowed.</small>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
        <div class="card-footer">
            <a href="<?= base_url('index.php/lm/LMController/index') ?>">
                <button type="button" class="btn btn-default ">Cancel <i class="fa fa-times" aria-hidden="true"></i></button>
            </a>
            <button type="button" class="btn btn-primary float-right" id="psubmit" name="psubmit" onclick="userDetailsSubmit();">Submit <i class="fas fa-save"></i></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $('#d').change(function() {
        var baseurl = $("#base").val();
        var id = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Chithacontrol/subdivisiondetails",
            method: "POST",
            data: {
                id: id
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Subdivision</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#sd').html(html);
            }
        });
        return false;
    });
    $('#sd').change(function() {
        var baseurl = $("#base").val();
        var dis = $('#d').val();
        var subdiv = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Chithacontrol/circledetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Circle</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#c').html(html);
            }
        });
        return false;
    });
    $('#c').change(function() {
        var baseurl = $("#base").val();
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Chithacontrol/mouzadetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Mouza</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#m').html(html);
            }
        });
        return false;
    });
    $('#m').change(function() {
        var baseurl = $("#base").val();
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $('#c').val();
        var mza = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Chithacontrol/lotdetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir,
                mza: mza
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Lot</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#l').html(html);
            }
        });
        return false;
    });
    $('#l').change(function() {
        var baseurl = $("#base").val();
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $('#c').val();
        $.ajax({
            url: baseurl + "index.php/lm/LMController/listSKUsers",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir,
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Supervisor Kanungo</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].user_code + '>' + data[i].username +
                        '</option>';
                }
                $('#corres_sk_code').html(html);
            }
        });
        return false;
    });

    function userDetailsSubmit() {
        var baseurl = $("#base").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/lm/LMController/createLotMondal",
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