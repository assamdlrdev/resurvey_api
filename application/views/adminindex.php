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


        <?php echo form_open('Login/AdminLoginSubmit'); ?>
        <div class="form-group">
          <label for="user_code">User Type</label>
          <select required name="user_code" id="user_code" class="form-control form-control-sm">
            <option value="">--Select--</option>
            <option value="<?php echo ($this->UserModel::$DEO_CODE) ?>">Data Entry Operator</option>
            <option value="<?php echo ($this->UserModel::$CO_CODE) ?>">Circle Officer</option>
            <!-- <option value="<?php echo ($this->UserModel::$SUPERVISOR_CODE) ?>">Supervisor</option>
            <option value="<?php echo ($this->UserModel::$SURVEYOR_CODE) ?>">Surveyor</option>
            <option value="<?php echo ($this->UserModel::$SPMU_CODE) ?>">SPMU</option> -->
          </select>
        </div>
        <div class="form-group" style="display:none;" id="district_div">
          <label for="sel1">District:</label>
          <select name="dist_code" class="form-control form-control-sm" id="d" required>
            <option selected value="">Select District</option>
            <?php foreach ($districts as $value) { ?>
              <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group" style="display:none;" id="subdiv_div">
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
        </div>
        <div class="form-group" style="display:none;" id="name_div">
          <label for="sel1">Name:</label>
          <input type="text" name="name" id="name" maxlength="15" class="form-control form-control-sm" required>

        </div>
        <div class="form-group" style="display:none;" id="username_div">
          <label for="sel1">Username:</label>
          <input type="text" name="username" id="username" maxlength="15" class="form-control form-control-sm" required>

        </div>
        <div class="form-group" style="display:none;" id="pwd_div">
          <label for="sel1">Password:</label>
          <input type="password" name="password" id="password" class="form-control form-control-sm" required>
        </div>

        <div class="text-center">
          <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
          <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="userDetailsSubmit();">
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>

</body>

</html>

<script src="<?= base_url('assets/js/login.js') ?>"></script>
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
  $(document).on('change', '#user_code', function(e) {
    var user_code = e.currentTarget.value;
    console.log(user_code);
    if(user_code == 4 || user_code == '00') {
      $('#district_div').attr({style:'display:block;'});
      $('#subdiv_div').attr({style:'display:block;'});
      $('#circle_div').attr({style:'display:block;'});
      $('#name_div').attr({style:'display:block;'});
      $('#username_div').attr({style:'display:block;'});
      $('#pwd_div').attr({style:'display:block;'});
    }
    else if(user_code == 10 || user_code == 11 || user_code == 12) {
      $('#district_div').attr({style:'display:block;'});
      $('#name_div').attr({style:'display:block;'});
      $('#username_div').attr({style:'display:block;'});
      $('#pwd_div').attr({style:'display:block;'});
      $('#subdiv_div').attr({style:'display:none;'});
      $('#circle_div').attr({style:'display:none;'});

      $('#sd').val('');
      $('#c').val('');
    }
    else {
      $('#district_div').attr({style:'display:none;'});
      $('#subdiv_div').attr({style:'display:none;'});
      $('#circle_div').attr({style:'display:none;'});
      $('#name_div').attr({style:'display:none;'});
      $('#username_div').attr({style:'display:none;'});
      $('#pwd_div').attr({style:'display:none;'});

      $('#d').val('');
      $('#sd').val('');
      $('#c').val('');
      $('#name').val('');
      $('#username').val('');
      $('#password').val('');
    }
  });
</script>