<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dharitee Data Entry</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view('header');?>
  <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
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
<div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif"></div>

  <div class="card col-md-10" id="loc_save">
       <div class="card-body">


    <?php echo form_open('ChithaReport/generateDagChitha'); ?>
             <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
          <h5>Select Dag Location for Chitha Report</h5>
        </div>
      </div>
      <div class="form-group">
        <label for="sel1">District:</label>
        <select name="dist_code" class="form-control" id="d">
          <option selected value="">Select District</option>

          <?php foreach ($districts as $value) {?>
            <option value="<?=$value['dist_code']?>" <?php if ($locations and $current_url == current_url()) {if ($locations['dist']['code'] == $value['dist_code']) {
    echo 'selected';
}}
    ?>><?=$value['loc_name']?></option>
          <?php }?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Sub-Div:</label>
        <select name="subdiv_code" class="form-control" id="sd">
          <option value="">Select Sub Division </option>
          <?php if ($locations and $current_url == current_url()) {foreach ($locations['subdivs']['all'] as $value) {?>
              <option value="<?=$value['subdiv_code']?>" <?php if ($locations['subdivs']['code'] == $value['subdiv_code']) {
    echo 'selected';
}
    ?>><?=$value['loc_name']?></option>
            <?php }}?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Circle:</label>
        <select name="cir_code" class="form-control" id="c">
          <option value="">Select Circle </option>
          <?php if ($locations and $current_url == current_url()) {foreach ($locations['cir']['all'] as $value) {?>
              <option value="<?=$value['cir_code']?>" <?php if ($locations['cir']['code'] == $value['cir_code']) {
    echo 'selected';
}
    ?>><?=$value['loc_name']?></option>
            <?php }}?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Mouza/Porgona:</label>
        <select name="mouza_pargona_code" class="form-control" id="m">
          <option value="">Select Mouza </option>
          <?php if ($locations and $current_url == current_url()) {foreach ($locations['mza']['all'] as $value) {?>
              <option value="<?=$value['mouza_pargona_code']?>" <?php if ($locations['mza']['code'] == $value['mouza_pargona_code']) {
    echo 'selected';
}
    ?>><?=$value['loc_name']?></option>
            <?php }}?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Lot:</label>
        <select name="lot_no" class="form-control" id="l">
          <option value="">Select Lot </option>
          <?php if ($locations and $current_url == current_url()) {foreach ($locations['lot']['all'] as $value) {?>
              <option value="<?=$value['lot_no']?>" <?php if ($locations['lot']['code'] == $value['lot_no']) {
    echo 'selected';
}
    ?>><?=$value['loc_name']?></option>
            <?php }}?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Village:</label>
        <select name="vill_townprt_code" class="form-control" id="v">
          <option value="">Select Village </option>
          <?php if ($locations and $current_url == current_url()) {foreach ($locations['vill']['all'] as $value) {?>
              <option value="<?=$value['vill_townprt_code']?>" <?php if ($locations['vill']['code'] == $value['vill_townprt_code']) {
    echo 'selected';
}
    ?>><?=$value['loc_name']?></option>
            <?php }}?>
        </select>
      </div>
<input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
       <div class="text-center"><input type='submit' class="btn btn-primary" id="loc_save_btn" name="submit" value="Submit"></input></div>
     <?php echo form_close(); ?>
       </div>
</div>
</div>
</body>
</html>


<script src="<?=base_url('assets/js/location.js?v=1.1')?>"></script>