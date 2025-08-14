<style>
    *{
        font-family:'Firefly';
    }
    table,tr,td{
        border:1px solid black !important;
    }

</style>
<style>
    .onTopNotification{
        display:none;
    }
    @media print {
        body { font-size: 10pt }
    }
    @media screen {
        /*        body { font-size: 13px }*/
    }
    @media print {
        body { line-height: 1.2 }
    }
</style>
<div class='chitha_report container-fluid'>
    <br>
    <p align="left" style="margin-top: 0; margin-bottom: 0">
        <font size="4" face="courier"><?php echo($this->lang->line('assam_schedule_xxxvii_form_no_30')) ?></font></p>
    <center>
        <div id="table_head">
            <p style="margin-top: 1; margin-bottom: 0;text-align: center;"><font face="Verdana" size="5">Chitha for Surveyed Villages / <?php echo($this->lang->line('chitha_for_surveyed_villages')) ?></font></p>
            <br>
        </div>

        <table class="table" id='table1'>
            <tr>
                <td width="20%" style="text-align: center;"><!--DISTRICT-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('district')) ?><b> :<?php echo $location['dist']; ?></b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--SUB-DIVISION-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('subdivision')) ?><b>: <?php echo $location['sub']; ?>  </b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--CIRCLE-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('circle')) ?><b>:<?php echo $location['cir']; ?></b> </font>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="20%" style="text-align: center;"><!--mouza-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('mouza')) ?><b>:<?php echo $location['mouza']; ?>  </b></font>
                    </p>
                </td>

                <td width="20%" style="text-align: center;"><!--lot-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('lot_no')) ?><b>:<?php echo $location['lot']; ?>   </b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--vill-->
                    <p>
                        <font size="2" color="#000080"><?php echo($this->lang->line('vill_town')) ?><b>: <?php echo $location['vill']; ?> </b>  </font>
                    </p>
                </td>

            </tr>
        </table>
    </center>
    <br/>

    <?php
    //var_dump($data);
    //print_r($data);
    foreach ($data as $chithainf):
        ?>
        <div class=" form-top">
            <div class="row">
                <div align="center" class="col-lg-12 table-responsive">
                    <table class="table table_black" id='table2'  >
                        <tr >
                            <td align=center rowspan="2" style="width:50px; " > <!--DAG NUMBER COL. 1-->
                            <?php echo($this->lang->line('dag_no')) ?>
                            </td>
                            <td colspan="2" align=center  style="width:110px; "> <!--LAND CLASS COL. 2-->
                            <?php echo($this->lang->line('land_class')) ?></td>
                            <td align=center rowspan="2" style="width:150px;"> <!--AREA COL. 3-->
                            <?php echo($this->lang->line('area_with_units')) ?></td>
                            <td align=center rowspan="2" style="width:150px; "> <?php echo($this->lang->line('patta_no_and_type')) ?></td>

                            <td align=center rowspan="2" style="width:75px; "> <!--REVENUE COL.5-->
                            <?php echo($this->lang->line('revenue_in_rupee')) ?> </td>
                            <td align=center rowspan="2" style="width:75px;"> <!--LOCAL RATE COL. 6-->
                            <?php echo($this->lang->line('local_tax_in_rupee')) ?></td>
                        </tr>

                        <tr>
                            <td>
                            <?php echo($this->lang->line('agriculture')) ?>
                            </td>
                            <td>
                            <?php echo($this->lang->line('non_agriculture')) ?>
                            </td>

                        </tr>

                        <tr >
                            <td align='center' >১</td>
                            <td align='center'  colspan="2">২</td>
                            <td align='center' >৩</td>
                            <td align='center' >৪</td>
                            <td align='center' >৫</td>
                            <td align='center' >৬</td>
                        </tr>
                        <tr >
                            <td align='center' >
                                <?php
                                //  echo $chithainf['dag_no'];
                                ?>
                                <?php
                                if ($chithainf['old_dag_no'] != "") {
                                    echo $this->utilityclass->cassnum($chithainf['old_dag_no']) . '/' . $this->utilityclass->cassnum($chithainf['dag_no']);
                                } else {
                                    if (is_numeric($chithainf['dag_no'])) {
                                        echo $this->utilityclass->cassnum($chithainf['dag_no']);
                                    } else {
                                        echo $chithainf['dag_no'];
                                    }
                                }
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                if ($chithainf['class_code_cat'] == '01') {
                                    echo $chithainf['land_type'];
                                }
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                if ($chithainf['class_code_cat'] == '02') {
                                    echo $chithainf['land_type'];
                                }
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                //echo $this->utilityclass->cassnum($chithainf['dag_area_b']).'<br>';
                                $lc = $chithainf['dag_area_lc'];
                                echo $this->utilityclass->cassnum($chithainf['dag_area_b']) . '-' . $this->utilityclass->cassnum($chithainf['dag_area_k']) . '-' . $this->utilityclass->cassnum($lc);
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                if (is_numeric($chithainf['patta_no'])) {
                                    echo $this->utilityclass->cassnum($chithainf['patta_no']). '&nbsp;' . ',' ;
                                } else {
                                    echo $chithainf['patta_no']. '&nbsp;' . ',' ;
                                }
                                echo  $chithainf['patta_type_name']; //$chithainf['patta_type'];
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                $total = $chithainf['dag_area_b'] * 100 + $chithainf['dag_area_k'] * 20 + $chithainf['dag_area_lc'];
                                $total /= 100;
                                echo $this->utilityclass->cassnum(number_format($chithainf['dag_revenue'], 2));
                                // echo round($chithainf['dag_revenue'], 2) . '<br>';
                                ?>
                            </td>
                            <td align='center' >
                                <?php
                                echo $this->utilityclass->cassnum(number_format($chithainf['dag_localtax'], 2)) . '<br>';
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div align="center" class="col-lg-12 table-responsive">
                    <table class="table" id='table3' STYLE="width:100%; border-collapse:collapse" cellPadding=0 cellSpacing=0 height="580">
                        <tr>
                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                <?php echo($this->lang->line('pattdar_father_name_address')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                <?php echo($this->lang->line('namjari_encroacher_father_name_address')) ?>
                            </td>
                            <td align="center" rowspan="2" valign="top" height="127">
                                <?php echo($this->lang->line('rayat_father_name_address')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                <?php echo($this->lang->line('rayat_type_khatian_no_khajana_rates')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                <?php echo($this->lang->line('lower_rayat_father_name_address')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                <?php echo($this->lang->line('year')) ?>
                            </td>

                            <td align="center" colspan="2" valign="top" height="25">
                                <?php echo($this->lang->line('non_crop_soil_area')) ?>
                            </td>

                            <td align="center" colspan="4" valign="top" height="25">
                                <?php echo($this->lang->line('crop_soil_area')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                <?php echo($this->lang->line('fruit_tree_name_and_count')) ?>
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                <?php echo($this->lang->line('comment')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('how_land_is_used')) ?></td>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('area_with_units')) ?></td>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('from_where_water_get')) ?> </td>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('crop_name')) ?> </td>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('area_with_units')) ?></td>
                            <td align="center" valign="top" height="100"><?php echo($this->lang->line('multiple_crop_soil_area')) ?></td>

                        </tr>

                        <tr>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ৭</p></td>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ৮</p></td>
                            <td STYLE="width:150px" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ৯</p></td>
                            <td STYLE="width:80px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১০</p></td>
                            <td STYLE="width:150px" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১১</p></td>
                            <td STYLE="width:40px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">১ম</p>
                                <p style="margin-top: 0; margin-bottom: 0">২য়</p>
                                <p style="margin-top: 0; margin-bottom: 0">৩য়</td>
                            <td STYLE="width:60px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১২</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৮</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৪</p></td>
                            <td STYLE="width:50px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৩</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৯</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৫</p></td>
                            <td STYLE="width:57.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৪</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২০</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৬</p></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৫</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২১</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৭</p></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৬</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২২</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৮</p></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ১৭</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৩</p>
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ২৯</p></td>
                            <td STYLE="width:60px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ৩০</p></td>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0">
                                    ৩১</p></td>
                        </tr>
                        <tr>
                            <?php include('pattadars.php'); ?>
                            <!-------------------------NEW MODIFIED CHITHA COL 8----------------------------->
                            <?php include('col8.php'); ?>
                            <td	rowspan="3" valign="top" height="409"id=chitha_col_9 ><!--Tenant Descr.-->

                                <?php
                                if (isset($chithainf['tenant'])) {
                                    foreach ($chithainf['tenant'] as $tenantdesc):
                                        //var_dump($tenantdesc);
                                        $tenantName = $tenantdesc['tenant_name'];
                                        //echo $tenantName;
                                        if ($tenantdesc['tenants_father'] != '') {
                                            $tenantsFather = $tenantdesc['tenants_father'];
                                        } else {
                                            $tenantsFather = "";
                                        }
                                        if ($tenantdesc['tenants_add1'] != "") {
                                            $tenantsadd1 = $tenantdesc['tenants_add1'];
                                        } else {
                                            $tenantsadd1 = "";
                                        }
                                        if ($tenantdesc['tenants_add2'] != "") {
                                            $tenantsadd2 = $tenantdesc['tenants_add2'];
                                        } else {
                                            $tenantsadd2 = "";
                                        }
                                        if($tenantdesc['status']==0)
                                        {
                                            echo $tenantName . '<br>(' . $tenantsFather . ")" . '<br>' . $tenantsadd1 . '<br>' . $tenantsadd2;
                                        }
                                        else
                                        {
                                            echo "<s>". $tenantName . '<br>(' . $tenantsFather . ")" . '<br>' . $tenantsadd1 . '<br>' . $tenantsadd2 ."</s>";
                                        }
                                        echo "<br>---<br>";
                                    endforeach;
                                }
                                ?>
                            </td>
                            <td  valign="top" rowspan="3" height="409" id=chitha_col_10><!--Tenant TYPE & NO.-->
                                <?php
                                if (isset($chithainf['tenant'])) {
                                    foreach ($chithainf['tenant'] as $tenantdesc):
                                        if ($tenantdesc['tenant_type'] != "00" && $tenantdesc['tenant_type'] != "None") {
                                            $type_of_tenant = $tenantdesc['tenant_type'];
                                        } else {
                                            $type_of_tenant = "";
                                        }

                                        if ($tenantdesc['khatian_no'] != "" && $tenantdesc['khatian_no'] != 0) {
                                            $khatian_no = $tenantdesc['khatian_no'];
                                        } else {
                                            $khatian_no = "";
                                        }
                                        
                                        echo $type_of_tenant . '<br>' . $khatian_no;
                                        if(!empty($type_of_tenant) || !empty($khatian_no)){
                                            echo "<br>---<br>";
                                        }
                                        //'<br>' .// $revenue_tenant . '<br>' . $crop_rate;
                                    endforeach;
                                }
                                ?>



                            </td>
                            <td	rowspan="3" valign="top" height="409" id=chitha_col_11><!--Sub Tenant-->
                                <?php
                                if (isset($chithainf['subtenant']))
                                {
                                    foreach ($chithainf['subtenant'] as $subtenant):
                                        if ($subtenant['subtenant_name'] != "")
                                        {
                                            $subtenantName = $subtenant['subtenant_name'];
                                        }
                                        else
                                        {
                                            $subtenantName = "";
                                        }
                                        if ($subtenant['subtenants_father'] != "")
                                        {
                                            $subtenantfatherName = $subtenant['subtenants_father'];
                                        }
                                        else {
                                            $subtenantfatherName = "";
                                        }
                                        if ($subtenant['subtenants_add1'] != "")
                                        {
                                            $subtenantadd1 = $subtenant['subtenants_add1'];
                                        }
                                        else
                                        {
                                            $subtenantadd1 = "";
                                        }
                                        if ($subtenant['subtenants_add2'] != "")
                                        {
                                            $subtenantadd2 = $subtenant['subtenants_add2'];
                                        }
                                        else
                                        {
                                            $subtenantadd2 = "";
                                        }
                                        if ($subtenant['subtenants_add3'] != "")
                                        {
                                            $subtenantadd3 = $subtenant['subtenants_add3'];
                                        }
                                        else
                                        {
                                            $subtenantadd3 = "";
                                        }
                                        if($subtenant['status']==0)
                                        {
                                            echo $subtenantName . '<br>(' . $subtenantfatherName . ")" . '<br>' . $subtenantadd1 . '<br>' . $subtenantadd2;
                                        }
                                        else
                                        {
                                            echo "<s>". $subtenantName . '<br>(' . $subtenantfatherName . ")" . '<br>' . $subtenantadd1 . '<br>' . $subtenantadd2 ."</s>";
                                        }
                                        echo "<br>---<br>";

                                    endforeach;
                                }
                                ?>
                            </td>

                            <!-- THREE LATEST YEARS ARE FOUND OUT FROM TABLES Chitha_MCrop AND Chitha_Noncrop -->


                            <!----------------------------1ST YEAR ---------------------------------------->
                            <!----------------------------1ST YEAR ---------------------------------------->
                            <td align="center" valign="top" height="100" id=crop_year_no_1><!--YEAR NO-->
                                <?php
                                rsort($chithainf['years']);

                                foreach ($chithainf['years'] as $key => $yr):

                                    echo $year_assam = $chithainf['years'][$key]['year'];
                                    echo $this->utilityclass->cassnum($year_assam) . '<br>';

                                endforeach;
                                ?>
                            </td>

                            <td align="center" valign="top" height="100"  id=chitha_col_12>&nbsp;<!--NONCROP TYPE 1st YEAR-->
                                <?php
                                foreach ($chithainf['noncrp'] as $key => $noncrop):
                                    echo $chithainf['noncrp'][$key]['type_of_used_noncrp'];
                                endforeach;
                                ?>
                            </td>
                            <td align="center" valign="top" width="200" height="100" id=chitha_col_13>&nbsp;<!--NONCROP LAND 1st YEAR-->

                                <?php
                                foreach ($chithainf['noncrp'] as $key => $noncrop):
                                    echo $chithainf['noncrp'][$key]['noncrp_b'] . '-' . $chithainf['noncrp'][$key]['noncrop_k'] . '-' . $chithainf['noncrp'][$key]['noncrop_lc'] . '<br>';

                                endforeach;
                                ?>


                            </td>

                            <!-- CROP SOURCE OF WATER 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_14>&nbsp;
                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):
                                    //if (sizeof($chithainf['noncrp']) > 0) {
                                    echo $chithainf['mcrp'][$key]['sourceofwater'] . '<br>';
                                    // }

                                endforeach;
                                ?>

                            </td>
                            <!-- CROP TYPE 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_15>
                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):

                                    echo $chithainf['mcrp'][$key]['cropname'] . '<br>' .
                                        '(' . $chithainf['mcrp'][$key]['crop_category'] . ')<br>';
                                endforeach;
                                ?>
                            </td>
                            <!-- CROP LAND 1st YEAR-->
                            <td align="center" valign="top" height="100"  id=chitha_col_16>

                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):

                                    echo $chithainf['mcrp'][$key]['mcrp_b'] . '-' . $chithainf['mcrp'][$key]['mcrop_k'] . '-' . $chithainf['mcrp'][$key]['mcrop_lc'] . '<br>';
                                endforeach;
                                ?>

                            </td>
                            <!-- MULTIPLE CROP LAND 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_17>
                                <?php
                                foreach ($chithainf['mcrp_akeadhig'] as $key => $mcrop_ekadhig):

                                    echo $chithainf['mcrp_akeadhig'][$key]['bigha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['katha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['lesa'] . '<br>';
                                endforeach;
                                ?>

                            </td>

                            <!---------------------- CROP LAND DETAILS FOR THE FIRST YEAR ENDS------------------>
                            <td align="left" valign="top"  height="409" rowspan=3 id=chitha_col_30> <!--FRUIT-->
                                <?php
                                foreach ($chithainf['fruit'] as $key => $fruit):

                                    echo $chithainf['fruit'][$key]['fruitname'] . '<br>' . $chithainf['fruit'][$key]['no_of_plants'] . '<br>' . '(' . $chithainf['fruit'][$key]['fbigha'] . '-' . $chithainf['fruit'][$key]['fkatha'] . '-' . $chithainf['fruit'][$key]['flesa'] . ')' . '<br>';
                                endforeach;
                                ?>
                            </td>
                            <!--===========================================================================
                            ----------------->
                            <?php include 'col31.php'; ?>
                        </tr>

                        <!--1 ROW COMPLETES HERE. BUT WE NED TO GENERATE 2 MORE ROWS, WHICH CONTAINS ENTRIES FOR 2ND & 3RD YEAR CROPS-->

                        <!--END OF ROW 3: RECORD PORTION-->
                    </table>


                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>


</div>
<div class='dontshow' >
    <!--    <div class="form-group" style="text-align: center">
            <div class="col-sm-3" style="margin: 0 auto;float: none;margin-top: 20px;margin-bottom: 20px;">
                <button class='btn btn-primary' onclick="myFunction()"><i class='fa fa-print'></i> Print this page</button>
                <button class='btn btn-danger' onclick="self.close()"><i class='fa fa-close'></i> Close this window</button>
            </div>
        </div>-->
    <div>
        <script>
            function myFunction() {
                var divToPrint0 = document.getElementById('table_head');
                var divToPrint1 = document.getElementById('table1');
                var divToPrint2 = document.getElementById('table2');
                var divToPrint3 = document.getElementById('table3');

                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'table{display:inline-block;} p{font-size:1em;} font{ font-size:1em }' +
                    'th,td {' +
                    'border:1px solid #000;font-size:0.5em;' +
                    'padding;0.5em;' +
                    '}' +
                    '</style>';
                htmlToPrint += divToPrint0.outerHTML;
                htmlToPrint += divToPrint1.outerHTML;
                htmlToPrint += divToPrint2.outerHTML;
                htmlToPrint += divToPrint3.outerHTML;

                newWin = window.open("");
                newWin.document.write(htmlToPrint);
                newWin.print();
                newWin.close();

            }
            function windowClose() {
                window.open('', '_parent', '');
                window.close();
            }
        </script>
    </div>
</div>            

