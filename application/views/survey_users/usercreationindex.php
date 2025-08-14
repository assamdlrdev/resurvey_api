<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dharitee Data Entry</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->load->view('header'); ?>
  <style>
    .card {
      margin: 0 auto;
      /* Added */
      float: none;
      /* Added */
      margin-bottom: 10px;
      /* Added */
      /* margin-top: 50px; */
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card col-md-6 p-0" id="loc_save">
      <div class="card-header bg-info">
        <div class="text text-center font-weight-bold">
          <h5>User Registration</h5>
        </div>
      </div>
      <div class="card-body">


        <?php //echo form_open('Login/createUser'); 
        ?>
        <div class="form-group">
          <label for="user_code">User Type *</label>
          <select required name="user_code" id="user_code" class="form-control form-control-sm">
            <option value="">--Select--</option>
            <!-- <option value="<?php echo ($this->UserModel::$DEO_CODE) ?>">Data Entry Operator</option>
            <option value="<?php echo ($this->UserModel::$CO_CODE) ?>">Circle Officer</option> -->
            <option value="<?php echo ($this->UserModel::$SUPERVISOR_CODE) ?>">Supervisor</option>
            <option value="<?php echo ($this->UserModel::$SURVEYOR_CODE) ?>">Surveyor</option>
            <option value="<?php echo ($this->UserModel::$SURVEY_GIS_ASSISTANT_CODE) ?>">GIS Assistant</option>
            <option value="<?php echo ($this->UserModel::$SPMU_CODE) ?>">SPMU</option>
          </select>
        </div>
        <!-- <div class="form-group" style="display:none;" id="district_div">
          <label for="sel1">District:</label>
          <select name="dist_code" class="form-control form-control-sm" id="d" required>
            <option selected value="">Select District</option>
            <?php foreach ($districts as $value) { ?>
              <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
            <?php } ?>
          </select>
        </div> -->
        <!-- <div class="form-group" style="display:none;" id="subdiv_div">
          <label for="sel1">Sub-Div:</label>
          <select name="subdiv_code" class="form-control form-control-sm" id="sd" required>
            <option value="">Select Sub Division </option>
          </select>
        </div>
        <div class="form-group" style="display:none;" id="circle_div">
          <label for="sel1">Circle:</label>
          <select name="cir_code" class="form-control form-control-sm" id="c" required>
            <option value="">Select Circle </option>
          </select>
        </div> -->
        <div class="form-group" id="name_div">
          <label for="sel1">Name *</label>
          <input type="text" name="name" id="name" class="form-control form-control-sm" required>

        </div>
        <div class="form-group" id="username_div">
          <label for="sel1">Username *</label>
          <input type="text" name="username" id="username" maxlength="15" class="form-control form-control-sm" required>
        </div>
        <div class="form-group" id="phone_div">
          <label for="sel1">Phone No. (10 digits) *</label>
          <input type="text" name="phoneno" id="phoneno" maxlength="10" class="form-control form-control-sm" required>
        </div>
        <!-- <div class="form-group" id="pwd_div">
          <label for="sel1">Password *</label>
          <input type="password" name="password" id="password" class="form-control form-control-sm" required>
        </div> -->
        <div class="form-group psswrd_wrap" id="pwd_div">
          <label for="sel1">Password </label>
          <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control form-control-sm" autocomplete="new-password">
            <div class="input-group-prepend">
              <span class="input-group-text togglePassword">
                <i class="far fa-eye"></i>
              </span>
            </div>
          </div>
        </div>

        <div class="input-group form-group psswrd_wrap" id="con_pwd_div">
          <label for="selc1">Confirm Password </label>
          <div class="input-group mb-3">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-sm" autocomplete="new-password">
            <div class="input-group-prepend">
              <span class="input-group-text togglePassword">
                <i class="far fa-eye"></i>
              </span>
            </div>
          </div>
        </div>

        <div class="text-center">
          <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
          <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="">
          <?php //echo form_close(); 
          ?>
        </div>
      </div>
    </div>

</body>

</html>

<!-- <script src="<?= base_url('assets/js/login.js') ?>"></script> -->
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
  $(document).on('change', '#user_code', function(e) {

  });

  $(document).on('click', '#psubmit', (e) => {
    var baseurl = $('#base').val();
    var user_code = $('#user_code').val();
    var name = $('#name').val();
    var username = $('#username').val();
    var phoneno = $('#phoneno').val();
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();

    if (user_code == '' || name == '' || username == '' || phoneno == '' || password == '' || confirm_password == '') {
      alert('All fields with (*) marked are mandatory');
      return false;
    }

    $.ajax({
      url: baseurl + 'index.php/Login/userCreateSubmit',
      method: 'POST',
      dataType: 'JSON',
      data: {
        user_code: user_code,
        name: name,
        username: username,
        phoneno: phoneno,
        password: password,
        confirm_password: confirm_password
      },
      success: function(response) {
        if (response.st == 1) {
          swal.fire("", response.msg, "success")
            .then((value) => {
              window.location = baseurl + "index.php/Login/usercreationIndex";
            });
        } else {
          swal.fire("", response.msg, "info");

        }
      },
      error: function(error) {
        console.log(error);
      }
    });
  });

  $('.togglePassword').on('click', function() {
    const $this = $(this);
    const passwordWrap = $this.closest('.psswrd_wrap');

    if ($this.hasClass('text-primary')) {
      $this.removeClass('text-primary');
      $('input', passwordWrap).prop('type', 'password');
    } else {
      $this.addClass('text-primary');
      $('input', passwordWrap).prop('type', 'text');
    }
  });
</script>