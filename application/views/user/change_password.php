<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js')?>"></script>
    <style>
        .card {
            margin: 0 auto; /* Added */
            float: none; /* Added */
            margin-bottom: 10px; /* Added */
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'message.php'; ?>
    <div class="row">
        <div class="col-md-6">
            
        </div>
        <div class="col-md-6"></div>
    </div>
    <div class="card col-md-10" id="loc_save">
        <div class="card-body">

            <form action="<?php echo $base?>index.php/set-new-password" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px" >
                            Change Password
                        </h4>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Old Password :</label>
                            <input type="password" class="form-control" name="old_password" id="old_password"  required minlength="2" maxlength="20">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">New Password :</label>
                            <input type="password" class="form-control" name="password" id="password"  required minlength="5" maxlength="20">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Confirm Password :</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"  required minlength="5" maxlength="20">
                        </div>
                    </div>


                </div>


                <br>

                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <input type="hidden" id="oldHashedPassword" name="oldHashedPassword" value="">
                <input type="hidden" id="hashedPassword" name="hashedPassword" value="">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
                <div class="text-right">
                    <button type='button' class="btn btn-primary" onclick="changePassword(this.form)">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    <br>
</div>

</body>
</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>


<script>

    function validateAndHashWithSHA256() {
        var pwd = $("#password").val();
        var cpwd = $('#confirm_password').val();
        var opwd = $('#old_password').val();

        if(pwd != cpwd || pwd == opwd) {
            alert("Password must not be same as the old password and password should match with confirm password");
            return false;
        }
        if(pwd == '' || cpwd == '' || opwd == '' || pwd == undefined || cpwd == undefined || opwd == undefined) {
            alert("Required parameters are empty");
            return false;
        }

        $('#hashedPassword').val($.sha1(pwd));
        $('#oldHashedPassword').val($.sha1(opwd));
        $("#password").val("");
        $('#confirm_password').val("");
        $('#old_password').val("");
        return true;
    }
    

    function changePassword(form) {
        var validation = validateAndHashWithSHA256();
        if (!validation) {
            return false;
        }
        $('form').submit();
        
    }
</script>