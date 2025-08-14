<div class="row login">

    <div class="col-lg-12 ">
        <div class="col-lg-6 col-lg-offset-3">
            <?php if ($this->session->flashdata('message')) : ?>
                <?php include 'message.php'; ?>
            <?php endif; ?>
            <div class="well well-sm mis_report">
                <h3 style="text-align: center; font-size: 28px">Chitha Display</h3>
                <h2 style="text-align: center; color: #fff; font-size: 34px"></h2>
            </div>

            <div class="panel panel-form">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $this->lang->line('select_location') ?></h3>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal unicode" name="form" method='post' action="<?php echo base_url() . 'index.php/chithareport/generateDagChitha' ?>">
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('district') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control districtselect" id="d" name="dist_code" required>
                                    <?php $dist_code = $this->session->userdata('dist_code'); ?>
                                    <option value="<?php echo $dist_code; ?>" selected>
                                        <?php echo $this->utilityclass->getDistrictName($dist_code); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('subdivision') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control subdivselect" id="sd" name="subdiv_code" required>
                                    <?php $subdiv_code = $this->session->userdata('subdiv_code'); ?>
                                    <option value="<?php echo $subdiv_code; ?>" selected>
                                        <?php echo $this->utilityclass->getSubDivName($dist_code, $subdiv_code); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('circle') ?></label>
                            <div class="col-lg-9">
                                <?php
                                $d = $this->utilityclass->getAllCircleName($dist_code, $subdiv_code);
                                ?>
                                <select class="form-control circleselect" id="c" required name="circle_code">
                                    <option selected disabled>Select Circle</option>
                                    <?php foreach ($d as $name) { ?>
                                        <option value="<?php echo $name->cir_code; ?>">
                                            <?php echo $name->loc_name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('mouza') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control mouzaselect" id="m" required name="mouza_code">
                                    <option disabled selected>Select Mouza</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('lot_no') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control lotselect" id="l" name="lot_no">
                                    <option disabled selected>Select Lot No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('vill_town') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control villageselect" id="v" name="vill_code">
                                    <option disabled selected>Select Village/Town</option>
                                </select>
                            </div>
                        </div>
                        <hr style="border-bottom: 2px solid #000;">
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-3">
                                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                <button type="submit" name="ASTSTEP1Submit" class="btn btn-success" onclick="return check();">Submit</button>
                                <button type="reset" name="ASTSTEP1Su" class="btn btn-primary">Reset</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= base_url('assets/js/location.js') ?>"></script>