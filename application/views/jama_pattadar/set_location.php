<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php include APPPATH . '/views/user/message.php'; ?>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <option selected value="">Select District</option>
                <?php foreach ($districts as $value) { ?>
                    <option value="<?= $value['dist_code'] ?>" <?php if ($locations and $current_url == current_url()) {
                                                                    if ($locations['dist']['code'] == $value['dist_code']) {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>><?= $value['loc_name'] ?></option>
                <?php } ?>
                <i class="fa fa-map-marker" aria-hidden="true"></i> Select Location for Pattadar Serial No
            </h5>
        </div>
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <form action="<?php echo $base ?>index.php/jamabandi/JamaPattadarController/getJamabandiPattadars" method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">District:</label>
                            <select name="dist_code" class="form-control" id="d">

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
                            <select onchange="getPattaNos()" name="vill_townprt_code" class="form-control" id="v">
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
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="patta_no">Patta No:</label>
                            <select onchange="getPattaTypes()" name="patta_no" class="form-control" id="patta_no">
                                <option value="">Select Patta No </option>
                                <?php if ($locations and $current_url == current_url()) : ?>
                                    <?php foreach ($patta_nos as $p) : ?>
                                        <option value="<?php echo $p->patta_no ?>" <?php if ($patta_no == $p->patta_no) {
                                                                                        echo "selected";
                                                                                    } ?>>
                                            <?php echo $p->patta_no ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('patta_no'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="patta_type">Patta Type:</label>
                            <select name="patta_type" class="form-control" id="patta_type">
                                <option value="">Select Patta Type </option>
                                <?php if ($locations and $current_url == current_url()) : ?>
                                    <?php foreach ($patta_types as $p) : ?>
                                        <option value="<?php echo $p->type_code ?>" <?php if ($patta_type == $p->type_code) {
                                                                                        echo "selected";
                                                                                    } ?>>
                                            <?php echo $p->patta_type ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('patta_type'); ?>
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
    function getPattaNos() {
        var dist_code = $("#d").val();
        var subdiv_code = $("#sd").val();
        var cir_code = $("#c").val();
        var mouza_pargona_code = $("#m").val();
        var lot_no = $("#l").val();
        var vill_townprt_code = $("#v").val();
        var baseurl = $("#base").val();
        $.ajax({
            url: baseurl + 'index.php/jamabandi/JamaPattadarController/getPattaNos',
            type: 'POST',
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {


                // var html = '';
                // var i;
                // html += '<option value="">--Select Patta No--</option>';
                // for (i = 0; i < data.length; i++) {
                //     html += '<option value=' + data[i].patta_no + '>' + data[i].patta_no + '</option>';
                // }
                // $('#patta_no').html(html);

                var data = JSON.parse(data);
                var patta_no_html = '<option selected disabled>Select Patta No</option>';
                if (data.st == 1) {
                    if (data.msg) {
                        $.each(data.msg, function(item, patta_no) {
                            patta_no_html += '<option value="' + patta_no + '">' + patta_no + '</option>'
                        });
                    } else {
                        $("#patta_no").html(patta_no_html);
                        var patta_type_html = '<option selected disabled>Select Patta Type</option>';
                        $("#patta_type").html(patta_type_html);
                    }
                } else {
                    Swal.fire("", JSON.parse(data).msg, "error");
                }
                $("#patta_no").html(patta_no_html);
                var patta_type_html = '<option selected disabled>Select Patta Type</option>';
                $("#patta_type").html(patta_type_html);
            }
        });

    }

    function getPattaTypes() {
        var dist_code = $("#d").val();
        var subdiv_code = $("#sd").val();
        var cir_code = $("#c").val();
        var mouza_pargona_code = $("#m").val();
        var lot_no = $("#l").val();
        var vill_townprt_code = $("#v").val();
        var patta_no = $("#patta_no").val();
        var baseurl = $("#base").val();
        $.ajax({
            url: baseurl + 'index.php/jamabandi/JamaPattadarController/getJamabandiPattaTypes',
            type: 'POST',
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code,
                patta_no: patta_no
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {
                var data = JSON.parse(data);
                var patta_type_html = '<option selected disabled>Select Patta Type</option>';
                if (data.st == 1) {
                    if (data.msg) {
                        $.each(data.msg, function(item, patta_type) {
                            patta_type_html += '<option value="' + patta_type.type_code + '">' + patta_type.patta_type + '</option>'
                        });
                    }
                } else {
                    Swal.fire("", JSON.parse(data).msg, "error");
                }
                $("#patta_type").html(patta_type_html);
            }
        });

    }
</script>