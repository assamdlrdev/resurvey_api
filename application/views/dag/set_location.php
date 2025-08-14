<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php include APPPATH . '/views/user/message.php'; ?>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-map-marker" aria-hidden="true"></i> Select Location For Dag
            </h5>
        </div>
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <form action="<?php echo $base ?>index.php/dag/DagController/showDags" method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">District:</label>
                            <select name="dist_code" class="form-control" id="d">
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
                            <?php echo form_error('dist_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Sub-Div:</label>
                            <select name="subdiv_code" class="form-control" id="sd">
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
                            <?php echo form_error('subdiv_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Circle:</label>
                            <select name="cir_code" class="form-control" id="c">
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
                            <?php echo form_error('cir_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Mouza/Porgona:</label>
                            <select name="mouza_pargona_code" class="form-control" id="m">
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
                            <?php echo form_error('mouza_pargona_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Lot:</label>
                            <select name="lot_no" class="form-control" id="l">
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
                            <?php echo form_error('lot_no'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="v">Village:</label>
                            <select name="vill_townprt_code" class="form-control" id="v">
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
                            <?php echo form_error('vill_townprt_code'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <div class="text-right">
                    <button type='submit' class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
</div>
<script src="<?= base_url('assets/js/location.js') ?>"></script>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
<script>
    $(document).ready(function() {

    });
</script>