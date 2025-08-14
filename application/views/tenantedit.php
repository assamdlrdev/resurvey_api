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

    * {
      box-sizing: border-box;
    }

    .row {
      margin-left: -5px;
      margin-right: -5px;
    }

    .column {
      float: left;
      width: 50%;
      padding: 5px;
    }

    /* Clearfix (clear floats) */
    .row::after {
      content: "";
      clear: both;
      display: table;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
      /* border: 1px solid #ddd;*/
    }

    th,
    td {
      text-align: left;
      padding: 5px 16px !important;
    }

    tr {
      border-top: hidden;
    }
  </style>
  <script src="<?= base_url('assets/js/common.js') ?>"></script>
  <?php if ($this->session->userdata('dist_code') == '21') { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
  <?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
  <?php } ?>
</head>

<body>
  <?php echo form_open('Chithacontrol/tenantedit'); ?>

  <div class="container-fluid mt-3 mb-2 font-weight-bold">
    <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
    <?php echo $daghd ?><?php echo $landhd ?>
    <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
      <div class="row bg-light p-0 border border-dark">
        <div class="col-12 px-0 pb-3">
          <div class="bg-info text-white text-center py-2">
            <h3>Edit of Tenant Details(Column 9-10)</h3>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Tenant Id</th>
                  <th>Tenant Name</th>
                  <th>Guardian's Name</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($tendet) {
                  foreach ($tendet as $row) {
                    $tid = $row->tenant_id;
                    $dagno = $row->dag_no;
                    $dcode = $row->dist_code;
                    $scode = $row->subdiv_code;
                    $ccode = $row->cir_code;
                    $mcode = $row->mouza_pargona_code;
                    $lotno = $row->lot_no;
                    $vcode = $row->vill_townprt_code;
                    $nm = $tid . '-' . $dagno . '-' . $dcode . '-' . $scode . '-' . $ccode . '-' . $mcode . '-' . $lotno . '-' . $vcode
                ?>

                    <tr>
                      <td><?php echo $row->tenant_id; ?></td>
                      <td style="font-size:14px"><?php echo $row->tenant_name; ?></td>
                      <td><?php echo $row->tenants_father; ?></td>
                      <td><a href="<?php echo base_url('index.php/Chithacontrol/tenantmod/' . $nm); ?>" class="btn btn-info ">Edit</a></td>
                    </tr>

                <?php }
                } ?>
              </tbody>
            </table>
            <!--table class="table" border=0>

          <tr>
            <td>Tenant Id:</td>
            <td><input type="text" value="<?php echo $tenantId; ?>" class="form-control form-control-sm" id="tid" name="tenant_id"></td>

          </tr>
          <tr>
            <td>Tenant Name:<font color=red>*</font></td>
            <td><input type="text" class="form-control form-control-sm" id="tname" name="tenant_name" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>

          </tr>
          <tr>
            <td>Guardian's Name:<font color=red>*</font></td>
            <td><input type="text" class="form-control form-control-sm" id="guard_name" name="tenants_father" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
            </td>
          </tr>
		  <tr>
            <td>Guardian's relation:<font color=red>*</font></td>
            <td><select class="form-control relation-type" name="guard_rel" required id="relation">
					<option  value="">Select</option>
				   <?php foreach ($relanm as $row) { ?>
					<option value="<?php echo $row->guard_rel; ?>" 
					<?php if ($row->guard_rel == $relnm) { ?> selected <?php } ?>>
					<?php echo $row->guard_rel_desc_as; ?></option>
					<?php } ?>
				</select>
			</td>
          </tr>
          <tr>
            <td>Address line1:</td>
            <td><input type="text" class="form-control form-control-sm"  id="add1" name="tenants_add1"></td>
          </tr>
          <tr>
            <td>Address line2:</td>
            <td><input type="text" class="form-control form-control-sm"  id="add2" name="tenants_add2"></td>
          </tr>
		  <tr>
            <td>Address line3:</td>
            <td><input type="text" class="form-control form-control-sm"  id="add3" name="tenants_add3"></td>
          </tr>
          <tr>
            <td>Tenant type:</td>
             <td>
				<select name="type_of_tenant" class="form-control" >
					<option  value="">Select</option>
				   <?php foreach ($tentype as $row) { ?>
					<option value="<?php echo $row->type_code; ?>" 
					<?php if ($row->type_code == $tenttype1) { ?> selected <?php } ?>>
					<?php echo $row->tenant_type; ?></option>
					<?php } ?>
				</select>
			</td>
          </tr>

          <tr>
            <td>Khatian no:</td>
            <td><input type="text" class="form-control form-control-sm" id="khatian_no" value="0" name="khatian_no"></td>
          </tr>
          <tr>
            <td>Tenant's revenue:</td>
            <td><input type="text" class="form-control form-control-sm" id="trev"  value="0" name="revenue_tenant"></td>
          </tr>
          <tr>
            <td>Crop rate:</td>
            <td><input type="text" class="form-control form-control-sm" id="croprate"  name="crop_rate"></td>
          </tr>
        </table-->
          </div>
        </div>

        <div class="col-12 text-center pb-3">
          <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
          <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
          <input type='button' class="btn btn-primary" id="tsubmit" name="tsubmit" value="Add New Tenant" onclick="tntentry();"></input>
          <input type='button' class="btn btn-primary" id="tnext" name="tnext" value="Next" onclick="subtentry();"></input>
        </div>
      </div>
    </form>
  </div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>