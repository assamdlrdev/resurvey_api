<style>
    @keyframes spinner {
        to {
            transform: rotate(360deg);
        }
    }

    .spinner:before {
        content: '';
        box-sizing: border-box;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40px;
        height: 40px;
        margin-top: -10px;
        margin-left: -10px;
        border-radius: 50%;
        border: 4px solid transparent;
        border-top-color: #07d;
        border-bottom-color: #07d;
        animation: spinner .8s ease infinite;
    }

    body:not(.modal-open) {
        padding-right: 0px !important;
    }
</style>
<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
<div class="container my-3">
    <div class="card card-info">
        <div class="card-header">
            Select Village to Remove Data
        </div>
        <div class="card-body">
            <div class="form-row border border-info p-4">
                <div class="form-group col-md-4">
                    <label for="dist_code">Select district</label>
                    <select name="dist_code" id="dist_code" class="form-control">
                        <option value="">--Select--</option>
                        <?php foreach ($districts as $district) : ?>
                            <option value="<?php echo ($district['dist_code']) ?>"><?php echo ($district['loc_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="subdiv_code">Select Sub-Division</label>
                    <select name="subdiv_code" id="subdiv_code" class="form-control">
                        <option value="">--Select--</option>

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cir_code">Select Circle</label>
                    <select name="cir_code" id="cir_code" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mouza_pargona_code">Select Mouza</label>
                    <select name="mouza_pargona_code" id="mouza_pargona_code" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="lot_no">Select Lot</label>
                    <select name="lot_no" id="lot_no" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="vill_townprt_code">Select Village</label>
                    <select name="vill_townprt_code" id="vill_townprt_code" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Auth Password</label>
                        <input name="password" type="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <button type="button" id="submit_btn" onclick="submitForm()" class="btn btn-success">SUBMIT </button>
                    </div>
                    <div class="form-group">
                        <div style="display: flex; justify-content:center;align-items:center;">
                            <div style="position: relative;" class="" id="spinner">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12" id="error_div" style="display: none;">
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <strong class="text-left" style="color:red !important; font-weight: bold !important;" id="form_errors">
                        </strong>
                    </div>
                    <div id="error_div" style="display: none;">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <strong class="text-left" style="color:red !important; font-weight: bold !important;" id="form_errors">
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css') ?>">
<script src="<?php echo base_url('assets/js/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/data_manage.js') ?>"></script>