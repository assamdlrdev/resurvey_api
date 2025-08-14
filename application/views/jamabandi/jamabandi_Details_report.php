<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js')?>"></script>
    <style>
        .card {
            margin: 0 auto; /* Added */
            float: none; /* Added */
            margin-bottom: 10px; /* Added */
            margin-top: 50px;
        }
        td{
            font-size: .75em !important;
            background: #fff;
        }
        /*.onTopNotification{
            display:none;
        }
        @media print {
            body { font-size: 10pt }
        }
        @media screen {
            body { font-size: 13px }
        }
        @media screen, print {
            body { line-height: 1.2 }
        }*/
    </style>
</head>
<body>

<div class="container-fluid">
    <?php include 'message.php'; ?>

    <div class="card col-md-12 card-body" id="loc_save">

        <div align="center" class="col-lg-12">
            <table align="center table_black" width="100%" >
                <tr>
                    <td align="center"><?php echo($this->lang->line('jamabandi_for_surveyed_village')) ?> (Jamabandi for Surveyed Village)</td>
                </tr>
                <tr>
                    <td align="center"><?php echo $namedata[0]->district . "/" . $namedata[1]->subdiv . "/" . $namedata[2]->circle . "/" . $namedata[3]->mouza . "/" . $namedata[4]->lot_no . "/" . $namedata[5]->village . "/" . $namedata[6]->patta_type; ?></td>
                </tr>
            </table>

            <table class="table table-striped table-bordered" width="100%" >
                <tr>
                    <td align="center" colspan="2" height="20">  <?php echo($this->lang->line('patta_no')) ?>  </td>
                    <td align= "center" rowspan="3" height="78" width="150">   <?php echo($this->lang->line('pattadar_name')) ?>,<?php echo($this->lang->line('father_name')) ?>/<?php echo($this->lang->line('husband_name_and_address')) ?>  </td>
                    <td align="center" colspan=5 height="34">  &nbsp;&nbsp;<?php echo($this->lang->line('each_dag_land')) ?> &nbsp;  </td>
                    <td align="center" rowspan="3" height="73" width="30">  <?php echo($this->lang->line('revenue')) ?><br> </td>
                    <td align="center" rowspan="3" height="73" width="30">  <?php echo($this->lang->line('local_tax')) ?><br>  </td>
                    <td align="center" rowspan="3" height="100" width="170">  <?php echo($this->lang->line('comment')) ?>  </td>
                </tr>
                <tr>
                    <td align="center" rowspan="2" height="48"  width="30"> <?php echo($this->lang->line('old')) ?> </td>
                    <td align="center" rowspan="2" height="48"  width="30"> <?php echo($this->lang->line('new')) ?> </td>
                    <td align="center" rowspan="2" height="48"  width="30"> নং</td>
                    <td align="center" rowspan="2" height="48"  width="30"> <?php echo($this->lang->line('area_with_units')) ?> </td>
                    <td align="center" height="48" colspan="2"  width="30"> <?php echo($this->lang->line('class')) ?> </td>
                    <td align="center" rowspan="2" height="48" width="50"> কালি<br>(হে-আৰ-ছে) </td>
                </tr>
                <tr>
                    <td align="middle"  width="15">
                    <?php echo($this->lang->line('agriculture')) ?>
                    </td>
                    <td align="middle"  width="15">
                    <?php echo($this->lang->line('non_agriculture')) ?>
                    </td>

                </tr>
                <tr>
                    <td align="middle" height="24"> 1 </td>
                    <td  align="center" height="24"> 2 </td>
                    <td align="center" height="24"> 3</td>
                    <td align="center" height="24"> 4 </td>
                    <td  align="center" height="24"> 5 </td>
                    <td  align="center" height="24" colspan="2"> 6 </td>
                    <td  align="center" height="24"> 7 </td>
                    <td  align="center"  height="24"> 8 </td>
                    <td  align="center"  height="24"> 9 </td>
                    <td align="center"  height="24"> 10 </td>
                </tr>

                <tr>
                    <td align="middle">
                        <?php
                        $GrandlocaltaxTotal = '';
                        $GrandrevenueTotal = '';
                        $Grandbigha_total = '';
                        $Grandkatha_total = '';
                        $Grandlesa_total = '';
                        //  $details="";
                        $GrandtotalHAC1 = "";
                        $localtaxTotal = '';
                        $revenueTotal = '';
                        $bigha_total = '';
                        $katha_total = '';
                        $lesa_total = '';
                        $bigha_totall = '';
                        $katha_totall = '';
                        $lesa_totall = '';
                        foreach ($oldpno as $p):
                            ?>
                            <p><?php echo $this->utilityclass->cassnum($p->old_patta_no); ?> </p>
                        <?php endforeach; ?>
                    </td>
                    <td align="middle">
                        <?php
                        $pp = $this->session->userdata('patta_no');
                        if (is_numeric($pp)) {
                            echo $this->utilityclass->cassnum($pp);
                        } else {
                            echo $pp;
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (!empty($pattadarinf)) {
                            $i = 1;
                            foreach ($pattadarinf as $p):
                                ?>
                                <p><?php
                                    $pdarflag = $p->p_flag;
                                    $newpdar_name = $p->new_pdar_name;
                                    if ((($p->pdar_land_b) != '0') || (($p->pdar_land_k) != '0') || (($p->pdar_land_lc) != '0')) {
                                        $bkl = "(" . $p->pdar_land_b . "B-" . $p->pdar_land_k . "K-" . round($p->pdar_land_lc, 2) . "L) ";
                                    } else {
                                        $bkl = "";
                                    }
                                    if ($pdarflag == '1') {
                                        $pattadarName = '<span style="Color:#ff0000;text-decoration: line-through;">' . $p->pdar_name . '</span>';
                                    } elseif (($pdarflag == '1') and ( $newpdar_name == "N")) {
                                        $pattadarName = '<span style="Color:#ff0000;">' . $p->pdar_name . '</span>';
                                    } elseif (($pdarflag == null) and ( $newpdar_name == "N")) {
                                        $pattadarName = '<span style="Color:#ff0000;">' . $p->pdar_name . '</span>';
                                    } elseif ($newpdar_name == "N") {
                                        $pattadarName = '<span style="Color:#ff0000;">' . $p->pdar_name . '</span>';
                                    } elseif ($newpdar_name != "N") {
                                        $pattadarName = '<span style="Color:black;">' . $p->pdar_name . '</span>';
                                    }

                                    $pdar_serial_no = $p->pdar_sl_no . ") ";

                                    if (($p->pdar_sl_no == '0') || ($p->pdar_sl_no == '') || ($p->pdar_sl_no == null)) {
                                        $pdar_serial_no = $p->pdar_id . ") ";
                                    }

                                    if (($p->pdar_add1 != '') || ($p->pdar_add2 != '') || ($p->pdar_add3 != '') || ($p->pdar_add1 != '0') || ($p->pdar_add2 != '0') || ($p->pdar_add3 != '0')) {
                                        if ($sort_pdar_by == 'serial_no') {
                                            // 1 means sort by serial no
                                            echo $pdar_serial_no . '' . $pattadarName . "(" . $p->pdar_father . ")" . '<br>' . $p->pdar_add1 . "," . $p->pdar_add2 . "<br>" . $bkl;
                                        } else {
                                            echo $i++ . ') ' . $pattadarName . "(" . $p->pdar_father . ")" . '<br>' . $p->pdar_add1 . "," . $p->pdar_add2 . "<br>" . $bkl;
                                        }
                                    } else {
                                        if ($sort_pdar_by == 'serial_no') {
                                            // 1 means sort by serial no
                                            echo $pdar_serial_no . '' . $pattadarName . ",(" . $p->pdar_father . ")" . "<br>" . $bkl;
                                        } else {
                                            echo $i++ . ') ' . $pattadarName . ",(" . $p->pdar_father . ")" . "<br>" . $bkl;
                                        }
                                    }
                                    ?></p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <p><?php echo $this->utilityclass->cassnumfordags($p->dag_no); ?></p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <p><?php
                                    $les = round($p->dag_area_lc, 2);
                                    $bkl_ass = $p->dag_area_b . "-" . $p->dag_area_k . "-" . number_format($p->dag_area_lc, 2);
                                    echo $this->utilityclass->cassnum($bkl_ass);
                                    ?> </p>
                            <?php
                            endforeach;
                        } else {
                            echo "0-0-0";
                        }
                        ?>
                    </td>

                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <?php if ($p->class_code_cat == '01') { ?>
                                <p><?php
                                echo $p->land_type;
                            }
                                if($p->class_code_cat != '01'){
                                    print "-"; }
                                ?> </p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>

                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <?php if ($p->class_code_cat == '02') { ?>
                                <p><?php
                                echo $p->land_type;
                            }
                                if($p->class_code_cat != '02'){
                                    print "-"; }
                                ?> </p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                //var_dump($p);
                                ?>
                                <?php
                                $bigha_total = (int)($bigha_total) + (int)($p->dag_area_b);
                                $katha_total = (int)($katha_total) + $p->dag_area_k;
                                $lesa_total = (int)($lesa_total) + $p->dag_area_lc;
                                //echo "<br>";
                                $bigha_totall = (int)($bigha_totall) + $p->dag_area_b;
                                $katha_totall = (int)($katha_totall) + $p->dag_area_k;
                                $lesa_totall = (int)($lesa_totall) + $p->dag_area_lc;
                                //echo "<br>";
                                if ($lesa_total > 20) {
                                    $lesa = ($lesa_total / 20);
                                    $lesa_whole = floor($lesa);
                                    $lesa_fraction = $lesa - $lesa_whole;
                                    $lesa_fraction = $lesa_fraction * 20;
                                    $katha_total = $katha_total + $lesa_whole;
                                } else {
                                    $lesa_fraction = $lesa_total;
                                }
                                if ($katha_total > 4) {
                                    $katha = ($katha_total / 5);
                                    $katha_whole = floor($katha);
                                    $katha_fraction = $katha - $katha_whole;
                                    $katha_fraction = $katha_fraction * 5;
                                    $bigha_total = $bigha_total + $katha_whole;
                                    //$to_be_added_to_bigha=($grand_katha/5);
                                    //$grand_bigha=$bigha_total+$to_be_added_to_bigha;
                                } else {
                                    $katha_fraction = $katha_total;
                                }

                                $GrandtotalHAC = $this->utilityclass->get_Hec_Are_CAre($bigha_total, $katha_fraction, $lesa_fraction);
                                ?>
                                <?php
                                $H_A_C = $this->utilityclass->get_Hec_Are_CAre($p->dag_area_b, $p->dag_area_k, $p->dag_area_lc);
                                echo $this->utilityclass->cassnum($H_A_C) . '<br>';
                                ?></p>
                            <?php
                            endforeach;
                        } else {
                            echo "0-0-0";
                        }
                        ?>
                    </td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <?php $revenueTotal = (int)($revenueTotal) + $p->dag_revenue; ?>
                                <p><?php
                                    $rajah = number_format($p->dag_revenue, 2);
                                    echo $this->utilityclass->cassnum($rajah);
                                    ?> </p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            foreach ($daginfo as $p):
                                ?>
                                <?php $localtaxTotal = (int)($localtaxTotal) + $p->dag_localtax; ?>
                                <p><?php
                                    $local = number_format($p->dag_localtax, 2);
                                    echo $this->utilityclass->cassnum($local);
                                    ?> </p>
                            <?php
                            endforeach;
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        foreach ($remarkinf as $p):
                            ?>
                            <p><?php echo strip_tags($p->remark, '<p><br>'); ?> </p>
                            <?php
                            if($p->entry_mode=='O'){
                                ?>
                                <i class='small red'>Order(s) Manually Entered By CO:<?php $name=$this->utilityclass->getSelectedCOName($p->dist_code,$p->subdiv_code,$p->cir_code,$p->user_code);
                                    echo $name->username;
                                    ?>
                                    on dated <?=$p->entry_date?> </i>
                                <?php
                            }
                            if($p->entry_mode=='K'){
                                ?>
                                <i class='green red'>Above Remark(s) Edited By CO:<?php $name=$this->utilityclass->getSelectedCOName($p->dist_code,$p->subdiv_code,$p->cir_code,$p->user_code);
                                    echo $name->username;
                                    ?>
                                    on dated <?=$p->entry_date?> </i>
                            <?php } ?>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="middle">
                        <?php
                        if (!empty($daginfo)) {
                            $bigha_totall;
                            $katha_totall;
                            $lesa_totall;
                            $total_lessa = $this->utilityclass->Total_Lessa($bigha_totall, $katha_totall, $lesa_totall);
                            $tbkl = $this->utilityclass->Total_Bigha_Katha_Lessa($total_lessa);
                            echo $this->utilityclass->cassnum($tbkl[0] . "-" . $tbkl[1] . "-" . $tbkl[2]);
                            $Grandbigha_total = (int)($Grandbigha_total) + $bigha_total;
                            $Grandkatha_total = (int)($Grandkatha_total) + $katha_fraction;
                            $Grandlesa_total = (int)($Grandlesa_total) + $lesa_fraction;

                            if ($Grandlesa_total > 20) {
                                $lesa = ($Grandlesa_total / 20);
                                $lesa_whole = floor($lesa);
                                $lesa_fraction = $lesa - $lesa_whole;
                                $lesa_fraction = $lesa_fraction * 20;
                                $Grandkatha_total = $Grandkatha_total + $lesa_whole;
                            } else {
                                $lesa_fraction = $Grandlesa_total;
                            }
                            if ($Grandkatha_total > 4) {
                                $katha = ($Grandkatha_total / 5);
                                $katha_whole = floor($katha);
                                $katha_fraction = $katha - $katha_whole;
                                $katha_fraction = $katha_fraction * 5;
                                $Grandbigha_total = $Grandbigha_total + $katha_whole;
                            } else {
                                $katha_fraction = $Grandkatha_total;
                            }
                            $GrandtotalHAC1 = $this->utilityclass->get_Hec_Are_CAre($bigha_totall, $katha_totall, $lesa_totall);
                        } else {
                            echo "0-0-0";
                            $GrandtotalHAC1 = "0-0-0";
                        }
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td align="middle">
                        <?php echo $this->utilityclass->cassnum($GrandtotalHAC1); ?>
                    </td>
                    <td align="middle">
                        <?php
                        $reza = number_format($revenueTotal, 2);
                        echo  $this->utilityclass->cassnum($reza);
                        $GrandrevenueTotal = (int)($GrandrevenueTotal) + $revenueTotal;
                        ?>
                    </td>
                    <td align="middle">
                        <?php
//                        echo $localtaxTotal;
                         $mas = number_format($localtaxTotal, 2);
                        echo  $this->utilityclass->cassnum($mas);
                        $GrandlocaltaxTotal = (int)($GrandlocaltaxTotal) + $localtaxTotal;
                        ?>
                    </td>
                    <td></td>
                </tr>
            </table>

            <br>
            <span class='pull-right red'>Patta Last Updated on: <i class='fa fa-calendar'></i> <?php echo date('d/m/Y',strtotime($oldpno[0]->entry_date))?></span>
        </div>



        <br>
        <div class='dontshow' >
            <div class="form-group" style="text-align: center">
                <div class="col-sm-3" style="margin: 0 auto;float: none;margin-top: 20px;margin-bottom: 20px;">
                    <button class='btn btn-primary' onclick="myFunction()"><i class='fa fa-print'></i> Print this page</button>
                </div>
            </div>
            <div>
                <script>
                    function myFunction() {
                        $(".dontshow").hide();
                        window.print();
                        $(".dontshow").show();

                    }
                </script>
            </div>
        </div>


    </div>
    <br>

</div>





</body>
</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>