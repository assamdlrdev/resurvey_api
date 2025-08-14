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
                                <select class="form-control districtselect" id="LmMutationSelectDistrict" name="dist_code" required>
                                    <option selected disabled>Select District</option>
                                    <?php $dist_code = $this->session->userdata('dist_code'); ?>
                                    <option value="<?php echo $dist_code; ?>">
                                        <?php echo $this->utilityclass->getDistrictName($dist_code); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('subdivision') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control subdivselect" id="select" name="subdiv_code" required>
                                    <option selected disabled>Select Sub-divsion</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('circle') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control circleselect" id="select" required name="circle_code">
                                    <option selected disabled>Select Circle</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('mouza') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control mouzaselect" id="select" required name="mouza_code">
                                    <option disabled selected>Select Mouza</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('lot_no') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control lotselect" id="select" required name="lot_no">
                                    <option disabled selected>Select Lot Number</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label"><?php echo $this->lang->line('vill_town') ?></label>
                            <div class="col-lg-9">
                                <select class="form-control villageselect" id="select" required name="vill_code">
                                    <option disabled selected>Select Village/Town</option>
                                </select>
                            </div>
                        </div>
                        <hr style="border-bottom: 2px solid #000;">
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-3">
                                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                <button type="submit" name="ASTSTEP1Submit" class="btn btn-success" onclick="return check();"><i class='fa fa-check'></i>&nbsp;<?php echo $this->lang->line('submit_button'); ?></button>
                                <button type="reset" name="ASTSTEP1Su" class="btn btn-primary"><i class='fa fa-refresh'>&nbsp;</i><?php echo $this->lang->line('reset'); ?></button>
                                <a href="<?php echo base_url(); ?>index.php/JamabandiControllerBondita/menu" class="btn btn-danger">
                                    <i class="fa fa-arrow-left"></i>&nbsp;<?php echo $this->lang->line('back_to_main_menu'); ?>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>