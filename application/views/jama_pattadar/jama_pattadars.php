<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'] . '/ Patta No - ' . $locationname['patta_no'] . '/ Patta Type - ' . $locationname['patta_type']; ?>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Jama Pattadars
            </h5>
        </div>
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <div class="card-body">
            <div class="row border border-info p-3">
                <div class="table table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Serial Number</th>
                                <th>Pdar ID</th>
                                <th>Pdar Name</th>
                                <th>Pdar Father</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pattadars as $pattadar) : ?>
                                <tr>
                                    <td><?php echo ($pattadar->pdar_sl_no ? $pattadar->pdar_sl_no : '') ?></td>
                                    <td><?php echo ($pattadar->pdar_id) ?></td>
                                    <td><?php echo ($pattadar->pdar_name) ?></td>
                                    <td><?php echo ($pattadar->pdar_father) ?></td>
                                    <td>
                                        <button onclick="openModal(<?php echo ($pattadar->pdar_id) ?>,<?php echo ($pattadar->pdar_sl_no) ?>)" class="btn btn-sm btn-info" type="button"><i class="fa fa-pencil"></i> Edit Serial No</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPdarModal" tabindex="-1" aria-labelledby="editPdarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editPdarModalLabel">Update Pdar ID</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_serial_no">Serial No</label>
                        <input type="text" value="" name="edit_serial_no" placeholder="Serial No" id="edit_serial_no" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_pdar_id">Patttdar ID</label>
                        <input type="text" value="" readonly name="edit_pdar_id" placeholder="Pdar ID" id="edit_pdar_id" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateJamapattadar()">Update</button>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
<script>
    function openModal(pdar_id, serial_no) {
        $('#editPdarModal').modal('show');
        $("#edit_serial_no").val(serial_no);
        $("#edit_pdar_id").val(pdar_id);
    }

    function updateJamapattadar() {
        $('#editPdarModal').modal('hide');
        var serial_no = $("#edit_serial_no").val();
        var pdar_id = $("#edit_pdar_id").val();
        var baseurl = $("#base").val();
        $.ajax({
            url: baseurl + 'index.php/jamabandi/JamaPattadarController/updateJamapattadar',
            type: 'POST',
            data: {
                serial_no: serial_no,
                pdar_id: pdar_id
            },
            beforeSend: function() {
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            error: function() {
                $.unblockUI();
                Swal.fire("", 'Something is wrong.', "error");
            },
            success: function(data) {
                $.unblockUI();
                var data = JSON.parse(data);
                if (data.st == 1) {
                    Swal.fire({
                        title: '',
                        text: data.msg,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        window.location.href=baseurl + 'index.php/jamabandi/JamaPattadarController/getJamabandiPattadarsView';
                    })
                } else {
                    Swal.fire("", data.msg, "error");
                }
            }
        });
    }
</script>