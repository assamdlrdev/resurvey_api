<td valign="top" height="409" width="15%"  rowspan=3 id=chitha_col_31><!--REMARKS-->
    <?php
    //var_dump($chithainf['col31']);
    ?>

    <?php
    $sro_hukum_no = 1;
    if (isset($chithainf['sro'])) {
        $size_of_sro_order = sizeof($chithainf['sro']);
        echo "<u class='text-danger'>SRO টোকা</u>";
        foreach ($chithainf['sro'] as $key => $sr):
            $newDatesro = date("d-m-Y", strtotime($chithainf['sro'][$key]['date_of_deed']));
            $headingsro = '(SR -' . $chithainf['sro'][$key]['name_of_sro'] . ')';
            ?>
            <p><?php //echo  $headingsro.'&nbsp;'.'&nbsp;' . $newDatesro . 'তাৰিখে' . '&nbsp;'.'&nbsp;'.'Deed No.'.'&nbsp;' . $chithainf['sro'][$key]['deed_no'] . 'নং দলিল যোগে' . '&nbsp;'.'&nbsp;' . $chithainf['sro'][$key]['reg_from_name'] .'&nbsp;'. 'পৰা' . '&nbsp;' . $chithainf['sro'][$key]['reg_to_name'] . 'ৰ নামত' . '(B' . $chithainf['sro'][$key]['dag_area_b'] . '- K' . $chithainf['sro'][$key]['dag_area_k'] . '- L' . $chithainf['sro'][$key]['dag_area_lc'] . ')' . 'মাটি' . '&nbsp;' . 'হস্তান্তৰ ' . '&nbsp;' . 'হয়.'               ?></p>
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
    ?>


    <?php
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
                //echo "<p class='text-danger'><u>হুকুম নং : </u></p><p>" . $r['case_no'] . " শ্রেণী সংশোধনীকৰণ প্রস্তাব " . $r['order_passed_designation'] . "উপায়ুক্ত মহোদয়ে " . date('d-m-Y', strtotime($r['dc_approval_date'])) . " তাৰিখে দিয়া অনুমোদন মৰ্মে " . $r['patta_no'] . " নং পট্টাৰ " . $r['dag_no'] . " নং দাগৰ শ্রেণী " . $r['present_land_class'] . "'ৰ পৰা " . $r['proposed_land_class'] . " লৈ পৰিবৰ্তন কৰা হ'ল ।</p>";
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
                    <!--                        <p>Reg No (<?php //echo $r['reg_deal_no'];              ?>)</p>
                    <p>Reg Date (<?php //echo date('d-m-Y',strtotime($r['reg_date']));             ?>)</p>-->
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
            <!-- <p>--><?php //echo $chithainf['encro'][$key]['encro_name'] . 'য়ে' . '&nbsp;' . '(' . $chithainf['encro'][$key]['encro_land_b'] . '-' . $chithainf['encro'][$key]['encro_land_k'] . '-' . $chithainf['encro'][$key]['encro_land_lc'] . ')' . 'মাটি' . '&nbsp;' . $newDate . 'তাৰিখৰ পৰা' . '&nbsp;' . $chithainf['encro'][$key]['land_used_by_encro'] . 'কাৰণত ব্যৱহাৰ কৰি আছে'; ?><!--</p>-->
            <p>
                <?php echo $chithainf['encro'][$key]['encro_name'] . '<br>' . 'মাটি ' . '(' . $chithainf['encro'][$key]['encro_land_b'] . '-' . $chithainf['encro'][$key]['encro_land_k'] . '-' . $chithainf['encro'][$key]['encro_land_lc'] . ')'. '<br>'  . $chithainf['encro'][$key]['land_used_by_encro']; ?>

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


    ?>
    <?php
    //var_dump($chithainf);
    //var_dump($chithainf['appeal147']);

    // if($chithainf['appeal147']){
    // foreach ($chithainf['appeal147'] as $b) {
    // //foreach ($appeal147 as $b) {
    // //	var_dump($b);
    // echo "<u class='red'>"."Appeal case U/S 147 "."</u><br>";
    // echo "<p>". " উপায়ুত্তৰ হুকুমমৰ্মে " . $b['dcfinal_note'] ."</p>";
    // $coname = $this->utilityclass->getSelectedCOName($this->session->userdata('dist_code'), $b['subdiv_code'], $b['cir_code'], $b['co_id']);
    // //var_dump($coname);
    // echo "<p><u class='text-danger'>স্বা (চক্ৰ বিষয়া )</u> : ". $coname->username ."</p>";
    // echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
    // // }
    // }
    // }
    //foreach ($chithainf['backlogs31'] as $backlog) {
    //    foreach ($backlog as $b) {
    //        //var_dump($b);
    //        echo "<u>"."৩১ নং স্তম্ভত পোনপটীয়া প্ৰৱেশ"."</u><br>";
    //        echo "<p>$b->remark</p>";
    //		 echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
    //    }
    //}

    //foreach ($chithainf['backlog_court_order'] as $court_order) {
    //    foreach ($court_order as $b) {
    //		echo "<u>"."১১৮ Court Order"."</u><br>";
    //        echo "<p>$b->remark</p>";
    //		 echo "<hr style='border-bottom: 2px solid #b3b0b0;'>";
    //    }
    //}
    ?>
</td>
<style>
    p{
        line-height:120%;
    }
</style>