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
<?php echo form_open('Chithacontrol/tenant'); ?>

<div class="container-fluid mt-3 mb-2 font-weight-bold">
  <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
  <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $daghd ?><?php echo $landhd ?>
  <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
    <div class="row bg-light p-0 border border-dark">
      <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
          <h3>Entry of Tenant Details(Column 9-10)</h3>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="table-responsive">
          <table class="table" border=0>

            <tr>
              <td>Tenant Id:</td>
              <td><input type="text" value="<?php echo $tenantId; ?>" class="form-control form-control-sm" id="tid" name="tenant_id"></td>

            </tr>
            <tr>
              <td>Tenant Name:<font color=red>*</font>
              </td>
              <td><input type="text" class="form-control form-control-sm" id="tname" name="tenant_name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>

            </tr>
            <tr>
              <td>Guardian's Name:<font color=red>*</font>
              </td>
              <td><input type="text" class="form-control form-control-sm" id="guard_name" name="tenants_father" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
            </tr>
            <tr>
              <td>Guardian's relation:<font color=red>*</font>
              </td>
              <td><select class="form-control form-control-sm relation-type" name="guard_rel" required id="relation">
                  <option value="">Select</option>
                  <?php foreach ($relanm as $row) { ?>
                    <option value="<?php echo $row->guard_rel; ?>" <?php if ($row->guard_rel == $relnm) { ?> selected <?php } ?>>
                      <?php echo $row->guard_rel_desc_as; ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Address line1:</td>
              <td><input type="text" class="form-control form-control-sm" id="add1" name="tenants_add1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
            </tr>
            <tr>
              <td>Address line2:</td>
              <td><input type="text" class="form-control form-control-sm" id="add2" name="tenants_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
            </tr>
            <tr>
              <td>Address line3:</td>
              <td><input type="text" class="form-control form-control-sm" id="add3" name="tenants_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></td>
            </tr>
            <tr>
              <td>Tenant type:</td>
              <td>
                <select name="type_of_tenant" class="form-control form-control-sm">
                  <option value="">Select</option>
                  <?php foreach ($tentype as $row) { ?>
                    <option value="<?php echo $row->type_code; ?>" <?php if ($row->type_code == $tenttype1) { ?> selected <?php } ?>>
                      <?php echo $row->tenant_type; ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>

            <tr>
              <td>Khatian no:<font color=red>*</font>
              </td>
              <td><input type="text" class="form-control form-control-sm" id="khatian_no" value="0" name="khatian_no"></td>
            </tr>
            <tr>
              <td>Tenant's revenue:</td>
              <td><input type="text" class="form-control form-control-sm" id="trev" value="0" name="revenue_tenant"></td>
            </tr>
            <tr>
              <td>Crop rate:</td>
              <td><input type="text" class="form-control form-control-sm" id="croprate" name="crop_rate"></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="table-responsive">
          <table class="table" border=0>
            <tr>
              <td>Land Possession Area <span class="text-danger">*</span> :</td>
              <td>
                <label for="">Bigha</label>
                <input type="number" class="form-control form-control-sm" name="possession_area_b">
              </td>
              <td>
                <label for="">Katha</label>
                <input type="number" class="form-control form-control-sm" name="possession_area_k">
              </td>
              <td>
                <?php if (in_array($this->session->userdata('dist_code'), BARAK_VALLEY)) : ?>
                  <label for="">Chatak</label>
                <?php else : ?>
                  <label for="">Lessa</label>
                <?php endif; ?>
                <input type="number" step='0.01' class="form-control form-control-sm" name="possession_area_l">
              </td>
              <?php if (in_array($this->session->userdata('dist_code'), BARAK_VALLEY)) : ?>
                <td>
                  <label for="">Ganda</label>
                  <input type="number" step='0.01' class="form-control form-control-sm" name="possession_area_g">
                </td>
                <td>
                  <label for="">Kranti</label>
                  <input type="number" step='0.01' class="form-control form-control-sm" name="possession_area_k">
                </td>
              <?php endif; ?>
            </tr>
            <tr>
              <td>Length of Possession (In Years) <span class="text-danger">*</span> :</td>
              <td colspan="3">
                <input type="number" class="form-control form-control-sm" name="possession_length">
              </td>
            </tr>
            <tr>
              <td>Status of Tenant <span class="text-danger">*</span> :</td>
              <td colspan="3">
                <input type="text" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" class="form-control form-control-sm" name="tenant_status">
              </td>
            </tr>
            <tr>
              <td>Paid Cash/Kind :</td>
              <td colspan="3">
                <input type="text" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" class="form-control form-control-sm" name="paid_cash_kind">
              </td>
            </tr>
            <tr>
              <td>Payable Cash/Kind :</td>
              <td colspan="3">
                <input type="text" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" class="form-control form-control-sm" name="payable_cash_kind">
              </td>
            </tr>
            <tr>
              <td>Special Conditions and incidence, right of way casement etc :</td>
              <td colspan="3">
                <input type="text" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" class="form-control form-control-sm" name="special_condition">
              </td>
            </tr>
            <tr>
              <td>Remark :</td>
              <td colspan="3">
                <textarea name="remark" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" rows="2" class="form-control"></textarea>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-12 text-center pb-3">
        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
        <?php if ($tenantId > 1) { ?>
          <input type='button' class="btn btn-primary" id="modten" name="modten" value="Edit Tenant" onclick="edittenant();"></input>
        <?php } ?>
        <input type='button' class="btn btn-primary" id="tsubmit" name="tsubmit" value="Submit" onclick="tenantentry();"></input>
        <input type='button' class="btn btn-primary" id="tnext" name="tnext" value="Next" onclick="subtentry();"></input>
        <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
      </div>
    </div>
  </form>
</div>

<script src="<?= base_url('assets/js/location.js') ?>"></script>