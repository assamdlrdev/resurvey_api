<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dharitee Data Entry</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->load->view('header'); ?>
</head>

<body>

  <div class="container bg-light p-0 border border-dark mt-5 mb-5">
    <div class="col-12 px-0 pb-3">
      <?php include(APPPATH . 'views/alert/session.php'); ?>
      <div class="bg-info text-white text-center py-2">
        <h3>Location Details</h3>
      </div>
    </div>
    <?php echo form_open('ChithaReport/generateChitha'); ?>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">District:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[0]->district; ?></strong>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">Sub Division:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[1]->subdiv; ?></strong>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-3 col-form-label">Circle:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[2]->circle; ?></strong>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-3 col-form-label">Mouza:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[3]->mouza; ?></strong>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-3 col-form-label">Lot:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[4]->lot_no; ?></strong>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-3 col-form-label">Village:</label>
          <div class="col-sm-9">
            <strong><?php echo $namedata[5]->village; ?></strong>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-4 col-form-label">Patta Type:</label>
          <div class="col-sm-6">
            <select class="form-control" id="select_patta_type" name="patta_code" required>
              <option disabled selected>Select Pattatype</option>
              <option value='0000'>All</option>
              <?php foreach ($pattatype as $patta) : ?>
                <?php
                $typeCode = $patta->type_code;
                $pattatype = $patta->patta_type;
                ?>
                <option value="<?php echo $typeCode; ?>"><?php echo $pattatype; ?></option>
              <?php endforeach; ?>
            </select>
            <?php echo form_error('patta_code', '<span class="text-danger">', '</span>'); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-4 col-form-label">Dag No From:</label>
          <div class="col-sm-6">
            <select class="form-control dag_no_lower" id="selectlw" name="dag_no_lower">
              <option value="">Lower Range</option>
            </select>
            <?php echo form_error('dag_no_lower', '<span class="text-danger">', '</span>'); ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-4 col-form-label">Dag No To:</label>
          <div class="col-sm-6">
            <select class="form-control" id="selectup" name="dag_no_upper">
              <option value="">Upper Range</option>
            </select>
            <?php echo form_error('dag_no_upper', '<span class="text-danger">', '</span>'); ?>
          </div>
        </div>

      </div>
    </div>
    <div class="col-12 text-center pb-3">
      <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
      <input type="submit" class="btn btn-primary" name="psubmit" value="Submit"></input>

    </div>
    <?php echo form_close(); ?>


  </div>

  </div>


</body>


</head>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>