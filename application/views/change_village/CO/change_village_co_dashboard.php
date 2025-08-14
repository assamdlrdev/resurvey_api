<div class="container">
    <div class="card-header rounded-0 py-1 bg-info">
        <h5 style="display: inline;" class="left"> Change Village Name
        </h5>
        <a class="btn btn-dark" style="float: right;" href="<?= base_url('index.php/change_village/ChangeVillageCoController/changeVillageNameRequest') ?>">See all <i class="fa fa-forward"></i> </a>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-lg-12 mt-4 col-md-12 col-sm-12 col-xs-12">
            <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        </div>
        <div class="col-lg-2">
            <label for="">District</label>
            <select name="dist_code" class="form-control form-control-sm" id="ds">
                <option selected value="">Select District</option>

                <?php foreach ($districts as $value) { ?>
                    <option value="<?= $value['dist_code'] ?>" selected><?= $value['loc_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Sub-Division</label>
            <select name="subdiv_code" class="form-control form-control-sm" id="sd">
                <option selected value="">Select Sub Division</option>
                <?php foreach ($subDivisions as $value) { ?>
                    <option value="<?= $value['subdiv_code'] ?>" selected><?= $value['loc_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Circle</label>
            <select name="cir_code" class="form-control form-control-sm" id="circle">
                <option value="">Select Circle </option>
                <?php foreach ($circles as $value) { ?>
                    <option value="<?= $value['cir_code'] ?>" selected><?= $value['loc_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Mouza</label>
            <select name="mouza_pargona_code" class="form-control form-control-sm" id="mo">
                <option value="">Select Mouza </option>
                <?php foreach ($mouzas as $value) { ?>
                    <option value="<?= $value['mouza_pargona_code'] ?>"><?= $value['loc_name'] ?></option>
                <?php } ?>
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
                            <h5 class="mr-2" style="display: inline-block;">CHANGE NAME REQUEST <h6 id="count" style="display: inline-block;">(TOTAL REQUEST: <?php echo $count ?> )</h6>
                            </h5>
                        </div>
                    </div>
                    <div id="vill_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Submit Changed Village Name</h5>
                                </div>
                                <div class="modal-body">

                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <form action="">

                                                <div class="row border border-info p-3">
                                                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">District:</label>
                                                            <h6 id="d">District</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">Sub-Div:</label>
                                                            <h6 id="s">District</h6>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">Circle:</label>
                                                            <h6 id="cir">District</h6>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">Mouza/Porgona:</label>
                                                            <h6 id="mza">District</h6>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">Lot:</label>
                                                            <h6 id="lot">District</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">Village:</label>
                                                            <h6 id="vill">District</h6>

                                                        </div>
                                                    </div>

                                                    <input style="display: none;" name="dist" type="text" class="form-control" id="dist" required>
                                                    <input style="display: none;" name="sub" type="text" class="form-control" id="sub" required>
                                                    <input style="display: none;" name="cr" type="text" class="form-control" id="cr" required>
                                                    <input style="display: none;" name="mouza" type="text" class="form-control" id="mouza" required>
                                                    <input style="display: none;" name="lt" type="text" class="form-control" id="ltNo" required>
                                                    <input style="display: none;" name="vuuid" type="text" class="form-control" id="vuuid" required>

                                                    <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">New Village Name (Engg):</label>
                                                            <input name="old_vill_name_eng" type="text" class="form-control" id="vill_name_engg" required>
                                                        </div>
                                                    </div>
                                                    <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">New Village Name (Engg):</label>
                                                            <input name="old_vill_name" type="text" class="form-control" id="old_vill_name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">New Village Name:</label>
                                                            <input name="new_vill_name" type="text" class="form-control" id="new_vill_name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="sel1">New Village Name (Eng):</label>
                                                            <input name="new_vill_name_eng" type="text" class="form-control" id="new_vill_name_eng" required>
                                                        </div>
                                                    </div>
                                                    <div id="template" style="display: none;" class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <textarea id="templateTA" class="form-control" aria-label="With textarea" disabled></textarea>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="uuid" id="uuid" value="" />
                                <input style="display: none;" type="text" name="new_vill_name" id="new_vill_name" value="" />
                                <input style="display: none;" type="text" name="new_vill_name_eng" id="new_vill_name_eng" value="" />


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type='button' class="btn btn-info" name="submit" onclick='checkloc();' value="Submit">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr class="text-center">
                                <th>Village Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="villages">
                            <?php foreach ($villages as $value) { ?>
                                <tr class="text-center">
                                    <td><?= $value->loc_name ?></td>
                                    <?php if ($value->status === null) : ?>
                                        <td>
                                            <small id="status_<?= $value->uuid ?>" class="text-warning">Pending</small>
                                        </td>
                                        <td>
                                            <button id="<?= $value->uuid ?>" class="btn btn-sm btn-info change text-white">Change Village Name</button>
                                        </td>
                                    <?php elseif ($value->status === 'D') : ?>
                                        <td>
                                            <small id="status_<?= $value->uuid ?>" class="text-warning">Pending</small>
                                        </td>
                                        <td>
                                            <button disabled class="btn btn-sm btn-warning approve text-white">Submitted to DC</button>
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
        <script src="<?= base_url('assets/js/change_location.js') ?>"></script>
    </div>
</div>