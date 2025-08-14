<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
    <div class="card">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                Select Location For Svamitva Card
            </h5>
        </div>
        <div class="card-body">
            <form action="<?php echo $base ?>index.php/show-svamitva-card" method="post" enctype="multipart/form-data">
                <div class="row border border-info p-3">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dist_code">District:</label>
                            <select name="dist_code" class="form-control form-control-sm" id="d">
                                <option selected value="">Select District</option>
                                <?php foreach ($districts as $value) { ?>
                                    <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
                                <?php } ?>
                            </select>
                            <?php echo form_error('dist_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="subdiv_code">Sub-Div:</label>
                            <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                                <option value="">Select Sub Division </option>
                            </select>
                            <?php echo form_error('subdiv_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="cir_code">Circle:</label>
                            <select name="cir_code" class="form-control form-control-sm" id="c">
                                <option value="">Select Circle </option>
                            </select>
                            <?php echo form_error('cir_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="mouza_pargona_code">Mouza/Porgona:</label>
                            <select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
                                <option value="">Select Mouza </option>
                            </select>
                            <?php echo form_error('mouza_pargona_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="lot_no">Lot:</label>
                            <select name="lot_no" class="form-control form-control-sm" id="l">
                                <option value="">Select Lot </option>
                            </select>
                            <?php echo form_error('lot_no'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="vill_townprt_code">Village:</label>
                            <select onchange="getDags()" name="vill_townprt_code" class="form-control form-control-sm" id="v">
                                <option value="">Select Village </option>
                            </select>
                            <?php echo form_error('vill_townprt_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dag_no">Dag No:</label>
                            <select name="dag_no" class="form-control form-control-sm" id="dag_no">
                                <option value="">Select Dag </option>
                            </select>
                            <?php echo form_error('dag_no'); ?>
                        </div>
                    </div>
                </div>


                <br>


                <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <div class="text-right">
                    <button type='submit' class="btn btn-primary">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    <br>
</div>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/location_svamitva.js?v=1.1') ?>"></script>
<script>
    function getDags() {
        var baseurl = $("#base").val();
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/SvamitvaCardController/getDags",
            data: $('form').serialize(),
            type: "POST",
            beforeSend: function() {
                $('#dag_no').prop('selectedIndex', 0);
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function(data) {
                $.unblockUI();
                var html = '';
                var i;
                html += '<option value="">Select Dag</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].dag + '>' + data[i].dag + '</option>';
                }
                $('#dag_no').html(html);
            },
            error: function(jqXHR, exception) {
                $.unblockUI();
                $('#dag_no').prop('selectedIndex', 0);
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
    }
</script>