<div class="col-lg-12 col-md-12">
    <div class="card-header mb-6 rounded-0 py-1 bg-info">
        <h5 style="display: inline;" class="left"> DAG Filter
        </h5>
    </div>
    <div class="row mt-3 justify-content-center">
        <div class="col-lg-12">
            <div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif"></div>
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
        </div>
        <div class="col-lg-2">
            <label for="">District</label>
            <select name="dist_code" class="form-control form-control-sm" id="d">
                <option selected value="">Select District</option>

                <?php foreach ($districts as $value) {?>
                    <option value="<?=$value['dist_code']?>"><?=$value['loc_name']?></option>
                <?php }?>
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
            <select name="cir_code" class="form-control form-control-sm" id="c">
                <option value="">Select Circle </option>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Mouza</label>
            <select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
                <option value="">Select Mouza </option>
            </select>
        </div>
        <div class="col-lg-2">
            <label for="">Lot</label>
            <select name="lot_no" class="form-control form-control-sm" id="l">
                <option value="">Select Lot </option>
            </select>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5>TOTAL DAG: <p style="display: inline;" id="count"><?=$count?></p>
                            </h5>
                        </div>
                    </div>

                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr class="text-center">
                                <th>Village</th>
                                <th>Patta Type</th>
                                <th>Patta No</th>
                                <th>Dag No</th>
                                <th>Dag Entry on</th>
                            </tr>
                        </thead>
                        <tbody id="villages">
                            <?php foreach ($villages as $value) {?>

                                <tr class="text-center">
                                    <td><?=$value['loc_name']?> </td>
                                    <td><?=$value['patta_type']?> </td>
                                    <td><?=$value['patta_no']?> </td>
                                    <td><?=$value['dag_no']?> </td>
                                    <td><?=$value['date_entry']?></td>
                                </tr>
                            <?php }?>
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
        <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
        <script src="<?=base_url('assets/js/dag-filter.js')?>"></script>
    </div>
</div>