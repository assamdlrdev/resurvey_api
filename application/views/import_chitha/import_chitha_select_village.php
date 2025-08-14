<div class="container">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif">
    </div>
    <?php include(APPPATH . 'views/alert/session.php'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="loc_save">
                <div class="card-body">
                    <?php echo form_open('import_chitha/ImportChithaController/importVillageChitha', ['enctype' => "multipart/form-data"]); ?>
                    <div class="col-12 px-0 pb-3">
                        <div class="bg-info text-white text-center py-1">
                            <h5>Select VIllage to Import Chitha</h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="d">District:</label>
                        <select name="dist_code" class="form-control form-control-sm" id="d">
                            <option selected value="">Select District</option>

                            <?php foreach ($districts as $value) { ?>
                                <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sd">Sub-Div:</label>
                        <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                            <option value="">Select Sub Division </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="c">Circle:</label>
                        <select name="cir_code" class="form-control form-control-sm" id="c">
                            <option value="">Select Circle </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="m">Mouza/Porgona:</label>
                        <select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
                            <option value="">Select Mouza </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="l">Lot:</label>
                        <select name="lot_no" class="form-control form-control-sm" id="l">
                            <option value="">Select Lot </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="v">Village:</label>
                        <select name="vill_townprt_code" class="form-control form-control-sm" id="v">
                            <option value="">Select Village </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="xl">Select XL:</label>
                        <input type="file" name="xl" class="form-control form-control-sm">
                    </div>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <div class="text-center"><input type='submit' class="btn btn-primary" id="loc_save_btn" name="submit" value="Submit"></input></div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#d').change(function() {
        var baseurl = '<?php echo (base_url()); ?>';
        var dis = $(this).val();
        $.ajax({
            url: baseurl + "index.php/reports/DagReportController/subdivisiondetails",
            method: "POST",
            data: {
                dis: dis
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Subdivision</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#sd').html(html);
            }
        });
        return false;
    });
    $('#sd').change(function() {
        var baseurl = '<?php echo (base_url()); ?>';
        var dis = $('#d').val();
        var subdiv = $(this).val();
        $.ajax({
            url: baseurl + "index.php/reports/DagReportController/circledetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Circle</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
                }
                $('#c').html(html);
            }
        });
        return false;
    });
    $('#c').change(function() {
        var baseurl = '<?php echo (base_url()); ?>';
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $(this).val();
        $.ajax({
            url: baseurl + "index.php/reports/DagReportController/mouzadetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Mouza</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#m').html(html);
            }
        });
        return false;
    });
    $('#m').change(function() {
        var baseurl = '<?php echo (base_url()); ?>';
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $('#c').val();
        var mza = $(this).val();
        $.ajax({
            url: baseurl + "index.php/reports/DagReportController/lotdetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir,
                mza: mza
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Lot</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
                }
                $('#l').html(html);
            }
        });
        return false;
    });
    $('#l').change(function() {
        var baseurl = '<?php echo (base_url()); ?>';
        var dis = $('#d').val();
        var subdiv = $('#sd').val();
        var cir = $('#c').val();
        var mza = $('#m').val();
        var lot = $(this).val();
        $.ajax({
            url: baseurl + "index.php/reports/DagReportController/villagedetails",
            method: "POST",
            data: {
                dis: dis,
                subdiv: subdiv,
                cir: cir,
                mza: mza,
                lot: lot
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">Select Village</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name +
                        '</option>';
                }
                $('#v').html(html);
            }
        });
        return false;
    });
</script>