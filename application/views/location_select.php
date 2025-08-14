<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div class="card col-md-10" id="loc_save">
    <div class="card-body">
      <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
      <?php echo form_open('Chithacontrol/indexSubmit'); ?>
      <div class="card-title  text-center font-weight-bold">Select Dag Location</div><br><br>
      <div class="form-group">
        <label for="sel1">District:</label>
        <select name="dist_code" class="form-control" id="d">
          <option selected value="">Select District</option>

          <?php foreach ($districts as $value) { ?>
            <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Sub-Div:</label>
        <select name="subdiv_code" class="form-control" id="sd">
          <option value="">Select Sub Division </option>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Circle:</label>
        <select name="cir_code" class="form-control" id="c">
          <option value="">Select Circle </option>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Mouza/Porgona:</label>
        <select name="mouza_pargona_code" class="form-control" id="m">
          <option value="">Select Mouza </option>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Lot:</label>
        <select name="lot_no" class="form-control" id="l">
          <option value="">Select Lot </option>
        </select>
      </div>
      <div class="form-group">
        <label for="sel1">Village:</label>
        <select name="vill_townprt_code" class="form-control" id="v">
          <option value="">Select Village </option>
        </select>
      </div>
      <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
      <div class="text-center"><input type='button' class="btn btn-primary" id="loc_save_btn" name="submit" onclick='checkloc();' value="Submit"></input></div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php $this->load->view('header'); ?>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/location.js') ?>"></script>