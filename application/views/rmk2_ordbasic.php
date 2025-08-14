<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
   </head>
  <script>$( function() {
    $( "#orderdate" ).datepicker({dateFormat: 'yy-mm-dd'});
    $( "#lmsigndate" ).datepicker({dateFormat: 'yy-mm-dd'});
    $( "#sksigndate" ).datepicker({dateFormat: 'yy-mm-dd'});
    $( "#cosigndate" ).datepicker({dateFormat: 'yy-mm-dd'});
  } );</script>
    <style>

      .row {
          margin-left:-5px;
          margin-right:-5px;

      }



    </style>

  <body>
      <div class="container-fluid mt-3 mb-2 font-weight-bold"> 
<?php if($locationname["dist_name"]!=NULL) echo $locationname['dist_name']['loc_name'].'/'.$locationname['subdiv_name']['loc_name'].'/'.$locationname['cir_name']['loc_name'].'/'.$locationname['mouza_name']['loc_name'].'/'.$locationname['lot']['loc_name'].'/'.$locationname['village']['loc_name']; ?>
<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$daghd?><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;".$landhd?>
</div>
    <div class="container bg-light p-0 border border-dark mt-5 mb-5">
      <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
          <h3>Order form – 31st column</h3>
        </div> 
      </div>

       <?php echo form_open('Remark/remarkFormsubmit'); ?>
        <div class="row">

          <div class="col-sm-6">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Remark Type Hist No:</label>


              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" readonly name="rmk_type_hist_no" value="<?= $rmk_type_hist_no?>">
              </div>


            </div>


            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Order Cron No:</label>
              <div class="col-sm-9">

                <input type="text" class="form-control" id="inputEmail3" readonly name="ord_cron_no" value="<?= $ord_cron_no?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Order No:<font color=red>*</font></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputPassword3" name="ord_no">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Order Date:<font color=red>*</font></label>
              <div class="col-sm-9">
                <input type="text" class="form-control"  name="ord_date" id='orderdate'>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Order Type:<font color=red>*</font></label>
              <div class="col-sm-9">
                <select name="ord_type_code" class="form-control" >
                  <option selected value="">Select Order Type</option>
<?php foreach ($order_type as $value) { ?>
                            <option  value="<?= $value['order_type_code']?>"><?= $value['order_type']?></option>
                           <?php } ?> 
                </select></div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Ord Passed By:<font color=red>*</font></label>
              <div class="col-sm-9">
                <select name="ord_passby_desig" class="form-control">
                  <option selected>Select One</option>
                  <option value="DC">DC</option>
                  <option value="ADC">ADC</option>
                  <option value="SDO">SDO</option>
                  <option value="CO" >CO</option>
                </select></div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Ord Passed By Sign(y/n):<font color=red>*</font></label>
              <div class="col-sm-9">
                <input type="radio" name="ord_passby_sign_yn" value="Y" checked>Yes  &nbsp;&nbsp;&nbsp;
                <input type="radio" name="ord_passby_sign_yn" value="N"> No  &nbsp;
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Case No:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputPassword3"  name="case_no">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Type of Govt. Land:</label>
              <div class="col-sm-9">
                <select name="ord_on_gl_type" class="form-control">
                  <option selected>Select One</option>
                  </select></div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">Ref. Letter No:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3"  name="ord_ref_let_no">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">Mondal's Name:<font color=red>*</font></label>
              <div class="col-sm-9">
                <select name="lm_code" class="form-control">
                  <option selected>Select Mondal's Name</option>
<?php foreach ($mandal_name as $value) { ?>
                            <option  value="<?= $value['lm_code']?>"><?= $value['lm_name']?></option>
                           <?php } ?> 
                </select></div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">LM Sign(y/n):</label>
              <div class="col-sm-9">
                <input type="radio" name="lm_sign_yn" value="Y" checked>Yes  &nbsp;&nbsp;&nbsp;
                <input type="radio" name="lm_sign_yn" value="N"> No  &nbsp;
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">LM Sign Date:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="lmsigndate" name="lm_sign_date">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">SK's Name:</label>
              <div class="col-sm-9">
                <select name="sk_code" class="form-control">
                  <option selected>Select SK's Name</option>
                  <?php foreach ($sk_name as $value) { ?>
                            <option  value="<?= $value['user_code']?>"><?= $value['username']?></option>
                           <?php } ?> 
                </select></div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">SK Sign(y/n):</label>
              <div class="col-sm-9">
                <input type="radio" name="sk_sign_yn" value="Y" checked>Yes  &nbsp;&nbsp;&nbsp;
                <input type="radio" name="sk_sign_yn" value="N"> No  &nbsp;
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">SK Sign Date:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="sksigndate" name="sk_sign_date">
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">CO's Name:<font color=red>*</font></label>
              <div class="col-sm-9">
                <select name="co_code" class="form-control">
                  <option selected>Select CO's Name</option>
                     <?php foreach ($co_name as $value) { ?>
                            <option  value="<?= $value['user_code']?>"><?= $value['username']?></option>
                           <?php } ?>
                </select></div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">CO Sign(y/n):</label>
              <div class="col-sm-9">
                <input type="radio" name="co_sign_yn" value="Y" checked>Yes  &nbsp;&nbsp;&nbsp;
                <input type="radio" name="co_sign_yn" value="N"> No  &nbsp;
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword3" class="col-sm-3 col-form-label">CO's Order Date:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="cosigndate" name="co_ord_date">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">W.R.T. Order1:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" name="wrt_order1">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">W.R.T. Order2:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" name="wrt_order2">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">W.R.T. Order3:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" name="wrt_order3">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">W.R.T. Order4:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" name="wrt_order4">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-3 col-form-label">W.R.T. Order5:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputEmail3" name="wrt_order5">
              </div>
            </div>

          </div>
        </div>
        <div class="col-12 text-center pb-3">
        <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
        <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="remarkentry();"></input>
        <input type="button" class="btn btn-primary" id="rmext" name="rmext" value="Exit" onclick="rmkexit();" ></input>
      				
        </div>
<?php echo form_close(); ?>


    </div>

  </div>


</body>


</head>
</html>
<script src="<?= base_url('assets/js/remark.js') ?>"></script>


