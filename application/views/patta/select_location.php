<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
<div class="col-lg-12 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-info">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title text-center font-weight-bold">Patta Details</h3>
                </div>
                <div class="card-heading">
                    <h3 class="card-title text-center"><?php echo $this->lang->line('select_location') ?></h3>
                </div>
                <div class="card-body">
                    <?php echo form_open(base_url('index.php/PattaController/storePattaLocation')); ?>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label required">Application Type</label>
                        <div class="col-lg-12">
                            <select class="form-control" id="application_type" name="application_type" required>
                                <option disabled selected>Application Type</option>
                                <?php foreach (json_decode(PATTA_APPLICATION_TYPE) as $nor) : ?>
                                    <option value="<?= $nor->CODE ?>"><?= $nor->NAME ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('application_type', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label" >District</label>
                        <div class="col-lg-12">
                            <select class="form-control districtselect" disabled id="d" name="dist_code" required>
                                <?php $dist_code = $this->session->userdata('dcode'); ?>
                                <option value="<?php echo $dist_code; ?>" selected>
                                    <?php echo $this->utilityclass->getDistrictName($dist_code); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label">Sub-Div</label>
                        <div class="col-lg-12">
                            <select class="form-control subdivselect" disabled id="sd" name="subdiv_code" required>
                                <?php $subdiv_code = $this->session->userdata('subdiv_code'); ?>
                                <option value="<?php echo $subdiv_code; ?>" selected>
                                    <?php echo $this->utilityclass->getSubDivName($dist_code, $subdiv_code); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label">Circle</label>
                        <div class="col-lg-12">
                            <select class="form-control circleselect" disabled id="c" required name="circle_code">
                                <?php $cir_code = $this->session->userdata('cir_code'); ?>
                                <option value="<?php echo $cir_code; ?>" selected>
                                    <?php echo $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label required">Mouza/Porgona</label>
                        <div class="col-lg-12">
                            <select class="form-control"  name="mouza_code" required id="m">
                                <option disabled selected>Select Mouza/Porgona</option>
                                <?php foreach ($mouzas as $mouza) : ?>
                                    <option value='<?php echo $mouza['mouza_pargona_code']; ?>'><?php echo $mouza['loc_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('mouza_code', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text control-label required">Lot</label>
                        <div class="col-lg-12">
                            <select class="form-control lotselect" id="l" name="lot_no" required>
                                <option disabled selected>Select Lot</option>
                                <?php $lot_no = $this->session->userdata('lot_no'); ?>
                                <option value="<?php echo $lot_no; ?>">
                                    <?php echo $this->utilityclass->getLotLocationName($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no); ?>
                                </option>
                            </select>
                            <?php echo form_error('lot_no', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text required control-label">Village / Town</label>
                        <div class="col-lg-12">
                            <select class="form-control" id="v" name="vill_code" required>
                                <option disabled selected>Select Village/Town</option>
                                <?php foreach ($villages as $d) : ?>
                                    <option value='<?php echo $d->vill_townprt_code; ?>'><?php echo $d->loc_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('vill_code', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text required control-label">Patta Type</label>
                        <div class="col-lg-12">
                            <select class="form-control" id="patta_type" name="patta_type" required>
                                <option disabled selected>Select Patta Type</option>
                            </select>
                            <?php echo form_error('patta_type', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select" class="col-lg-3 uni_text required control-label">Patta No</label>
                        <div class="col-lg-12">
                            <select class="form-control" id="patta_no" name="patta_no" required>
                                <option disabled selected>Select Patta No</option>
                            </select>
                            <?php echo form_error('patta_no', '<p class="text-danger form_error">', '</p>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-lg-offset-3">
                            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                            <button type='submit' class="btn btn-primary">SUBMIT</button>
                        </div>
                    </div>
                    </form>
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="col-lg-12 ">
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong class="rasid" style="color:red !important"><?php echo $this->session->flashdata('message'); ?></strong>
                            </div>
                            <?php if ($this->session->flashdata('message2')) : ?>
                                <div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong class="rasid" style="color:red !important"><?php echo $this->session->flashdata('message2'); ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/patta.js') ?>"></script>