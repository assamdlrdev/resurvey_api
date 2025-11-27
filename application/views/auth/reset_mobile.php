<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo base_url() ?>"><b>Dharitee Data Entry</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header bg-primary text-center">
            Please Add Your Mobile Number to Continue
        </div>
        <div class="card-body login-card-body">
            <?php echo form_open('Auth/AuthController'); ?>
            <div class="input-group mb-3">
                <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Mobile Number">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-phone"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="otp" class="form-control" placeholder="Enter OTP">
                <div class="input-group-append">
                    <button type="button" class="btn btn-success btn-flat" id="send_otp" onclick="getOtp()" type="button">SEND OTP</button>
                </div>
            </div>
            <div class="input-group">
                <p id="timer"></p>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" onclick="submitMobile()" id="reset_password" class="btn btn-primary btn-block">SUBMIT</button>
                </div>
                <?php echo form_close(); ?>

            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <p class="mb-0 text-center">
                        <a href="<?= base_url('index.php/Login/logout') ?>" class="text-center">Logout</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function submitMobile() {
        var baseurl = '<?php echo base_url() ?>';
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/Auth/AuthController/submitMobile",
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

    function getOtp() {
        var baseurl = '<?php echo base_url() ?>';
        var mobile_no = $("#mobile_no").val();
        if (!mobile_no) {
            swal.fire("", 'Please Enter Your Mobile Number', "warning");
            return;
        }
        startTimer();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/Auth/AuthController/getOtp",
            data: $('form').serialize(),
            type: "POST",
            success: function(data) {
                if (data.st == 1) {
                    swal.fire("", data.msg, "success")
                        .then((value) => {

                        });
                } else {
                    swal.fire("", data.msg, "info");

                }
            }
        });
    }
    var timerDuration = '<?= RESEND_OTP_TIMER ?>'; // Timer duration in seconds
    var timerInterval;

    function startTimer() {
        var timeLeft = timerDuration;

        $('#send_otp').prop('disabled', true); // Disable the button initially
        $('#timer').text('You can resend the OTP in ' + timeLeft + ' seconds.');

        timerInterval = setInterval(function() {
            timeLeft--;
            $('#timer').text('You can resend the OTP in ' + timeLeft + ' seconds.');

            if (timeLeft <= 0) {
                clearInterval(timerInterval); // Stop the timer
                $('#send_otp').prop('disabled', false); // Enable the button
                $('#timer').text('You can now resend the OTP.');
            }
        }, 1000);
    }
</script>