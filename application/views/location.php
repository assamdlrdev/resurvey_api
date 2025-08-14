<style>
  body,
  html {
    height: 100%;
  }

  /*
 * Off Canvas sidebar at medium breakpoint
 * --------------------------------------------------
 */
  @media screen and (max-width: 992px) {

    .row-offcanvas {
      position: relative;
      -webkit-transition: all 0.25s ease-out;
      -moz-transition: all 0.25s ease-out;
      transition: all 0.25s ease-out;
    }

    .row-offcanvas-left .sidebar-offcanvas {
      left: -33%;
    }

    .row-offcanvas-left.active {
      left: 33%;
      margin-left: -6px;
    }

    .sidebar-offcanvas {
      position: absolute;
      top: 0;
      width: 33%;
      height: 100%;
    }
  }

  /*
 * Off Canvas wider at sm breakpoint
 * --------------------------------------------------
 */
  @media screen and (max-width: 34em) {
    .row-offcanvas-left .sidebar-offcanvas {
      left: -45%;
    }

    .row-offcanvas-left.active {
      left: 45%;
      margin-left: -6px;
    }

    .sidebar-offcanvas {
      width: 45%;
    }
  }

  .card {
    overflow: hidden;
  }

  .card-body .rotate {
    z-index: 8;
    float: right;
    height: 100%;
  }

  .card-body .rotate i {
    color: rgba(20, 20, 20, 0.15);
    position: absolute;
    left: 0;
    left: auto;
    right: -10px;
    bottom: 0;
    display: block;
    -webkit-transform: rotate(-44deg);
    -moz-transform: rotate(-44deg);
    -o-transform: rotate(-44deg);
    -ms-transform: rotate(-44deg);
    transform: rotate(-44deg);
  }

  #total_dags {
    cursor: pointer;
  }
</style>

<div id="total_dags" style="float: left;" class="col-xl-12 py-2">
  <div class="card text-white total bg-info h-20">
    <div class="card-body bg-info">
      <div class="rotate">
        <i class="fa fa-list fa-4x"></i>
      </div>
      <h6 class="text-uppercase">Total Dags: <?=$count?> </h6>
    </div>
  </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div class="card col-md-10 offset-md-1" id="loc_save">
    <div class="card-body">
      <div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif"></div>
      <?php echo form_open('Chithacontrol/indexSubmit'); ?>
      <div class="card-title  text-center font-weight-bold">Select Dag Location</div><br><br>
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
      <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
      <div class="text-center"><input type='button' class="btn btn-primary" id="loc_save_btn" name="submit" onclick='checkloc();' value="Submit"></input></div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php $this->load->view('header');?>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?=base_url('assets/js/location.js')?>"></script>
<script>
  $("#total_dags").click(function() {
    var baseurl = $("#base").val();
    window.location.href = baseurl + "index.php/Chithacontrol/dag_filter";
  });
  $(".total").hover(
    function() {
      $(this).removeClass("bg-info");
      $(this).addClass("bg-success");
    },
    function() {
      $(this).removeClass("bg-success");
      $(this).addClass("bg-info");
    }
  );
</script>