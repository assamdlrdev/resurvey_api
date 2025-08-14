<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
</head>
<style>
    .row {
        margin-left:-5px;
        margin-right:-5px;
    }
</style>
<body>
<div class="container-fluid mt-3 mb-2 font-weight-bold">
    <?php  if($locationname["dist_name"]!=NULL) echo $locationname['dist_name']['loc_name'].'/'.$locationname['subdiv_name']['loc_name'].'/'.$locationname['cir_name']['loc_name'].'/'.$locationname['mouza_name']['loc_name'].'/'.$locationname['lot']['loc_name'].'/'.$locationname['village']['loc_name']; ?>
    <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$daghd?><?php echo $landhd?>
</div>
<div class="container bg-light p-0 border border-dark mt-5 mb-5">
    <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
            <h3>General Remark Details(Column 31)</h3>
        </div>
    </div>

    <?php echo form_open('Remark/remarkHomeSubmit'); ?>
    <div class="row">

        <div class="col-sm-6">

            <!--            <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-5 col-form-label">Rmk Type Hist No:</label>
                          <div class="col-sm-7">

                            <input type="text" class="form-control" id="inputEmail3" name="rmk_type_hist_no">
                          </div>
                        </div>-->
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-5 col-form-label">Remark Type:</label>
                <div class="col-sm-7">
                    <select name="remark_type" class="form-control">
                        <option selected value="">Select Remark Type</option>
                        <?php foreach ($remark as $value) { ?>
                            <option  value="<?= $value['type_code']?>"><?= $value['content_type']?></option>
                        <?php } ?>
                    </select></div>
            </div>

            <div class="col-12 text-center pb-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
                <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="RemarkHomeEntry();"></input>
                <input type="button" class="btn btn-primary" id="rmext" name="rmext" value="Exit" onclick="rmkexit();" ></input>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</body>
</html>
<script src="<?= base_url('assets/js/remark.js') ?>"></script>