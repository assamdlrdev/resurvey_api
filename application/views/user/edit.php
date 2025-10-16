<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
<div class="container my-3">
    <div class="row">
        <div class="col-lg-12 ">
            <?php include(APPPATH . 'views/alert/session.php'); ?>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit User
                    </h3>
                </div>
                <div class="card-body">
                    <?php echo form_open(base_url('index.php/UserManagement/updateUser')); ?>
                    <h4>Basic Details</h4>
                    <div class="border border-dark p-4">
                        <input type="hidden" name="serial_no" value="<?php echo($serial_no) ?>">
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-4">
                                <input type="text" name="name" value="<?php echo($user['name']) ?>" class="form-control" placeholder="Please enter name">
                                <?php echo form_error('name', '<span class="text-danger">', '</span>'); ?>
                            </div>
                            <label for="" class="col-sm-2 col-form-label">( In Assamese )</label>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Role Type</label>
                            <div class="col-sm-4">
                                <input type="hidden" value="<?php echo($user['role_type']) ?>" name="role_type">
                                <select disabled class="form-control role_type" id="role_type">
                                    <option selected disabled>Select Role Type</option>
                                    <?php foreach ($privileges as $priv) : ?>
                                        <?php if (in_array(trim($priv->priv_code), ['adm', 'mut'])) : ?>
                                            <option <?php echo((trim($user['role_type']) == trim($priv->priv_code)) ? 'selected="selected"' : '')  ?> value="<?php echo trim($priv->priv_code); ?>"><?php echo $priv->priv_desc; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('role_type', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Status</label>
                            <input type="hidden" value="<?php echo($user['status']) ?>" name="status">
                            <label class="col-sm-2 rasid">
                                <input disabled <?php echo($user['status'] == 'E' ? 'checked' : '') ?> type="radio" name="status" id="inlineRadio2" class="col-form-label" value="E"> Enable
                            </label>
                            <label class="col-sm-2 rasid">
                                <input disabled <?php echo($user['status'] == 'D' ? 'checked' : '') ?> type="radio" name="status" id="inlineRadio3" class="col-form-label" value="D"> Disable
                            </label>
                            <?php echo form_error('status', '<span class="text-danger">', '</span>'); ?>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-4">
                                <input type="hidden" value="<?php echo(trim($user['role'])) ?>" name="role">
                                <select disabled class="form-control role" id="role">
                                    <option value="">-- Select Role --</option>
                                    <?php foreach ($roles as $rol) : ?>
                                        <?php if (in_array(trim($rol->user_desig_code), ['CO', 'DEO', 'LM', 'SK'])) : ?>
                                            <option <?php echo((trim($user['role']) == trim($rol->user_desig_code)) ? 'selected="selected"' : '')  ?> value="<?php echo trim($rol->user_desig_code); ?>"><?php echo $rol->user_desig_as; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('role', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Type</label>
                            <div class="col-sm-4">
                                <input type="hidden" value="<?php echo($user['type']) ?>" name="type">
                                <select disabled class="form-control" name="type">
                                    <option value="">--Select--</option>
                                    <option value="O">স্থায়ী</option>
                                    <option value="P">আনৰ শ্হলত</option>
                                    <option value="A">সংলগ্ন</option>
                                </select>
                                <?php echo form_error('type', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Contact Number</label>
                            <div class="col-sm-4">
                                <input type="number" value="<?php echo($user['phone_no']) ?>" maxlength="10" minlength="10" class="form-control" name="phone_no">
                                <?php echo form_error('phone_no', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Date of Joining</label>
                            <div class="col-sm-4">
                                <input name="date_of_joining" value="<?php echo($user['date_of_joining'] ? date('Y-m-d',strtotime($user['date_of_joining'])) : '') ?>" type="date" class="form-control " name="date_of_joining">
                                <?php echo form_error('date_of_joining', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3">Location Details</h4>
                    <div class="border border-dark p-4">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">District</label>
                                <input type="hidden" value="<?php echo($user['dist_code']) ?>" name="dist_code">
                                <select disabled class="form-control" id="dist_code" name="dist_code">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($districts as $district) : ?>
                                        <option <?php echo (($user['dist_code'] === $district['dist_code']) ? 'selected="selected"' : '') ?> value="<?php echo $district['dist_code']; ?>"><?php echo $district['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('dist_code', '<span class="text-danger">', '</span>'); ?>
                            </div>

                            <div class="col-sm-4">
                                <label for="subdiv_code" class="col-form-label">Sub-Div</label>
                                <input type="hidden" value="<?php echo($user['subdiv_code']) ?>" name="subdiv_code">
                                <select disabled class="form-control" id="subdiv_code" name="subdiv_code">
                                    <option value="">--Select--</option>
                                    <?php foreach ($sub_divs as $subdiv) : ?>
                                        <option <?php echo (($user['subdiv_code'] === $subdiv['subdiv_code']) ? 'selected="selected"' : '') ?> value="<?php echo $subdiv['subdiv_code']; ?>"><?php echo $subdiv['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('subdiv_code', '<span class="text-danger">', '</span>'); ?>
                            </div>

                            <div class="col-sm-4">
                                <label for="" class="col-form-label">Circle</label>
                                <input type="hidden" value="<?php echo($user['circle_code']) ?>" name="circle_code">
                                <select disabled class="form-control" id="circle_code" name="circle_code">
                                    <option value='' selected>--Select--</option>
                                    <?php foreach ($circles as $circle) : ?>
                                        <option <?php echo (($user['circle_code'] === $circle['cir_code']) ? 'selected="selected"' : '') ?> value="<?php echo $circle['cir_code']; ?>"><?php echo $circle['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('circle_code', '<span class="text-danger">', '</span>'); ?>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">Mouza/Porgona</label>
                                <input type="hidden" value="<?php echo($user['mouza_pargona_code']) ?>" name="mouza_pargona_code">
                                <select disabled class="form-control" id="mouza_pargona_code" name="mouza_pargona_code">
                                    <option value='' selected>--Select--</option>
                                    <?php foreach ($mouzas as $mouza) : ?>
                                        <option <?php echo (($user['mouza_pargona_code'] === $mouza['mouza_pargona_code']) ? 'selected="selected"' : '') ?> value="<?php echo $mouza['mouza_pargona_code']; ?>"><?php echo $mouza['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('mouza_pargona_code', '<span class="text-danger">', '</span>'); ?>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">Lot No</label>
                                <input type="hidden" value="<?php echo($user['lot_no']) ?>" name="lot_no">
                                <select disabled class="form-control" id="lot_no" name="lot_no">
                                    <option value='' selected>--Selecct--</option>
                                    <?php foreach ($lots as $lot) : ?>
                                        <option <?php echo (($user['lot_no'] === $lot['lot_no']) ? 'selected="selected"' : '') ?> value="<?php echo $lot['lot_no']; ?>"><?php echo $lot['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('lot_no', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group row" id="sk_input">
                            <div class="col-sm-4">
                                <label for="" class="control-label">SK</label>
                                <input type="hidden" value="<?php echo($user['sk_name']) ?>" name="sk_name">
                                <select disabled class="form-control" id="sk" name="sk_name">
                                    <option value='' >Select SK</option>
                                    <?php foreach ($sks as $sk) : ?>
                                        <option <?php echo (($user['sk_name'] === $sk->user_code) ? 'selected="selected"' : '') ?> value="<?php echo $sk->user_code; ?>"><?php echo $sk->username; ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <?php echo form_error('sk_name', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3">Primary Login Details</h4>
                    <div class="border border-dark p-4">
                        <div class="form-group row">
                            <label for="" class="col-sm-12 col-form-label">
                                <p style=" color: #ff0000;">Enter password to reset otherwise keep empty.</p>
                            </label>
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">User Name</label>
                                <input type="hidden" value="<?php echo($user['username']) ?>" name="username">
                                <input readonly value="<?php echo($user['username']) ?>" type="text" class="form-control" placeholder="User Name" id="username">
                                <?php echo form_error('username', '<span class="text-danger">', '</span>'); ?>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">Password (Min:8, Max:12)</label>
                                <input type="password" class="form-control" placeholder="Password" id="password" name='password'>
                                <?php echo form_error('password', '<span class="text-danger">', '</span>'); ?>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="col-form-label">Confirm Password</label>
                                <input type="password" class="form-control " placeholder="Confirm Password" name='confirm_password'>
                                <?php echo form_error('confirm_password', '<span class="text-danger">', '</span>'); ?>
                            </div>
                            <div id="msg_user_exists" style="float:left"></div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> SUBMIT</button>
                        </div>
                    </div>
                    <div class="col-lg-12 alert alert-warning rasid" style="display: none;">
                        <div id="msg"></div>
                    </div>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#role_type').change(function() {
        var baseurl = $("#base").val();
        var role = $(this).val();
        $.ajax({
            url: baseurl + "index.php/UserManagement/getRoles",
            method: "POST",
            data: {
                role: role
            },
            async: true,
            dataType: 'json',
            beforeSend: function() {
                $('#role').prop('selectedIndex', 0);
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function(data) {
                $.unblockUI();
                var template = "<option selected disabled>-- Select role --</option>"
                for (var i = 0; i < data.length; i++) {
                    template += "<option value='" + data[i].user_desig_code + "'>" + data[i].user_desig_as + "</option>"
                }
                $('#role').html(template);
            },
            error: function(jqXHR, exception) {
                $.unblockUI();
                $('#role').prop('selectedIndex', 0);
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
        return false;
    });
    $("#role").change(function(){
        if($(this).val() == 'LM'){
            $("#sk_input").show();
        }else{
            $("#sk_input").hide();
        }
    });

    $('#dist_code').change(function() {
        var baseurl = $("#base").val();
        var id = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Login/subdivisiondetailsall",
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
                    html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
                }
                $('#subdiv_code').html(html);
            }
        });
        return false;
    });
    $('#subdiv_code').change(function() {
        var baseurl = $("#base").val();
        var dis = $('#dist_code').val();
        var subdiv = $(this).val();
        $.ajax({
            url: baseurl + "index.php/Login/circledetailsall",
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
                    html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
                }
                $('#circle_code').html(html);
            }
        });
        return false;
    });
    $('#circle_code').change(function() {
        var baseurl = $("#base").val();
        var dist_code = $('#dist_code').val();
        var subdiv_code = $("#subdiv_code").val();
        var circle_code = $(this).val();
        $.ajax({
            url: baseurl + "index.php/UserManagement/getMouzas",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                circle_code: circle_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Circle</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
                }
                $('#mouza_pargona_code').html(html);
            }
        });
        var role = $("#role").val();
        if (role && role == 'LM') {
            getSks();
        }
        return false;
    });

    function getSks() {
        var baseurl = $("#base").val();
        var dist_code = $('#dist_code').val();
        var subdiv_code = $("#subdiv_code").val();
        var cir_code = $("#circle_code").val();
        $.ajax({
            url: baseurl + "index.php/UserManagement/getSks",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                var html = '';
                var i;
                html += '<option value="">Select SK</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lmuser + '>' + data[i].username + '</option>';
                }
                $('#sk').html(html);
            }
        });
    }
    $('#mouza_pargona_code').change(function() {
        var baseurl = $("#base").val();
        var dist_code = $('#dist_code').val();
        var subdiv_code = $("#subdiv_code").val();
        var circle_code = $("#circle_code").val();
        var mouza_pargona_code = $(this).val();
        $.ajax({
            url: baseurl + "index.php/UserManagement/getLots",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                circle_code: circle_code,
                mouza_pargona_code: mouza_pargona_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Circle</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
                }
                $('#lot_no').html(html);
            }
        });
        return false;
    });
</script>