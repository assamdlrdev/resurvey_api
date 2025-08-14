<div class="card w-100 card-primary">
    <div class="card-header">
        <h3>View Signed PDF</h3>
    </div>
    <div class="card-body hide-for-print">
        <div class="row">

            <div class="col-md-6">
                <label for="vill_townprt_code">Village</label>
                <select name="vill_townprt_code" id="vill_townprt_code" class="form-control">
                    <option value="">---Select Village---</option>
                    <?php foreach($villages as $village): ?>
                        <option value="<?php echo $village->uuid; ?>" <?php echo ($village->vill_townprt_code == $villages[0]->vill_townprt_code) ? 'selected' : ''; ?>><?php echo $village->loc_name . ' (' . $village->locname_eng . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="card card-primary w-100" id="view-section">
    <div class="card-header">
        <h4 class="card-title" id="location-details">
            <?php echo $location_names; ?>
        </h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="view-section-table">
            <thead>
                <tr>
                    <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
                    <!-- <th>Location</th> -->
                    <th>Dag No</th>
                    <th>Date of Sign</th>
                    <th>View Signed Chitha</th>
                </tr>
            </thead>
            <tbody id="view-section-body">
                <?php foreach($verified_dags as $verified_dag): ?>
                    <tr>
                        <!-- <td>
                            <?php //echo 'District: ' . $alphaDagPatta->dist_name . ', SubDivisional: ' . $alphaDagPatta->subdiv_name . ', Circle: ' . $alphaDagPatta->cir_name . ', Mouza: ' . $alphaDagPatta->mouza_pargona_name . ', Lot: ' . $alphaDagPatta->lot_name . ', Village: ' . $alphaDagPatta->vill_townprt_name; ?>
                        </td> -->
                        <td>
                            <div style="display: flex;">
                                <div style="margin-right: 5%"><?php echo $verified_dag->dag_no; ?>  </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex;">
                                <div style="margin-right: 5%"><?php echo date('d-m-Y H:i:s', strtotime($verified_dag->date_of_sign_lm)); ?></div> 
                            </div>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?php echo base_url('index.php/verification/LMController/getSignedPdf?param=' . $verified_dag->param); ?>" target="_blank">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        
        </table>
    </div>
</div>

<!-- <div class="modal fade" id="dagModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Dag</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="unique_id_dag" name="unique_id_dag">
                        <div class="form-group">
                            <label for="">New Dag No. *</label>
                            <input type="text" class="form-control form-control-sm" id="dag_no_new" name="dag_no_new">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btn-submit-dag" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div> -->

<!-- <div class="modal fade" id="pattaModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Patta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="unique_id_patta" name="unique_id_patta">
                        <div class="form-group">
                            <label for="">New Patta No. *</label>
                            <input type="text" class="form-control form-control-sm" id="patta_no_new" name="patta_no_new">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btn-submit-patta" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div> -->
    

<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script>

    $(document).on('change', '#vill_townprt_code', (e)=>{
        
        var baseurl = $('#baseurl').val();
        if(e.currentTarget.value != '' && e.currentTarget.value != undefined) {
            $.ajax({
                url: baseurl + 'index.php/verification/LMController/viewSignedPdf',
                method: 'POST',
                data: {uuid:e.currentTarget.value},
                success: function(resp) {
                    $('#view-section-body').html('');
                    var response = JSON.parse(resp).data;
                    $('#location-details').text(response.location_names);
                    var verified_dags = response.verified_dags;
                    console.log(verified_dags);
                    if(verified_dags.length > 0) {
                        
                        var segment = '';
                        verified_dags.forEach(element => {
                            var segment1 = `<tr>
                                <td>
                                    <div style="display: flex;">
                                        <div style="margin-right: 5%">${element.dag_no}  </div>`;
                            var segment3 = `</div>
                                </td>
                                <td>
                                    <div style="display: flex;">
                                        <div style="margin-right: 5%">${element.sign_date}</div>`;
                            var segment5 = `</div>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="${baseurl}index.php/verification/LMController/getSignedPdf?param=${element.param}" target="_blank">View</a>
                                </td>
                            </tr>`;
                            segment += segment1 + segment3 + segment5;
                        });
                        $('#view-section-body').html(segment);
                    }
                    else{
                        var segment = `<tr>
                            <td>No data Available</td>
                            <td></td>
                            <td></td>
                        </tr>`;
                        $('#view-section-body').html(segment);
                    }
                }
            });
        }
        else {
            alert("Please Select the village.");
        }
    });

    // $(document).on('click', '.btn-edit-dag', (e)=>{
    //     var baseurl = $('#baseurl').val();
    //     var id = e.currentTarget.id;
    //     var unique_id = id.split('_')[1];

    //     // var location = unique_id.split('-');
    //     $('#dagModal').modal('show', {unique_id:unique_id});
        
    // });

    // $(document).on('click', '.btn-edit-patta', (e)=>{
    //     var baseurl = $('#baseurl').val();
    //     var id = e.currentTarget.id;
    //     var unique_id = id.split('_')[1];

    //     // var location = unique_id.split('-');
    //     $('#pattaModal').modal('show', {unique_id:unique_id});
        
    // });

    // $('#dagModal').on('show.bs.modal', function(e){
    //     $('#unique_id_dag').val(e.relatedTarget.unique_id);
    //     $('#dag_no_new').val('');
    // });

    // $('#pattaModal').on('show.bs.modal', function(e){
    //     $('#unique_id_patta').val(e.relatedTarget.unique_id);
    //     $('#patta_no_new').val('');
    // });

    // $(document).on('click', '#btn-submit-dag', (e)=>{
    //     var baseurl = $('#baseurl').val();
    //     var unique_id = $('#unique_id_dag').val();
    //     var dag_no_new = $('#dag_no_new').val();

    //     if(unique_id != '' && unique_id != undefined && dag_no_new != '' && dag_no_new != undefined) {
    //         if(confirm("You are about to alter the Dag No. value in all existing related database tables. Remember the old dag no wont be accessible anymore. Do you want to proceed?")) {
    //             $.ajax({
    //                 url: baseurl + 'index.php/barakvalley/AlphaEditController/alphaDagEdit',
    //                 method: 'POST',
    //                 data: {unique_id:unique_id, dag_no_new:dag_no_new},
    //                 success: function(response) {
    //                     $('#dagModal').modal('hide');
    //                     var resp = JSON.parse(response);
    //                     if(resp.status == 'SUCCESS') {
    //                         alert(resp.msg);
    //                         refreshPage();
    //                     }
    //                     else if(resp.status == 'FAILED') {
    //                         alert(resp.msg);
    //                     }
    //                 }
    //             });
    //         }
    //     }
    //     else {
    //         alert("Please enter the new dag.");
    //     }
    // });

    // $(document).on('click', '#btn-submit-patta', (e)=>{
    //     var baseurl = $('#baseurl').val();
    //     var unique_id = $('#unique_id_patta').val();
    //     var patta_no_new = $('#patta_no_new').val();

    //     if(unique_id != '' && unique_id != undefined && patta_no_new != '' && patta_no_new != undefined) {
    //         if(confirm("You are about to alter the Patta No. value in all existing related database tables. Remember the old patta no wont be accessible anymore. Do you want to proceed?")) {
    //             $.ajax({
    //                 url: baseurl + 'index.php/barakvalley/AlphaEditController/alphaPattaEdit',
    //                 method: 'POST',
    //                 data: {unique_id:unique_id, patta_no_new:patta_no_new},
    //                 success: function(response) {
    //                     console.log(response);
    //                     $('#pattaModal').modal('hide');
    //                     var resp = JSON.parse(response);
    //                     if(resp.status == 'SUCCESS') {
    //                         alert(resp.msg);
    //                         refreshPage();
    //                     }
    //                     else if(resp.status == 'FAILED') {
    //                         alert(resp.msg);
    //                     }
    //                 }
    //             });
    //         }
    //     }
    //     else {
    //         alert("Please enter the new patta.");
    //     }
    // });

    function refreshPage() {
        var vill_townprt_code = $('#vill_townprt_code').val();
        var baseurl = $('#baseurl').val();
        if(vill_townprt_code != '' && vill_townprt_code != undefined) {
            $.ajax({
                url: baseurl + 'index.php/barakvalley/AlphaEditController/getVillageAlphaDagPatta',
                method: 'POST',
                data: {vill_townprt_code:vill_townprt_code},
                success: function(resp) {
                    $('#view-section-body').html('');
                    var response = JSON.parse(resp);
                    $('#location-details').text('District: ' + response.villages['dist_name'] + ', SubDivisional: ' + response.villages['subdiv_name'] + ', Circle: ' + response.villages['cir_name'] + ', Mouza: ' + response.villages['mouza_pargona_name'] + ', Lot: ' + response.villages['lot_name'] + ', Village: ' + response.villages['vill_townprt_name']);
                    var alphaDagPattas = response.alphaDagPattas;
                    console.log(alphaDagPattas);
                    if(alphaDagPattas.length > 0) {
                        var segment = '';
                        alphaDagPattas.forEach(element => {
                            var segment1 = `<tr>
                                <td>
                                    <div style="display: flex;">
                                        <div style="margin-right: 5%">${element.dag_no}  </div>`;
                            if(element.alpha_dag == 1) {
                                var segment2 = `<button class="btn btn-primary btn-edit-dag" id="editDag_${element.unique_id}">Edit</button>`;
                            }
                            else{
                                var segment2 = '';
                            }
                            var segment3 = `</div>
                                </td>
                                <td>
                                    <div style="display: flex;">
                                        <div style="margin-right: 5%">${element.patta_no}</div>`;
                            if(element.alpha_patta == 1) {
                                var segment4 = `<button class="btn btn-primary btn-edit-patta" id="editPatta_${element.unique_id}">Edit</button>`;
                            }
                            else{
                                var segment4 = '';
                            }
                            var segment5 = `</div>
                                </td>
                                <td>
                                    <button class="btn btn-primary view-more" id="viewmore_${element.unique_id}">View More</button>
                                </td>
                            </tr>`;
                            segment += segment1 + segment2 + segment3 + segment4 + segment5;
                        });
                        $('#view-section-body').html(segment);
                    }
                    else{
                        var segment = `<tr>
                            <td>No data available</td>
                            <td></td>
                            <td></td>
                        </tr>`;
                        $('#view-section-body').html(segment);
                    }
                }
            });
        }
        else {
            alert("Please Select the village.");
        }
    }
</script>