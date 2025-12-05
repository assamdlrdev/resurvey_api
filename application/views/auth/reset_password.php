<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo base_url() ?>"><b>Dharitee Data Entry</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header bg-info text-center">
            Please Reset Your Password to Continue
        </div>
        <div class="card-body login-card-body">

            <?php echo form_open('Auth/AuthController/resetPassword'); ?>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="New Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirm Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" id="reset_password" onclick="resetPassword()" class="btn btn-primary btn-block">Reset</button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="row mb-0 mt-2">
                <div class="col-12">
                    <p class="text-center">
                        <a href="<?= base_url('index.php/Login/logout') ?>" class="text-center">Logout</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function resetPassword() {
        var baseurl = '<?php echo base_url() ?>';
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/Auth/AuthController/resetPassword",
            data: $('form').serialize(),
            type: "POST",
            success: function(data) {
                if (data.st == 1) {
                    swal.fire("", data.msg, "success")
                        .then((value) => {
                            if (data.st == 1 && data.ut == 00) {
                                window.location = baseurl + "index.php/SvamitvaCardController/location";
                            } else if (data.st == 1 && data.ut == 1) {
                                window.location = baseurl + "index.php/Login/adminindex";
                            } else if (data.st == 1 && data.ut == 2) {
                                window.location = baseurl + "index.php/Login/superadminindex";
                            } else if (data.st == 1 && (data.ut == 3 || data.ut == 4 || data.ut == 5)) {
                                window.location = baseurl + "index.php/Login/dashboard";
                            } else if (data.st == 1 && data.ut == 9) {
                                window.location = baseurl + "index.php/reports/DagReportController/index";
                            } else {
                                window.location = baseurl + "index.php/Login/index";
                            }
                        });
                } else {
                    swal.fire("", data.msg, "info");

                }
            }
        });
    }
</script>