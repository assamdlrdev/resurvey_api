<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
<div class="container my-3">
    <div class="row">
        <div class="col-lg-12 ">
            <?php include(APPPATH . 'views/alert/session.php'); ?>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Enable/Disable User
                    </h3>
                </div>
                <div class="card-body">
                    <?php echo form_open(base_url('index.php/UserManagement/index'), ['id' => 'form']); ?>
                    <h4>Filter</h4>
                    <div class="border border-dark p-4">
                        <div class="form-group row">
                            <div class="col-sm-3">

                                <label for="" class="col-form-label">Role Type</label>
                                <select class="form-control role_type" id="role_type" name="role_type" required>
                                    <option value="">Select Role Type </option>
                                    <?php foreach ($privileges as $priv) : ?>
                                        <?php if (in_array(trim($priv->priv_code), ['adm', 'mut'])) : ?>
                                            <?php if (($_POST['role_type'] === trim($priv->priv_code))) : ?>
                                                <option selected="selected" value="<?php echo trim($priv->priv_code); ?>"><?php echo $priv->priv_desc; ?></option>
                                            <?php else : ?>
                                                <option value="<?php echo trim($priv->priv_code); ?>"><?php echo $priv->priv_desc; ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="" class="col-form-label">Role</label>
                                <select class="form-control role" required name="role" id="role">
                                    <option value="">-- Select Role --</option>
                                    <?php foreach ($roles as $rol) : ?>
                                        <?php if (($_POST['role'] === trim($rol->user_desig_code))) : ?>
                                            <option selected="selected" value="<?php echo trim($rol->user_desig_code); ?>"><?php echo $rol->user_desig_as; ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo trim($rol->user_desig_code); ?>"><?php echo $rol->user_desig_as; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="" class="col-form-label">Status</label>
                                <select class="form-control" required name="status" id="status">
                                    <option <?php echo ((isset($_POST['status']) && $_POST['status'] === 'E') ? 'selected="selected"' : '') ?> value="E" selected>Enabled</option>
                                    <option <?php echo ((isset($_POST['status']) && $_POST['status'] === 'D') ? 'selected="selected"' : '') ?> value="D">Disabled</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="" class="col-form-label">District</label>
                                <select required class="form-control" id="dist_code" name="dist_code">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($districts as $district) : ?>
                                        <option <?php echo (($_POST['dist_code'] === $district['dist_code']) ? 'selected="selected"' : '') ?> value="<?php echo $district['dist_code']; ?>"><?php echo $district['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('dist_code', '<span class="text-danger">', '</span>'); ?>
                            </div>

                            <div class="col-sm-3">
                                <label for="subdiv_code" class="col-form-label">Sub-Div</label>
                                <select required class="form-control" id="subdiv_code" name="subdiv_code">
                                    <option value="">--Select--</option>
                                    <?php foreach ($sub_divs as $subdiv) : ?>
                                        <option <?php echo (($_POST['subdiv_code'] === $subdiv['subdiv_code']) ? 'selected="selected"' : '') ?> value="<?php echo $subdiv['subdiv_code']; ?>"><?php echo $subdiv['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label for="" class="col-form-label">Circle</label>
                                <select required class="form-control" id="circle_code" name="circle_code">
                                    <option value='' selected>--Select--</option>
                                    <?php foreach ($circles as $circle) : ?>
                                        <option <?php echo (($_POST['circle_code'] === $circle['cir_code']) ? 'selected="selected"' : '') ?> value="<?php echo $circle['cir_code']; ?>"><?php echo $circle['loc_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="" class="col-form-label"></label>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-info"><i class="fas fa-check"></i> Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 alert alert-warning rasid" style="display: none;">
                        <div id="msg"></div>
                    </div>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    </form>
                    <dic class="col-lg-12">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered table-sm" width="100%">
                                <thead>
                                    <tr>
                                        <td class="bold bg-warning">Status</td>
                                        <td class="bold bg-warning">Full Name</td>
                                        <td class="bold bg-warning">Login Name</td>
                                        <td class="bold bg-warning">Designation</td>
                                        <td class="bold bg-warning">District</td>
                                        <td class="bold bg-warning">Sub Division</td>
                                        <td class="bold bg-warning">Circle</td>
                                        <td class="bold bg-warning">Mouza (For LM Only)</td>
                                        <td class="bold bg-warning">Edit</td>
                                        <td class="bold bg-warning">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($users) == 0) : ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No records</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($users as $user) : ?>
                                        <tr>
                                            <td>
                                                <?php echo ($user['status'] == 'E' ? '<span class="text-success">Enabled </span>' : '<span class="text-danger">Disabled </span>') ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['name']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['login_name']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['role']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['dist']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['subdiv']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['circle']) ?>
                                            </td>
                                            <td>
                                                <?php echo ($user['mouza']) ?>
                                                <?php echo ($user['lot']) ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo $base . 'index.php/UserManagement/edit?serial_no='.$user['serial_no']  ?>" title="Edit User">
                                                    <span class="fas fa-pen" aria-hidden="true"></span></a>
                                            </td>
                                            <td>
                                                <?php if ($user['status'] == 'E') : ?>
                                                    <button onclick="changeStatus('D',<?php echo ($user['serial_no']) ?>)" class="btn btn-sm btn-danger">Disable</button>
                                                <?php else : ?>
                                                    <button onclick="changeStatus('E',<?php echo ($user['serial_no']) ?>)" class="btn btn-sm btn-success">Enable</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </dic>
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

    function changeStatus(action, id) {
        var action_title = action == 'E' ? 'Enable' : 'Disable';
        if (confirm('Really want to ' + action_title + ' this User?')) {
            var baseurl = $("#base").val();
            $.ajax({
                url: baseurl + "index.php/UserManagement/changeStatus",
                method: "POST",
                data: {
                    serial_no: id,
                    action: action
                },
                async: true,
                dataType: 'json',
                success: function(data) {
                    $("#form").submit();
                },
                error: function(jqXHR, exception) {
                    alert('Could not Complete your Request ..!, Please Try Again later..!');
                }
            });
        } else {
            return (false);
        }
    }
</script>