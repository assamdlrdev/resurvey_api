<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
  </head>
  <script>$(function () {
        $("#orderdate").datepicker({dateFormat: 'yy-mm-dd'});

    });</script>
  <style>
    .row {
        margin-left:-5px;
        margin-right:-5px;

    }
  </style>

  <body>
    <div class="container-fluid mt-3 mb-2 font-weight-bold"> 
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
    </div>
    <div class="container bg-light p-0 border border-dark mt-5 mb-5">
      <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
          <h3>In place of Details(Column 31)</h3>
        </div> 
      </div>

      <?php echo form_open('Remark/remarkForm_in_place_of_submit'); ?>
      <div class="row">

        <div class="col-sm-6">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Place of ID:</label>


            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="inplace_of_id">
            </div>


          </div>


          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Rmk Type Hist No:</label>
            <div class="col-sm-7">

              <input type="text" class="form-control" id="inputEmail3" name="rmk_type_hist_no">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-5 col-form-label">Order No:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputPassword3" name="ord_no">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Order Date:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="orderdate" name="ord_date">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Order Cron No:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="ord_cron_no" >
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Place of Name:<font color=red>*</font></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="inplace_of_name" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Place of Gurdian Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="inplace_of_guardian" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-5 col-form-label">Relation With Guardian:</label>
            <div class="col-sm-7">
              <select name="inplace_of_relation" class="form-control">
                <option selected value="">Select Relation With Guardian</option>
                <?php foreach ($relation as $value) { ?>
                  <option  value="<?= $value['guard_rel'] ?>"><?= $value['guard_rel_desc_as'] ?></option>
                <?php } ?>
              </select></div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Place Address 1:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="inplace_of_add1" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Place Address 2:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="inplace_of_add2" >
            </div>
          </div>

          <div class="col-12 text-center pb-3">
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
            <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="inplaceofentry();"></input>
            <input type="button" class="btn btn-primary pull-right" id="psubmit" name="psubmit" value="Next" onclick="inplaceofentrySkip();"></input>

          </div>
          <?php echo form_close(); ?>

        </div>

      </div>


  </body>


</head>
</html>



<script src="<?= base_url('assets/js/remark.js') ?>"></script>