<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 py-1 bg-info text-center">
            <h5>
                <i class="fa fa-map-marker" aria-hidden="true"></i> Location Details For NC Village Chitha Entry
            </h5>
        </div>
        <div class="card-body">
            <?php echo form_open('ChithaSvamitvaController/indexSubmitSvamitva'); ?>

            <div class="row border border-info p-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">District:</label>
                        <select name="dist_code" class="form-control form-control-sm" id="d">
                            <option selected value="">Select District</option>
                            <?php foreach ($districts as $value) { ?>
                                <option value="<?= $value['dist_code'] ?>" <?php if ($locations and $current_url == current_url()) {
                                                                                if ($locations['dist']['code'] == $value['dist_code']) {
                                                                                    echo 'selected';
                                                                                }
                                                                            }
                                                                            ?>><?= $value['loc_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Sub-Div:</label>
                        <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                            <option value="">Select Sub Division </option>
                            <?php if ($locations and $current_url == current_url()) {
                                foreach ($locations['subdivs']['all'] as $value) { ?>
                                    <option value="<?= $value['subdiv_code'] ?>" <?php if ($locations['subdivs']['code'] == $value['subdiv_code']) {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>><?= $value['loc_name'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Circle:</label>
                        <select name="cir_code" class="form-control form-control-sm" id="c">
                            <option value="">Select Circle </option>
                            <?php if ($locations and $current_url == current_url()) {
                                foreach ($locations['cir']['all'] as $value) { ?>
                                    <option value="<?= $value['cir_code'] ?>" <?php if ($locations['cir']['code'] == $value['cir_code']) {
                                                                                echo 'selected';
                                                                            }
                                                                            ?>><?= $value['loc_name'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Mouza/Porgona:</label>
                        <select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
                            <option value="">Select Mouza </option>
                            <?php if ($locations and $current_url == current_url()) {
                                foreach ($locations['mza']['all'] as $value) { ?>
                                    <option value="<?= $value['mouza_pargona_code'] ?>" <?php if ($locations['mza']['code'] == $value['mouza_pargona_code']) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>><?= $value['loc_name'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Lot:</label>
                        <select name="lot_no" class="form-control form-control-sm" id="l">
                            <option value="">Select Lot </option>
                            <?php if ($locations and $current_url == current_url()) {
                                foreach ($locations['lot']['all'] as $value) { ?>
                                    <option value="<?= $value['lot_no'] ?>" <?php if ($locations['lot']['code'] == $value['lot_no']) {
                                                                                echo 'selected';
                                                                            }
                                                                            ?>><?= $value['loc_name'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Village:</label>
                        <select name="vill_townprt_code" class="form-control form-control-sm" id="v">
                            <option value="">Select Village </option>
                            <?php if ($locations and $current_url == current_url()) {
                                foreach ($locations['vill']['all'] as $value) { ?>
                                    <option value="<?= $value['vill_townprt_code'] ?>" <?php if ($locations['vill']['code'] == $value['vill_townprt_code']) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>><?= $value['loc_name'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Block:</label>
                        <select name="block_code" class="form-control form-control-sm" id="block">
                            <option value=""> Select Block</option>
                            <?php foreach ($blocks as $singleBlock) : ?>
                                <option value="<?php echo $singleBlock->block_code ?>" <?php if ($locations and $current_url == current_url()) {
                                                                                            if ($singleBlock->block_code == $block) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>>
                                    <?php echo $singleBlock->block_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="sel1">Gram Panchayat:</label>
                        <select name="gram_Panch_code" class="form-control form-control-sm" id="gb">
                            <option value="">Select Gram Panchayat </option>
                            <?php if ($locations and $current_url == current_url()) : ?>
                                <?php foreach ($panches as $p) : ?>
                                    <option value="<?php echo $p->panch_code ?>" <?php if ($panch == $p->panch_code) {
                                                                                        echo "selected";
                                                                                    } ?>>
                                        <?php echo $p->panch_name ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right" style="margin-top: 20px">
                    <button type='button' class="btn btn-info" name="submit" onclick='checkloc();' value="Submit">
                        <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/location_svamitva.js?v=1.3') ?>"></script>