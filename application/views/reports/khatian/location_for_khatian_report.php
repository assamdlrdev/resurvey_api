<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif"></div>
    <div class="card">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                Select Location For Khatian Report
            </h5>
        </div>
        <div class="card-body">
            <form action="<?php echo base_url() ?>index.php/show-khatian-report" method="post" enctype="multipart/form-data">
                <div class="row border border-info p-3">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dist_code">District:</label>
                            <select name="dist_code" class="form-control form-control-sm" id="dist_code">
                                <option selected value="">Select District</option>
                                <?php foreach ($districts as $value) {?>
                                    <option value="<?=$value['dist_code']?>" <?php if ($locations and $current_url == current_url()) {
    if ($locations['dist']['code'] == $value['dist_code']) {
        echo 'selected';
    }
}
    ?>><?=$value['loc_name']?></option>
                                <?php }?>
                            </select>
                            <?php echo form_error('dist_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="subdiv_code">Sub-Div:</label>
                            <select name="subdiv_code" class="form-control form-control-sm" id="subdiv_code">
                                <option value="">Select Sub Division </option>
                                <?php if ($locations and $current_url == current_url()) {
    foreach ($locations['subdivs']['all'] as $value) {?>
                                        <option value="<?=$value['subdiv_code']?>" <?php if ($locations['subdivs']['code'] == $value['subdiv_code']) {
        echo 'selected';
    }
        ?>><?=$value['loc_name']?></option>
                                <?php }
}?>
                            </select>
                            <?php echo form_error('subdiv_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="cir_code">Circle:</label>
                            <select name="cir_code" class="form-control form-control-sm" id="cir_code">
                                <option value="">Select Circle </option>
                                <?php if ($locations and $current_url == current_url()) {
    foreach ($locations['cir']['all'] as $value) {?>
                                        <option value="<?=$value['cir_code']?>" <?php if ($locations['cir']['code'] == $value['cir_code']) {
        echo 'selected';
    }
        ?>><?=$value['loc_name']?></option>
                                <?php }
}?>
                            </select>
                            <?php echo form_error('cir_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="mouza_pargona_code">Mouza/Porgona:</label>
                            <select name="mouza_pargona_code" class="form-control form-control-sm" id="mouza_pargona_code">
                                <option value="">Select Mouza </option>
                                <?php if ($locations and $current_url == current_url()) {
    foreach ($locations['mza']['all'] as $value) {?>
                                        <option value="<?=$value['mouza_pargona_code']?>" <?php if ($locations['mza']['code'] == $value['mouza_pargona_code']) {
        echo 'selected';
    }
        ?>><?=$value['loc_name']?></option>
                                <?php }
}?>
                            </select>
                            <?php echo form_error('mouza_pargona_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="lot_no">Lot:</label>
                            <select name="lot_no" class="form-control form-control-sm" id="lot_no">
                                <option value="">Select Lot </option>
                                <?php if ($locations and $current_url == current_url()) {
    foreach ($locations['lot']['all'] as $value) {?>
                                        <option value="<?=$value['lot_no']?>" <?php if ($locations['lot']['code'] == $value['lot_no']) {
        echo 'selected';
    }
        ?>><?=$value['loc_name']?></option>
                                <?php }
}?>
                            </select>
                            <?php echo form_error('lot_no'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="vill_townprt_code">Village:</label>
                            <select onchange="getKhatianNos()" name="vill_townprt_code" class="form-control form-control-sm" id="vill_townprt_code">
                                <option value="">Select Village </option>
                                <?php if ($locations and $current_url == current_url()) {
    foreach ($locations['vill']['all'] as $value) {?>
                                        <option value="<?=$value['vill_townprt_code']?>" <?php if ($locations['vill']['code'] == $value['vill_townprt_code']) {
        echo 'selected';
    }
        ?>><?=$value['loc_name']?></option>
                                <?php }
}?>
                            </select>
                            <?php echo form_error('vill_townprt_code'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="khatian_no_start">Khatian No Start<span class="text-danger">*</span></label>
                            <select name="khatian_no_start" class="form-control form-control-sm" id="khatian_no_start">
                                <option value="">Khatian No Start</option>
                                <?php if ($locations and $current_url == current_url()): ?>
                                    <?php foreach ($khatians as $p): ?>
                                        <option value="<?php echo $p["khatian_no"] ?>" <?php if ($khatian_start == $p["khatian_no"]) {
    echo "selected";
}?>>
                                            <?php echo $p["khatian_no"] ?>
                                        </option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                            <?php echo form_error('khatian_no_start'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="khatian_no_end">Khatian No End<span class="text-danger">*</span></label>
                            <select name="khatian_no_end" class="form-control form-control-sm" id="khatian_no_end">
                                <option value="">Khatian No End</option>
                                <?php if ($locations and $current_url == current_url()): ?>
                                    <?php foreach ($khatians as $p): ?>
                                        <option value="<?php echo $p["khatian_no"] ?>" <?php if ($khatian_end == $p["khatian_no"]) {
    echo "selected";
}?>>
                                            <?php echo $p["khatian_no"] ?>
                                        </option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                            <?php echo form_error('khatian_no_end'); ?>
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
<script src="<?=base_url('assets/js/only_location.js')?>"></script>
<script>
    function getKhatianNos() {
        var baseurl = $("#base").val();
        var dist_code = $('#dist_code').val();
        var subdiv_code = $('#subdiv_code').val();;
        var cir_code = $('#cir_code').val();
        var mouza_pargona_code = $('#mouza_pargona_code').val();
        var lot_no = $('#lot_no').val();
        var vill_townprt_code = $('#vill_townprt_code').val();
        $.ajax({
            url: baseurl + "index.php/reports/KhatianReportController/getKhatianNos",
            method: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_townprt_code: vill_townprt_code
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">--Select--</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].khatian_no + '>' + data[i].khatian_no + '</option>';
                }
                $('#khatian_no_start').html(html);
                $('#khatian_no_end').html(html);
            }
        });
        return false;
    }
</script>