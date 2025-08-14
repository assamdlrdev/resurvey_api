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

    <style>

        .row {
            margin-left:-5px;
            margin-right:-5px;

        }
    </style>
</head>
<body>
<div class="container-fluid mt-3 mb-2 font-weight-bold">
    <?php if($locationname["dist_name"]!=NULL) echo $locationname['dist_name']['loc_name'].'/'.$locationname['subdiv_name']['loc_name'].'/'.$locationname['cir_name']['loc_name'].'/'.$locationname['mouza_name']['loc_name'].'/'.$locationname['lot']['loc_name'].'/'.$locationname['village']['loc_name']; ?>
</div>
<div class="container bg-light p-0 border border-dark mt-5 mb-5">
    <div class="col-12 px-0 pb-3">
        <div class="bg-info text-white text-center py-2">
            <h3>Encroacher Details (Column 31)</h3>
        </div>
    </div>

    <?php echo form_open('Remark/EncroacherFormSubmit'); ?>
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Encro ID:<font color=red>*</font></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" readonly id="inputEmail3" name="encro_id" value="<?php echo $encroId?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Remark Type Hist No:<font color=red>*</font></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" readonly id="inputEmail3" name="rmk_type_hist_no" value="<?php echo $encroId?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Encroacher Name:<font color=red>*</font></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inputEmail3"  name="encro_name" value="" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Encroacher Guardian Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inputEmail3" name="encro_guardian" value="" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Relation with Guardian:</label>
                <div class="col-sm-9">

                    <select name="encro_guar_relation" class="form-control">
                        <option selected value="">Select Relation With Guardian</option>
                        <?php foreach ($relation as $value) { ?>
                            <option  value="<?= $value['guard_rel']?>"><?= $value['guard_rel_desc_as']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Encroacher's Address:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="inputPassword3" name="encro_add" charset="utf-8"  onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Nature of Encroacher's Land:</label>
                <div class="col-sm-9">
                    <select name="encro_class_code" class="form-control" >
                        <option selected value="">Select Nature of Encroacher's Land</option>
                        <?php foreach ($classcode as $value) { ?>
                            <option  value="<?= $value['class_code']?>"><?= $value['class']?></option>
                        <?php } ?>
                    </select></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-6 col-form-label">Encroacher's Land Used For:</label>
                <div class="col-sm-6">
                    <select name="nature_land_code" class="form-control">
                        <option selected>Encroacher's Land Used For</option>
                        <?php foreach ($landusedfor as $value) { ?>
                            <option  value="<?= $value['code']?>"><?= $value['used_for']?></option>
                        <?php } ?>
                    </select></div>
            </div>

            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-6 col-form-label">Land Area(Bigha) :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control"  name="encro_land_b">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-6 col-form-label">Land Area(Katha) :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control"  name="encro_land_k">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-6 col-form-label">Land Area(Lessa/Chatak) :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control"  name="encro_land_lc">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-6 col-form-label">Encroacher Evicted:</label>
                <div class="col-sm-6">
                    <input type="radio" name="encro_evicted_yn" value="Y" >Yes  &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="encro_evicted_yn" value="N" checked> No  &nbsp;
                </div>
            </div>
            <div class="form-group row" id="mDate" style="display: none">
                <label for="inputPassword3" class="col-sm-6 col-form-label">Encroacher Evic Date:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="cosigndate" name="encro_evic_date">
                </div>
            </div>

        </div>
        <div class="col-12 text-center pb-3">
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
            <input type="button" class="btn btn-primary" id="psubmit" name="psubmit" value="Submit" onclick="encroacherentry();"></input>

        </div>
        <?php echo form_close(); ?>


    </div>

</div>


</body>

<script>
    $( function() {
        $( "#orderdate" ).datepicker({dateFormat: 'yy-mm-dd'});
        $( "#lmsigndate" ).datepicker({dateFormat: 'yy-mm-dd'});
        $( "#sksigndate" ).datepicker({dateFormat: 'yy-mm-dd'});
        $( "#cosigndate" ).datepicker({dateFormat: 'yy-mm-dd'});


    } );

    $("input[name='encro_evicted_yn']").click(function(){
        var radioValue = $("input[name='encro_evicted_yn']:checked").val();
        if(radioValue =='N')
        {
            $("#mDate").hide();
        }
        if(radioValue =='Y')
        {
            $("#mDate").show();
        }

    });

</script>
<script src="<?= base_url('assets/js/remark.js') ?>"></script>


</html>


