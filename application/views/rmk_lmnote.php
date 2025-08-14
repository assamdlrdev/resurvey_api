<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <script src="<?= base_url('assets/js/common.js') ?>"></script>
    <?php if ($this->session->userdata('dist_code') == '21') { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>

</head>

<body>
    <div class="container-fluid mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']. '/ Dag No : '.$this->session->userdata('dag_no'); ?>
    </div>
    <div class="container bg-light p-0 border border-dark mb-5">
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <div class="col-12 px-0 pb-3">
            <div class="bg-info text-white text-center py-2">
                <h3>Lot Mandal's Note Details(Column 31)</h3>
            </div>
        </div>

        <?php echo form_open('Remark/LmNoteFormSubmit'); ?>
        <div class="row">
            <div class="col-md-3 col-sm-6"></div>
            <div class=" col=md-6 col-sm-6">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">LM Note Cron No:</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="inputEmail3" name="lm_note_cron_no" value="<?php echo $lmnoteId; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">LM Note:<font color=red>*</font></label>
                    <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="inputPassword3" maxlength="999" name="lm_note" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
              </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">Note Date:</label>
                    <div class="col-sm-7">
                        <input type="date" class="form-control" id="orderdate" name="lm_note_date">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mandal's Name:<font color=red>*</font></label>
                    <div class="col-sm-7">
                        <select name="lm_code" class="form-control">
                            <option selected value="">Select Mandal's Name</option>
                            <?php foreach ($mandal_name as $value) { ?>
                                <option value="<?= $value['lm_code'] ?>"><?= $value['lm_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mandal's Sign(y/n):<font color=red>*</font></label>
                    <div class="col-sm-7">
                        <input type="radio" name="lm_sign" value="Y" checked>Yes &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="lm_sign" value="N"> No &nbsp;
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">CO's Approval(y/n):<font color=red>*</font></label>
                    <div class="col-sm-7">
                        <input type="radio" name="co_approval" value="Y" checked>Yes &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="co_approval" value="N"> No &nbsp;
                    </div>
                </div>
                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="LMnoteentry();"></input>

                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
        <div class="col-12 px-0">
            <div class="bg-info text-white text-center py-1">
                <h5>Lot Mandal's Notes</h5>
            </div>
        </div>
        <div class="container">
            <div class="table table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Cron No</th>
                            <th>LM Note</th>
                            <th>Note Date</th>
                            <th>Mandal's Name</th>
                            <th>Mandal's Sign</th>
                            <th>CO's Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lm_notes as $lm_note) : ?>
                            <tr>
                                <td><?php echo ($lm_note->lm_note_cron_no) ?></td>
                                <td><?php echo ($lm_note->lm_note) ?></td>
                                <td><?php echo ($lm_note->lm_note_date ? date('Y-m-d', strtotime($lm_note->lm_note_date)) : '') ?></td>
                                <td><?php echo ($lm_note->lm_name) ?></td>
                                <td><?php echo ($lm_note->lm_sign == 'Y' ? 'Yes' : 'No') ?></td>
                                <td><?php echo ($lm_note->co_approval == 'Y' ? 'Yes' : 'No') ?></td>
                                <td>
                                    <button onclick="deleteRemark(<?php echo ($lm_note->lm_note_cron_no); ?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                    <button onclick='openModalRemark(`<?php echo ($lm_note->lm_note_cron_no) ?>`,`<?php echo ($lm_note->lm_note) ?>`,`<?php echo ($lm_note->lm_note_date ? date("Y-m-d", strtotime($lm_note->lm_note_date)) : "") ?>`,`<?php echo ($lm_note->lm_code) ?>`,`<?php echo ($lm_note->lm_sign) ?>`,`<?php echo ($lm_note->co_approval) ?>`)' class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRemarkModal" tabindex="-1" aria-labelledby="editRemarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editRemarkModalLabel">Update Pdar ID</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="cron_no_edit" class="col-sm-5 col-form-label">LM Note Cron No:</label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control" id="cron_no_edit" name="lm_note_cron_no" value="<?php echo $lmnoteId; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lm_note_edit" class="col-sm-5 col-form-label">LM Note:<font color=red>*</font></label>
                        <div class="col-sm-7">
                            <textarea type="text" class="form-control" id="lm_note_edit" maxlength="999" name="lm_note" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
              </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lm_note_date_edit" class="col-sm-5 col-form-label">Note Date:</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control" id="lm_note_date_edit" name="lm_note_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lm_code_edit" class="col-sm-5 col-form-label">Mandal's Name:<font color=red>*</font></label>
                        <div class="col-sm-7">
                            <select name="lm_code" id="lm_code_edit" class="form-control">
                                <option selected value="">Select Mandal's Name</option>
                                <?php foreach ($mandal_name as $value) { ?>
                                    <option value="<?= $value['lm_code'] ?>"><?= $value['lm_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lm_sign_edit" class="col-sm-5 col-form-label">Mandal's Sign(y/n):<font color=red>*</font></label>
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lm_sign_edit" id="lm_sign_edit_y" value="Y">
                                <label class="form-check-label" for="lm_sign_edit_y">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lm_sign_edit" id="lm_sign_edit_n" value="N">
                                <label class="form-check-label" for="lm_sign_edit_n">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="co_approval_edit" class="col-sm-5 col-form-label">CO's Approval(y/n):<font color=red>*</font></label>
                        <div class="col-sm-7">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="co_approval_edit" id="co_approval_edit_y" value="Y">
                                <label class="form-check-label" for="co_approval_edit_y">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="co_approval_edit" id="co_approval_edit_n" value="N">
                                <label class="form-check-label" for="co_approval_edit_n">No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateRemark()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
    <script>
        function openModalRemark(lm_note_cron_no, lm_note, lm_note_date, lm_code, lm_sign, co_approval) {
            $('#editRemarkModal').modal('show');
            $("#cron_no_edit").val(lm_note_cron_no);
            $("#lm_note_edit").val(lm_note);
            $("#lm_note_date_edit").val(lm_note_date);
            $("#lm_code_edit").val(lm_code);
            if (lm_sign == 'Y') {
                $("#lm_sign_edit_y").attr("checked", true);
            } else {
                $("#lm_sign_edit_n").attr("checked", true);
            }
            if (co_approval == 'Y') {
                $("#co_approval_edit_y").attr("checked", true);
            } else {
                $("#co_approval_edit_n").attr("checked", true);
            }
        }

        function updateRemark() {
            Swal.fire({
                title: '',
                text: 'Please confirm to Update the Remark?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    var baseurl = '<?php echo base_url(); ?>';;
                    var lm_note_cron_no = $("#cron_no_edit").val();
                    var lm_note = $("#lm_note_edit").val();
                    var lm_note_date = $("#lm_note_date_edit").val();
                    var lm_code = $("#lm_code_edit").val();
                    var lm_sign = $("input[name='lm_sign_edit']").val();
                    var co_approval = $("input[name='co_approval_edit']").val();
                    $.ajax({
                        url: baseurl + 'index.php/Remark/updateRemark',
                        type: 'POST',
                        data: {
                            lm_note_cron_no: lm_note_cron_no,
                            lm_note: lm_note,
                            lm_note_date: lm_note_date,
                            lm_code: lm_code,
                            lm_sign: lm_sign,
                            co_approval: co_approval
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
                            if (data.status == 'success') {
                                Swal.fire({
                                    title: '',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    window.location.reload();
                                })
                            } else {
                                Swal.fire("", data.message, "error");
                            }
                        }
                    });
                }
            })
        }

        function deleteRemark(lm_note_cron_no) {
            Swal.fire({
                title: '',
                text: 'Please confirm to DELETE the Remark?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '',
                        text: 'Please confirm Again DELETE the Remark?',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Again',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var baseurl = '<?php echo base_url(); ?>';;

                            $.ajax({
                                url: baseurl + 'index.php/Remark/deleteRemark',
                                type: 'POST',
                                data: {
                                    lm_note_cron_no: lm_note_cron_no
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
                                    if (data.status == 'success') {
                                        Swal.fire({
                                            title: '',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok'
                                        }).then((result) => {
                                            window.location.reload();
                                        })
                                    } else {
                                        Swal.fire("", data.message, "error");
                                    }
                                }
                            });
                        }
                    })
                }
            })
        }
    </script>
</body>

</html>

<script src="<?= base_url('assets/js/remark.js') ?>"></script>