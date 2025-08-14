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
        $("#regdate").datepicker({dateFormat: 'yy-mm-dd'});

    });</script>

  <body>
    <div class="container-fluid mt-3 mb-2 font-weight-bold"> 
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
    </div>
    <div class="container bg-light p-0 border border-dark mt-5 mb-5">
      <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
          <h3>In Favor of Details(Column 31)</h3>
        </div> 
      </div>

      <?php echo form_open('Remark/remarkForm_in_favor_of_submit'); ?>
      <div class="row">

        <div class="col-sm-6">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Favor of ID:</label>


            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="infavor_of_id">
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
            <label for="inputPassword3" class="col-sm-5 col-form-label">Patta Type:<font color=red>*</font></label>
            <div class="col-sm-7">
              <select name="patta_type_code" class="form-control" >
                <option selected value="">Select Patta Type</option>
                <?php foreach ($patta_type as $value) { ?>
                  <option  value="<?= $value['type_code'] ?>"><?= $value['patta_type'] ?></option>
                <?php } ?>
              </select></div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Patta No:<font color=red>*</font></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="patta_no" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Favor of Name:<font color=red>*</font></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="infavor_of_name" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Favor of Gurdian Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="infavor_of_guardian" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-5 col-form-label">Relation With Guardian:</label>
            <div class="col-sm-7">
              <select name="infav_of_guar_relation" class="form-control">
                <option selected value="">Select Relation With Guardian</option>
                <?php foreach ($relation as $value) { ?>
                  <option  value="<?= $value['guard_rel'] ?>"><?= $value['guard_rel_desc_as'] ?></option>
                <?php } ?>
              </select></div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Favor of Address 1:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="infavor_of_add1" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">In Favor of Address 2:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="infavor_of_add2" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-5 col-form-label">By Right of:<font color=red>*</font></label>
            <div class="col-sm-7">
              <select name="by_right_of" class="form-control">
                <option selected value="">Select By Right of</option>
                <?php foreach ($byright as $value) { ?>
                  <option  value="<?= $value['trans_code'] ?>"><?= $value['trans_desc_as'] ?></option>
                <?php } ?>
              </select></div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Land Area (Bigha):</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="land_area_b" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Land Area (Katha):</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="land_area_k" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Land Area (Lessa/Chatak):</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="land_area_lc" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">New Dag No:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="new_dag_no" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">New Patta No:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="new_patta_no" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Registration Deed no:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="reg_deal_no" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Registration Deed Date:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="regdate" name="reg_date" >
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-5 col-form-label">Sub Registration Office:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="inputEmail3" name="sub_reg_office" >
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 text-center pb-3">
        <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
        <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="infavorentry();"></input>
        <input type="button" class="btn btn-primary pull-right" id="psubmit" name="psubmit" value="Next" onclick="infavorentrySkip();"></input>

      </div>
      <?php echo form_close(); ?>

    </div>

  </div>


</body>


</head>
</html>


<script src="<?= base_url('assets/js/remark.js') ?>"></script>
