<div class="col-lg-12 col-md-12">
    <div class="card-header mb-6 rounded-0 py-1 bg-info">
        <h5 style="display: inline;" class="left"> Change Village Name
        </h5>
    </div>
    <div class="row mt-3 justify-content-center">
        <div class="col-lg-12">
            <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
        </div>
        <div class="col-lg-2">
            <label for="">District</label>
            <select name="dist_code" class="form-control form-control-sm" id="ds">
                <option selected value="">Select District</option>

                <?php foreach ($districts as $value) { ?>
                    <option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Sub-Division</label>
            <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                <option value="">Select Sub Division </option>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Circle</label>
            <select name="cir_code" class="form-control form-control-sm" id="cr">
                <option value="">Select Circle </option>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Mouza</label>
            <select name="mouza_pargona_code" class="form-control form-control-sm" id="mo">
                <option value="">Select Mouza </option>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Lot</label>
            <select name="lot_no" class="form-control form-control-sm" id="lo">
                <option value="">Select Lot </option>
            </select>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5>VERIFIED VILLAGES BY CO</h5>
                        </div>
                    </div>
                    <div id="vill_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve Changed Village Name</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align:right; font-weight:bold;">District:</td>
                                                        <td id="d" style="text-align:left;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:right; font-weight:bold;">Sub Division:</td>
                                                        <td id="s" style="text-align:left;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:right; font-weight:bold;">Circle:</td>
                                                        <td id="c" style="text-align:left;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:right; font-weight:bold;">Mouza Pargona:</td>
                                                        <td id="m" style="text-align:left;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:right; font-weight:bold;">Lot:</td>
                                                        <td id="l" style="text-align:left;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align:center; font-weight:bold;" id="ch"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="uuid" id="uuid" value="" />
                                <input style="display: none;" type="text" name="new_vill_name" id="new_vill_name" value="" />
                                <input style="display: none;" type="text" name="new_vill_name_eng" id="new_vill_name_eng" value="" />


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" id="approved" class="btn btn-primary">Approve</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr class="text-center">
                                <th>Old Village Name</th>
                                <th>New Village Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="villages">
                            <?php foreach ($villages as $value) { ?>

                                <tr class="text-center">
                                    <td><?= $value['old_vill_name'] ?> (<?= $value['old_vill_name_eng'] ?>)</td>
                                    <td><?= $value['new_vill_name'] ?> (<?= $value['new_vill_name_eng'] ?>)</td>
                                    <?php if ($value['status'] === 'D') : ?>
                                        <td>
                                            <small id="status_<?= $value['uuid'] ?>" class="text-warning">Pending</small>
                                        </td>
                                        <td>
                                            <button id="<?= $value['uuid'] ?>" class="btn btn-sm btn-info approve text-white">Approve</button>
                                        </td>
                                    <?php else : ?>
                                        <td>
                                            <small class="text-success">Approved</small>
                                        </td>
                                        <td>
                                            <button disabled class="btn btn-sm btn-success approve text-white">Approved</button>
                                        <td>
                                        <?php endif; ?>

                                        </td>
                                </tr>
                            <?php } ?>
                            <!-- <tr>
                                <td colspan="6" class="text-center">
                                    <span>No villages Found</span>
                                </td>
                            </tr> -->

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Script>
            $('#vill_modal').on('show.bs.modal', function() {})
        </Script>
        <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
        <script src="<?= base_url('assets/js/change_location_dc.js') ?>"></script>
        <link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
        <script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
        <script>
            $("#approved").click(function() {
                $.confirm({
                    title: 'Confirm!',
                    content: 'Please confirm to Approve!',
                    buttons: {
                        confirm: {
                            text: 'Confirm!',
                            btnClass: 'btn-red',
                            action: function(confirm) {
                                $("#approved").prop('disabled',true);
                                var baseurl = $("#base").val();
                                var id = $("#uuid").val();
                                var new_vill_name = $("#new_vill_name").val();
                                var new_vill_name_eng = $("#new_vill_name_eng").val();
                                console.log(new_vill_name);
                                $.ajax({
                                    url: baseurl + "index.php/change_village/ChangeVillageDcController/approveVillageChangeName",
                                    method: "POST",
                                    data: {
                                        id: id,
                                        new_vill_name: new_vill_name,
                                        new_vill_name_eng: new_vill_name_eng
                                    },
                                    async: true,
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $('#sd').prop('selectedIndex', 0);
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



                                        if (data.st == 1) {
                                            $("#status_" + id).removeClass("text-warning");
                                            $("#status_" + id).addClass("text-success");
                                            $("#status_" + id).html("Approved");
                                            $("#" + id).removeClass("btn-info");
                                            $("#" + id).addClass("btn-success");
                                            $("#" + id).html("Approved").prop('disabled', true);
                                            $("#vill_modal").modal('hide');
                                            swal("", data.msg, "success");
                                        } else {
                                            $("#vill_modal").modal('hide');
                                            swal("", data.msg, "info");

                                        }
                                    },
                                    error: function(jqXHR, exception) {
                                        $.unblockUI();
                                        alert('Could not Complete your Request ..!, Please Try Again later..!');
                                    }
                                });
                            }
                        },
                        cancel: function() {

                        }
                    }
                });

            });
        </script>
    </div>
</div>