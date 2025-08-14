<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chitha Report</title>
    <link rel="stylesheet" href="<?php base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?php base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?php base_url('assets/dist/css/adminlte.min.css') ?>">
    <style>
        *{
            font-family:'Firefly';
        }
        table,tr,td{
            border:1px solid black !important;
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <p align="left" style="margin-top: 0; margin-bottom: 0">
            <font size="4" face="courier">
                <?php echo($this->lang->line('assam_schedule_xxxvii_form_no_30')) ?>
            </font>
        </p>
        <center>
            <div id="table_head">
                <p style="margin-top: 1; margin-bottom: 0;text-align: center;">
                    <font face="Verdana" size="5">
                        Chitha for Surveyed Villages / <?php echo $this->lang->line('chitha_for_surveyed_villages'); ?>
                    </font>
                </p>
                <br>
            </div>
        </center>
        <?php $dag_no = substr($dag_no_lower,  0, -2); ?>
        <!-- <center> -->
            <div style="width: 100%;">
                <div style="width: 100%; display:flex; margin-left: auto; margin-right: auto; justify-content: center; align-items: center;">
                    <table class="table" style="border: 1px solid black; width: 100%;">
                        <tr>
                            <td style="text-align: center;"><!--DISTRICT-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('district')) ?> :<?php echo $location['dist']; ?> </font>
                                </p>
                            </td>
                            <td style="text-align: center;"><!--SUB-DIVISION-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('subdivision')) ?>: <?php echo $location['sub']; ?>   </font>
                                </p>
                            </td>
                            <td style="text-align: center;"><!--CIRCLE-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('circle')) ?>:<?php echo $location['cir']; ?> </font>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><!--mouza-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('mouza')) ?>:<?php echo $location['mouza']; ?>  </font>
                                </p>
                            </td>

                            <td style="text-align: center;"><!--lot-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('lot_no')) ?>:<?php echo $location['lot']; ?>    </font>
                                </p>
                            </td>
                            <td style="text-align: center;"><!--vill-->
                                <p>
                                    <font size="5" color="#000080"><?php echo($this->lang->line('vill_town')) ?>: <?php echo $location['vill']; ?>   </font>
                                </p>
                            </td>

                        </tr>
                    </table>
                </div>
                <div style="width: 100%; display:flex; margin-left: auto; margin-right: auto; justify-content: center; align-items: center;">
                    <?php foreach($data as $chithainf): ?>
                        <table class="table table_black" style="width:100%;">
                            <tr >
                                <td align=center rowspan="2" style="width:50px; " > <!--DAG NUMBER COL. 1-->
                                    <?php echo $this->lang->line('dag_no'); ?>
                                </td>
                                <td colspan="2" align=center  style="width:110px; "> <!--LAND CLASS COL. 2-->
                                    <?php echo $this->lang->line('land_class'); ?>
                                </td>
                                <td align=center rowspan="2" style="width:150px;"> <!--AREA COL. 3-->
                                    <?php echo $this->lang->line('area_with_units'); ?>
                                </td>
                                <td align=center rowspan="2" style="width:150px; ">
                                    <?php echo $this->lang->line('patta_no_and_type'); ?>
                                </td>
                                <td align=center rowspan="2" style="width:75px; "> <!--REVENUE COL.5-->
                                    <?php echo $this->lang->line('revenue_in_rupee'); ?>
                                </td>
                                <td align=center rowspan="2" style="width:75px;"> <!--LOCAL RATE COL. 6-->
                                    <?php echo $this->lang->line('local_tax_in_rupee'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php echo $this->lang->line('agriculture'); ?>
                                </td>
                                <td>
                                    <?php echo $this->lang->line('non_agriculture'); ?>
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
                                    $land= $this->utilityclass->cassnum($chithainf['dag_area_b']) . '-' . $this->utilityclass->cassnum($chithainf['dag_area_k']) . '-' . $this->utilityclass->cassnum($lc);
                                    if(in_array($this->session->userdata('dist_code'),BARAK_VALLEY)){
                                        $land = $land.'-'.$this->utilityclass->cassnum($chithainf['dag_area_g']);
                                    }
                                    echo $land;
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
                    <?php endforeach; ?>
                </div>
                <div style="width: 100%; display:flex; margin-left: auto; margin-right: auto; justify-content: center; align-items: center;">
                    <?php foreach($data as $chithainf): ?>
                        <table class="table" style="width:100%; border-collapse:collapse" cellPadding=0 cellSpacing=0>
                            <tr>
                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('pattdar_father_name_address')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('namjari_encroacher_father_name_address')) ?>
                                </td>
                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('rayat_father_name_address')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('rayat_type_khatian_no_khajana_rates')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('lower_rayat_father_name_address')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('year')) ?>
                                </td>

                                <td align="center" colspan="2" valign="top" >
                                    <?php echo($this->lang->line('non_crop_soil_area')) ?>
                                </td>

                                <td align="center" colspan="4" valign="top" >
                                    <?php echo($this->lang->line('crop_soil_area')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
                                    <?php echo($this->lang->line('fruit_tree_name_and_count')) ?>
                                </td>

                                <td align="center" rowspan="2" valign="top" >
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
                                <td STYLE="" align="center" valign="top">
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ৭</p></td>
                                <td STYLE="" align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ৮</p></td>
                                <td STYLE="" align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ৯</p></td>
                                <td STYLE=""  align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১০</p></td>
                                <td STYLE="" align="center" valign="top">
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১১</p></td>
                                <td STYLE=""  align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">১ম</p>
                                    <p style="margin-top: 0; margin-bottom: 0">২য়</p>
                                    <p style="margin-top: 0; margin-bottom: 0">৩য়</td>
                                <td STYLE=""  align="center" valign="top">
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১২</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৮</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৪</p></td>
                                <td STYLE=""  align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৩</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৯</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৫</p></td>
                                <td STYLE=""align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৪</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২০</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৬</p></td>
                                <td STYLE=""align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৫</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২১</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৭</p></td>
                                <td STYLE=""align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৬</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২২</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৮</p></td>
                                <td STYLE=""align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ১৭</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৩</p>
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ২৯</p></td>
                                <td STYLE=""  align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ৩০</p></td>
                                <td STYLE="" align="center" valign="top" >
                                    <p style="margin-top: 0; margin-bottom: 0">
                                        ৩১</p></td>
                            </tr>

                            <?php 
                                $numRowPerPage = 10;
                                $pdar_count = count($chithainf['pattadars']);
                                $tenant_count = count($chithainf['tenant']);
                                if (isset($chithainf['col8']) && !empty($chithainf['col8'])){
                                    $col8_count = count($chithainf['col8']);
                                }
                                else{
                                    $col8_count = 0;
                                }
                                // $objection_count = count($chithainf['objection']);

                                $max_count = max([$pdar_count, $tenant_count, $col8_count]);
                                $numRows = ceil($max_count / $numRowPerPage);
                            ?>
                            <?php $count=1;?>
                            <?php for($i=0; $i<$numRows; $i++) { ?>
                                <tr>
                                    <td>
                                       <table style="border: none;">
                                       <?php for ($j=0; $j<$numRowPerPage; $j++) { ?>
                                        <tr style="border: none;">
                                            <?php if(isset($chithainf['pattadars'][$j+ ($numRowPerPage * $i)])) { ?>
                                            <td style="border: none;">
                                                    <?php $p = $chithainf['pattadars'][$j+ ($numRowPerPage * $i)] ?>
                                                    <?php
                                                    if((($p['dag_por_b'])!='0') or (($p['dag_por_k'])!='0') or (($p['dag_por_lc'])!='0')){
                                                        $bkl= $p['dag_por_b']." B -".$p['dag_por_k']." K -".round($p['dag_por_lc'],2)." L ";
                                                    }
                                                    else{
                                                        $bkl="";
                                                    }
                                                    ?>
                                                        
                                                    <?php if (($p['new_pdar_name'] == 'N') && ($p['p_flag']=='1')): ?>
                                                        <strike><p style="color:red">  <?php echo utf8_encode($count++).")";?><?php echo $p['pdar_name']; ?></p>
                                                            <p style="color:red;font-style: italic">( <?php echo $this->utilityclass->get_relation($p['pdar_relation'])." ". $p['pdar_father'].") <br>".$bkl; ?></p></strike>
                                                    <?php elseif (($p['new_pdar_name']) == 'N'  && ($p['p_flag']==null)): ?>
                                                        <p style="color:red;font-style: italic">  <?php echo $count++.")";?><?php echo $p['pdar_name']; ?></p>
                                                        <p style="color:red;font-style: italic">( <?php echo $this->utilityclass->get_relation($p['pdar_relation'])." ". $p['pdar_father']." )<br>".$bkl; ?></p>
                                                    <?php elseif (($p['new_pdar_name'] == null) && ($p['p_flag']=='1')): ?>
                                                        <strike><p style="color:red;font-style: italic">  <?php echo $count++.")";?><?php echo $p['pdar_name']; ?></p>
                                                            <p style="color:red">( <?php echo $this->utilityclass->get_relation($p['pdar_relation'])." ". $p['pdar_father']."<br> ) ".$bkl; ?></p></strike>
                                                    <?php elseif ($p['new_pdar_name'] == 'N'): ?>
                                                        <p style="color:red;font-style: italic">  <?php echo $count++.")";?><?php echo $p['pdar_name']; ?></p>
                                                        <p style="color:red;font-style: italic">( <?php echo $this->utilityclass->get_relation($p['pdar_relation'])." ". $p['pdar_father'].")<br> ".$bkl; ?>)</p>
                                                
                                                    <?php elseif ($p['new_pdar_name'] != 'N'): ?>
                                                        <p style="color:blue">  <?php echo $count++.")";?><?php echo $p['pdar_name']; ?></p>
                                                        <p style="color:blue">( <?php echo $this->utilityclass->get_relation($p['pdar_relation'])." ". $p['pdar_father'].")<br>  ".$bkl; ?></p>
                                                    <?php endif; ?>
                                                    <?php if(isset($p['pdar_address1']) && $p['pdar_address1'] != ''){?>
                                                        <p><?php echo($this->lang->line('address')) ?> (<?php echo $p['pdar_address1'].", ".$p['pdar_address2'];?>)</p>
                                                    <?php } ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                       </table>
                                                
                                            
                                    </td>
                                    <td>
                                        <?php
                                        if ($i==0) {
                                            $hukum_no = 1;
                                            if (isset($chithainf['col8'])) {
                                                $size_of_order=sizeof($chithainf['col8']);
                                                foreach ($chithainf['col8'] as $clmn8):
                                                    
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
                                                    $operation = $clmn8['operation'];
                                                    $co_name = $clmn8['co_name'];
                                                    if($order_type_code!=='03'){
                                                    ?>
                                                        <p>চক্ৰ বিষয়াৰ  <br> <?php echo $this->utilityclass->cassnum($formatDate); ?> তাৰিখৰ 
                                                            <?php
                                                            $bigha = 0;
                                                            $katha = 0;
                                                            $lesa = 0;
                                                            if ($order_type_code == "01") {
    
                                                                if ($mut_land_area_b != '0') {
                                                                    $bigha = $this->utilityclass->cassnum($mut_land_area_b) . 'বিঘা ';
                                                                } else {
                                                                    $bigha = "";
                                                                }
    
                                                                if ($mut_land_area_k != '0') {
                                                                    $katha = $this->utilityclass->cassnum($mut_land_area_k) . 'কঠা ';
                                                                } else {
                                                                    $katha = "";
                                                                }
                                                                if ($mut_land_area_lc != '0') {
                                                                    $lesa = $this->utilityclass->cassnum($mut_land_area_lc) . 'লেছা ';
                                                                } else {
                                                                    $lesa = "";
                                                                }
                                                            } else if ($order_type_code == "02") {
    
                                                                if ($mut_land_area_b != '0') {
                                                                    $bigha = $this->utilityclass->cassnum($mut_land_area_b) . 'বিঘা ';
                                                                } else {
                                                                    $bigha = "";
                                                                }
    
                                                                if ($mut_land_area_k != '0') {
                                                                    $katha = $this->utilityclass->cassnum($mut_land_area_k) . 'কঠা ';
                                                                } else {
                                                                    $katha = "";
                                                                }
                                                                if ($mut_land_area_lc != '0') {
                                                                    $lesa = $this->utilityclass->cassnum($mut_land_area_lc) . 'লেছা ';
                                                                } else {
                                                                    $lesa = "";
                                                                }
                                                            }
                                                            echo $order_type . ' নং ' . $case_no . '-ৰ ' . ' হুকুমমৰ্মে এই দাগৰ ' . $bigha . $katha . $lesa . ' মাটি ';
                                                            
                                                            if ($order_type_code != "02" and $clmn8['nature_trans_code'] != null) {
                                                                
                                                                echo " " . $this->utilityclass->getTransferType($clmn8['nature_trans_code']) . " ";
                                                            }
                                                            $count = 1;
                                                            $howmanys = sizeof($clmn8['inplace']) - 1;
                                                            foreach ($clmn8['inplace'] as $in) {
                                                                echo $in['inplace_of_name'] . "'ৰ";
                                                                if ($count < sizeof($clmn8['inplace']) - 1) {
                                                                    switch ($in['inplaceof_alongwith']) {
                                                                        case 'i':
                                                                            echo " স্হলত ";
                                                                            break;
                                                                        case 'a':
                                                                            echo " লগত  ";
                                                                            break;
                                                                    }
                                                                    echo " , ";
                                                                    $count++;
                                                                } elseif ($count == sizeof($clmn8['inplace']) - 1) {
                                                                    switch ($in['inplaceof_alongwith']) {
                                                                        case 'i':
                                                                            echo " স্হলত ";
                                                                            break;
                                                                        case 'a':
                                                                            echo " লগত  ";
                                                                            break;
                                                                    }
                                                                    echo " আৰু ";
                                                                    $count++;
                                                                } else {
                                                                    switch ($in['inplaceof_alongwith']) {
                                                                        case 'i':
                                                                            echo " স্হলত ";
                                                                            break;
                                                                        case 'a':
                                                                            echo " লগত  ";
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
                                                                echo $in['occupant_name'] . " ($r " . $in['occupant_fmh_name'] . ")";
                                                                if ($count < sizeof($clmn8['occup']) - 1) {
                                                                    echo " , ";
                                                                    $count++;
                                                                } elseif ($count == sizeof($clmn8['occup']) - 1) {
                                                                    echo " আৰু ";
                                                                    $count++;
                                                                } else {
                                                                    echo " ";
                                                                }
                                                            }
                                                            if ($clmn8['order_type_code'] == '01') {
                                                                echo " নামত নামজাৰী কৰা হ’ল | ";
                                                            } else if ($clmn8['order_type_code'] == '02') {
                                                                echo " নামত " . $clmn8['occup'][0]['new_dag_no'] . " নং দাগ " .
                                                                $clmn8['occup'][0]['new_patta_no'] . " ম্যাদী পট্টা কৰা হল | ";
                                                            }
    
                                                            if (($clmn8['rajah'] != 0) || ($clmn8['rajah'] == 'y')) {
                                                                echo "<p style='color:blue'>( ৰাজহ আদলত )</p>";
                                                            }
                                                            ?>   
                                                            <?php
                                                            if (($clmn8['deed_reg_no'] != "")) {
                                                                if($clmn8['nature_trans_code']=='08'){
                                                                    $lebel_one="উইল / প্ৰবেট নং : ";
                                                                    $label_two="উইল / প্ৰবেট তাৰিখ : ";
                                                                } else {
                                                                    $lebel_one="Deed No : ";
                                                                    $lebel_two="Deed No : ";
                                                                }
                                                                echo "<p class='text-danger'>Registration</p>";
                                                                echo $lebel_one . $clmn8['deed_reg_no'] . "<br>";
                                                                if($clmn8['nature_trans_code']!='08'){
                                                                    echo "Deed Value:" . $this->utilityclass->cassnum(number_format($clmn8['deed_value'], 2)) . "<br>";
                                                                }
                                                                $interval = date_diff(date_create('01-01-1970'), date_create($clmn8['deed_date']));
                                                                if ($interval->days > 0) {
                                                                    echo $lebel_two . $this->utilityclass->cassnum(date('d-m-y', strtotime($clmn8['deed_date']))) . " ";
                                                                }
                                                            }
                                                            ?>
                                                            <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $clmn8['lm_name']; ?>)</p>
                                                            <p><u class='text-danger'>চক্ৰ বিষয়া :</u><br>(<?php echo $clmn8['username']; ?>)</p>
                                                            <?php
                                                            
                                                            if (($operation == 'B') and ($order_type_code == "01")){
                                                                echo "লাঃ মঃৰ প্ৰতিবেদনৰ ভিত্তিত উপৰোক্ত বকেয়া নামজাৰী ও নথি সংশোধন অনুমোদন / নাকচ কৰা হ’ল ।  ";
                                                                echo "<br><u class='text-danger'> চঃ বিঃ –  ".$co_name."</u>";
                                                            }elseif (($operation == 'B') and ($order_type_code == "02")){
                                                                echo "লাঃ মঃৰ প্ৰতিবেদনৰ ভিত্তিত উপৰোক্ত আপোচ বাটোৱাৰা ও নথি সংশোধন  কৰা হ’ল ।  ";
                                                                echo "<br><u class='text-danger'> চঃ বিঃ –  ".$co_name."</u>";
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($hukum_no < $size_of_order) {
                                                                echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
                                                            }
                                                        }
                                                        $hukum_no++;
                                                    ?>
                                                    <?php
                                                endforeach;
                                            }
                                        }

                                        if($i == 0) {
                                            if (isset($chithainf['objection'])) {

                                                foreach ($chithainf['objection'] as $objection) {
                                                
                                                    $mut_type = $objection['mut_type'];
                                                    $objection_case_no = $objection['objection_case_no'];
                                                    $prev_fm_ca_no = $objection['prev_fm_ca_no'];
                                                    $submission_date = $objection['submission_date'];
                                                    $obj_name = $objection['obj_name'];
                                                    $co_id = $objection['co_id'];
                                                    $regist_date = $objection['regist_date'];
                                                    $occupant=$objection['occupant'];
                                                }
                                                $coname = $this->utilityclass->getSelectedCOName($this->session->userdata('dist_code'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'), $co_id);
                                                
                                                $mut_name = $this->utilityclass->getMutationTypeObject($mut_type);
                                                
                                                echo "চক্ৰ বিষয়াৰ ". date('d-m-y', strtotime($regist_date)) . " তাৰিখৰ  বিবিধ গোচৰ নং ". $objection_case_no  ." হুকুমমৰ্মে  ". $obj_name ." য়ে দিয়া চিঠি  অভিযোগ সাপেক্ষে ". $prev_fm_ca_no . " নং ". date('d-m-y', strtotime($submission_date)) ." তাৰিখৰ হুকুম নাকচ কৰা হয় আৰু ";
                                                echo $occupant ." নাম  কৰ্তন কৰা  হয়  | ";
                                                
                                                echo "<br><u class='text-danger'>স্বা (চক্ৰ বিষয়া )</u><br>";
                                                echo $coname->username;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                    if (isset($chithainf['tenant'])) {
                                        for($j=0; $j<$numRowPerPage; $j++) {
                                            if(isset($chithainf['tenant'][$j+ ($numRowPerPage * $i)])) {
                                                $tenantdesc = $chithainf['tenant'][$j+ ($numRowPerPage * $i)];
                                                $tenantName = $tenantdesc['tenant_name'];
                                               
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
                                            }
                                        }
                                    }
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($chithainf['tenant'])) {
                                            for($j=0; $j<$numRowPerPage; $j++) {
                                                if(isset($chithainf['tenant'][$j+ ($numRowPerPage * $i)])) {
                                                    $tenantdesc = $chithainf['tenant'][$j+ ($numRowPerPage * $i)];
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
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                    if (isset($chithainf['subtenant']))
                                    {
                                        for($j= 0; $j< $numRowPerPage; $j++) {
                                            if(isset($chithainf['subtenant'][$j+ ($numRowPerPage * $i)])) {
                                                $subtenant = $chithainf['subtenant'][$j+ ($numRowPerPage * $i)];
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
                                            }
                                        }
                                    }
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        rsort($chithainf['years']);

                                        if($i==0) {
                                            foreach ($chithainf['years'] as $key => $yr):

                                                echo $year_assam = $chithainf['years'][$key]['year'];
                                                echo $this->utilityclass->cassnum($year_assam) . '<br>';
    
                                            endforeach;
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['noncrp'] as $key => $noncrop):
                                                echo $chithainf['noncrp'][$key]['type_of_used_noncrp'];
                                            endforeach;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['noncrp'] as $key => $noncrop):
                                                $land_n_crop = $chithainf['noncrp'][$key]['noncrp_b'] . '-' . $chithainf['noncrp'][$key]['noncrop_k'] . '-' . $chithainf['noncrp'][$key]['noncrop_lc'];
                                                if(in_array($this->session->userdata('dist_code'),BARAK_VALLEY)){
                                                    $land_n_crop = $land_n_crop.'-'.$chithainf['noncrp'][$key]['noncrop_g'];
                                                }
                                                echo $land_n_crop . '<br>';
                                            endforeach;
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['mcrp'] as $key => $mcrop):
                                                //if (sizeof($chithainf['noncrp']) > 0) {
                                                echo $chithainf['mcrp'][$key]['sourceofwater'] . '<br>';
                                                // }
                                            endforeach;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['mcrp'] as $key => $mcrop):

                                                echo $chithainf['mcrp'][$key]['cropname'] . '<br>' .
                                                    '(' . $chithainf['mcrp'][$key]['crop_category'] . ')<br>';
                                            endforeach;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['mcrp'] as $key => $mcrop):
                                                $land_mcrop = $chithainf['mcrp'][$key]['mcrp_b'] . '-' . $chithainf['mcrp'][$key]['mcrop_k'] . '-' . $chithainf['mcrp'][$key]['mcrop_lc'] ;
                                                if(in_array($this->session->userdata('dist_code'),BARAK_VALLEY)){
                                                    $land_mcrop = $land_mcrop.$chithainf['mcrp'][$key]['mcrop_g'];
                                                }
                                                echo $land_mcrop. '<br>';
                                            endforeach;
                                        } 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['mcrp_akeadhig'] as $key => $mcrop_ekadhig):

                                                echo $chithainf['mcrp_akeadhig'][$key]['bigha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['katha'] . '-' . $chithainf['mcrp_akeadhig'][$key]['lesa'] . '<br>';
                                            endforeach;
                                        }   
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            foreach ($chithainf['fruit'] as $key => $fruit):

                                                echo $chithainf['fruit'][$key]['fruitname'] . '<br>' . $chithainf['fruit'][$key]['no_of_plants'] . '<br>' . '(' . $chithainf['fruit'][$key]['fbigha'] . '-' . $chithainf['fruit'][$key]['fkatha'] . '-' . $chithainf['fruit'][$key]['flesa'] . ')' . '<br>';
                                            endforeach;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($i== 0) {
                                            $sro_hukum_no = 1;
                                            if (isset($chithainf['sro'])) {
                                                $size_of_sro_order = sizeof($chithainf['sro']);
                                                echo "<u class='text-danger'>SRO টোকা</u>";
                                                foreach ($chithainf['sro'] as $key => $sr):
                                                    $newDatesro = date("d-m-Y", strtotime($chithainf['sro'][$key]['date_of_deed']));
                                                    $headingsro = '(SR -' . $chithainf['sro'][$key]['name_of_sro'] . ')';
                                                    ?>
                                                    <p><?php             ?></p>
                                                    <p><?php echo $headingsro . '&nbsp;' . '&nbsp;' . 'এই দাগৰ (' . $chithainf['sro'][$key]['dag_no'] . ' নং)  জমীত পটাদাৰ ' . $chithainf['sro'][$key]['reg_from_name'] . ' আৰু ক্ৰেতা / দানলওঁতা ' . $chithainf['sro'][$key]['reg_to_name'] . ' ৰ মাজত ' . '<br>(' . $chithainf['sro'][$key]['dag_area_b'] . 'B - ' . $chithainf['sro'][$key]['dag_area_k'] . 'K - ' . $chithainf['sro'][$key]['dag_area_lc'] . 'L)' . ' মাটিৰ <br> ৰেঃ দলিল <br>( Deed No.) ' . $chithainf['sro'][$key]['deed_no'] . ' নং <br>' . $newDatesro . ' তাৰিখে ' . ' পঞ্জীয়ন হয় |' ?></p>

                                                    <?php
                                                    if ($chithainf['sro'][$key]['status'] == '1') {
                                                        echo "<p class='red'><u>Status :</u><br> ( চক্র বিষয়াই নামজাৰীৰ পঞ্জীয়নৰ বাবে আদেশ দিছে | )";
                                                    } elseif ($chithainf['sro'][$key]['status'] == '2') {
                                                        echo "<p class='red'><u>Status :</u><br> ( সহায়কে পঞ্জীয়ন কৰিলে | )";
                                                    } elseif ($chithainf['sro'][$key]['status'] == '3') {
                                                        echo "<p class='red'><u>Status :</u><br> ( নামজাৰী হৈ গৈছে | )";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($sro_hukum_no < $size_of_sro_order) {
                                                        echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
                                                    }
                                                    $sro_hukum_no++;
                                                    ?>
                                                <?php
                                                endforeach;
                                            }
                                        }
                                        ?>

                                    <?php
                                    if($i == 0) {
                                        $order_count = 1;
                                        foreach ($chithainf['col31'] as $remark):
                                            ?>
                                            <?php foreach ($remark as $r): ?>
    
                                            <?php if (sizeof($r) > 0): ?>
                                                <!-- Name Correction Start-->
                                                <?php if ($r['ord_type_code'] == '06'): ?>
                                                    <?php echo "<p class='text-danger'><u>চক্র বিষয়া'ৰ : </u></p><p>" . $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['orderdate']))) . " তাৰিখ'ৰ " . $r['innerdata52'][0]->ord_no . " হুকুম নং নাম সংশোধনীকৰণ হুকুমমৰ্মে এই দাগৰ " . $r['innerdata52'][0]->infavor_of_name . "'ৰ  নাম  " . $r['innerdata52'][0]->infavor_of_corrected_name . " কৰা হ'ল  |</p>" ?>
                                                    <p class='hide'>লাট মণ্ডল : <?php echo $r['lmname']; ?></p>
                                                    <p><u class='text-danger'>চক্র বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                <?php endif; ?>
                                                <!-- Name Correction End-->
                                                <!-- Name Cancellation-->
                                                <?php
                                                //var_dump($r);
                                                if ($r['ord_type_code'] == '07'): ?>
                                                    <?php echo "<p class='text-danger'><u>চক্র বিষয়া'ৰ : </u></p><p>" . $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['orderdate']))) . " তাৰিখ'ৰ " . $r['order_no'] . " হুকুম নং নাম কৰ্তন  হুকুমমৰ্মে এই দাগৰ পটাদাৰ " . $r['infavor_of_name'] . "'  ৰ  আবেদন মৰ্মে পটাদাৰ " . $r['name_delete'] . "ৰ  নাম কৰ্তন কৰা হ'ল  |</p>" ?>
                                                    <p class='hide'>লাট মণ্ডল : <?php echo $r['lmname']; ?></p>
                                                    <p><u class='text-danger'>চক্র বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                <?php endif; ?>
                                                <!-- Name Cancellation End-->
    
                                                <!-- Reclassification Start-->
                                                <?php if ($r['remark_type_code'] == '08'): ?>
                                                    <?php
                                                    
                                                    echo "<p class='text-danger'><u>হুকুম নং : </u></p><p>" . $r['case_no'] . " শ্রেণী সংশোধনীকৰণ প্রস্তাব  উপায়ুক্ত মহোদয়ে " . date('d-m-Y', strtotime($r['dc_approval_date'])) . " তাৰিখে দিয়া অনুমোদন মৰ্মে " . $r['patta_no'] . " নং পট্টাৰ " . $r['dag_no'] . " নং দাগৰ শ্রেণী " . $r['present_land_class'] . "'ৰ পৰা " . $r['proposed_land_class'] . " লৈ পৰিবৰ্তন কৰা হ'ল ।</p>";
    
                                                    ?>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                <?php endif; ?>
                                                <!-- Reclassification End-->
    
                                                <!-- NR Start-->
                                                <?php if ($r['remark_type_code'] == '09'): ?>
                                                    <?php
                                                    echo "<p class='text-danger'><u>হুকুম নং : </u></p><p> চক্র বিষয়া'ৰ " . $r['case_no'] . " নং NR গোচৰৰ প্ৰস্তাৱৰ "
                                                        . $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['order_date']))) . "  তাৰিখে দিয়া অনুমোদন মৰ্মে " . $r['patta_no'] . " নং পট্টা আৰু " . $r['dag_no'] . "  নং দাগৰ পট্টাৰ প্ৰকাৰ একচণাৰ পৰা চৰকাৰীলৈ পৰিবৰ্ত্তন কৰাৰ হুকুম " . $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['final_date']))) . " তাৰিখে দিয়া হল ।</p>";
                                                    ?>
                                                    <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                                                    <p><u class='text-danger'>চক্র বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                <?php endif; ?>
                                                <!-- NR End-->
    
                                                <!-- Office Mutation Start-->
                                                <?php if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '03')): ?>
                                                    <u class='text-danger'><?php echo "হুকুম নং: " . $order_count++; ?><br></u>
                                                    <p>চক্র বিষয়া'ৰ  <br>
                                                        <?php echo $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['order_date']))); ?>
                                                        তাৰিখ'ৰ
                                                        <?php
                                                        $order_type = $r['ord_type_code'];
                                                        echo $this->utilityclass->getOfficeMutType($order_type) . " নং  ";
                                                        ?>
                                                        <?php echo $r['ord_no'] . " 'ৰ হুকুমমৰ্মে এই দাগৰ "; ?>
    
                                                        <?php
                                                        //var_dump($r);
                                                        if ($r['by_right_of'] == '11') {
                                                            echo " অংশৰ জমিত ";
                                                        } else {
                                                            //var_dump($r);
                                                            echo $this->utilityclass->cassnum($r['bigha']) . " বিঘা ";
                                                            echo $this->utilityclass->cassnum($r['katha']) . " কঠা ";
                                                            echo $this->utilityclass->cassnum(number_format($r['lessa'], 2)) . " লেছা মাটি ";
                                                        }
                                                        ?>
                                                        <?php echo $this->utilityclass->getTransferType($r['by_right_of']) . " "; ?>
                                                        <?php
                                                        $count = 1;
                                                        $howmany = sizeof($r['alongwith_name']) - 1;
                                                        foreach ($r['alongwith_name'] as $al):
                                                            ?>
                                                            <?php
                                                            echo $al['alongwithname'];
                                                            if ($count < sizeof($r['alongwith_name']) - 1) {
                                                                echo " , ";
                                                                $count++;
                                                            } elseif ($count == sizeof($r['alongwith_name']) - 1) {
                                                                echo " আৰু ";
                                                                $count++;
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
    
    
                                                        <?php
                                                        endforeach;
                                                        if (sizeof($r['alongwith_name']) != '0') {
                                                            echo "' ৰ লগত ";
                                                        }
                                                        ?>
    
                                                        <?php
                                                        $count = 1;
                                                        $howmany = sizeof($r['inplace_of_name']) - 1;
                                                        foreach ($r['inplace_of_name'] as $al):
                                                            ?>
                                                            <?php
                                                            echo $al['inplace_of_name'];
                                                            if ($count < sizeof($r['inplace_of_name']) - 1) {
                                                                echo " , ";
                                                                $count++;
                                                            } elseif ($count == sizeof($r['inplace_of_name']) - 1) {
                                                                echo " আৰু ";
                                                                $count++;
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
    
    
                                                        <?php
                                                        endforeach;
                                                        if (sizeof($r['inplace_of_name']) != '0') {
                                                            echo "'ৰ স্হলত ";
                                                        }
                                                        ?>
    
                                                        <?php
                                                        $count = 1;
                                                        $howmany = sizeof($r['infav']) - 1;
                                                        foreach ($r['infav'] as $in):
                                                            ?>
                                                            <?php
                                                            echo $in['infavor_of_name'];
                                                            if ($count < sizeof($r['infav']) - 1) {
                                                                echo " , ";
                                                                $count++;
                                                            } elseif ($count == sizeof($r['infav']) - 1) {
                                                                echo " আৰু ";
                                                                $count++;
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
                                                        <?php endforeach; ?>
    
                                                        <?php if ($r['ord_type_code'] == '03'): ?>
                                                            'ৰ নামত নামজাৰী কৰা হ’ল |
                                                        <?php endif; ?>
                                                    <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                                                    <p><u class='text-danger'>চক্র বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
                                                    <p>
                                                        <?php
                                                        if ($r['reg_deal_no'] != "") {
                                                            echo "Reg No (" . $this->utilityclass->cassnum($r['reg_deal_no']) . ")";
                                                        }
                                                        ?>
                                                    </p>
                                                    <p>
                                                        <?php
                                                        if ($r['reg_date'] != "") {
                                                            echo "Reg Date (" . $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['reg_date']))) . ")";
                                                        }
                                                        ?>
                                                    </p>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                    <p>
                                                        <?php
                                                        if ($r['operation'] == "B") {
                                                            echo "চঃ বিঃ – লাঃ মঃৰ প্ৰতিবেদনৰ ভিত্তিত উপৰোক্ত বকেয়া নামজাৰী ও নথি সংশোধন অনুমোদন / নাকচ কৰা হ’ল  ";
                                                            echo "<br><u class='text-danger'> চঃ বিঃ –  ".$r['co_name']."</u>";
                                                        }
                                                        ?>
                                                    </p>
                                                    <hr>
                                                <?php endif; ?>
                                                <?php if ($r['ord_type_code'] == '01'): ?>
    
                                                    <u class='text-danger'><?php echo "হুকুম নং: " . $order_count++; ?></u><br>
                                                    <p><?php echo $r['ord_passby_desig']; ?>ৰ  </p><p>
                                                        <?php echo $r['ord_no'] . "  নং  "; ?>
                                                        <?php
                                                        $order_type = $r['ord_type_code'];
                                                        echo $this->utilityclass->getOfficeMutType($order_type) . " গোচৰৰ  ";
                                                        ?>
    
                                                        <?php echo $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['order_date']))); ?>  তাৰিখৰ হুকুমমৰ্মে
    
    
                                                        <?php if ($r['premi_chal_recpt'] != '003'): ?>
                                                            <?php echo $this->utilityclass->cassnum($r['patta_no']) . " নং একচনা পট্টাৰ আৰু " . $this->utilityclass->cassnum($r['dag_no']) . " নং দাগৰ  "; ?>
                                                            <?php
                                                            if(($r['premi_chal_name'] == "") || ($r['premi_chal_name'] == "None")){
                                                                echo $this->utilityclass->cassnum($r['land_area_b']) . " বিঘা  " . $this->utilityclass->cassnum($r['land_area_k']) . " কঠা  " . $this->utilityclass->cassnum(number_format($r['land_area_lc'], 2)) . " লেছা মাটিৰ প্রিমিয়াম " . round($r['premium'], 2) . " টকা যোগে ";
                                                            } else {
                                                                echo $this->utilityclass->cassnum($r['land_area_b']) . " বিঘা  " . $this->utilityclass->cassnum($r['land_area_k']) . " কঠা  " . $this->utilityclass->cassnum(number_format($r['land_area_lc'], 2)) . " লেছা মাটিৰ প্রিমিয়াম " . round($r['premium'], 2) . " টকা " . $r['premi_chal_recpt_no'] . " নং " . $r['premi_chal_name'] . " যোগে ";
                                                            }
                                                            ?>
                                                            <?php
                                                            $count = 1;
                                                            $howmany = sizeof($r['ord_onbehalf_of']) - 1;
                                                            foreach ($r['ord_onbehalf_of'] as $in):
                                                                ?>
                                                                <?php
                                                                echo $in['app_name'];
                                                                if ($count < sizeof($r['ord_onbehalf_of']) - 1) {
                                                                    echo " , ";
                                                                    $count++;
                                                                } elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                                                    echo " আৰু ";
                                                                    $count++;
                                                                } else {
                                                                    echo " ";
                                                                }
                                                                ?>
                                                            <?php endforeach; ?>
                                                            ৰ পৰা আদায় হোৱাত
                                                        <?php endif; ?>
                                                        <?php
                                                        $count = 1;
                                                        $howmany = sizeof($r['ord_onbehalf_of']) - 1;
                                                        foreach ($r['ord_onbehalf_of'] as $in):
                                                            ?>
                                                            <?php
                                                            echo $in['app_name'];
                                                            if ($count < sizeof($r['ord_onbehalf_of']) - 1) {
                                                                echo " , ";
                                                                $count++;
                                                            } elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                                                echo " আৰু ";
                                                                $count++;
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
                                                        <?php endforeach; ?>
                                                        ৰ নামত <?php echo $this->utilityclass->cassnum($r['land_area_b']) . " বিঘা  " . $this->utilityclass->cassnum($r['land_area_k']) . " কঠা  " . $this->utilityclass->cassnum(number_format($r['land_area_lc'], 2)) . " লেছা "; ?> মাটি  পৃঠক
                                                        <?php echo $this->utilityclass->cassnum($r['new_patta_no']) . " নং " . $r['patta_type'] . "  পট্টা আৰু " . $this->utilityclass->cassnum($r['new_dag_no']); ?> নং দাগে ম্যাদীকৰণ কৰা হল | </p>
                                                    <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                                                    <p><u class='text-danger'>চক্র বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
                                                    <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                <?php endif; ?>
                                                <?php  if (($r['remark_type_code'] == '10') && ($r['ord_type_code'] == '10')): ?>
                                                    <p>হুকুম নং :<?=$r['history_no'];?><br>
                                                        উপায়ুক্ত মহোদয়ৰ <?=$r['ord_no']?> নং আৱন্টন বন্দৱস্তী গোচৰৰ  <?=date('d/m/Y',strtotime($r['date_entry']))?>  ইং তাৰিখৰ হুকুম মতে চৰকাৰী  <?=$r['old_dag']?>  নং দাগৰ  <?=$r['allottee_land_b']?> বিঘা  <?=$r['allottee_land_k']?>  কঠা  <?=$r['allottee_land_lc']?> লেছা মাটিৰ  <?=$r['new_dag']?>  নং দাগ আৰু  <?=$r['new_patta']?> নং নতুন খেৰাজ ম্যাদী পট্টা ভূক্ত কৰা হল । <?=date('Y')?> চনত দৌল ভূক্ত হব । </p>
                                                    <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                                                    <p><u class='text-danger'>চক্র বিষয়া  :</u><br>(<?php echo $r['username']; ?>)</p>
                                                <?php endif; ?>
                                                <?php
                                                if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '04')):
                                                    $howmany = sizeof($r['infav']);
                                                    if ($howmany != null) {
                                                        ?>
    
                                                        <p><u class="text-danger"><?php echo "হুকুম নং: " . $order_count++; ?></u></p>
                                                        <p>চক্র বিষয়া'ৰ
                                                            <?php echo $this->utilityclass->cassnum(date('d-m-Y', strtotime($r['order_date']))); ?>
                                                            তাৰিখৰ
                                                            <?php
                                                            $order_type = $r['ord_type_code'];
                                                            echo $this->utilityclass->getOfficeMutType($order_type) . " নং  ";
                                                            ?>
                                                            <?php echo $r['ord_no'] . " ৰ হুকুমমৰ্মে এই দাগৰ "; ?>
    
                                                            <?php
                                                            echo $this->utilityclass->cassnum($r['bigha']) . " বিঘা ";
                                                            echo $this->utilityclass->cassnum($r['katha']) . " কঠা ";
                                                            echo $this->utilityclass->cassnum(number_format($r['lessa'], 2)) . " লেছা মাটি   ";
                                                            ?>
                                                            <?php
                                                            $count = 1;
                                                            $howmany = sizeof($r['infav']);
                                                            foreach ($r['infav'] as $in):
                                                                ?>
                                                                <?php
                                                                echo $in['infavor_of_name'];
                                                                if ($count < sizeof($r['infav']) - 1) {
                                                                    echo " , ";
                                                                    $count++;
                                                                } elseif ($count == sizeof($r['infav']) - 1) {
                                                                    echo " আৰু ";
                                                                    $count++;
                                                                } else {
                                                                    echo " ";
                                                                }
                                                                ?>
                                                            <?php endforeach; ?>'ৰ নামত
                                                            <?php echo $this->utilityclass->cassnum($r['new_patta_no']) . " নং  পট্টা আৰু " . $this->utilityclass->cassnum($r['new_dag_no']); ?> নং দাগ কৰা হল |
                                                            <?php if ($r['ord_type_code'] == '04'): ?>
    
                                                            <?php endif; ?>
                                                        <p><u class="text-danger">লাট মণ্ডল :-</u><br>(<?php echo $r['lm_name']; ?>)</p>
                                                        <p><u class="text-danger">চক্র বিষয়া :-</u><br>(<?php echo $r['username']; ?>)</p>
                                                        <p>
                                                            <?php
                                                            if ($r['operation'] == "B") {
                                                                echo "চঃ বিঃ – লাঃ মঃৰ প্ৰতিবেদনৰ ভিত্তিত উপৰোক্ত বাটোৱাৰা ও নথি সংশোধন  কৰা হ’ল  ";
                                                                echo "<br><u class='text-danger'> চঃ বিঃ –  ".$r['co_name']."</u>";
                                                            }
                                                            ?>
                                                        </p>
                                                        
                                                        <hr style='border-bottom: 2px solid #b3b0b0;'>
                                                    <?php } ?>
                                                    <?php endif; ?>
                                                    <?php if ($r['ord_type_code'] == '01'): ?>
                                                        <?php if ($r['premi_chal_recpt'] == '003'): ?>
                                                            <?php echo "<hr><p class='text-danger'><u>টোকা :</u></p> আবেদনকাৰীয়ে প্রিমিয়াম আদায় নিদিয়া বাবে " . round($r['premium'], 2) . " টকা ৰাজহৰ বকেয়া হিচাবে আদায় লোৱা হওঁক ।" ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
    
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
    
    
                                        <?php
                                        if (isset($chithainf['lmnote'])) {
                                            echo "<u class='text-danger'>মণ্ডলৰ টোকা</u>";
                                            foreach ($chithainf['lmnote'] as $key => $enc):
                                                ?>
                                                <p><?php echo $chithainf['lmnote'][$key]['lm_note']."----".$chithainf['lmnote'][$key]['lm_code']; ?></p>
                                                <hr style='border-bottom: 2px solid #b3b0b0;'>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
    
    
                                        <?php
                                        if (isset($chithainf['encro'])) {
                                            $encro_hukum_no = 1;
                                            $size_of_encro_order=sizeof($chithainf['encro']);
                                            if(count($chithainf['encro']) != 0)
                                            {
                                                echo "<u class='text-danger'>বেদখলকাৰীৰ টোকা </u>";
                                            }
                                            foreach ($chithainf['encro'] as $key => $enc):
                                                $newDate = date("d-m-Y", strtotime($chithainf['encro'][$key]['encro_since']));
                                                ?>
                                                <p>
                                                    <?php echo ($key+1).'. '.$chithainf['encro'][$key]['encro_name'] . '<br>' . 'মাটি ' . '(' . $chithainf['encro'][$key]['encro_land_b'] . '-' . $chithainf['encro'][$key]['encro_land_k'] . '-' . $chithainf['encro'][$key]['encro_land_lc'] . ')'. '<br>'  . $chithainf['encro'][$key]['land_used_by_encro']; ?>
    
                                                    <?php
                                                    if($chithainf['encro'][$key]['encro_evic_date'] != NULL)
                                                    {
                                                        echo '<br>'.'উচ্ছেদিত তাং:' .'<br>'. date("d-m-Y", strtotime($chithainf['encro'][$key]['encro_evic_date']));
                                                    }
                                                    ?>
                                                </p>
                                            <?php
                                            endforeach;
                                            if ($encro_hukum_no < $size_of_encro_order) {
                                                echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
                                            }
                                            $encro_hukum_no++;
                                        }
                                        ?>
    
    
    
                                        <?php
                                        if (isset($chithainf['archeo'])) {
                                            $archeo_hukum_no = 1;
                                            $size_of_encro_order=sizeof($chithainf['archeo']);
                                            if(count($chithainf['archeo']) != 0)
                                            {
                                                echo "<u class='text-danger'> পূৰাতাত্বিক বুৰঞ্জী </u>";
                                            }
    
                                            foreach ($chithainf['archeo'] as $key => $archeo): ?>
                                                <p>
                                                    <?php
                                                    echo '<u>' . $chithainf['archeo'][$key]['hist_description_nme'] . ' </u><br>'
                                                        .'(' . $chithainf['archeo'][$key]['archeo_b'] . '-' . $chithainf['archeo'][$key]['archeo_k'] . '-' . $chithainf['archeo'][$key]['archeo_lc'] . ')'. '<br>'
                                                        .$chithainf['archeo'][$key]['archeo_decribed'] . '<br>'. '';
                                                    ?>
                                                </p>
                                            <?php endforeach;
    
    
                                            if ($archeo_hukum_no < $size_of_encro_order) {
                                                echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
                                            }
                                            $archeo_hukum_no++;
                                        }

                                        if(isset($chithainf['alphaDag'])) {
                                            echo $chithainf['alphaDag'];
                                        }
                                    }
                                    


                                    ?>

                                    </td>
                                   
                                    
                                </tr>
                            <?php } ?>















                            
                                
                           
                            <?php //include 'col31.php'; ?>
                        </table>
                    <?php endforeach; ?>
                </div>
            </div>
        <!-- </center> -->
    </div>
    

</body>
</html>