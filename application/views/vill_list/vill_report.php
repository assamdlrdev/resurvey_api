<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/dataTable/datatables.css') ?>" >
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/dataTable/datatables.min.css') ?>" >
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/dataTable/buttons.dataTables.min.css') ?>" >
    <script type="text/javascript" src="<?php echo base_url('/assets/dataTable/datatables.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/dataTable/datatables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/dataTable/dataTables.buttons.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/dataTable/buttons.print.min.js') ?>"></script>
  </head>
  <body>
    <div class="container bg-light p-0 border border-dark mt-5 mb-5">
      <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
            <h3>Location Details</h3>
        </div> 
      </div>
      <div class="row">
          <div class="col-md-12 mt-2">
              &nbsp;&nbsp;&nbsp;&nbsp; <b>District :</b> <?php print_r($this->session->userdata('vill_districtdata')); ?>, <b>Sub Division :</b> <?php print_r($this->session->userdata('vill_subdivdata')); ?>, <b>Circle:</b> <?php print_r($this->session->userdata('vill_circledata')); ?>, <b>Mouza :</b> <?php print_r($this->session->userdata('vill_mouzadata')); ?>, <b>Lot :</b> <?php print_r($this->session->userdata('vill_lotdata')); ?>, <b>Village :</b> <?php print_r($this->session->userdata('vill_villagedata')); ?>
          </div>     
      </div>
      <div class="row" style="margin: 20px 25px 0px 10px">
        <div class="col-md-12"> 
          <table class="table table-striped table-bordered" id="locDetails">
            <thead>
              <th style="width: 12%">Sl. No</th>
              <th style="width: 22%">Dag No</th>
              <th style="width: 22%">Patta No</th>
              <th style="width: 22%">Patta Type</th>
              <th style="width: 22%">Max Pattadar Id</th>
            </thead>
            <tbody>
              <?php 
              $i= 1;
              foreach($villages as $village) : 
              ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $village->dag_no ?></td>
                <td><?php echo $village->patta_no ?></td>
                <td><?php echo $village->patta_type ?></td>
                <td><?php echo $village->max ?></td>
              </tr>
              <?php $i++; endforeach; ?>
            </tbody>
          </table> 
        </div>    
      </div>
    </div>
  </body>
</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $('#locDetails').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        lengthMenu: [
            [25, 50, -1],
            [25, 50, 'All'],
        ],
      });
  });
</script>