<?php $this->load->view('header'); ?>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/common.js') ?>"></script>
<style>
    .icon-box {
        box-shadow: 0 0 25px 0 #572ede;
        background: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
        position: relative;
        -ms-transition: 0.4s;
        -o-transition: 0.4s;
        -moz-transition: 0.4s;
        -webkit-transition: 0.4s;
        transition: 0.4s;
    }

    .icon-box:hover {
        box-shadow: 0 0 25px 0 #45de2e;
        -webkit-transform: translateY(30px);
        transform: translateY(10px);
    }
</style>
<?php if (($this->session->userdata('dcode') == '21') || ($this->session->userdata('dcode') == '22') || ($this->session->userdata('dcode') == '23')) { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
<?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
<?php } ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card rounded-0">
        <div class="card-body">
            <div class="row border border-info p-3">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <input type="hidden" id="dcode" value="<?php echo $dist_code; ?>">
                            <input type="hidden" id="scode" value="<?php echo $subdiv_code; ?>">
                            <input type="hidden" id="ccode" value="<?php echo $circle_code; ?>">
                            <input type="hidden" id="mcode" value="<?php echo $mouza_code; ?>">
                            <input type="hidden" id="lcode" value="<?php echo $lot_no; ?>">
                            <input type="hidden" id="vcode" value="<?php echo $vill_code; ?>">
                            <input type="hidden" id="dagcode" value="<?php echo $dag_no; ?>">
                            <input type="hidden" id="pattacode" value="<?php echo $patta_no; ?>">
                            <input type="hidden" id="pattatypecode" value="<?php echo $patta_type_code; ?>">


                            <td class="text-center"><b>জিলা :</b> <?= $namedata[0]->district; ?></td>
                            <td class="text-center"><b>মহকুমা:</b> <?= $namedata[1]->subdiv; ?></td>
                            <td class="text-center"><b>চক্র :</b> <?= $namedata[2]->circle; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><b><?php echo ($this->lang->line('mouza')) ?>:</b> <?= $namedata[3]->mouza; ?></td>
                            <td class="text-center"><b>লাট নং:</b> <?= $namedata[4]->lot_no; ?></td>
                            <td class="text-center"><b><?php echo ($this->lang->line('vill_town')) ?>:</b> <?= $namedata[5]->village; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row border border-info p-3">
                <table class="table table-bordered table-striped">
                    <tbody>

                        <tr>
                            <td class="text-center"><b><?php echo ($this->lang->line('dag_no')) ?>:</b> <?= $record[0]->dag_no ?> </td>
                            <td class="text-center"><b>পাট্টা নং:</b> <?= $record[0]->patta_no ?></td>
                            <td class="text-center"><b><?php echo ($this->lang->line('patta_type')) ?>:</b> <?= $record[0]->patta_type ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><b><?php echo ($this->lang->line('land_class')) ?>:</b> <?= $record[0]->land_type ?></td>
                            <td class="text-center">
                                <b>
                                    <?php echo ($this->lang->line('dag_area')) ?> -
                                </b>&nbsp;
                                <b>
                                    <?php echo ($this->lang->line('bigha')) ?>:
                                </b>
                                <input type="number" id="chitha_bigha" value="<?php echo $record[0]->dag_area_b; ?>" class="form-control">,&nbsp;
                                <b>
                                    <?php echo ($this->lang->line('katha')) ?>:
                                </b>
                                <input type="number" id="chitha_katha" value="<?php echo $record[0]->dag_area_k; ?>" class="form-control">,&nbsp;
                                <b>
                                    <?php echo ($this->lang->line('lesa')) ?>:
                                </b>
                                <input type="number" id="chitha_lessa" value="<?php echo $record[0]->dag_area_lc ?>" class="form-control">,&nbsp;
                                <br>
                                <button class="btn btn-primary form-control" id="chitha_btn">Edit Dag Area</button>
                            </td>
                            <td class="text-center"><b><?php echo ($this->lang->line('revenue_in_rupee')) ?>:</b> <?= $record[0]->dag_revenue ?>, <b><?php echo ($this->lang->line('local_tax_in_rupee')) ?>:</b> <?= $record[0]->dag_local_tax ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($pattadars)) : ?>
                <div class="row border border-info p-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="9"><?php echo ($this->lang->line('pattadar_name')) ?></th>
                            </tr>
                            <tr>
                                <th class="text-center">ক্রমিক নং.</th>
                                <th class="text-center align-middle"><?php echo ($this->lang->line('pattadar_name')) ?> নাম</th>
                                <th class="text-center"><?php echo ($this->lang->line('pattadar_name')) ?> <?php echo ($this->lang->line('guardian_name')) ?> </th>
                                <th class="text-center">দাগৰ অংশ</th>
                                <th class="text-center">প্রক্রিয়া</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serialNo = 1;
                            // Number of columns you want to display
                            foreach ($pattadars as $pattadar) : ?>
                                <tr>
                                    <td class="text-center"><?= $serialNo++ ?>)</td>
                                    <td class="text-center"><?= $pattadar->pdar_name ?></td>
                                    <td class="text-center"><?= $pattadar->pdar_father ?></td>
                                    <td class="text-center">বিঘা-<?= $pattadar->dag_por_b ?>, <?php echo ($this->lang->line('katha')) ?>-<?= $pattadar->dag_por_k ?>, <?php echo ($this->lang->line('lesa')) ?>-<?= $pattadar->dag_por_lc ?> ?> </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm viewModalButton" id="<?= $dist_code . '-' . $subdiv_code . '-' . $circle_code . '-' . $mouza_code . '-' . $lot_no . '-' . $vill_code . '-' . $dag_no . '-' . $pattadar->pdar_id . '-' . $patta_no . '-' . $patta_type_code ?>">
                                            View <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($this->session->userdata('user_desig_code') == 'LM' || $this->session->userdata('user_desig_code') == 'SK' || $this->session->userdata('user_desig_code') == 'CO') : ?>
                                            <button type="button" class="btn btn-info btn-sm editModalButton" id="<?= $dist_code . '-' . $subdiv_code . '-' . $circle_code . '-' . $mouza_code . '-' . $lot_no . '-' . $vill_code . '-' . $dag_no . '-' . $pattadar->pdar_id . '-' . $patta_no . '-' . $patta_type_code ?>">
                                                Edit <i class=" fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (!empty($tenants)) : ?>
                <div class="row border border-info p-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td class="text-center" colspan="5"><b>Tenants</b></td>
                            </tr>
                            <tr>
                                <th class="text-center">Sl No.</th>
                                <th class="text-center">Tenant Name</th>
                                <th class="text-center">Tenant Father's Name</th>
                                <th class="text-center">Tenant Type</th>
                                <th class="text-center">Khatian Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $serialNumber = 1;
                            foreach ($tenants as $tenant) : ?>
                                <tr>
                                    <td class="text-center"><?= $serialNumber++ ?>)</td>
                                    <td class="text-center"><?= ($tenant->tenant_name != '') ? $tenant->tenant_name : '' ?></td>
                                    <td class="text-center"><?= ($tenant->tenants_father != '') ? $tenant->tenants_father : ''  ?></td>
                                    <td class="text-center"><?= ($tenant->tenant_type != '') ? $tenant->tenant_type : ''  ?></td>
                                    <td class="text-center"><?= ($tenant->khatian_no != '') ? $tenant->khatian_no : ''  ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('index.php/verification/SKController/index') ?>">
                <button type="button" class="btn btn-default "><i class="fa fa-backward mr-1"></i> Back</button>
            </a>
            <button type="button" class="btn btn-primary verifyModalButton float-right">Verify <i class="fas fa-check-double"></i></button>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">View Pattadar</h5>
                <button type="button" class="close closeViewModalButton" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <?php $is_govt = in_array($patta_type_code, GovtPattaCode) ?>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="pdar_id" class="col-sm-3 col-form-label">Pattadar ID:</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="view_pdar_id" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pdar_name" class="col-sm-3 col-form-label">Pattadar Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="view_pdar_name" placeholder="Pattadar Name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" disabled>
                                </div>
                            </div>
                            <?php if (!$is_govt) : ?>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian's Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_father" placeholder="Guardian's Name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Guardian Relation:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="view_pdar_relation" disabled>
                                            <option selected value="">Select</option>
                                            <?php foreach ($relname as $row) { ?>
                                                <option value="<?php echo ($row->guard_rel); ?>">
                                                    <?= $row->guard_rel_desc_as; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Address 1:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_add1" placeholder="Address 1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Address2:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_add2" placeholder="Address 2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Address 3:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_add3" placeholder="Address 3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Bigha:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_dag_por_b" placeholder="Dag Portion Bigha" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Dag Portion Katha:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_dag_por_k" placeholder="Dag Portion Katha" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Chatak:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_dag_por_lc" placeholder="Dag Portion Chatak" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Dag Portion Ganda:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_dag_por_g" placeholder="Dag Portion Ganda" disabled>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!$is_govt) : ?>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Revenue:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_revenue" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Local Rate:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_localtax" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Land in Acre:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_acre" placeholder="Land in Acre" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">North Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_n" placeholder=" North Description" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">South Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_s" placeholder="South Description" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">East Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_e" placeholder="East Description" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">West Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_land_w" placeholder="West Description" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Pattadar Strike Out?</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="p_flag" disabled>Yes&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="p_flag" disabled>No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Gender</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="m" name="p_gender" disabled>Male&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="f" name="p_gender" disabled>Female&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="o" name="p_gender" disabled>Others
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">PAN No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_pan_no" placeholder="PAN No" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Citizen No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="view_pdar_citizen_no" placeholder="Citizen No" disabled>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeViewModalButton">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editoMdalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="base" id="base" value='<?= base_url(); ?>' />
                <input type="hidden" name="dist_code" id="dist_code" value='<?= $dist_code; ?>' />
                <input type="hidden" name="subdiv_code" id="subdiv_code" value='<?= $subdiv_code; ?>' />
                <input type="hidden" name="cir_code" id="cir_code" value='<?= $circle_code; ?>' />
                <input type="hidden" name="mouza_pargona_code" id="mouza_pargona_code" value='<?= $mouza_code; ?>' />
                <input type="hidden" name="lot_no" id="lot_no" value='<?= $lot_no; ?>' />
                <input type="hidden" name="vill_townprt_code" id="vill_townprt_code" value='<?= $vill_code; ?>' />
                <input type="hidden" name="dag_no" id="dag_no" value='<?= $dag_no; ?>' />
                <input type="hidden" name="patta_no" id="patta_no" value='<?= $patta_no; ?>' />
                <input type="hidden" name="patta_type_code" id="patta_type_code" value='<?= $patta_type_code; ?>' />
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pattadar</h5>
                    <button type="button" class="close closeEditModalButton" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php $is_govt = in_array($patta_type_code, GovtPattaCode) ?>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="edit_pdar_id" class="col-sm-3 col-form-label">Pattadar ID:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pdar_id" id="edit_pdar_id" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_pdar_name" class="col-sm-3 col-form-label">Pattadar Name: <span style="color:red">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="edit_pdar_name" placeholder="Pattadar Name" name="pdar_name" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                </div>
                            </div>
                            <?php if (!$is_govt) : ?>
                                <div class="form-group row">
                                    <label for="edit_pdar_father" class="col-sm-3 col-form-label">Guardian's Name: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_father" placeholder="Guardian's Name" name="pdar_father" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_relation" class="col-sm-3 col-form-label">Guardian Relation: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="pdar_relation" class="form-control" id="edit_pdar_relation">
                                            <option selected value="">Select</option>
                                            <?php foreach ($relname as $row) { ?>
                                                <option value="<?php echo ($row->guard_rel); ?>">
                                                    <?= $row->guard_rel_desc_as; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_add1" class="col-sm-3 col-form-label">Address 1:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add1" placeholder="Address 1" name="pdar_add1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_add2" class="col-sm-3 col-form-label">Address 2:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add2" placeholder="Address 2" name="pdar_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_add3" class="col-sm-3 col-form-label">Address 3:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_add3" placeholder="Address 3" name="pdar_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_dag_por_b" class="col-sm-3 col-form-label">Dag Portion Bigha: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_dag_por_b" placeholder="Dag Portion Bigha" name="dag_por_b">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_dag_por_k" class="col-sm-3 col-form-label">Dag Portion Katha: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_dag_por_k" placeholder="Dag Portion Katha" name="dag_por_k">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_dag_por_lc" class="col-sm-3 col-form-label">Dag Portion Chatak: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_dag_por_lc" placeholder=" Dag Portion Chatak" name="dag_por_lc">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_dag_por_g" class="col-sm-3 col-form-label">Dag Portion Ganda: <span style="color:red">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_dag_por_g" placeholder="Dag Portion Ganda" name="dag_por_g">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!$is_govt) : ?>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="edit_pdar_land_revenue" class="col-sm-3 col-form-label">Revenue:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_revenue" name=" pdar_land_revenue">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_localtax" class="col-sm-3 col-form-label">Local Rate:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_localtax" name="pdar_land_localtax">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_acre" class="col-sm-3 col-form-label">Land in Acre:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_acre" name="pdar_land_acre" placeholder="Land in Acre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_n" class="col-sm-3 col-form-label">North Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_n" placeholder="North Description" name="pdar_land_n">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_s" class="col-sm-3 col-form-label">South Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_s" placeholder="South Description" name="pdar_land_s">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_e" class="col-sm-3 col-form-label">East Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_e" placeholder=" East Description" name="pdar_land_e">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_land_w" class="col-sm-3 col-form-label">West Description:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_land_w" placeholder=" West Description" name="pdar_land_w">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Pattadar Strike Out?</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="p_flag">Yes&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="p_flag">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Gender</label>
                                    <div class="col-sm-9">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="m" name="p_gender">Male&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="f" name="p_gender">Female&nbsp;&nbsp;
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="o" name="p_gender">Others
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_pan_no" class="col-sm-3 col-form-label">PAN No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_pan_no" placeholder="PAN No" name="pdar_pan_no">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_pdar_citizen_no" class="col-sm-3 col-form-label">Citizen No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="edit_pdar_citizen_no" placeholder="Citizen No" name="pdar_citizen_no">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeEditModalButton">Close</button>
                    <button type="button" id="pattadarEditSubmit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">AADHAAR HOLDER CONSENT</h5>
                <button type="button" class="close closeVerifyModalButton" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-bold">
                                        Please check the box to provide your consent to the below option.
                                    </p>
                                    <?= $addhaar_consent_content; ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="accept" id="consentAgreed">
                                        <label class="form-check-label text-bold" for="consentAgreed">
                                            <?= $aadhaar_consent_checkbox_text; ?>
                                        </label>
                                        <div class="text-danger" id="consentAgreedError"></div>
                                    </div>
                                    <button class="btn btn-primary" id="btn-esign">Verify Using Aadhaar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-center text-bold">Verify Using E-Sign</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeVerifyModalButton">Close <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>


<script>
    function preventCheckboxChange(e) {
        e.preventDefault();
    }


    $(document).on('click', '.viewModalButton', (e) => {
        var locationSplit = e.currentTarget.id.split('-');
        var dist_code = locationSplit[0];
        var subdiv_code = locationSplit[1];
        var cir_code = locationSplit[2];
        var mouza_pargona_code = locationSplit[3];
        var lot_no = locationSplit[4];
        var vill_townprt_code = locationSplit[5];
        var dag_no = locationSplit[6];
        var pdar_id = locationSplit[7];
        var patta_no = locationSplit[8];
        var patta_type_code = locationSplit[9];
        var baseurl = $('#base').val();

        $.ajax({
            url: baseurl + "index.php/Chithacontrol/viewPattadarDetails",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code,
                dag_no: dag_no,
                pdar_id: pdar_id,
                patta_no: patta_no,
                patta_type_code: patta_type_code
            },
            type: 'POST',
            success: function(data) {
                var response = JSON.parse(data);
                $('#viewModal').modal({
                    backdrop: 'static' // Prevent closing on click outside
                });
                $('#viewModal').modal('show', response);
            }
        });
    });

    $('#viewModal').on('show.bs.modal', function(e) {
        var patta_type_code = '<?php echo $patta_type_code; ?>';
        var govtPattaCodes = <?php echo json_encode(GovtPattaCode); ?>;

        $('input[type="radio"][name="pattadar_flag"]').on('change', preventCheckboxChange);
        $('input[type="radio"][name="pattadar_gender"]').on('change', preventCheckboxChange);

        console.log(e.relatedTarget);
        $('#view_pdar_id').val(e.relatedTarget.pdar_id);
        $('#view_pdar_name').val(e.relatedTarget.pdar_name);
        $('#view_pdar_father').val(e.relatedTarget.pdar_father);
        $('#view_pdar_relation').val(e.relatedTarget.pdar_guard_reln).trigger('change');
        $('#view_pdar_add1').val(e.relatedTarget.pdar_add1);
        $('#view_pdar_add2').val(e.relatedTarget.pdar_add2);
        $('#view_pdar_add3').val(e.relatedTarget.pdar_add3);
        $('#view_dag_por_b').val(e.relatedTarget.dag_por_b);
        $('#view_dag_por_k').val(e.relatedTarget.dag_por_k);
        $('#view_dag_por_lc').val(e.relatedTarget.dag_por_lc);
        $('#view_dag_por_g').val(e.relatedTarget.dag_por_g);
        $('#view_pdar_land_revenue').val(e.relatedTarget.pdar_land_revenue);
        $('#view_pdar_land_localtax').val(e.relatedTarget.pdar_land_localtax);
        $('#view_pdar_land_acre').val(e.relatedTarget.pdar_land_acre);
        $('#view_pdar_land_n').val(e.relatedTarget.pdar_land_n);
        $('#view_pdar_land_s').val(e.relatedTarget.pdar_land_s);
        $('#view_pdar_land_e').val(e.relatedTarget.pdar_land_e);
        $('#view_pdar_land_w').val(e.relatedTarget.pdar_land_w);
        $('#view_pdar_pan_no').val(e.relatedTarget.pdar_pan_no);
        $('#view_pdar_citizen_no').val(e.relatedTarget.pdar_citizen_no);

        if (govtPattaCodes.indexOf(patta_type_code) === -1) {
            // Check if e.relatedTarget.pdar_gender is defined and not empty
            if (e.relatedTarget && e.relatedTarget.pdar_gender) {
                // Set radio button for p_gender
                $("input[name=p_gender][value='" + e.relatedTarget.pdar_gender + "']").prop('checked', true);
            } else {
                // If e.relatedTarget.p_gender is not defined or empty, uncheck all radio buttons for p_gender
                $("input[name=p_gender]").prop('checked', false);
            }

            // Similarly, handle p_flag based on e.relatedTarget.p_flag
            if (e.relatedTarget && e.relatedTarget.p_flag) {
                // Set radio button for p_flag
                $("input[name=p_flag][value='" + e.relatedTarget.p_flag + "']").prop('checked', true);
            } else {
                // If e.relatedTarget.p_flag is not defined or empty, uncheck all radio buttons for p_flag
                $("input[name=p_flag]").prop('checked', false);
            }
        }
    });

    $(document).on('click', '.editModalButton', (e) => {
        var locationSplit = e.currentTarget.id.split('-');
        var dist_code = locationSplit[0];
        var subdiv_code = locationSplit[1];
        var cir_code = locationSplit[2];
        var mouza_pargona_code = locationSplit[3];
        var lot_no = locationSplit[4];
        var vill_townprt_code = locationSplit[5];
        var dag_no = locationSplit[6];
        var pdar_id = locationSplit[7];
        var patta_no = locationSplit[8];
        var patta_type_code = locationSplit[9];
        var baseurl = $('#base').val();

        $.ajax({
            url: baseurl + "index.php/Chithacontrol/viewPattadarDetails",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code,
                dag_no: dag_no,
                pdar_id: pdar_id,
                patta_no: patta_no,
                patta_type_code: patta_type_code
            },
            type: 'POST',
            success: function(data) {
                var response = JSON.parse(data);
                $('#editModal').modal({
                    backdrop: 'static' // Prevent closing on click outside
                });
                $('#editModal').modal('show', response);
            }
        });
    });

    $('#editModal').on('show.bs.modal', function(e) {
        var patta_type_code = $("#patta_type_code").val();
        var govtPattaCodes = <?= json_encode(GovtPattaCode); ?>;
        console.log(e.relatedTarget);
        $('#edit_pdar_id').val(e.relatedTarget.pdar_id);
        $('#edit_pdar_name').val(e.relatedTarget.pdar_name);
        $('#edit_pdar_father').val(e.relatedTarget.pdar_father);
        $('#edit_pdar_relation').val(e.relatedTarget.pdar_guard_reln).trigger('change');
        $('#edit_pdar_add1').val(e.relatedTarget.pdar_add1);
        $('#edit_pdar_add2').val(e.relatedTarget.pdar_add2);
        $('#edit_pdar_add3').val(e.relatedTarget.pdar_add3);
        $('#edit_dag_por_b').val(e.relatedTarget.dag_por_b);
        $('#edit_dag_por_k').val(e.relatedTarget.dag_por_k);
        $('#edit_dag_por_lc').val(e.relatedTarget.dag_por_lc);
        $('#edit_dag_por_g').val(e.relatedTarget.dag_por_g);
        $('#edit_pdar_land_revenue').val(e.relatedTarget.pdar_land_revenue);
        $('#edit_pdar_land_localtax').val(e.relatedTarget.pdar_land_localtax);
        $('#edit_pdar_land_acre').val(e.relatedTarget.pdar_land_acre);
        $('#edit_pdar_land_n').val(e.relatedTarget.pdar_land_n);
        $('#edit_pdar_land_s').val(e.relatedTarget.pdar_land_s);
        $('#edit_pdar_land_e').val(e.relatedTarget.pdar_land_e);
        $('#edit_pdar_land_w').val(e.relatedTarget.pdar_land_w);
        $('#edit_pdar_pan_no').val(e.relatedTarget.pdar_pan_no);
        $('#edit_pdar_citizen_no').val(e.relatedTarget.pdar_citizen_no);

        // Check if patta_type_code is not in the array of government patta codes
        if (govtPattaCodes.indexOf(patta_type_code) === -1) {
            // Check if e.relatedTarget.pdar_gender is defined and not empty
            if (e.relatedTarget && e.relatedTarget.pdar_gender) {
                // Set radio button for gender
                $("input[name=p_gender][value='" + e.relatedTarget.pdar_gender + "']").prop('checked', true);
            } else {
                // If e.relatedTarget.pdar_gender is not defined or empty, uncheck all radio buttons for p_gender
                $("input[name=p_gender]").prop('checked', false);
            }

            // Similarly, handle p_flag based on e.relatedTarget.p_flag
            if (e.relatedTarget && e.relatedTarget.p_flag) {
                // Set radio button for p_flag
                $("input[name=p_flag][value='" + e.relatedTarget.p_flag + "']").prop('checked', true);
            } else {
                // If e.relatedTarget.p_flag is not defined or empty, uncheck all radio buttons for p_flag
                $("input[name=p_flag]").prop('checked', false);
            }
        }


    });
    $(document).on('click', '#pattadarEditSubmit', (e) => {
        var baseurl = $('#base').val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/Chithacontrol/pattadarUpdate",
            data: $('form').serialize(),
            type: "POST",
            success: function(data) {
                if (data.st == 1) {
                    swal("", data.msg, "success")
                        .then((value) => {
                            window.location.reload();
                        });
                    //swal("", data.msg, "success");
                } else if (data.st == 0) {
                    swal("", data.msg, "info");
                } else {
                    swal("", data.msg, "info");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                swal("", "An error occurred while processing your request. Please try again later.", "error");
            }
        });
    });

    $(document).on('click', '.verifyModalButton', (e) => {
        $('#verifyModal').modal('show');
    });

    // Close view modal
    $(".closeViewModalButton").click(function() {
        $('#viewModal').modal('hide');
    });

    // Close edit modal
    $(".closeEditModalButton").click(function() {
        $('#editModal').modal('hide');
    });

    $(".closeVerifyModalButton").click(function() {
        $('#verifyModal').modal('hide');
    });

    $(document).on('click', '#btn-esign', (e) => {
        $('#consentAgreedError').text('');
        if (!$('#consentAgreed').prop('checked')) {
            $('#consentAgreedError').text('Please check the checkbox');
            return false;
        }
        $('.loader-wrap').show();
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();
        var cir_code = $('#cir_code').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $('#lot_no').val();
        var vill_townprt_code = $('#vill_townprt_code').val();
        var patta_no = $('#patta_no').val();
        var patta_type_code = $('#patta_type_code').val();
        var dag_no = $('#dag_no').val();
        var baseurl = $('#base').val();
        var acceptConsent = $('#consentAgreed').prop('checked');
        $.ajax({
            url: '<?= $signURL ?>',
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code,
                patta_no: patta_no,
                patta_type_code: patta_type_code,
                dag_no: dag_no,
                accept_consent: acceptConsent
            },
            type: 'POST',
            success: (data) => {
                var response = JSON.parse(data);
                if (response.status == "FAILED" && response.responseType == 1) {
                    $('.loader-wrap').hide();
                    alert(response.msg);
                } else if (response.status == "SUCCESS" && response.responseType == 0) {
                    console.log(response.data);
                    // loadESign(response.data.url);
                    // window.location.href = 'D:\\laragon\\www\\esign/index.php?dc=22&sc=01&cc=04&mc=01&lc=05&vc=10004&d=1';
                    window.location.href = response.data.url;
                } else {
                    $('.loader-wrap').hide();
                }
            },
            error: function(errors) {
                $('.loader-wrap').hide();
            }
        });

        // console.log(dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, patta_no, patta_type_code, dag_no);
    });

    // function loadESign(url) {
    //     $.ajax({
    //         url:url,
    //         type: 'POST',
    //         success: (response) => {
    //             console.log(response);
    //         }
    //     });
    // }

    $(document).on('click', '#chitha_btn', function() {
        var result = confirm("Please Confirm to Update Area ?");
        if (result) {
            var baseurl = $('#base').val();
            var chitha_bigha = $('#chitha_bigha').val();
            var chitha_katha = $('#chitha_katha').val();
            var chitha_lessa = $('#chitha_lessa').val();
            var chitha_ganda = $('#chitha_ganda').val();
            var dist_code = $('#dcode').val();
            var subdiv_code = $('#scode').val();
            var cir_code = $('#ccode').val();
            var mouza_pargona_code = $('#mcode').val();
            var lot_no = $('#lcode').val();
            var vill_townprt_code = $('#vcode').val();
            var dag_no = $('#dagcode').val();
            var patta_no = $('#pattacode').val();
            var patta_type_code = $('#pattatypecode').val();

            if (chitha_bigha != '' && chitha_katha != '' && chitha_lessa != '' && chitha_bigha != undefined && chitha_katha != undefined && chitha_lessa != undefined) {
                $.ajax({
                    url: baseurl + 'index.php/verification/SKController/dagAreaEdit',
                    method: 'POST',
                    data: {
                        dist_code: dist_code,
                        subdiv_code: subdiv_code,
                        cir_code: cir_code,
                        mouza_pargona_code: mouza_pargona_code,
                        lot_no: lot_no,
                        vill_townprt_code: vill_townprt_code,
                        dag_no: dag_no,
                        patta_no: patta_no,
                        patta_type_code: patta_type_code,
                        chitha_bigha: chitha_bigha,
                        chitha_katha: chitha_katha,
                        chitha_lessa: chitha_lessa
                    },
                    success: function(resp) {
                        var response = JSON.parse(resp);
                        if (response.status == 'FAILED') {
                            alert(response.msg);
                        } else if (response.status == 'SUCCESS') {
                            alert(response.msg);
                            var chitha_basic = response.data;
                            $('#chitha_bigha').val(chitha_basic.dag_area_b);
                            $('#chitha_katha').val(chitha_basic.dag_area_k);
                            $('#chitha_lessa').val(chitha_basic.dag_area_lc);
                        }
                    }
                });
            }
        } else {
            
        }

    });
</script>