<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" align="right">
            Basic Details (Column 1-6)
        </div>
    </div>
    <div class="card rounded-0">
        <!-- <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-map" aria-hidden="true"></i> DAG DETAILS
            </h5>
        </div> -->
        <form class='form-horizontal' id="f1" method="post" action="" enctype="mPtipart/form-data">
            <input type="hidden" name="dist_code" id="dist_code" value="<?php echo $this->session->userdata('dist_code'); ?>" />
            <input type="hidden" name="subdiv_code" id="subdiv_code" value="<?php echo $this->session->userdata('subdiv_code'); ?>" />
            <input type="hidden" name="cir_code" id="cir_code" value="<?php echo $this->session->userdata('cir_code'); ?>" />
            <input type="hidden" name="mouza_pargona_code" id="mouza_pargona_code" value="<?php echo $this->session->userdata('mouza_pargona_code'); ?>" />
            <input type="hidden" name="lot_no" id="lot_no" value="<?php echo $this->session->userdata('lot_no'); ?>" />
            <input type="hidden" name="vill_townprt_code" id="vill_townprt_code" value="<?php echo $this->session->userdata('vill_townprt_code'); ?>" />
            <!-- <input type="hidden" name="block_code" id="block_code" value="<?php echo $this->session->userdata('block_code'); ?>" />
            <input type="hidden" name="gp_code" id="gp_code" value="<?php echo $this->session->userdata('gram_panch_code'); ?>" /> -->
            <div class="card-body">
                <div class="row rounded-0 bg-info">
                    <div class="col-12">
                        <h5 class="text-center py-1">
                            <i class="fa fa-map" aria-hidden="true"></i> DAG DETAILS
                        </h5>
                    </div>
                </div>
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Dag No: <font color=red>*</font> </label>
                            <input type="text" placeholder="Dag No" class="form-control form-control-sm bkl" id="dag_no" name="dag_no" onchange="newdagchk();">
                        </div>
                    </div>
                    <div class=" col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Old Dag No: </label>
                            <input type="text" placeholder="Old Dag No" class="form-control form-control-sm " id="old_dag_no" name="old_dag_no">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Patta Type: <font color=red>*</font> </label>
                            <select  onchange="pattaTypeSelected(this.value)" name="patta_type_code" id="patta_type_code" class="form-control form-control-sm">
                                <option selected value="">Select</option>
                                <?php foreach ($pattype as $row) { ?>
                                    <option value="<?php echo ($row->type_code); ?>" <?php if ($row->type_code == $pcode) { ?> selected <?php } ?>>
                                        <?php echo $row->patta_type; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="patta_no"> New Patta No: <font color=red>*</font></label>
                            <input type="text" placeholder="New Patta No" class="form-control form-control-sm" id="patta_no" name="patta_no">
                        </div>
                    </div>
                    <?php if ($lm_edit != 'Y'): ?>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="patta_no_old"> Old Patta No: </label>
                                <input type="text" placeholder="Old Patta No" class="form-control form-control-sm" id="patta_no_old" name="patta_no_old">
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Grant No(if any): </label>
                                <input type="text" placeholder="Grant No" class="form-control form-control-sm" id="dag_nlrg_no" name="dag_nlrg_no">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Land Class: <font color=red>*</font> </label>
                            <select name="land_class_code" id="land_class_code" class="form-control form-control-sm">
                                <option selected value="">Select</option>
                                <?php foreach ($lclass as $row) { ?>
                                    <option value="<?php echo ($row->class_code); ?>" <?php if ($row->class_code == $lcode) { ?> selected <?php } ?>>
                                        <?php echo $row->land_type; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="police_station">Police Station: </label>
                            <input type="text" placeholder="Police Station" class="form-control form-control-sm" id="police_station" name="police_station">
                        </div>
                    </div> -->
                    <?php if ($lm_edit != 'Y'): ?>
                        <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>

                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1">Dag Area Bigha: <font color=red>*</font> </label>
                                    <input placeholder="Dag Area Bigha" type="text" class="form-control form-control-sm bklB" id="dag_area_bigha" name="dag_area_b">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1"> Dag Area Katha: <font color=red>*</font></label>
                                    <input placeholder="Dag Area Katha" type="text" class="form-control form-control-sm bklB" id="dag_area_katha" name="dag_area_k">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1"> Dag Area Chatak: <font color=red>*</font></label>
                                    <input type="text" placeholder="Dag Area Chatak" class="form-control form-control-sm bklB" id="dag_area_lessa" name="dag_area_lc">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1">Dag Area Ganda: <font color=red>*</font> </label>
                                    <input type="text" placeholder="Dag Area Ganda" class="form-control form-control-sm bklB" id="dag_area_ganda" name="dag_area_g">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1">Dag Area Are: <font color=red></font> </label>
                                    <input type="text" placeholder="Dag Area Are" class="form-control form-control-sm areB" id="dag_area_are_b" name="dag_area_r">
                                </div>
                            </div>

                        <?php } else { ?>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1"> Dag Area Bigha: <font color=red>*</font></label>
                                    <input type="text" placeholder="Dag Area Bigha" class="form-control form-control-sm bkl bigha" id="dag_area_bigha" name="dag_area_b">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1">Dag Area Katha: <font color=red>*</font> </label>
                                    <input type="text" placeholder="Dag Area Katha" class="form-control form-control-sm bkl katha" id="dag_area_katha" name="dag_area_k">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1"> Dag Area Lessa: <font color=red>*</font></label>
                                    <input type="text" placeholder="Dag Area Lessa" class="form-control form-control-sm bkl lessa" id="dag_area_lessa" name="dag_area_lc">
                                </div>
                            </div>
                            <input type="hidden" class="form-control form-control-sm" id="dag_area_ganda" name="dag_area_g">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="sel1"> Dag Area Are: <font color=red></font></label>
                                    <input type="text" placeholder="Dag Area Are" class="form-control form-control-sm are" id="dag_area_are" name="dag_area_r">
                                </div>
                            </div>
                        <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- <div class="card-header rounded-0 text-center bg-info py-1">
                <h5>
                    <i class="fa fa-inr" aria-hidden="true"></i> REVENUE DETAILS
                </h5>
            </div>
            <div class="card-body">
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dag_land_revenue"> Dag Land Revenue <font color=red>*</font></label>
                            <input type="text" placeholder="Dag Land Revenue" value="0" class="form-control form-control-sm" id="dag_land_revenue" name="dag_land_revenue" onkeyup="dagamtcal();">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dag_local_tax">Dag Local Rate </label>
                            <input type="text" placeholder="Dag Local Rate" class="form-control form-control-sm" id="dag_local_tax" name="dag_local_tax" value="0">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="zonal_value">Zonal Value </label>
                            <input type="text" placeholder="Zonal Value" class="form-control form-control-sm" id="zonal_value" name="zonal_value" value="0">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Revenue Paid Up To (Eg - 2020) </label>
                            <input type="text" placeholder="Revenue Paid Upto" class="form-control form-control-sm" id="revenue_paid" name="revenue_paid" value="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Direct Paying ?</label>
                            <br>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value='Y' name="dp_flag_yn">Yes
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" checked="checked" value='N' name="dp_flag_yn">No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="card-body">
                <div class="row rounded-0 bg-info">
                    <div class="col-12">
                        <h5 class="text-center py-1">
                            <i class="fa fa-arrows" aria-hidden="true"></i> AREA DESCRIPTION
                        </h5>
                    </div>
                </div>
                <div class="row border border-info p-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">North Description </label>
                            <input placeholder="North Descripion" type="text" class="form-control form-control-sm" id="north_descp" name="dag_n_desc">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> South Description</label>
                            <input type="text" placeholder="South Description" class="form-control form-control-sm" id="south_descp" name="dag_s_desc">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">East Description </label>
                            <input type="text" placeholder="East Description" class="form-control form-control-sm" id="east_descp" name="dag_e_desc">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">West Description </label>
                            <input type="text" placeholder="West Description" class="form-control form-control-sm" id="west_descp" name="dag_w_desc">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Dag No(North Side)</label>
                            <input type="text" placeholder="Dag No(North Side)" class="form-control form-control-sm" id="dag_no_north" name="dag_n_dag_no">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Dag No(South Side)</label>
                            <input type="text" placeholder="Dag No(South Side)" class="form-control form-control-sm" id="dag_no_south" name="dag_s_dag_no">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Dag No(East Side)</label>
                            <input type="text" placeholder="Dag No(East Side)" class="form-control form-control-sm" id="dag_no_east" name="dag_e_dag_no">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1"> Dag No(West Side)</label>
                            <input type="text" placeholder="Dag No(West Side)" class="form-control form-control-sm" id="dag_no_west" name="dag_w_dag_no">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right" style="margin-top: 20px">
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                        <input type="hidden" name="newpatta" id="newpatta" value="" />

                        <button type='button' class="btn btn-info" name="submit" onclick='dagentry();' value="Submit">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
                        </button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo base_url('assets/js/sweet2.11.js') ?>"></script>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/location_svamitva.js?v=1.3') ?>"></script>

<script>
    function pattaTypeSelected(patta_type_code) {
        if (patta_type_code == '0209') {
            $("#patta_no").val(0);
            $("#patta_no").prop('readonly', true);
            $("#dag_land_revenue").val(0);
            $("#zonal_value").val(0);
            $("#dag_local_tax").val(0);
        } else {
            $("#patta_no").val();
            $("#patta_no").prop('readonly', false);
            $("#dag_land_revenue").val();
        }
    }
    $('.bkl').keyup(function() {

        var b = $('#dag_area_bigha').val();
        var k = $('#dag_area_katha').val();
        var l = $('#dag_area_lessa').val();

        if (b == '') b = 0;
        if (k == '') k = 0;
        if (l == '') l = 0;

        var area_lessa = parseFloat(b) * 100 + parseFloat(k) * 20 + parseFloat(l);

        var area_are = area_lessa * (100 / 747.45);

        var total_area_are = parseFloat(area_are).toFixed(5);

        $("#dag_area_are").val(total_area_are);

    });

    $('.are').keyup(function() {

        var r = $('#dag_area_are').val();
        if (r > 438397.21719) {
            alert('Enter Are area within 0 to 438397.21719');
        }

        var area_B = $('#dag_area_bigha').val();
        var area_K = $('#dag_area_katha').val();
        var area_LC = $('#dag_area_lessa').val();
        var area_Are = $('#dag_area_are').val();

        if (area_Are == '') area_Are = 0;
        if (area_B == '') area_B = 0;
        if (area_K == '') area_K = 0;
        if (area_LC == '') area_LC = 0;
        area_LC = parseFloat(area_Are) * (747.45 / 100);
        area_B = parseInt(area_LC / 100);
        area_LC = area_LC % 100; //Rest area
        area_K = parseInt(area_LC / 20);
        area_LC = area_LC % 20; //Rest area

        var total_area_LC = parseFloat(area_LC).toFixed(4);

        // alert(r)

        $("#dag_area_bigha").val(area_B);
        $("#dag_area_katha").val(area_K);
        $("#dag_area_lessa").val(total_area_LC);


    });
    $('.bklB').keyup(function() {

        var b = $('#dag_area_bigha').val();
        var k = $('#dag_area_katha').val();
        var l = $('#dag_area_lessa').val();
        var g = $('#dag_area_ganda').val();

        if (b == '') b = 0;
        if (k == '') k = 0;
        if (l == '') l = 0;
        if (g == '') g = 0;


        var area_ganda = parseFloat(b) * 6400 + parseFloat(k) * 320 + parseFloat(l) * 20 + parseFloat(g);

        var area_are_b = area_ganda * (13.37804 / 6400);
        var total_area_are_b = parseFloat(area_are_b).toFixed(4);

        $("#dag_area_are_b").val(total_area_are_b);

    });
    $('.are').keyup(function() {

        var r = $('#dag_area_are_b').val();
        if (r > 133788.21325) {
            alert('Enter Are area within 0 to 133788.21325');
        }

        var area_B = $('#dag_area_bigha').val();
        var area_K = $('#dag_area_katha').val();
        var area_LC = $('#dag_area_lessa').val();
        var area_GN = $('#dag_area_ganda').val();
        var area_Are = $('#dag_area_are').val();

        if (area_Are == '') area_Are = 0;
        if (area_B == '') area_B = 0;
        if (area_K == '') area_K = 0;
        if (area_LC == '') area_LC = 0;
        if (area_G == '') area_G = 0;

        area_G = parseFloat(area_Are) * (6400 / 13.37804);
        area_B = parseInt(area_G / 6400);
        area_G = area_G % 6400; //Rest area
        area_K = parseInt(area_G / 320);
        area_G = area_G % 320
        area_LC = parseInt(area_G / 20);
        G = area_G % 20; //Rest area


        var total_area_g = parseFloat(G).toFixed(4);

        // alert(r)

        $("#dag_area_bigha").val(area_B);
        $("#dag_area_katha").val(area_K);
        $("#dag_area_lessa").val(area_LC);
        $("#dag_area_ganda").val(total_area_g);


    });
</script>
<script>
    $(document).ready(function() {
        $("#patta_type_code").val('0209');
        pattaTypeSelected('0209');
        // Retrieve dagNumber from sessionStorage
        var dagNumber = sessionStorage.getItem('dagNumberSession');

        // Check if dagNumber is not null or undefined
        if (dagNumber) {
            // Set the value of the input field with id 'dag_no'
            $('#dag_no').val(dagNumber);
            $('#dag_no').prop('readonly', true);
            newdagchk();
        } else {
            // Handle the case when dag_number is not found in sessionStorage
            console.error('dagNumberSession not found in sessionStorage');
            // You may want to handle this case differently based on your application logic
        }

    });
</script>