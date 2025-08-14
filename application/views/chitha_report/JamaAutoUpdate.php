<div class="row login">

    <div class="col-lg-12 ">
        <div class="col-lg-8 col-lg-offset-2">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><?php echo $this->lang->line('select_location') ?></h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="<?php echo base_url() . 'index.php/Jamabandi/index' ?>">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('district') ?></label>
                            <div class="col-sm-4">
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
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('subdivision') ?></label>
                            <div class="col-sm-4">
                                <select class="form-control subdivselect" id="select" name="subdiv_code" required>
                                    <option selected disabled>Select Sub-divsion</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('circle') ?></label>
                            <div class="col-sm-4">
                                <select class="form-control circleselect" id="select" required name="circle_code">
                                    <option selected disabled>Select Circle</option>

                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('mouza') ?></label>
                            <div class="col-sm-4">
                                <select class="form-control mouzaselect" id="select" required name="mouza_code">
                                    <option disabled selected>Select Mouza</option>

                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('lot_no') ?></label>
                            <div class="col-sm-4">
                                <select class="form-control lotselect" id="select" required name="lot_no">
                                    <option disabled selected>Select Lot Number</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 uni_text control-label"><?php echo $this->lang->line('vill_town') ?></label>
                            <div class="col-sm-4">
                                <select class="form-control villageselect" id="select" required name="vill_code">
                                    <option disabled selected>Select Village/Town</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>

                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-4">
                                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                <button type="submit" class="btn uni_text btn-primary"> <?php echo $this->lang->line('submit_button') ?> <i class="fa fa-history" aria-hidden="true"></i> </button>
                                <button type="reset" id="MainIndex" class="btn uni_text btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <?php echo $this->lang->line('cancel') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>