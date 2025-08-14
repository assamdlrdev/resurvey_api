<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: inherit !important;
    }
</style>
<?php
$dist_code = $this->session->userdata('dcode');
$subdiv_code = $this->session->userdata('dag_subdiv_code');
$cir_code = $this->session->userdata('dag_cir_code');
$mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
$lot_no = $this->session->userdata('dag_lot_no');
$vill_code = $this->session->userdata('dag_vill_code');
$dag_no = $this->session->userdata('dag_dag_no');

$subdivisions = $dist_code ? $this->Chithamodel->subdivisiondetails($dist_code,$this->session->userdata('subdiv_code')) : [];
$circles = $subdiv_code ? $this->Chithamodel->circledetails($dist_code, $subdiv_code,$this->session->userdata('cir_code')) : [];
$mouzas = $cir_code ? $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code) : [];
$lots = $mouza_pargona_code ? $this->Chithamodel->lotdetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code) : [];
$villages = $lot_no ? $this->Chithamodel->villagedetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no) : [];
if ($vill_code) {
    $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code')";
    $this->db->select('old_dag_no,dag_no,patta_no,patta_type_code');
    $query = $this->db->get_where('chitha_basic', $where);
    $dags = $query->result_array();
}else{
    $dags = [];
}
?>
<div class="col-md-12">
    <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo ($this->session->flashdata('success')); ?>
        </div>
    <?php endif; ?>
    <div class="card my-3 card-info">
        <div class="card-header">
            <div class="card-title  text-center font-weight-bold">Select Dag Location</div>
        </div>
        <div class="card-body">
            <?php echo form_open('ChithaDagEditController/currentPattadars'); ?>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="d">District:</label>
                    <select name="dist_code" class="form-control form-control-sm" id="d">
                        <option selected value="">Select District</option>
                        <option value="<?php echo ($dist_code) ?>" <?php echo ($dist_code ? 'selected' : '') ?>><?php echo ($dist_code ? $this->utilityclass->getDistrictName($dist_code) : ''); ?></option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="sd">Sub-Div:</label>
                    <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                        <option value="">Select Sub Division </option>
                        <?php if (count($subdivisions) > 0) : ?>
                            <?php foreach ($subdivisions as $subdiv) : ?>
                                <option value="<?php echo ($subdiv['subdiv_code']) ?>" <?php echo ($subdiv_code == $subdiv['subdiv_code'] ? 'selected' : '') ?>><?php echo ($subdiv['loc_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="c">Circle:</label>
                    <select name="cir_code" class="form-control form-control-sm" id="c">
                        <option value="">Select Circle </option>
                        <option value="<?php echo ($cir_code) ?>" <?php echo ($cir_code ? 'selected' : '') ?>><?php echo ($cir_code ? $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code) : ''); ?></option>
                        <?php if (count($circles) > 0) : ?>
                            <?php foreach ($circles as $circle) : ?>
                                <option value="<?php echo ($circle['cir_code']) ?>" <?php echo ($cir_code == $circle['cir_code'] ? 'selected' : '') ?>><?php echo ($circle['loc_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="m">Mouza/Porgona:</label>
                    <select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
                        <option value="">Select Mouza </option>
                        <option value="<?php echo ($mouza_pargona_code) ?>" <?php echo ($mouza_pargona_code ? 'selected' : '') ?>><?php echo ($mouza_pargona_code ? $this->utilityclass->getMouzaName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code) : ''); ?></option>
                        <?php if (count($mouzas) > 0) : ?>
                            <?php foreach ($mouzas as $mouza) : ?>
                                <option value="<?php echo ($mouza['mouza_pargona_code']) ?>" <?php echo ($cir_code == $mouza['mouza_pargona_code'] ? 'selected' : '') ?>><?php echo ($mouza['loc_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="l">Lot:</label>
                    <select name="lot_no" class="form-control form-control-sm" id="l">
                        <option value="">Select Lot </option>
                        <?php if (count($lots) > 0) : ?>
                            <?php foreach ($lots as $lot) : ?>
                                <option value="<?php echo ($lot['lot_no']) ?>" <?php echo ($lot_no == $lot['lot_no'] ? 'selected' : '') ?>><?php echo ($lot['loc_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="v">Village:</label>
                    <select onchange="getDags()" name="vill_townprt_code" class="form-control form-control-sm" id="v">
                        <option value="">Select Village </option>
                        <?php if (count($villages) > 0) : ?>
                            <?php foreach ($villages as $village) : ?>
                                <option value="<?php echo ($village['vill_townprt_code']) ?>" <?php echo ($vill_code == $village['vill_townprt_code'] ? 'selected' : '') ?>><?php echo ($village['loc_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="dag_no">Dag No:</label>
                    <select name="dag_no" class="form-control form-control-sm" id="dag_no">
                        <option value="">Select Dag No </option>
                        <?php if (count($dags) > 0) : ?>
                            <?php foreach ($dags as $dag) : ?>
                                <option value="<?php echo ($dag['dag_no']) ?>" <?php echo ($dag_no == $dag['dag_no'] ? 'selected' : '') ?>><?php echo ($dag['dag_no']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="patta_type_code">Patta Type</label>
                    <select readonly class="form-control form-control-sm" id="patta_type_code" name="patta_type_code" required>
                        <option disabled selected>Select Patta Type</option>
                        <?php foreach ($patta_types as $patta_type) : ?>
                            <option value="<?php echo ($patta_type->type_code) ?>"><?php echo ($patta_type->patta_type) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('patta_type_code', '<p class="text-danger form_error">', '</p>'); ?>
                </div>
                <div class="form-group col-md-4">
                    <label for="patta_no">Patta No:</label>
                    <input readonly type="text" id="patta_no" name="patta_no" class="form-control form-control-sm">
                </div>

            </div>
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
            <div class="text-center"><button class="btn btn-primary">SUBMIT</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/location.js') ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dag_no').select2({});
    });
    // $(document).on('change', '#v,#patta_type_code', function(e) {
    //     var formdata = createFormData();
    //     var baseurl = $("#base").val();
    //     if (formdata.vill_townprt_code && formdata.patta_type_code) {
    //         $.ajax({
    //             url: baseurl + "index.php/ChithaDagEditController/getPattaNos",
    //             method: "POST",
    //             data: formdata,
    //             async: true,
    //             dataType: 'json',
    //             beforeSend: function() {
    //                 $('#patta_no').prop('selectedIndex', 0);
    //                 $.blockUI({
    //                     message: $('#displayBox'),
    //                     css: {
    //                         border: 'none',
    //                         backgroundColor: 'transparent'
    //                     }
    //                 });
    //             },
    //             success: function(data) {
    //                 if (data) {
    //                     var html = '';
    //                     var i;
    //                     html += '<option value="">Select Patta No</option>';
    //                     for (i = 0; i < data.length; i++) {
    //                         html += '<option value=' + data[i].patta_no + '>' + data[i].patta_no + '</option>';
    //                     }
    //                     $('#patta_no').html(html);
    //                 }
    //                 $.unblockUI();
    //             },
    //             error: function(jqXHR, exception) {
    //                 $.unblockUI();
    //                 $('#patta_no').prop('selectedIndex', 0);
    //                 alert('Could not Complete your Request ..!, Please Try Again later..!');
    //             }
    //         });
    //     }
    // });
    $(document).on('change', '#dag_no', function(e) {
        var formdata = createFormData();
        var baseurl = $("#base").val();
        $.ajax({
            url: baseurl + "index.php/ChithaDagEditController/getDagPattaDetails",
            method: "POST",
            data: formdata,
            async: true,
            dataType: 'json',
            beforeSend: function() {
                $('#patta_no').prop('selectedIndex', 0);
                $('#patta_type_code').prop('selectedIndex', 0);
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function(data) {
                if (data) {
                    $('#patta_no').val(data.patta_no);
                    $('#patta_type_code').val(data.patta_type_code);
                }
                $.unblockUI();
            },
            error: function(jqXHR, exception) {
                $.unblockUI();
                $('#patta_no').prop('selectedIndex', 0);
                $('#patta_type_code').prop('selectedIndex', 0);
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
    });

    function getDags() {
        var formdata = createFormData();
        var baseurl = $("#base").val();
        $.ajax({
            url: baseurl + "index.php/ChithaDagEditController/getDags",
            method: "POST",
            data: formdata,
            async: true,
            dataType: 'json',
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
                if (data) {
                    var html = '';
                    var i;
                    html += '<option value="">Select Dag No</option>';
                    for (i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].dag_no + '>' + data[i].dag_no + '</option>';
                    }
                    $('#dag_no').html(html);
                }
                $.unblockUI();
            },
            error: function(jqXHR, exception) {
                $.unblockUI();
                $('#dag_no').prop('selectedIndex', 0);
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
    }

    function createFormData() {
        var dist_code = $("#d").val();
        var subdiv_code = $("#sd").val();
        var cir_code = $("#c").val();
        var mouza_pargona_code = $("#m").val();
        var lot_no = $("#l").val();
        var vill_townprt_code = $("#v").val();
        var patta_type_code = $("#patta_type_code").val();
        var patta_no = $("#patta_no").val();
        var dag_no = $("#dag_no").val();
        var formdata = {};
        formdata.dist_code = dist_code;
        formdata.subdiv_code = subdiv_code;
        formdata.cir_code = cir_code;
        formdata.mouza_pargona_code = mouza_pargona_code;
        formdata.lot_no = lot_no;
        formdata.vill_townprt_code = vill_townprt_code;
        formdata.patta_type_code = patta_type_code;
        formdata.patta_no = patta_no;
        formdata.dag_no = dag_no;
        return formdata;
    }
</script>