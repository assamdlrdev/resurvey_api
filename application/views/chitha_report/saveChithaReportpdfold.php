
<?php

include("C:\mpdf\mpdf.php");
$mpdf = new mPDF('', 'Legal-L');


$html = '
<body>


 <p align="left" style="margin-top: 0; margin-bottom: 0" align="center">
        <font size="4" face="courier">Assam Schedule XXXVII,Form No. 30</font></p>
    <center>
        <p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" size="5">Chitha for Surveyed Villages /</font></p>
        <p align="center"><font size="5">জৰীপ হোৱা গাঁৱৰ চিঠা</font></p>
        <br>
';
$ht1 = '
<link rel="stylesheet" href="./app.css">
 <table align="center" border="1">
            <tr>
                <td width="20%" style="text-align: center;"><!--DISTRICT-->
                    <p>
                        <font size="5" color="#000080">জিলা : :' . $location['dist'] . ' </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--SUB-DIVISION-->
                    <p>
                        <font size="5" color="#000080">&#2478;&#2489;&#2453;&#2497;&#2478;া: ' . $location['sub'] . '   </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--CIRCLE-->
                    <p>
                        <font size="5" color="#000080">&#2458;&#2453;&#2509;&#2544;:' . $location['cir'] . ' </font>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="20%" style="text-align: center;"><!--mouza-->
                    <p class="uni_text">
                        <font size="5"  font-family:Open Sans; font-weight:normal>&#2478;&#2508;&#2460;া:' . $location['mouza'] . '  </b></font> 
                    </p>
                </td>

                <td width="20%" style="text-align: center;"><!--lot-->
                    <p class="uni_text">
                        <font size="5"  font-family:Open Sans; font-weight:normal>&#2482;&#2494;&#2463;<b>:' . $location['lot'] . '   </b> </font>
                    </p>
                </td>
                <td width="20%" style="text-align: center;"><!--vill-->
                    <p>
                        <font size="5" color="#000080">&#2455;&#2494;&#2451;&#2433;<span>/ </span>&#2458;&#2489;&#2544;: ' . $location['vill'] . '   </font>
                    </p>
                </td>

            </tr>
        </table>
        <br>
';

// Outputs: A 'quote' is &lt;b&gt;bold&lt;/b&gt;
// Outputs: A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt;
//$a=htmlentities($str, ENT_QUOTES | ENT_IGNORE, "UTF-8");
$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont = true;
$mpdf->use_kwt = FALSE;
$mpdf->WriteHTML($html);
$mpdf->SetFont('vrinda');
$mpdf->WriteHTML($ht1);
foreach ($data as $chithainf):
    //$mpdf->AddPage();

    if ($chithainf['old_dag_no'] != "") {
        $dg = $chithainf['old_dag_no'] . '/' . $chithainf['dag_no'];
    } else {
        $dg = $chithainf['dag_no'];
    }
    $total = $chithainf['dag_area_b'] * 100 + $chithainf['dag_area_k'] * 20 + $chithainf['dag_area_lc'];
    $total /= 100;
    $ht2 = '<div align="center" >
                    <table border="1" align="center">
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
						
                        <tr style="border-left: 1px solid black;">
                            <td align="center" style="border-right: 1px solid black;">১</td>
                           <td align="center" style="border-right: 1px solid black;" colspan="2">২</td>
                            <td align="center" style="border-right: 1px solid black;">৩</td>
                            <td align="center" style="border-right: 1px solid black;">৪</td>
                            <td align="center" style="border-right: 1px solid black;">৫</td>
                            <td align="center" style="border-right: 1px solid black;">৬</td>
                        </tr>
                        <tr style="border-left: 1px solid black;">
                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black;"> 
                               
                            
                                ' . $dg . '
                            </td>
                            <td align="center" colspan="2" style="border-bottom: 1px solid black; border-right: 1px solid black;"> 
                                ' . $chithainf['land_type'] . '
                                
                            </td>
                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black;"> 
                               
                 ' . $chithainf['dag_area_b'] . '-' . $chithainf['dag_area_k'] . '-' . round($chithainf['dag_area_lc'], 2) . '
                               
                            </td>
                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black;"> 
                                ' . $chithainf['patta_no'] . '&nbsp;' . ',' . $this->session->userdata('patta_type') . '
                                
                            </td>
                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black;"> 
                             ' . round($chithainf['dag_revenue'], 2) . '
                               
                            </td>
                            <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black"> 
                                ' . round($chithainf['dag_localtax'], 2) . '
                               
                                
                            </td>
                        </tr>
                    </table>
                    
                    	<table class="table table-bordered"  BORDER="1" bordercolor="#000000" STYLE="width:100%; border-collapse:collapse" cellPadding=0 cellSpacing=0 height="580">
                        <tr>
                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                পট্টাদাৰৰ নাম, পিতাৰ নাম আৰু ঠিকনা				
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                নামজাৰী হবলগী<span lang="as">য়া</span> দখলদাৰৰ নাম, পিতাৰ নাম, ঠিকনা</td>

                            <td align="center" rowspan="2" valign="top" height="127"  width="9%">
                                ৰায়ত/ আধিয়াৰৰ নাম, পিতাৰ নাম, ঠিকনা
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127"  width="7%">
                                ৰায়তৰ প্ৰকাৰ/ খতিয়ন নং, খাজানা বা ফছলৰ নিৰিখ</td>

                            <td align="center" rowspan="2" valign="top" height="127"  width="9%">
                                তলতীয়া ৰায়তৰ নাম, পিতাৰ নাম, ঠিকনা
                            </td>

                            <td align="center" rowspan="2" valign="top" height="127"  width="3%">
                                বছৰ</td>

                            <td align="center" colspan="2" valign="top" height="25"  width="8%">
                                বেফছলী মাটিৰ কালি
                            </td>

                            <td align="center" colspan="4" valign="top" height="25" width="14%">
                                ফছলী মাটিৰ কালি</td>

                            <td align="center" rowspan="2" valign="top" height="127"  width="7%">
                                ফলৰ গছৰ নাম আৰু সঙ্খ্যা</td>

                            <td align="center" rowspan="2" valign="top" height="127" width="15%">
                                মন্তব্য</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" height="100">মাটি কেনেদৰে ব্যৱহৃত</td>
                            <td align="center" valign="top" height="100">কালি <p>(বি-ক-লে)</p></td>
                            <td align="center" valign="top" height="100">ক&#39;ৰপৰা পানী পায় </td>
                            <td align="center" valign="top" height="100">ফছলৰ নাম </td>
                            <td align="center" valign="top" height="100">কালি <p>(বি-ক-লে)</p></td>
                            <td align="center" valign="top"  height="100">একাধিক ফছলী মাটিৰ কালি</td>
                              
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
                                <p style="margin-top: 0; margin-bottom: 0"><span lang="as">
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
                        </tr>
                        
               ';
    $mpdf->WriteHTML($ht2);
    // include('pattadars.php');
    //include('col8.php');
    $mpdf->WriteHTML('<tr style="page-break-inside: auto;">');

    $mpdf->WriteHTML('<td> ');

    $count = 1;
    foreach ($chithainf['pattadars'] as $p):

        if (($p['new_pdar_name'] == 'N') && ($p['p_flag'] == '1')) {

            $mpdf->WriteHTML('<p style="color:red"><strike>(' . utf8_encode($count++) . ')' . $p['pdar_name']);

            //$mm=$this->utilityclass->get_relation($p['pdar_relation']);
            $mpdf->WriteHTML('</strike></p>');
            $mpdf->WriteHTML('<p style="color:red;font-style: italic"><strike>(');
            $mpdf->WriteHTML('' . $this->utilityclass->get_relation($p['pdar_relation']) . '-' . $p['pdar_father']);
            $mpdf->WriteHTML('</strike></p>');
        } elseif (($p['new_pdar_name']) == 'N' && ($p['p_flag'] == null)) {
            $mpdf->WriteHTML('<p style="color:red;font-style: italic">' . $count++ . ')' . $p['pdar_name'] . '</p>');
            $mpdf->WriteHTML('<p style="color:red;font-style: italic">(' . $this->utilityclass->get_relation($p['pdar_relation']) . '' . $p['pdar_father'] . ')</p>');
        } elseif (($p['new_pdar_name'] == null) && ($p['p_flag'] == '1')) {

            $mpdf->WriteHTML('<p style="color:red;font-style: italic">');
            $mpdf->WriteHTML('<strike>');
            $mpdf->WriteHTML('' . $count++);
            $mpdf->WriteHTML(')' . $p['pdar_name']);
            $mpdf->WriteHTML('</strike></p>');

            $mpdf->WriteHTML('<p style="color:red"><strike>' . $this->utilityclass->get_relation($p['pdar_relation']) . $p['pdar_father'] . ')</strike></p>');
        } elseif ($p['new_pdar_name'] == 'N') {

            $mpdf->WriteHTML('<p style="color:red;font-style: italic">' . $count++ . ")" . $p['pdar_name'] . ' </p>');
            $mpdf->WriteHTML('<p style="color:red;font-style: italic">(' . $this->utilityclass->get_relation($p['pdar_relation']) . " " . $p['pdar_father'] . ')</p>');
        } elseif ($p['new_pdar_name'] != 'N') {

            $mpdf->WriteHTML('<p style="color:blue">' . $count++ . ")" . $p['pdar_name'] . '</p>');
            $mpdf->WriteHTML('<p style="color:blue">(' . $this->utilityclass->get_relation($p['pdar_relation']) . " " . $p['pdar_father'] . ')</p>');
        }

        if (isset($p['pdar_address1'])) {

            $mpdf->WriteHTML('<p>ঠিকনা (');
            $mpdf->WriteHTML('' . $p['pdar_address1'] . ', ' . $p['pdar_address2']);
            $mpdf->WriteHTML(')</p>');
        }

    endforeach;



    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
    if (isset($chithainf['col8'])) {
        foreach ($chithainf['col8'] as $clmn8):
            //echo "<hr>";
            $co_order_date1 = $clmn8['co_ord_date'];
            $case_no = $clmn8['case_no'];
            $col8order_cron_no = $clmn8['col8order_cron_no'];
            $order_type = $clmn8['order_type'];
            $co_order_date = strtotime($co_order_date1);
            $formatDate = date("d/m/Y", $co_order_date);
            $order_type_code = $clmn8['order_type_code'];
            $nature_trans_code = $clmn8['nature_trans_code'];
            $mut_land_area_b = $clmn8['mut_land_area_b'];
            $mut_land_area_k = $clmn8['mut_land_area_k'];
            $mut_land_area_lc = $clmn8['mut_land_area_lc'];

            $mpdf->WriteHTML('<p>চক্ৰ বিষয়াৰ  <br>' . $formatDate . ' তাৰিখৰ ');

            $bigha = 0;
            $katha = 0;
            $lesa = 0;
            if ($order_type_code == "01") {

                if ($mut_land_area_b != '0') {
                    $bigha = $mut_land_area_b . 'বিঘা ';
                } else {
                    $bigha = "";
                }

                if ($mut_land_area_k != '0') {
                    $katha = $mut_land_area_k . 'কঠা ';
                } else {
                    $katha = "";
                }
                if ($mut_land_area_lc != '0') {
                    $lesa = $mut_land_area_lc . 'লেছা ';
                } else {
                    $lesa = "";
                }
            } else if ($order_type_code == "02") {

                if ($mut_land_area_b != '0') {
                    $bigha = $mut_land_area_b . 'বিঘা ';
                } else {
                    $bigha = "";
                }

                if ($mut_land_area_k != '0') {
                    $katha = $mut_land_area_k . 'কঠা ';
                } else {
                    $katha = "";
                }
                if ($mut_land_area_lc != '0') {
                    $lesa = $mut_land_area_lc . 'লেছা ';
                } else {
                    $lesa = "";
                }
            }

            $mpdf->WriteHTML('' . $order_type . ' নং ' . $case_no . '-ৰ ' . ' হুকুমমৰ্মে এই দাগৰ ' . $bigha . $katha . $lesa . ' মাটি ');
            if ($order_type_code != "02") {
                $mpdf->WriteHTML(' ' . $this->utilityclass->getTransferType($clmn8['nature_trans_code']) . " ");
            }
            $count = 1;
            $howmanys = sizeof($clmn8['inplace']) - 1;
            foreach ($clmn8['inplace'] as $in) {
                $mpdf->WriteHTML('' . $in['inplace_of_name'] . 'ৰ');
                if ($count < sizeof($clmn8['inplace']) - 1) {
                    switch ($in['inplaceof_alongwith']) {
                        case 'i':
                            $mpdf->WriteHTML(' স্হলত ');
                            break;
                        case 'a':
                            $mpdf->WriteHTML('  লগত  ');
                            break;
                    }
                    echo " , ";
                    $count++;
                } elseif ($count == sizeof($clmn8['inplace']) - 1) {
                    switch ($in['inplaceof_alongwith']) {
                        case 'i':
                            $mpdf->WriteHTML(' স্হলত ');
                            break;
                        case 'a':
                            $mpdf->WriteHTML(' লগত  ');
                            break;
                    }
                    $mpdf->WriteHTML(' আৰু ');
                    $count++;
                } else {
                    switch ($in['inplaceof_alongwith']) {
                        case 'i':
                            $mpdf->WriteHTML(' স্হলত ');
                            break;
                        case 'a':
                            $mpdf->WriteHTML(' লগত  ');
                            break;
                    }
                    echo " ";
                }
            }


            $count = 1;
            $howmany = sizeof($clmn8['occup']) - 1;
            foreach ($clmn8['occup'] as $in) {
                $r = "";
                switch ($in['occupant_fmh_flag']) {
                    case 'm':
                        $r = "মাতৃ";
                        break;
                    case 'f':
                        $r = "পিতৃ";
                        break;
                    case 'h':
                        $r = "পতি";
                        break;
                    case 'w':
                        $r = "পত্নী";
                        break;
                    case 'a':
                        $r = "অধ্যক্ষ মাতা";
                        break;
                    default:
                        $r = "অভিভাৱক";
                }
                $mpdf->WriteHTML('' . $in['occupant_name'] . " (" . $r . $in['occupant_fmh_name'] . ")");
                if ($count < sizeof($clmn8['occup']) - 1) {
                    $mpdf->WriteHTML(" , ");
                    $count++;
                } elseif ($count == sizeof($clmn8['occup']) - 1) {
                    $mpdf->WriteHTML(" আৰু ");
                    $count++;
                } else {
                    echo " ";
                }
            }
            if ($clmn8['order_type_code'] == '01') {
                $mpdf->WriteHTML(" নামত নামজাৰী কৰা হ’ল | ");
            } else if ($clmn8['order_type_code'] == '02') {
                $mpdf->WriteHTML(" নামত " . $clmn8['occup'][0]['new_dag_no'] . " নং দাগ " .
                        $clmn8['occup'][0]['new_patta_no'] . " ম্যাদী পট্টা কৰা হল | ");
            }

            if (($clmn8['rajah'] != 0) || ($clmn8['rajah'] == 'y')) {
                $mpdf->WriteHTML("<p style='color:blue'>( ৰাজহ আদলত )</p>");
            }



            if (($clmn8['deed_reg_no'] != "")) {
                $mpdf->WriteHTML("<p class='text-danger'>Registration</p>");
                $mpdf->WriteHTML("Deed No:" . $clmn8['deed_reg_no'] . "<br>");
                $mpdf->WriteHTML("Deed Value:" . $clmn8['deed_value'] . "<br>");
                $interval = date_diff(date_create('01-01-1970'), date_create($clmn8['deed_date']));
                if ($interval->days > 0) {
                    $mpdf->WriteHTML("Deed Date:" . date('d-m-y', strtotime($clmn8['deed_date'])) . ") ");
                }
            }
            $mpdf->WriteHTML('<p><u class="text-danger">লাট মণ্ডল :</u><br>(' . $clmn8['lm_name'] . ')</p>');
            $mpdf->WriteHTML('<p><u class="text-danger">চক্ৰ বিষয়া :</u><br>(' . $clmn8['username'] . ')</p>');


        endforeach;
    }
    if (isset($chithainf['objection'])) {
        foreach ($chithainf['objection'] as $objection) {
            //var_dump($objection);
            $mut_type = $objection['mut_type'];
            $objection_case_no = $objection['objection_case_no'];
            $prev_fm_ca_no = $objection['prev_fm_ca_no'];
            $submission_date = $objection['submission_date'];
            $obj_name = $objection['obj_name'];
            $co_id = $objection['co_id'];
            $regist_date = $objection['regist_date'];
        }
        $coname = $this->utilityclass->getSelectedCOName($this->session->userdata('dist_code'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'), $co_id);
        //var_dump($coname);
        $mpdf->WriteHTML('' . $coname->username);
        $mut_name = $this->utilityclass->getMutationTypeObject($mut_type);
        //var_dump($mut_name);
        $mpdf->WriteHTML("<hr>");
        $mpdf->WriteHTML('' . $obj_name);
        $mpdf->WriteHTML(" ৰ নামত " . date('d-m-y', strtotime($regist_date)) . " তাৰিখে দিয়া চিঠি " . $mut_name->order_type . "  হুকুম " . $objection_case_no . " নং অভিযোগ সাপেক্ষে আজিৰ তাৰিখত (" . date('d-m-y', strtotime($submission_date)) . ") জাৰী কৰা হ’ল  |");
        $mpdf->WriteHTML("<br><u class='text-danger'>স্বা (চক্ৰ বিষয়া )</u><br>");
        $mpdf->WriteHTML('' . $coname->username);
    }


    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
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

            if ($tenantdesc['tenants_add3'] != "") {
                $tenantsadd3 = $tenantdesc['tenants_add3'];
            } else {
                $tenantsadd3 = "";
            }

            $mpdf->WriteHTML('' . $tenantName . '<br>' . $tenantsFather . '<br>' . $tenantsadd1 . '<br>' . $tenantsadd2 . '<br>' . $tenantsadd3);
        endforeach;
    }
    $mpdf->WriteHTML('</td>');

    $mpdf->WriteHTML('<td>- ');
    if (isset($chithainf['tenant'])) {
        foreach ($chithainf['tenant'] as $tenantdesc):
            if ($tenantdesc['tenant_type'] != "") {
                $type_of_tenant = $tenantdesc['tenant_type'];
            } else {
                $type_of_tenant = "";
            }

            if ($tenantdesc['khatian_no'] != "") {
                $khatian_no = $tenantdesc['khatian_no'];
            } else {
                $khatian_no = "";
            }
            if ($tenantdesc['revenue_tenant'] != "") {
                $revenue_tenant = $tenantdesc['revenue_tenant'];
            } else {
                $revenue_tenant = "";
            }

            if ($tenantdesc['crop_rate'] != "") {
                $crop_rate = $tenantdesc['crop_rate'];
            } else {
                $crop_rate = " ";
            }
            if ($tenantdesc['tenant_type'] != "") {
                $tenant_type = $tenantdesc['tenant_type'];
            } else {
                $tenant_type = "";
            }


            $mpdf->WriteHTML('' . $type_of_tenant . '<br>' . $khatian_no . '<br>' . $revenue_tenant . '<br>' . $crop_rate);
        endforeach;
    }
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
    if (isset($chithainf['subtenant'])) {
        foreach ($chithainf['subtenant'] as $subtenant):
            if ($subtenant['subtenant_name'] != "") {
                $subtenantName = $subtenant['subtenant_name'];
            } else {
                $subtenantName = "";
            }
            if ($subtenant['subtenants_father'] != "") {
                $subtenantfatherName = $subtenant['subtenants_father'];
            } else {
                $subtenantfatherName = "";
            }
            if ($subtenant['subtenants_add1'] != "") {
                $subtenantadd1 = $subtenant['subtenants_add1'];
            } else {
                $subtenantadd1 = "";
            }
            if ($subtenant['subtenants_add2'] != "") {
                $subtenantadd2 = $subtenant['subtenants_add2'];
            } else {
                $subtenantadd2 = "";
            }
            if ($subtenant['subtenants_add3'] != "") {
                $subtenantadd3 = $subtenant['subtenants_add3'];
            } else {
                $subtenantadd3 = "";
            }

        endforeach;
    }
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td> ');
    rsort($chithainf['years']);

    foreach ($chithainf['years'] as $key => $yr):

        $mpdf->WriteHTML('' . $chithainf['years'][$key]['year'] . '<br>');

    endforeach;
    $mpdf->WriteHTML('</td>');




    $mpdf->WriteHTML('<td> ');
    foreach ($chithainf['noncrp'] as $key => $noncrop):
        $mpdf->WriteHTML('' . $chithainf['noncrp'][$key]['type_of_used_noncrp']);
    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
    foreach ($chithainf['noncrp'] as $key => $noncrop):
        $mpdf->WriteHTML('' . $chithainf['noncrp'][$key]['noncrp_b'] . '-' . $chithainf['noncrp'][$key]['noncrop_k'] . '-' . $chithainf['noncrp'][$key]['noncrop_lc'] . '<br>');

    endforeach;

    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td> ');
    foreach ($chithainf['mcrp'] as $key => $mcrop):
        //if (sizeof($chithainf['noncrp']) > 0) {
        $mpdf->WriteHTML('' . $chithainf['mcrp'][$key]['sourceofwater'] . '<br>');
        // }

    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td> ');
    foreach ($chithainf['mcrp'] as $key => $mcrop):

        $mpdf->WriteHTML('' . $chithainf['mcrp'][$key]['cropname'] . '<br>' .
                '(' . $chithainf['mcrp'][$key]['crop_category'] . ')<br>');
    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
    foreach ($chithainf['mcrp'] as $key => $mcrop):

        $mpdf->WriteHTML('' . $chithainf['mcrp'][$key]['mcrp_b'] . '-' . $chithainf['mcrp'][$key]['mcrop_k'] . '-' . $chithainf['mcrp'][$key]['mcrop_lc'] . '<br>');
    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td>');
    foreach ($chithainf['mcrp_akeadhig'] as $key => $mcrop_ekadhig):

        $mpdf->WriteHTML('' . $chithainf['mcrp_akeadhig'][$key]['bigha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['katha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['lesa'] . '<br>');
    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td> ');
    foreach ($chithainf['fruit'] as $key => $fruit):

        $mpdf->WriteHTML('' . $chithainf['fruit'][$key]['fruitname'] . '<br>' . $chithainf['fruit'][$key]['no_of_plants'] . '<br>' . '(' . $chithainf['fruit'][$key]['fbigha'] . '-' . $chithainf['fruit'][$key]['fkatha'] . '-' . $chithainf['fruit'][$key]['flesa'] . ')' . '<br>');
    endforeach;
    $mpdf->WriteHTML('</td>');
    $mpdf->WriteHTML('<td> ');
    if (isset($chithainf['sro'])) {
        $mpdf->WriteHTML("<u class='text-danger'>SRO টোকা</u>");
        foreach ($chithainf['sro'] as $key => $sr):
            $newDatesro = date("d-m-Y", strtotime($chithainf['sro'][$key]['date_of_deed']));
            $mpdf->WriteHTML('<p>' . $chithainf['sro'][$key]['name_of_sro'] . 'ট' . '&nbsp;' . $newDatesro . 'তাৰিখে' . '&nbsp;' . $chithainf['sro'][$key]['deed_no'] . 'নং দলিল যোগে' . '&nbsp;' . $chithainf['sro'][$key]['reg_from_name'] . 'পৰা' . '&nbsp;' . $chithainf['sro'][$key]['reg_to_name'] . 'ৰ নামত' . '(' . $chithainf['sro'][$key]['dag_area_b'] . '-' . $chithainf['sro'][$key]['dag_area_k'] . '-' . $chithainf['sro'][$key]['dag_area_lc'] . ')' . 'মাটি' . '&nbsp;' . 'হস্তান্তৰ ' . '&nbsp;' . 'হয়.' . '</p>');


        endforeach;
    }
    $mpdf->WriteHTML(' <hr>');

    $order_count = 1;
    foreach ($chithainf['col31'] as $remark) {

        foreach ($remark as $r) {

            if (sizeof($r) > 0) {

                if ($r['ord_type_code'] == '06') {

                    $mpdf->WriteHTML("<p class='text-danger'><u>চক্ৰ বিষয়াৰ হুকুম নং : </u></p>" . $r['innerdata52'][0]->ord_no . " নাম সংশোধনীকৰণ হুকুমমৰ্মে এই দাগৰ " . $r['innerdata52'][0]->infavor_of_name . "'ৰ  নাম  " . $r['innerdata52'][0]->infavor_of_corrected_name . " কৰা হ'ল");

                    $mpdf->WriteHTML('<p>লাট মণ্ডল :</p>');
                    $mpdf->WriteHTML('' . $r['lmname']);
                }
                if ($r['remark_type_code'] == '08') {

                    $mpdf->WriteHTML("<p class='text-danger'><u>চক্ৰ বিষয়াৰ হুকুম নং : </u></p>" . $r['case_no'] . " শ্রেণী সংশোধনীকৰণ প্রস্তাব " . "উপয়াক্ত মহোদয়ে " . date('d-m-Y', strtotime($r['dc_approval_date'])) . " তাৰিখে দিয়া অনুমোদন মৰ্মে " . $r['patta_no'] . " নং পট্টাৰ " . $r['dag_no'] . " নং দাগৰ শ্রেণী " . $r['present_land_class'] . " পৰা " . $r['proposed_land_class'] . " লৈ পৰিবৰ্তন কৰা হ'ল ।<hr>");
                }
                if ($r['remark_type_code'] == '09') {


                    $mpdf->WriteHTML("<p class='text-danger'><u>হুকুম নং : </u></p> চক্ৰ বিষয়াৰ " . $r['case_no'] . " নং NR কেছৰ প্ৰস্তাৱৰ " . date('d-m-Y', strtotime($r['order_date'])) . "  তাৰিখে দিয়া অনুমোদন মৰ্মে " . $r['patta_no'] . " নং পট্টা আৰু " . $r['dag_no'] . "  নং দাগৰ পপট্টাৰ প্ৰকাৰ একচণাৰ পৰা চৰকাৰীলৈ পৰিবৰ্ত্তন কৰাৰ হুকুম দিয়া হল ।<hr>");
                }


                if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '03')) {

                    $mpdf->WriteHTML('<u class="text-danger"> "হুকুম নং: " ' . $order_count++ . '<br></u>');
                    $mpdf->WriteHTML('<p>চক্ৰ বিষয়াৰ  <br>');
                    $mpdf->WriteHTML('' . date('d-m-Y', strtotime($r['order_date'])));
                    $mpdf->WriteHTML('তাৰিখৰ');

                    $order_type = $r['ord_type_code'];
                    $mpdf->WriteHTML('' . $this->utilityclass->getOfficeMutType($order_type) . " নং  ");

                    $mpdf->WriteHTML('' . $r['ord_no'] . " 'ৰ হুকুমমৰ্মে এই দাগৰ ");


                    $mpdf->WriteHTML('' . $r['bigha'] . " বিঘা ");
                    $mpdf->WriteHTML('' . $r['katha'] . " কঠা ");
                    $mpdf->WriteHTML('' . round($r['lessa'], 2) . " লেছা মাটি ");
                    $mpdf->WriteHTML('' . $this->utilityclass->getTransferType($r['by_right_of']) . " ");

                    $count = 1;
                    $howmany = sizeof($r['alongwith_name']) - 1;
                    foreach ($r['alongwith_name'] as $al) {
                        echo $al['alongwithname'];
                        if ($count < sizeof($r['alongwith_name']) - 1) {
                            $mpdf->WriteHTML(" , ");
                            $count++;
                        } elseif ($count == sizeof($r['alongwith_name']) - 1) {
                            $mpdf->WriteHTML(" আৰু ");
                            $count++;
                        } else {
                            $mpdf->WriteHTML(" ");
                        }
                    }
                    if (sizeof($r['alongwith_name']) != '0') {
                        $mpdf->WriteHTML("' ৰ লগত ");
                    }
                    $count = 1;
                    $howmany = sizeof($r['inplace_of_name']) - 1;
                    foreach ($r['inplace_of_name'] as $al) {

                        $mpdf->WriteHTML('' . $al['inplace_of_name']);
                        if ($count < sizeof($r['inplace_of_name']) - 1) {
                            $mpdf->WriteHTML(" , ");
                            $count++;
                        } elseif ($count == sizeof($r['inplace_of_name']) - 1) {
                            $mpdf->WriteHTML(" আৰু ");
                            $count++;
                        } else {
                            $mpdf->WriteHTML(" ");
                        }
                    }
                    if (sizeof($r['inplace_of_name']) != '0') {
                        $mpdf->WriteHTML("'ৰ স্হলত ");
                    }

                    $count = 1;
                    $howmany = sizeof($r['infav']) - 1;
                    foreach ($r['infav'] as $in) {

                        $mpdf->WriteHTML('' . $in['infavor_of_name']);
                        if ($count < sizeof($r['infav']) - 1) {
                            $mpdf->WriteHTML(" , ");
                            $count++;
                        } elseif ($count == sizeof($r['infav']) - 1) {
                            $mpdf->WriteHTML(" আৰু ");
                            $count++;
                        } else {
                            $mpdf->WriteHTML(" ");
                        }
                    }

                    if ($r['ord_type_code'] == '03') {

                        $mpdf->WriteHTML('ৰ নামত নামজাৰী কৰা হ’ল |');
                    }
                    $mpdf->WriteHTML('<p><u class="text-danger">লাট মণ্ডল :</u><br>(' . $r['lm_name'] . ')</p>');
                    $mpdf->WriteHTML('<p><u class="text-danger">চক্ৰ বিষয়া :</u><br>(' . $r['username'] . ')</p>');
                    $mpdf->WriteHTML('<p>');
                    if ($r['reg_deal_no'] != "") {
                        $mpdf->WriteHTML("Reg No (" . $r['reg_deal_no'] . ")");
                    }

                    $mpdf->WriteHTML('</p>');
                    $mpdf->WriteHTML('<p>');
                    if ($r['reg_date'] != "") {
                        $mpdf->WriteHTML("Reg Date (" . date('d-m-Y', strtotime($r['reg_date'])) . ")");
                    }

                    $mpdf->WriteHTML('</p>');
                    $mpdf->WriteHTML('<hr>');
                    if ($r['ord_type_code'] == '01') {

                        $mpdf->WriteHTML('<u class="text-danger" "হুকুম নং: "' . $order_count++ . '</u><br>');
                        $mpdf->WriteHTML('<p>চক্ৰ বিষয়াৰ  </p>');
                        $mpdf->WriteHTML('' . $r['ord_no'] . "  নং  ");

                        $order_type = $r['ord_type_code'];
                        $mpdf->WriteHTML('' . $this->utilityclass->getOfficeMutType($order_type) . " গোচৰৰ  ");
                        $mpdf->WriteHTML('' . date('d-m-Y', strtotime($r['order_date'])) . ' তাৰিখৰ হুকুমমৰ্মে');


                        if ($r['premi_chal_recpt'] != '003') {
                            $mpdf->WriteHTML('' . $r['patta_no'] . " নং একচনা পট্টাৰ আৰু " . $r['dag_no'] . " নং দাগৰ  ");
                            $mpdf->WriteHTML('' . $r['land_area_b'] . " বিঘা  " . $r['land_area_k'] . " কঠা  " . round($r['land_area_lc'], 2) . " লেছা মাটিৰ প্রিমিয়াম " . round($r['premium'], 2) . " টকা " . $r['premi_chal_recpt_no'] . " নং " . $r['premi_chal_name'] . " যোগে ");

                            $count = 1;
                            $howmany = sizeof($r['ord_onbehalf_of']) - 1;
                            foreach ($r['ord_onbehalf_of'] as $in) {
                                $mpdf->WriteHTML('' . $in['app_name']);
                                if ($count < sizeof($r['ord_onbehalf_of']) - 1) {
                                    $mpdf->WriteHTML(" , ");
                                    $count++;
                                } elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                    $mpdf->WriteHTML(" আৰু ");
                                    $count++;
                                } else {
                                    $mpdf->WriteHTML(" ");
                                }
                            }
                            $mpdf->WriteHTML('ৰ পৰা আদায় হোৱাত ');
                        }
                        $count = 1;
                        $howmany = sizeof($r['ord_onbehalf_of']) - 1;
                        foreach ($r['ord_onbehalf_of'] as $in) {
                            $mpdf->WriteHTML('' . $in['app_name']);
                            if ($count < sizeof($r['ord_onbehalf_of']) - 1) {
                                $mpdf->WriteHTML(" , ");
                                $count++;
                            } elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                $mpdf->WriteHTML(" আৰু ");
                                $count++;
                            } else {
                                $mpdf->WriteHTML(" ");
                            }
                        }
                        $mpdf->WriteHTML('ৰ নামত ' . $r['land_area_b'] . " বিঘা  " . $r['land_area_k'] . " কঠা  " . $r['land_area_lc'] . " লেছা " . ' মাটি  পৃঠক');
                        $mpdf->WriteHTML('' . $r['new_patta_no'] . " নং " . $r['patta_type'] . "  পট্টা আৰু " . $r['new_dag_no'] . ' নং দাগে ম্যাদীকৰণ কৰা হল |');
                        $mpdf->WriteHTML('<p><u class="text-danger">লাট মণ্ডল :</u><br>(' . $r['lm_name'] . ')</p>');
                        $mpdf->WriteHTML('<p><u class="text-danger">চক্ৰ বিষয়া :</u><br>(' . $r['username'] . ')</p>');
                    }

                    if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '04')) {
                        $howmany = sizeof($r['infav']);
                        if ($howmany != null) {

                            $mpdf->WriteHTML('<p><u class="text-danger"> "হুকুম নং: "' . $order_count++ . '</u></p>');
                            $mpdf->WriteHTML('<p>চক্ৰ বিষয়াৰ');
                            $mpdf->WriteHTML('' . date('d-m-Y', strtotime($r['order_date'])));
                            $mpdf->WriteHTML('তাৰিখৰ   ');

                            $order_type = $r['ord_type_code'];
                            $mpdf->WriteHTML('' . $this->utilityclass->getOfficeMutType($order_type) . " নং  ");

                            $mpdf->WriteHTML('' . $r['ord_no'] . " ৰ হুকুমমৰ্মে এই দাগৰ ");

                            $mpdf->WriteHTML('' . $r['bigha'] . " বিঘা ");
                            $mpdf->WriteHTML('' . $r['katha'] . " কঠা ");
                            $mpdf->WriteHTML('' . round($r['lessa'], 2) . " লেছা মাটি   ");
                            $count = 1;
                            $howmany = sizeof($r['infav']);
                            foreach ($r['infav'] as $in) {
                                $mpdf->WriteHTML('' . $in['infavor_of_name']);
                                if ($count < sizeof($r['infav']) - 1) {
                                    $mpdf->WriteHTML(" , ");
                                    $count++;
                                } elseif ($count == sizeof($r['infav']) - 1) {
                                    $mpdf->WriteHTML(" আৰু ");
                                    $count++;
                                } else {
                                    $mpdf->WriteHTML(" ");
                                }
                            }
                            $mpdf->WriteHTML('ৰ নামত ');
                            $mpdf->WriteHTML('' . $r['new_patta_no'] . " নং  পট্টা আৰু " . $r['new_dag_no'], ' নং দাগ কৰা হল |');
                            //  if ($r['ord_type_code'] == '04'): 
                            //endif;
                            $mpdf->WriteHTML('<p><u class="text-danger">লাট মণ্ডল :-</u>(' . $r['lm_name'] . ')</p>');
                            $mpdf->WriteHTML('<p><u class="text-danger">চক্ৰ বিষয়া :-</u>(' . $r['username'] . ')</p>');


                            $mpdf->WriteHTML('<hr>');
                        }
                    }
                    if ($r['ord_type_code'] == '01') {
                        if ($r['premi_chal_recpt'] == '003') {
                            $mpdf->WriteHTML("<hr><p class='text-danger'><u>টোকা :</u></p> আবেদনকাৰীয়ে প্রিমিয়াম আদায় নিদিয়া বাবে " . $r['premium'] . " টকা ৰাজহৰ বকেয়া হিচাবে আদায় লোৱা হওঁক ।");
                        }
                    }
                    // endif;
                }
            }
        }
    }


    if (isset($chithainf['lmnote'])) {
        $mpdf->WriteHTML("<u class='text-danger'>মণ্ডলৰ টোকা</u>");
        foreach ($chithainf['lmnote'] as $key => $enc) {

            $mpdf->WriteHTML('<p>' . $chithainf['lmnote'][$key]['lm_note'] . '</p>');
        }
    }



//if (isset($chithainf['col8'])) {
//var_dump($chithainf['encro']);
    if (isset($chithainf['encro'])) {
        $mpdf->WriteHTML('<u class="text-danger">বেদখলকাৰীৰ টোকা</u>');
        foreach ($chithainf['encro'] as $key => $enc) {
            $newDate = date("d-m-Y", strtotime($chithainf['encro'][$key]['encro_since']));

            $mpdf->WriteHTML('<p>' . $chithainf['encro'][$key]['encro_name'] . 'য়ে' . '&nbsp;' . '(' . $chithainf['encro'][$key]['encro_land_b'] . '-' . $chithainf['encro'][$key]['encro_land_k'] . '-' . $chithainf['encro'][$key]['encro_land_lc'] . ')' . 'মাটি' . '&nbsp;' . $newDate . 'তাৰিখৰ পৰা' . '&nbsp;' . $chithainf['encro'][$key]['land_used_by_encro'] . 'কাৰণত ব্যৱহাৰ কৰি আছে' . '</p>');
        }
    }


    $mpdf->WriteHTML('<hr>');

    foreach ($chithainf['archeo'] as $key => $archeo) {

        $mpdf->WriteHTML('<u>' . $chithainf['archeo'][$key]['hist_description_nme'] . ': </u><br>'
                . $chithainf['archeo'][$key]['archeo_decribed'] . '<br>' . '(' . $chithainf['archeo'][$key]['archeo_b'] . '-' . $chithainf['archeo'][$key]['archeo_k'] . '-' . $chithainf['archeo'][$key]['archeo_lc'] . ')' . '<hr>');
    }

    $mpdf->WriteHTML('</td>');

    $mpdf->WriteHTML('</tr>');

    $mpdf->WriteHTML('</table>');
    // if( !next( $chithainf ) ) {
    // echo 'Last Item';

    $mpdf->AddPage();
    //}

endforeach;




$mpdf->Output();
exit;
?>