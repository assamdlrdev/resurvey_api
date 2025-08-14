

<?php echo $current_code;?>
<td valign="top" height="409" width="15%"  rowspan=3 id=chitha_col_31><!--REMARKS-->

<?php 
if(isset($chithainf['sro'])){
	echo "<u class='text-danger'>SRO টোকা</u>";
foreach($chithainf['sro']  as $key => $sr):
    $newDatesro = date("d-m-Y", strtotime($chithainf['sro'][$key]['date_of_deed']));
    ?>
	<p><?php echo $chithainf['sro'][$key]['name_of_sro'].'ট'.'&nbsp;'.$newDatesro.'তাৰিখে'.'&nbsp;'.$chithainf['sro'][$key]['deed_no'].'নং দলিল যোগে'.'&nbsp;'.$chithainf['sro'][$key]['reg_from_name'].'পৰা'.'&nbsp;'.$chithainf['sro'][$key]['reg_to_name'].'ৰ নামত'.'('. $chithainf['sro'][$key]['dag_area_b'].'-'. $chithainf['sro'][$key]['dag_area_k'].'-'. $chithainf['sro'][$key]['dag_area_lc'].')'.'মাটি'.'&nbsp;'.'হস্তান্তৰ '.'&nbsp;'.'হয়.'?></p>
        
<?php endforeach;
}
?>
<hr>
    <?php $order_count = 1;
    foreach ($chithainf['col31'] as $remark):
        ?>
        <?php foreach ($remark as $r): ?>
			  
            <?php if (sizeof($r) > 0): ?>
            <?php if($r['remark_type_code'] == '08'):?>
                    <?php echo "<p class='text-danger'><u>চক্ৰ বিষয়াৰ হুকুম নং : </u></p>".$r['case_no']." শ্রেণী সংশোধনীকৰণ প্রস্তাব "
                    . "উপয়াক্ত মহোদয়ে ".date('d-m-Y', strtotime($r['dc_approval_date']))." তাৰিখে দিয়া অনুমোদন মৰ্মে ".$r['patta_no']." নং পট্টাৰ ".$r['dag_no']." নং দাগৰ শ্রেণী ".$r['present_land_class']." পৰা ".$r['proposed_land_class']." লৈ পৰিবৰ্তন কৰা হ'ল ।<hr>";  ?>
            <?php endif; ?>
			<?php if($r['remark_type_code'] == '09'):?>
					<?php echo "<p class='text-danger'><u>হুকুম নং : </u></p> চক্ৰ বিষয়াৰ ".$r['case_no']." নং NR কেছৰ প্ৰস্তাৱৰ "
                    . date('d-m-Y',strtotime($r['order_date']))."  তাৰিখে দিয়া অনুমোদন মৰ্মে ".$r['patta_no']." নং পট্টা আৰু ".$r['dag_no']."  নং দাগৰ পপট্টাৰ প্ৰকাৰ একচণাৰ পৰা চৰকাৰীলৈ পৰিবৰ্ত্তন কৰাৰ হুকুম দিয়া হল ।<hr>";  ?>
            <?php endif; ?>
    
    
            <?php if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '03')): ?><!--mutation-->
                    <u class='text-danger'><?php echo "হুকুম নং: " . $order_count++; ?><br></u>
                    <p>চক্ৰ বিষয়াৰ  <br>  
                        <?php echo date('d-m-Y', strtotime($r['order_date'])); ?> 
                        তাৰিখ'ৰ   
                        <?php
                        $order_type = $r['ord_type_code'];
                        echo $this->utilityclass->getOfficeMutType($order_type) . " নং  ";
                        ?>
                        <?php echo $r['ord_no'] . " 'ৰ হুকুমমৰ্মে এই দাগৰ "; ?>

                        <?php
                        echo $r['bigha'] . " বিঘা ";
                        echo $r['katha'] . " কঠা ";
                        echo round($r['lessa'], 2) . " লেছা মাটি ";
                        ?>

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
                            }
                            elseif ($count == sizeof($r['alongwith_name']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
                            ?>
							
                         
                    <?php endforeach; 
					if(sizeof($r['alongwith_name']) != '0')
					{
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
                            }
                            elseif ($count == sizeof($r['inplace_of_name']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
                            
                            ?>
						
						
                    <?php endforeach;
					if(sizeof($r['inplace_of_name']) != '0')
					{
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
                            }
                            elseif ($count == sizeof($r['infav']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
                        
                        ?>
                <?php endforeach; ?>
                    
                    <?php if ($r['ord_type_code'] == '03'): ?>
							'ৰ নামত নামজাৰী কৰা হ’ল |
                    <?php endif; ?>
                        <p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                        <p><u class='text-danger'>চক্ৰ বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
						<p>
						<?php
						if ($r['reg_deal_no'] != "")
						{
							echo "Reg No (".$r['reg_deal_no'].")";
						}
						?>
						</p>
						<p>
						<?php
						if ($r['reg_date'] != "")
						{
							echo "Reg Date (".date('d-m-Y',strtotime($r['reg_date'])).")";
						}
						?>
						</p>
                    <hr>
                    <?php endif; ?>
                    <?php if ($r['ord_type_code'] == '01'): ?>
                   
                    <u class='text-danger'><?php echo "হুকুম নং: " . $order_count++; ?></u><br>
                    <p>চক্ৰ বিষয়াৰ  </p>
                    <?php echo $r['ord_no']."  নং  " ; ?>
                    <?php
                        $order_type = $r['ord_type_code'];
                        echo $this->utilityclass->getOfficeMutType($order_type). " গোচৰৰ  " ;
                   ?>
                    
                    <?php echo date('d-m-Y',  strtotime($r['order_date'])); ?>  তাৰিখৰ হুকুমমৰ্মে
                    
                  
                    <?php if($r['premi_chal_recpt']!='003'):?>
						<?php echo $r['patta_no']. " নং একচনা পট্টাৰ আৰু ". $r['dag_no'] . " নং দাগৰ  "; ?>
                        <?php echo $r['land_area_b'] . " বিঘা  " . $r['land_area_k'] . " কঠা  " . round($r['land_area_lc'], 2) . " লেছা মাটিৰ প্রিমিয়াম ". round($r['premium'], 2)." টকা ".$r['premi_chal_recpt_no']." নং ".$r['premi_chal_name']." যোগে "; ?> 
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
                            }
                            elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
						?>
                    <?php endforeach; ?>   
                    ৰ পৰা আদায় হোৱাত 
                    <?php endif;?>
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
                            }
                            elseif ($count == sizeof($r['ord_onbehalf_of']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
                            ?>
                    <?php endforeach; ?>
						ৰ নামত <?php echo $r['land_area_b'] . " বিঘা  " . $r['land_area_k'] . " কঠা  " . round($r['land_area_lc'], 2) . " লেছা "; ?> মাটি  পৃঠক
						<?php echo $r['new_patta_no'] . " নং ".$r['patta_type']."  পট্টা আৰু " . $r['new_dag_no']; ?> নং দাগে ম্যাদীকৰণ কৰা হল |
						<p><u class='text-danger'>লাট মণ্ডল :</u><br>(<?php echo $r['lm_name']; ?>)</p>
                        <p><u class='text-danger'>চক্ৰ বিষয়া :</u><br>(<?php echo $r['username']; ?>)</p>
            <?php endif; ?>
                    
                    <?php if (($r['remark_type_code'] == '01') && ($r['ord_type_code'] == '04')): ?>
               
                    <p><u class="text-danger"><?php echo "হুকুম নং: " . $order_count++; ?></u></p>
                    <p>চক্ৰ বিষয়াৰ    
                        <?php echo date('d-m-Y', strtotime($r['order_date'])); ?> 
                        তাৰিখৰ   
                        <?php
                        $order_type = $r['ord_type_code'];
                        echo $this->utilityclass->getOfficeMutType($order_type) . " নং  ";
                        ?>
                        <?php echo $r['ord_no'] . " ৰ হুকুমমৰ্মে এই দাগৰ "; ?>

                        <?php
                        echo $r['bigha'] . " বিঘা ";
                        echo $r['katha'] . " কঠা ";
                        echo round($r['lessa'], 2) . " লেছা মাটি   ";
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
                            }
                            elseif ($count == sizeof($r['infav']) - 1) {
                                echo " আৰু ";
                                $count++;
                            }
							else{
								echo " ";
							}
						
						
                        ?>
                <?php endforeach; ?>'ৰ নামত 
					<?php echo $r['new_patta_no'] . " নং  পট্টা আৰু " . $r['new_dag_no']; ?> নং দাগ কৰা হল |
                    <?php if ($r['ord_type_code'] == '04'): ?>
                   
                    <?php endif; ?>
                        <p><u class="text-danger">লাট মণ্ডল :-</u>(<?php echo $r['lm_name']; ?>)</p>
                        <p><u class="text-danger">চক্ৰ বিষয়া :-</u>(<?php echo $r['username']; ?>)</p>
<!--                        <p>Reg No (<?php //echo $r['reg_deal_no'];?>)</p>
                        <p>Reg Date (<?php //echo date('d-m-Y',strtotime($r['reg_date']));?>)</p>-->
                    <hr>
                    <?php endif; ?>
                    <?php if ($r['ord_type_code'] == '01'):?>
                    <?php if($r['premi_chal_recpt']=='003'):?>
                        <?php echo "<hr><p class='text-danger'><u>টোকা :</u></p> আবেদনকাৰীয়ে প্রিমিয়াম আদায় নিদিয়া বাবে ".round($r['premium'], 2)." টকা ৰাজহৰ বকেয়া হিচাবে আদায় লোৱা হওঁক ।"?>
                    <?php endif;?>
                    <?php endif;?>
        <?php endif; ?>
       
    <?php endforeach; ?> 
	
<?php endforeach; ?>

<?php 
if(isset($chithainf['lmnote'])){
	echo "<u class='text-danger'>মণ্ডলৰ টোকা</u>";
foreach($chithainf['lmnote']  as $key => $enc):
   
    ?>
	<p><?php echo $chithainf['lmnote'][$key]['lm_note'];?></p>
        
<?php endforeach; }?>
<hr>

<?php
if(isset($chithainf['encro'])){
		echo "<u class='text-danger'>বেদখলকাৰীৰ টোকা</u>";
 foreach($chithainf['encro']  as $key => $enc):
     $newDate = date("d-m-Y", strtotime($chithainf['encro'][$key]['encro_since']));
    ?>
	<p><?php echo $chithainf['encro'][$key]['encro_name'].'য়ে'.'&nbsp;'.'('. $chithainf['encro'][$key]['encro_land_b'].'-'. $chithainf['encro'][$key]['encro_land_k'].'-'. $chithainf['encro'][$key]['encro_land_lc'].')'.'মাটি'.'&nbsp;'.$newDate.'তাৰিখৰ পৰা'.'&nbsp;'. $chithainf['encro'][$key]['land_used_by_encro'].'কাৰণত ব্যৱহাৰ কৰি আছে';?></p>
  
<?php endforeach; }?>

<hr>
<?php


 //foreach ($chithainf['archeo'] as $key => $archeo):

                                       // echo  '<u>'.$chithainf['archeo'][$key]['hist_description_nme'] .': </u><br>'
                                          //  .$chithainf['archeo'][$key]['archeo_decribed']. '<br>' .'('. $chithainf['archeo'][$key]['archeo_b'] . '-' . $chithainf['archeo'][$key]['archeo_k'].'-'.$chithainf['archeo'][$key]['archeo_lc'].')'.'<hr>';
                                    //endforeach;
									
									
									
//foreach ($chithainf['archeo'] as $key => $archeo):
                             //   $code =  $chithainf['archeo'][$key]['archeo_hist_code'];
                               //if($code =='h'){
                                 // $plcnme = "বুৰঞ্জীমুলক";
                              // }
                              // elseif($code =='a')
                              // {
                               //  $plcnme =  "পুৰাতাক্তিক কৃতীচিহ";
                               //}
                               
                              // echo  "Archaeological Description".' : '. $plcnme . '<br>' . $chithainf['archeo'][$key]['archeo_b'] . ' ( বিঘা ) -' . $chithainf['archeo'][$key]['archeo_k'].' ( কঠা ) -'.$chithainf['archeo'][$key]['archeo_lc'].' ( লেছা ) ';
                                   // endforeach;
									?>
</td>