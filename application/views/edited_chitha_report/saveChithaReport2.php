<style>
    td{
        margin:0;padding:2px !important;
    }
</style>
<div class='chitha_report'>
    <p align="left" style="margin-top: 0; margin-bottom: 0">
        <font size="3" face="courier">Assam Schedule XXXVII,Form No. 30</font></p>
    <center>
        <p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" size="7">Chitha for Surveyed Villages /</font></p>
        <p><font size="5">জৰীপ হোৱা গাঁৱৰ চিঠা</font></p>
        <br>
        <table style="border: 1px solid black !important;" class="table-bordered table_black" align="center">
            <tr>
                <td width="20%" style="text-align: center;"><!--DISTRICT-->
                    <p>
                        <font size="5">&#2460;&#2495;&#2482;া<b> :<?php echo $location['dist']; ?></b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--SUB-DIVISION-->
                    <p>
                        <font size="5" color="#000080">&#2478;&#2489;&#2453;&#2497;&#2478;া<b>: <?php echo $location['sub']; ?>  </b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--CIRCLE-->
                    <p>
                        <font size="5">&#2458;&#2453;&#2509;&#2544;<b>:<?php echo $location['cir']; ?></b> </font>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="20%" style="text-align: center;"><!--mouza-->
                    <p>
                        <font size="5" color="#000080">&#2478;&#2508;&#2460;া<b>:<?php echo $location['mouza']; ?>  </b></font> 
                    </p>
                </td>

                <td width="18%" style="text-align: center;"><!--lot-->
                    <p>
                        <font size="5">&#2482;&#2494;&#2463;<b>:<?php echo $location['lot']; ?>   </b> </font>
                    </p>
                </td>
                <td width="18%" style="text-align: center;"><!--vill-->
                    <p>
                        <font size="5">&#2455;&#2494;&#2451;&#2433;<span>/ </span>&#2458;&#2489;&#2544;<b>: <?php echo $location['vill']; ?> </b>  </font>
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


        <div class="container-fluid form-top" font-weight: bold>
            <div class="row">
                <div align="" class="col-lg-12">
                    <table style="border: 1px solid black !important; width: 800px;" class=" table table_black"  align="center" width="100%" >
                        <tr style="border-left: 1px solid black;">
                            <td align=center rowspan="2" style="width:50px; border-top-color:#000000; border-top-width:1; border-right: 1px solid black" > <!--DAG NUMBER COL. 1-->
                                দাগ নং
                            </td>
                            <td colspan="2" align=center  style="width:110px; border-top-color:#000000; border-top-width:1; border-right: 1px solid black"> <!--LAND CLASS COL. 2-->
                                মাটিৰ শ্ৰেণী</td>
                            <td align=center rowspan="2" style="width:120px; border-top-color:#000000; border-top-width:1; border-right: 1px solid black"> <!--AREA COL. 3-->
                                কালি <p>( বি-ক-লে )</p></td>
                            <td align=center rowspan="2" style="width:150px; border-top-color:#000000; border-top-width:1; border-right: 1px solid black"> &nbsp;পট্টাৰ 
                                নং আৰু প্ৰকাৰ</td>

                            <td align=center rowspan="2" style="width:75px; border-top-color:#000000; border-top-width:1; border-right: 1px solid black"> <!--REVENUE COL.5-->
                                ৰাজহ(টকা) </td>
                            <td align=center rowspan="2" style="width:95px; border-top-color:#000000; border-right: 1px solid black;"> <!--LOCAL RATE COL. 6-->
                                স্হানীয় কৰ(টকা)</td>
                        </tr>

                        <tr>
                            <td>
                                কৃষি
                            </td>
                            <td>
                                অকৃষি
                            </td>


                        </tr>


                        <tr class="" style="border-left: 1px solid black;">
                            <td align='center' style=";">১</td>
                            <td align='center' style="border-right: 1px solid black;" colspan="2">২</td>
                            <td align='center' style=";">৩</td>
                            <td align='center' style=";">৪</td>
                            <td align='center' style=";">৫</td>
                            <td align='center' style=";">৬</td>
                        </tr>
                        <tr style="border-left: 1px solid black;">
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                // echo $chithainf['dag_no'];
                                ?>
                                <?php
                                if ($chithainf['old_dag_no'] != "") {
                                    echo $chithainf['old_dag_no'] . '/' . $chithainf['dag_no'];
                                } else {
                                    echo $chithainf['dag_no'];
                                }
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                if ($chithainf['class_code_cat'] == '01') {
                                    echo $chithainf['land_type'];
                                }
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                if ($chithainf['class_code_cat'] == '02') {
                                    echo $chithainf['land_type'];
                                }
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                echo $chithainf['dag_area_b'] . '-' . $chithainf['dag_area_k'] . '-' . round($chithainf['dag_area_lc'], 2);
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                echo $chithainf['patta_no'] . '&nbsp;' . ',' . $this->session->userdata('patta_type'); //$chithainf['patta_type'];
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; ;"> 
                                <?php
                                $total = $chithainf['dag_area_b'] * 100 + $chithainf['dag_area_k'] * 20 + $chithainf['dag_area_lc'];
                                $total /= 100;
                                echo round($chithainf['dag_revenue'], 2) . '<br>';
                                ?>
                            </td>
                            <td align='center' style="border-bottom: 1px solid black; "> 
                                <?php
                                echo round($chithainf['dag_localtax'], 2) . '<br>';
                                ?>
                            </td>
                        </tr>
                    </table>
                    <table class="table table_black table-bordered"  style="border: 1px solid black;"  STYLE="width:100%; border-collapse:collapse" cellPadding=0 cellSpacing=0 height="580">
                        <tr>
                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                পট্টাদাৰৰ নাম, পিতাৰ নাম আৰু ঠিকনা										
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                নামজাৰী হবলগী<span lang="as">য়া</span> দখলদাৰৰ নাম, পিতাৰ নাম, ঠিকনা</td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                ৰায়ত/ আধিয়াৰৰ নাম, পিতাৰ নাম, ঠিকনা
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                ৰায়তৰ প্ৰকাৰ/ খতিয়ন নং, খাজানা বা ফছলৰ নিৰিখ</td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                তলতীয়া ৰায়তৰ নাম, পিতাৰ নাম, ঠিকনা
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127" >
                                বছৰ/ তাৰিখ</td>

                            <td align="center" colspan="2" valign="top" height="25" >
                                বেফছলী মাটিৰ কালি
                            </td>

                            <td align="center" colspan="4" valign="top" height="25">
                                ফছলী মাটিৰ কালি</td>

                            <td align="center" rowspan="2" valign="top" height="127">
                                ফলৰ গছৰ নাম আৰু সঙ্খ্যা</td>
                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                <span style="font-family:Vrinda;">  পৰিস্হিতিতন্ত্ৰ </span>/
                                বুৰঞ্জীপ্রসিদ্ধ
                                মাটিৰ বিৱৰণ 
                                আৰু কালি
                                (বি-ক-লে)</td>
                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                মন্তব্য</td>

                        </tr>
                        <tr>
                            <td align="center" valign="top" height="100">মাটি কেনেদৰে ব্যৱহৃত</td>
                            <td align="center" valign="top" height="100">কালি <p>(বি-ক-লে)</p></td>
                            <td align="center" valign="top" height="100">ক&#39;ৰপৰা পানী পায় </td>
                            <td align="center" valign="top" height="100">ফছলৰ নাম </td>
                            <td align="center" valign="top" height="100">কালি <p>(বি-ক-লে)</p></td>
                            <td align="center" valign="top" height="100">একাধিক ফছলী মাটিৰ কালি</td>

                        </tr>

                        <tr>

                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৭</font></span></td>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৮</font></span></td>
                            <td STYLE="width:150px" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৯</font></span></td>
                            <td STYLE="width:80px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১০</font></span></td>
                            <td STYLE="width:150px" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১১</font></span></td>
                            <td STYLE="width:40px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><font size="3">১ম</font></p>
                                <p style="margin-top: 0; margin-bottom: 0"><font size="3">২য়</font></p>
                                <p style="margin-top: 0; margin-bottom: 0"><font size="3">৩য়</font></td>
                            <td STYLE="width:60px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১২</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৮</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৪</font></span></td>
                            <td STYLE="width:50px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৩</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৯</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৫</font></span></td>
                            <td STYLE="width:57.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৪</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২০</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৬</font></span></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৫</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২১</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৭</font></span></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৬</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২২</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0 "width:120px;"><span lang="as">
                                        <font size="3">২৮</font></span></td>
                            <td STYLE="width:47.5px"align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">১৭</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৩</font></span></p>
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">২৯</font></span></td>
                            <td STYLE="width:60px"  align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৩০</font></span></td>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৩১</font></span></td>
                            <td STYLE="width:15%" align="center" valign="top" height="38">
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
                                        <font size="3">৩২</font></span></td>        
                        </tr>
                        <tr>
                            <?php include('pattadars.php'); ?>
                            <!-------------------------NEW MODIFIED CHITHA COL 8----------------------------->
                            <?php include('col8.php'); ?>
                            <td	rowspan="3" valign="top" height="409"id=chitha_col_9 ><!--Tenant Descr.-->

                                <?php
                                if (isset($chithainf['tenant'])) {
                                    foreach ($chithainf['tenant'] as $tenantdesc):
                                        if ($tenantName = $tenantdesc->tenant_name != '') {
                                            $tenantName = $tenantdesc->tenant_name;
                                        } else {
                                            $tenantName = "";
                                        }
                                        if ($tenantdesc->tenants_father != '') {
                                            $tenantsFather = $tenantdesc->tenants_father;
                                        } else {
                                            $tenantsFather = "";
                                        }
                                        if ($tenantdesc->tenants_add1 != "") {
                                            $tenantsadd1 = $tenantdesc->tenants_add1;
                                        } else {
                                            $tenantsadd1 = "";
                                        }
                                        if ($tenantdesc->tenants_add2 != "") {
                                            $tenantsadd2 = $tenantdesc->tenants_add2;
                                        } else {
                                            $tenantsadd2 = "";
                                        }

                                        if ($tenantdesc->tenants_add3 != "") {
                                            $tenantsadd3 = $tenantdesc->tenants_add3;
                                        } else {
                                            $tenantsadd3 = "";
                                        }

                                        echo $tenantName . '<br>' . $tenantsFather . '<br>' . $tenantsadd1 . '<br>' . $tenantsadd2 . '<br>' . $tenantsadd3;
                                    endforeach;
                                }
                                ?>
                            </td>
                            <td  valign="top" rowspan="3" height="409"id=chitha_col_10><!--Tenant TYPE & NO.-->
                                <?php
                                foreach ($chithainf['tenant'] as $tenantdesc):
                                    if ($tenantdesc->type_of_tenant != "") {
                                        $type_of_tenant = $tenantdesc->type_of_tenant;
                                    } else {
                                        $type_of_tenant = "";
                                    }

                                    if ($tenantdesc->khatian_no != "") {
                                        $khatian_no = $tenantdesc->khatian_no;
                                    } else {
                                        $khatian_no = "";
                                    }
                                    if ($tenantdesc->revenue_tenant != "") {
                                        $revenue_tenant = $tenantdesc->revenue_tenant;
                                    } else {
                                        $revenue_tenant = "";
                                    }

                                    if ($tenantdesc->crop_rate != "") {
                                        $crop_rate = $tenantdesc->crop_rate;
                                    } else {
                                        $crop_rate = " ";
                                    }
                                    if ($tenantdesc->tenant_type != "") {
                                        $tenant_type = $tenantdesc->tenant_type;
                                    } else {
                                        $tenant_type = "";
                                    }


                                    echo $type_of_tenant . '<br>' . $khatian_no . '<br>' . $revenue_tenant . '<br>' . $crop_rate;
                                endforeach;
                                ?>



                            </td>
                            <td	rowspan="3" valign="top" height="409" id=chitha_col_11><!--Sub Tenant-->
                                <?php
                                foreach ($chithainf['subtenant'] as $subtenant):
                                    if ($subtenant->subtenant_name != "") {
                                        $subtenantName = $subtenant->subtenant_name;
                                    } else {
                                        $subtenantName = "";
                                    }
                                    if ($subtenant->subtenants_father != "") {
                                        $subtenantfatherName = $subtenant->subtenants_father;
                                    } else {
                                        $subtenantfatherName = "";
                                    }
                                    if ($subtenant->subtenants_add1 != "") {
                                        $subtenantadd1 = $subtenant->subtenants_add1;
                                    } else {
                                        $subtenantadd1 = "";
                                    }
                                    if ($subtenant->subtenants_add2 != "") {
                                        $subtenantadd2 = $subtenant->subtenants_add2;
                                    } else {
                                        $subtenantadd2 = "";
                                    }
                                    if ($subtenant->subtenants_add3 != "") {
                                        $subtenantadd3 = $subtenant->subtenants_add3;
                                    } else {
                                        $subtenantadd3 = "";
                                    }

                                endforeach
                                ?>
                            </td>

                            <!-- THREE LATEST YEARS ARE FOUND OUT FROM TABLES Chitha_MCrop AND Chitha_Noncrop -->


                            <!----------------------------1ST YEAR ---------------------------------------->
                            <td align="center" valign="top" height="100" id=crop_year_no_1><!--YEAR NO-->
                                <?php
                                rsort($chithainf['years']);

                                foreach ($chithainf['years'] as $key => $yr):

                                    echo $chithainf['years'][$key]['year'] . '<br>';

                                endforeach;
                                ?>
                            </td>

                            <td align="center" valign="top" height="100"  id=chitha_col_12>&nbsp;<!--NONCROP TYPE 1st YEAR-->
                                <?php
                                foreach ($chithainf['noncrp'] as $key => $noncrop):
                                    echo $chithainf['noncrp'][$key]['type_of_used_noncrp'] . "<br>";
                                endforeach;
                                ?>

                            </td>

                            <td align="center" valign="top" height="100" style="font-size:.7em;font-weight:bold" id=chitha_col_13 >&nbsp;<!--NONCROP LAND 1st YEAR-->

                                <?php
                                foreach ($chithainf['noncrp'] as $key => $noncrop):
                                    echo $chithainf['noncrp'][$key]['noncrp_b'] . '-' . $chithainf['noncrp'][$key]['noncrop_k'] . '-' . $chithainf['noncrp'][$key]['noncrop_lc'] . '<br>';

                                endforeach;
                                ?>


                            </td>

                            <!-- CROP SOURCE OF WATER 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_14 style="font-size:.7em;font-weight:bolder">&nbsp;
                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):
                                    //if (sizeof($chithainf['noncrp']) > 0) {
                                    echo $chithainf['mcrp'][$key]['sourceofwater'] . '<br>';
                                    // }

                                endforeach;
                                ?>

                            </td>
                            <!-- CROP TYPE 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_15 style="font-size:.7em;font-weight:bolder">
                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):

                                    echo $chithainf['mcrp'][$key]['cropname'] . '<br>' .
                                    '(' . $chithainf['mcrp'][$key]['crop_category'] . ')<br>';

                                endforeach;
                                ?>
                            </td>
                            <!-- CROP LAND 1st YEAR-->
                            <td align="center" valign="top" height="100"  id=chitha_col_16 style="font-size:.7em;font-weight:bold">

                                <?php
                                foreach ($chithainf['mcrp'] as $key => $mcrop):

                                    echo $chithainf['mcrp'][$key]['mcrp_b'] . '-' . $chithainf['mcrp'][$key]['mcrop_k'] . '-' . $chithainf['mcrp'][$key]['mcrop_lc'] . '<br>';
                                endforeach;
                                ?>

                            </td>
                            <!-- MULTIPLE CROP LAND 1st YEAR-->
                            <td align="center" valign="top" height="100" id=chitha_col_17 style="font-size:.7em;font-weight:bold">

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
                            <td align="left" valign="top"  height="409" rowspan=3 id=chitha_col_history style="font-size:.7em;font-weight:bolder"> <!--historic-->
                                <?php
                                foreach ($chithainf['archeo'] as $key => $archeo):

                                    echo '<u>' . $chithainf['archeo'][$key]['hist_description_nme'] . ': </u><br>'
                                    . $chithainf['archeo'][$key]['archeo_decribed'] . '<br>' . '(' . $chithainf['archeo'][$key]['archeo_b'] . '-' . $chithainf['archeo'][$key]['archeo_k'] . '-' . $chithainf['archeo'][$key]['archeo_lc'] . ')' . '<hr>';
                                endforeach;
                                ?>
                            </td>
                            <!--===========================================================================
                            ----------------->
                            <?php $current_code = $chithainf['dag_no']; ?>
                            <?php include 'col31_for_newchitha.php'; ?>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</div>