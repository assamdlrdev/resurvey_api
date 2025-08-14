<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 px-0 my-3">
    <div class="card card-info">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="card-header bg-success text-white">
                    <?php $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);
                    if ($application_type == $patta_application_type[0]->CODE) : ?>
                        <h3 class="card-title text-center text-white"><b>PERIODIC KHIRAJ PATTA</b></h3>
                    <?php else : ?>
                        <h3 class="card-title text-center text-white"><b>ANNUAL KHIRAJ PATTA</b></h3>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0" style="border-radius: 5px">
                    <form class="form-horizontal" method='post' id="save_patta_application" autocomplete="off">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
                                <input type="hidden" value="<?= $this->session->userdata('dcode') ?>" id="dist_code" name="dist_code">
                                <input type="hidden" value="<?= $patta_basic_existing ? $patta_basic_existing->case_no : $case_no ?>" id="case_no" name="case_no">
                                <input type="hidden" value="<?= $patta_basic_existing ? $patta_basic_existing->petition_no : $petition_no ?>" id="petition_no" name="petition_no">
                                <input type="hidden" value="<?= $application_type ?>" id="application_type" name="application_type">
                                <input type="hidden" value="<?= $vill_code ?>" id="vill_code" name="vill_code">
                                <input type="hidden" value="<?= $patta_type ?>" id="patta_type" name="patta_type">
                                <input type="hidden" value="<?= $patta_no ?>" id="patta_no" name="patta_no">
                                <input type="hidden" value="<?= $mouza_pargona_code ?>" id="mouza_code">
                                <input type="hidden" value="<?= $lot_no ?>" id="lot_no">
                                <input type="hidden" value="<?= $vill_code ?>" id="vill_code">

                                <input type="hidden" value="<?php echo (count($existing_dags) > 0 ? (count($existing_dags) + 1) : 1) ?>" id="row_id" name="row_id">

                                <table class="table table-striped table-bordered text-bold">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #136a6f; color: #fff" colspan="6">
                                                Location Details
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Applied Patta : <kbd><?= $patta_no ?></kbd> </td>
                                            <td colspan="3">Applied Patta Type : <kbd><?= $this->utilityclass->getPattaName($patta_type) ?></kbd> </td>
                                        </tr>
                                        <tr>
                                            <th>District :</th>
                                            <th class="red"><?= $this->utilityclass->getDistrictName($dist_code) ?></th>
                                            <th>Sub Division :</th>
                                            <th class="red"><?= $this->utilityclass->getSubDivName($dist_code, $subdiv_code) ?></th>
                                        </tr>
                                        <tr>
                                            <th>Circle :</th>
                                            <th class="red"><?= $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code) ?></th>
                                            <th>Mouza :</th>
                                            <th class="red"><?= $this->utilityclass->getMouzaName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code) ?></th>
                                        </tr>
                                        <tr>
                                            <th>Lot :</th>
                                            <th class="red"><?= $this->utilityclass->getLotName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no) ?></th>
                                            <th>Village :</th>
                                            <th class="red"><?= $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code) ?></th>
                                        </tr>
                                    </thead>
                                </table>
                                <table class="table table-striped table-bordered text-bold">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #136a6f; color: #fff" colspan="8">
                                                Application Details
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="25%">Date :</th>
                                            <th width="25%"><?= date('d-m-Y') ?></th>
                                        </tr>
                                    </thead>
                                </table>
                                <?php $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);
                                if ($application_type == $patta_application_type[0]->CODE) : ?>
                                    <table class="table table-striped table-bordered text-bold">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #136a6f; color: #fff" colspan="8">
                                                    Periodic Khiraj Patta Details
                                                </th>
                                            </tr>
                                            <tr>
                                                <th width="25%">For How Many Years(s) : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"> <input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->time_period : '') ?>" name='time_period' id="time_period" placeholder='For How Many Years(s)' class="form-control" /></th>
                                                <th width="25%">Upto which Date :</th>
                                                <th width="25%"><input type='text' value="<?php echo ($patta_basic_existing ? date('d-m-Y', strtotime($patta_basic_existing->upto_date)) : '') ?>" name='upto_date' id="upto_date" placeholder='To Date' class="form-control" readonly />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th width="25%">First Installment Date : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"> <input type='date' value="<?php echo ($patta_basic_existing ? date('Y-m-d', strtotime($patta_basic_existing->installment1)) : '') ?>" name='installment1' placeholder='First Installment' class="form-control dateNew" /></th>
                                                <th width="25%">Revenue to be Paid : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"><input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->revenue_to_be_paid1 : '') ?>" name='revenue_to_be_paid1' placeholder='Revenue to be Paid' class="form-control" /></th>
                                            </tr>
                                            <tr>
                                                <th width="25%">Second Installment Date: <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"> <input type='date' value="<?php echo ($patta_basic_existing ? date('Y-m-d', strtotime($patta_basic_existing->installment2)) : '') ?>" name='installment2' placeholder='Second Installment' class="form-control dateNew" /></th>
                                                <th width="25%">Revenue to be Paid : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"><input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->revenue_to_be_paid2 : '') ?>" name='revenue_to_be_paid2' placeholder='Revenue to be Paid' class="form-control" /></th>
                                            </tr>
                                        </thead>
                                    </table>
                                <?php else : ?>
                                    <table class="table table-striped table-bordered text-bold">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #136a6f; color: #fff" colspan="6">
                                                    Annual Khiraj Patta Details
                                                </th>
                                            </tr>
                                            <tr>
                                                <th width="25%">For How Many Years(s) : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"> <input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->time_period : '') ?>" name='time_period' id="time_period" placeholder='For How Many Years(s)' class="form-control" min="0" /></th>
                                                <th width="25%">Upto which Date : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th width="25%"><input type='text' value="<?php echo ($patta_basic_existing ? date('d-m-Y', strtotime($patta_basic_existing->upto_date)) : '') ?>" name='upto_date' id="upto_date" placeholder='To Date' class="form-control" readonly />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th width="25%">Revenue to be Paid :</th>
                                                <th width="25%"><input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->revenue_to_be_paid1 : '') ?>" name='revenue_to_be_paid1' placeholder='Revenue to be Paid' class="form-control" /></th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                <?php endif; ?>
                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success" id="add_dag"><i class='fa fa-plus'></i> Add Dag</button>
                                    </div>
                                </div>
                                <?php foreach ($existing_dags as $key => $dag) : ?>
                                    <table class="table table-striped table-bordered text-bold" id="div_<?php echo ($key + 1) ?>">
                                        <thead>
                                            <tr>
                                                <th style=" background-color: #136a6f; color: #fff" colspan="10">
                                                    Applicant Dag and Land Area
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" width="25%">Dag No :<span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                                <th colspan="3" width="25%">
                                                    <select class="form-select dag_no_new" data-id="<?php echo ($key + 1) ?>" id="dag_no<?php echo ($key + 1) ?>" name="dag_no[]" required>
                                                        <option value="" selected>Select Dag No</option>
                                                        <?php foreach ($dag_no as $d) : ?>
                                                            <option <?php echo ($dag->dag_no == $d->dag_no ? 'selected' : '') ?> value='<?php echo $d->dag_no; ?>'><?php echo $d->dag_no; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </th>
                                                <th colspan="2">
                                                    <?php if (count($existing_dags) == 0) : ?>
                                                        <button type="button" class="btn btn-success" id="add_dag"><i class='fa fa-plus'></i> Add More Dag</button>
                                                    <?php else : ?>
                                                        <button type="button" class="btn btn-danger dag_row" data-existing-id="<?php echo ($dag->id) ?>" data-id="<?php echo ($key + 1) ?>"><i class='fa fa-trash'></i> Delete Dag</button>
                                                    <?php endif; ?>
                                                </th>
                                                <th colspan="2"></th>
                                            </tr>
                                            <?php if (in_array($dist_code, json_decode(BARAK_VALLEY))) : ?>
                                                <tr style="background-color: #136a6f; color: #fff">
                                                    <th colspan="2">Bigha</th>
                                                    <th colspan="2">Katha</th>
                                                    <th colspan="2">Chatak</th>
                                                    <th colspan="2">Ganda</th>
                                                    <th colspan="2">Kranti</th>
                                                </tr>
                                            <?php else : ?>
                                                <tr style="background-color: #136a6f; color: #fff">
                                                    <th colspan="3">Bigha :</th>
                                                    <th colspan="3">Katha</th>
                                                    <th colspan="4">Lessa</th>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (in_array($dist_code, json_decode(BARAK_VALLEY))) : ?>
                                                <tr>
                                                    <th colspan="2" id="bigha<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_b) ?></th>
                                                    <th colspan="2" id="katha<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_k) ?></th>
                                                    <th colspan="2" id="lessa<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_lc) ?></th>
                                                    <th colspan="2" id="ganda<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_g) ?></th>
                                                    <th colspan="2" id="kranti<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_kr) ?></th>
                                                </tr>
                                            <?php else : ?>
                                                <tr id="dag_area">
                                                    <th colspan="3" id="bigha<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_b) ?></th>
                                                    <th colspan="3" id="katha<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_k) ?></th>
                                                    <th colspan="4" id="lessa<?php echo ($key + 1) ?>"><?php echo ($dag->dag_area_lc) ?></th>
                                                </tr>
                                            <?php endif; ?>
                                        </thead>
                                    </table>
                                <?php endforeach; ?>
                                <div id="add_more_dag"></div>
                                <table class="table table-striped table-bordered text-bold">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #136a6f; color: #fff" colspan="6">
                                                Applicant Details
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="25%">Applicant Name / Pattadar Name : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                            <th width="25%">
                                                <select class="form-select" id="pattadar_name" name="pattadar_name" required>
                                                    <option disabled selected>Select Pattadar</option>
                                                    <?php foreach ($pattadar as $p) : ?>
                                                        <option <?php echo ($patta_basic_existing ? ($patta_basic_existing->pattadar_id == $p->pdar_id ? 'selected' : '') : '') ?> value='<?php echo $p->pdar_id; ?>'><?php echo $p->pdar_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </th>
                                            <th width="25%">Guardian Name : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                            <th width="25%"><input type='text' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->guardian_name : '') ?>" name='guardian_name' id="guardian_name" placeholder='Guardian Name' class="form-control" readonly />
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="25%" class="required">Relation with Guardian : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>
                                            <th width="25%">
                                                <select class="form-select" id="relation" name="relation" required>
                                                    <option disabled selected>Select Relation with Guardian</option>
                                                    <?php foreach ($relation as $r) : ?>
                                                        <option <?php echo ($patta_basic_existing ? ($patta_basic_existing->relation == $r->guard_rel ? 'selected' : '') : '') ?> value='<?php echo $r->guard_rel; ?>'><?php echo $r->guard_rel_desc_as; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </th>
                                            <th width="25%">Mobile No. : </th>
                                            <th width="25%">
                                                <input type='number' value="<?php echo ($patta_basic_existing ? $patta_basic_existing->mobile_no : '') ?>" name='mobile_no' id="mobile_no" placeholder='Mobile No.' class="form-control" maxlength="10" />
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="col-lg-12" id="save_form_error_div" style="display: none;">
                                    <div class="alert alert-warning alert-dismissible" role="alert">
                                        <strong class="text-left" style="color:red !important; font-weight: bold !important;" id="form_errors">
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group" style="width: 100%;text-align: center;">
                                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                    <button type="submit" class="btn uni_text btn-primary"><i class='fa fa-check'></i> Submit & Save
                                    </button>
                                </div>
                                <div class="col-lg-12 text-center mt-3" id="save_success_div" style="display: none;">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <strong class="text-left" style="color:blue !important; font-weight: bold !important;" id="form_success">
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var BARAK_VALLEY = <?php echo BARAK_VALLEY ?>
</script>

<script src="<?= base_url('assets/js/patta.js') ?>"></script>