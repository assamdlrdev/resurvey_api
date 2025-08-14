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
      margin-top: 50px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card col-md-10" id="loc_save">
      <div class="card-body">
        <?php echo form_open('Login/SuperAdminLoginSubmit'); ?>
        <div class="text pb-2 text-center font-weight-bold">User Registration</div>
        <div class="form-group">
          <label for="user_type">User Type: <span class="text-danger">*</span></label>
          <select name="user_type" id="user_type" class="form-control">
            <option value="">Select User Type</option>
            <option value="1">Admin</option>
            <option value="9">Guest</option>
          </select>
        </div>
        <div class="form-group myDiv" id="showOne">
          <label for="sel1">District: <span class="text-danger">*</span></label>
          <select name="dist_code" class="form-control" id="d" required>
            <option selected value="">Select District</option>
            <?php foreach ($districts as $value) { ?>
              <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name">Name: <span class="text-danger">*</span></label>
          <input type="text" name="name" maxlength="50" id="name" class="form-control" required>

        </div>
        <div class="form-group">
          <label for="username">Username: <span class="text-danger">*</span></label>
          <input type="text" name="username" maxlength="5" id="username" class="form-control" required>
          <small>Maximum 5 characters allowed.</small>
        </div>
        <div class="form-group">
          <label for="password">Password: <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="form-group">
          <label for="password-confirm">Confirm Password: <span class="text-danger">*</span></label>
          <input type="password" name="password-confirm" class="form-control" id="password-confirm" required>
        </div>

        <div class="text-center">
          <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
          <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="superadminuserDetailsSubmit();">
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>

</body>

</html>

<script src="<?= base_url('assets/js/login.js') ?>"></script>
<script src="<?= base_url('assets/js/remark.js') ?>"></script>

<script>
  $(document).ready(function() {
    $('#showOne').hide();
    $('#user_type').on('change', function() {
      var demovalue = $(this).val();
      if (demovalue == '1') {
        $('#showOne').show();
      } else {
        $('#showOne').hide();
      }
    });
  });
</script>