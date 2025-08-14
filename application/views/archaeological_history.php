<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->load->view('header'); ?>
    <script src="<?= base_url('assets/js/common.js') ?>"></script>

    <?php if (($this->session->userdata('dist_code')=='21') || ($this->session->userdata('dist_code')=='22') || ($this->session->userdata('dist_code')=='23')) { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>
</head>
<script>$(function () {
        $("#orderdate").datepicker({dateFormat: 'yy-mm-dd'});

    });</script>
<style>
    .row {
        margin-left:-5px;
        margin-right:-5px;
    }
</style>
<body>
<div class="container-fluid mt-3 mb-2 font-weight-bold">
    <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
</div>
<div class="container bg-light p-0 border border-dark mt-5 mb-5">
    <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
            <h3>Archaeological History Details(Column 31)</h3>
        </div>
    </div>
    <?php echo form_open('Remark/saveArchaeologicalHistoricalData'); ?>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-5 col-form-label">Archaeological Historical Place Id :</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control" id="inputEmail3" name="archHisId" value="<?php echo $sl_id ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-5 col-form-label">Archaeological Historical Place Name :</label>
                <div class="col-sm-7">
                    <select name="archHistoPlace" class="form-control">
                        <option selected value="">Select</option>
                        <?php foreach ($codes as $mm) { ?>
                            <option value="<?php echo $mm->id ?>"> <?php echo $mm->archeo_hist_desc ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-5 col-form-label">Description About The Place :<font color=red>*</font></label>
                <div class="col-sm-7">
                    <textarea type="text" class="form-control" id="inputPassword3" rows="5"  required name="placeDescription" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)"></textarea>
                </div>
            </div>

            <?php if ($this->session->userdata('dist_code')=='21') { ?>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">Land (Bigha) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_b" placeholder="land in Bigha" name="land_b" value="0" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">Land (Katha) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_k" placeholder="land in Katha" name="land_k" value="0" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">Land (Chatak) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_lc" placeholder="land in Chatak" name="land_lc" value="0" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">Land (Ganda) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_g" placeholder="land in  Ganda" name="land_g" value="0" required>
                    </div>
                </div>
            <?php } else { ?>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">Land (Bigha) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_b" placeholder="land in Bigha" name="land_b" value="0" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-5 col-form-label">Land (Katha) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_k" placeholder="land in Katha" name="land_k" value="0" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-5 col-form-label">Land (Lessa) :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="dag_por_lc" placeholder="land in Lessa" name="land_lc" value="0" required>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="dag_por_g" placeholder="dag_por_lc" name="land_g" value="0">
            <?php } ?>
            <br>
        </div>
            <div class="col-12 text-center pb-3">
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
                <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="archHistoricalEntry();"></input>

<!--                <button type="submit" class="btn btn-primary" >SUBMIT</button>-->
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</body>

</html>

<script src="<?= base_url('assets/js/remark.js') ?>"></script>