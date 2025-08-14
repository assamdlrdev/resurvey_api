<style type="text/css" media="print">
    @page {
        size: auto;   /* auto is the initial value */
        /*margin: 0mm;  !* this affects the margin in the printer settings *!*/
        margin: 10mm 10mm 10mm 10mm;
        size: portrait; /* for page layout */
    }

    html {
        background-color: #FFFFFF;
        margin: 0px; /* this affects the margin on the html before sending to printer */
    }

    body {
        border: solid 1 px blue;
        /*margin: 10mm 15mm 10mm 15mm; !* margin you want for the content *!*/
        margin: 0px;

    }

    .unicode {
        font-size: 5px !important;
    }

    table.print-friendly tr td, table.print-friendly tr th {
        page-break-inside: avoid;
    }

    @media print {
        p {
            break-inside: avoid;
        }
    }
</style>
<div class="container-fluid form-top">
    <div class="row">
        <div class="col-lg-12 panel-body ">
            <p class="uni_text" style="text-align: center;margin-top: 14px;">[কামৰূপ, শিৱসাগৰ, নগাওঁ, দৰং আৰু লক্ষীমপুৰ জিলাৰ ডেপুটি
                কমিচনাৰ চাহাব বাহাদুৰৰ ব্যৱহাৰৰ কাৰণে]</p>
            <h2 style="text-align: center;margin-top: 16px;">PERIODIC KHHIRAJ PATTA</h2>
            <p class="bold uni_text" style="text-align: center;margin-top: 16px;">মিয়াদি খেৰাজী পট্টা নং
                : <?= $this->utilityclass->cassnum($patta_basic->patta_no) ?>
            </p>
            <div style="line-height: 30px;margin-top: 30px;margin-bottom: 30px;">
                <p class='uni_text' style="font-size:1em; text-align:justify;">
                    <p style="float: right;">তাৰিখঃ <?= $this->utilityclass->cassnum(date('d-m-y')) ?></p>
                    <br><br>
                    জিলা <b><?= $this->utilityclass->getDistrictName($patta_basic->dist_code) ?></b> মৌজা <b><?php
                        echo $this->utilityclass->getMouzaName($patta_basic->dist_code, $patta_basic->subdiv_code,
                            $patta_basic->cir_code, $patta_basic->mouza_pargona_code) ?></b> গাওঁ <b><?php
                        echo $this->utilityclass->getVillageName($patta_basic->dist_code, $patta_basic->subdiv_code, $patta_basic->cir_code,
                            $patta_basic->mouza_pargona_code, $patta_basic->lot_no, $patta_basic->vill_townprt_code) ?></b>
                    ।
                    মই ওপৰত লিখা জিলাৰ ডেপুটি কমিচনাৰে ইয়াৰ দ্বাৰা প্ৰচাৰ কৰো যে অসমৰ ভূমি আৰু ৰাজহ বিধিৰ ব্যস্থা আৰু
                    সময়ে সময়ে
                    কৰা তাৰ নিয়মৰ বশৱৰ্ত্তী হৈ প্ৰাদেশিক গভৰ্ণমেন্টৰ হকে এই পট্টাৰ সিপিঠিৰ তপচিলত লিখা মাটিখিনি
                    <b><?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->created_date))) ?></b> পৰা
                    <b><?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->upto_date))) ?></b>
                    তাৰিখলৈকে
                    <b><?= $this->utilityclass->cassnum($patta_basic->time_period) ?></b> বছৰৰ কাৰণে তলত লিখা ৰাজহ আৰু
                    স্থানীয় কৰত আপুনি
                    <b><?= $patta_basic->pattadar_name ?></b> অভিভাৱক <b><?= $patta_basic->guardian_name ?></b> আপোনাৰ
                    উত্তৰাধিকাৰী প্ৰতিনিধি আৰু
                    স্থলভিষিক্ত বিলাকে তলত লিখা কিস্তিমতে নিয়মিত সময়ত সম্পূৰ্ণৰূপে আদায় কৰিব।
                </p>

                <?php $total_locat_tax = null;
                foreach ($patta_basic_dag as $d2) {
                    $total_locat_tax = $total_locat_tax + $d2->dag_local_tax;
                } ?>


                <table class="table table-bordered table_black print-friendly" style="font-size:1em; padding-top: 10px;">
                    <tr>
                        <td rowspan="2"></td>
                        <td rowspan="2">যি তাৰিখে দিব লাগে</td>
                        <td colspan="2">দিবলগীয়া টকা</td>
                        <td rowspan="2">মুঠ</td>
                    </tr>
                    <tr>
                        <td>ৰাজহ</td>
                        <td>স্থানীয় কৰ</td>
                    </tr>
                    <tr>
                        <td>প্ৰথম কিস্তিৰ ধন</td>
                        <td>
                            <?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->installment1))) ?>
                        </td>
                        <td><?= $this->utilityclass->cassnum($patta_basic->revenue_to_be_paid1) ?> </td>
                        <td><?= $this->utilityclass->cassnum(number_format($total_locat_tax, 2)) ?> </td>
                        <td><?= $this->utilityclass->cassnum(number_format(($patta_basic->revenue_to_be_paid1 + $total_locat_tax), 2)) ?></td>
                    </tr>

                    <tr>
                        <td>দ্বিতীয় কিস্তিৰ ধন</td>
                        <td><?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->installment2))) ?></td>
                        <td><?= $this->utilityclass->cassnum($patta_basic->revenue_to_be_paid2) ?> </td>
                        <td><?= $this->utilityclass->cassnum(number_format($total_locat_tax, 2)) ?></td>
                        <td><?= $this->utilityclass->cassnum(number_format(($patta_basic->revenue_to_be_paid2 + $total_locat_tax), 2)) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>মুঠ</td>
                        <td><?php $total_revenue = $patta_basic->revenue_to_be_paid1 + $patta_basic->revenue_to_be_paid2;
                            echo $this->utilityclass->cassnum($total_revenue) ?></td>
                        <td><?= $this->utilityclass->cassnum(number_format(($total_locat_tax + $total_locat_tax), 2)) ?></td>
                        <td><?= $this->utilityclass->cassnum(number_format((($patta_basic->revenue_to_be_paid2 + $total_locat_tax) + ($patta_basic->revenue_to_be_paid1 + $total_locat_tax)), 2)) ?></td>
                    </tr>
                </table>

                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ২. প্ৰাদেশিক গৱৰ্ণমেন্টে সম্প্ৰতি ধাৰ্য্য কৰা নিৰিখ মতে এই পট্টাত স্থানীয় কৰ লগোৱা হৈছে। সময়ে সময়ে
                    এই নিৰিখ প্ৰাদেশিক গৱৰ্ণমেন্টে আইন
                    অনুসাৰে পৰিৱৰ্ত্তন কৰিব পাৰে।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৩. এই পট্টাৰ মাটি কেৱল খেতি সম্পৰ্কীয় কামৰ বাবেহে ব্যৱহাৰ কৰিব এনে ভাবি ইয়াত লিখা ৰাজহ আৰু স্থানীয়
                    কৰ ধাৰ্য কৰা হৈছে।
                    যদি এই মাটি বা তাৰ কোনো অংশ খেতি সম্পৰ্কীয় কামত বাজে অইন কোনো কামত ব্যৱহাৰ কৰিছে বুলি প্ৰাদেশিক
                    গৱৰ্ণমেন্টে বিবেচনা
                    কৰে তেনেহ’লে তৎক্ষণাত সেই মাটিৰ ৰাজহ আৰু স্থানীয় কৰ নকৈ ধাৰ্য্য কৰিব পাৰিব আৰু আপুনি আপোনাৰ
                    উত্তৰাধিকাৰী, প্ৰতিনিধি আৰু
                    স্থলভিষিক্ত বিলাকে সেই মাটিৰ নিমিত্তে এতিয়াই নিৰ্দিষ্ট কৰি দিয়া পিছত নকৈ লগোৱা নিৰিখ মতে ৰাজহ আৰু
                    স্থানীয় কৰ দিবৰ নিমিত্তে দায়ী থাকিব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৪. উক্ত মাটিৰ সীমাইদি বা ওপৰেদি বৈ যোৱা নৈ আৰু জান বিলাকত য’ত বছৰৰ কোনো সময়ত নাও চলাব পাৰি বা কাঠ
                    উটাই নিব
                    পাৰি সেই বিলাকত চলাচল কৰিবৰ নিমিত্তে সৰ্বসাধাৰণৰ স্বত্ব থাকিব আৰু সেই বিলাক নৈ বা জানৰ দুয়ো কাষে
                    ২০ ফুট বহল এডোখৰ মাটি সৰ্বসাধাৰণৰ নাও টানিবৰ বা বান্ধিবৰ নিমিত্তে বস্তু-বেহানি তোলাপাৰা কৰিবৰ
                    নিমিত্তে আৰু পানীত চলাচল
                    কৰোতে কাঠ উটাই আনোতে আৰু মাছ মাৰোতে যিবিলাক কৰিবৰ আৱশ্যক হয়, সেইবিলাকৰ নিমিত্তে ব্যৱহাৰ কৰিব
                    পাৰিব। </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৫. আলি মেৰামত কৰিবৰ নিমিত্তে প্ৰাদেশিক গৱৰ্ণমেন্টে বা গৱৰ্ণমেন্টেৰ কাৰ্যকাৰক সকলো কোনো লোকচান
                    নিদিয়াকৈ প্ৰাদেশিক আৰু স্থানীয়
                    আলিৰ কাষৰ পৰা ৩৫ ফুটৰ ভিতৰতে মাটি কটাই আনিবৰ স্বত্ব বাহাল থাকিব। আৰু সেই মাটি বা তাৰ কোনো অংশত তাত
                    থকা শস্
                    লাগনী গছ বা ঘৰৰ মূল্যত বাজে আন কোনো লোকচানি নিদিয়াকৈ লব পৰিব। </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৬. যদি আপুনি আচল খেতিয়ক হয়, তেন্তে মিয়াদি পট্টাৰ আটাইখিনি মাটি বা তাৰ ভিতৰত কোনো দাগ বা দাগৰ অংশ
                    ডেপুটি কমিচনাৰ
                    চাহাবৰ মঞ্জুৰী হুকুম আগেয়ে লৈহে আচল খেতিয়ক নহয় এনে কোনো মানুহক হস্তান্তৰ কৰিব পাৰিব। </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৭. যদি আপুনি এই পট্টাত ভুক্ত মাটিৰ কোনো এটা দাগ বা আটাইখিনি মাটি ইস্তফা দিব খোজে, তেনেহ’লে ইস্তফা
                    দিবৰ নিমিত্তে যি তাৰিখ
                    নিৰ্দ্ধাৰিত কৰা হয় সেই তাৰিখ বা তাৰ আগেয়ে আপুনি ইস্তফা দিব খোজা কথা লিখি জাননী দিব।</p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৮. আপোনাৰ পট্টাৰ সমূদায় বা তাৰ কোনো দাগ মাটি ইস্তফা দিলে তাত লগোৱা ৰাজহ আৰু
                    সোধাব নালাগে আৰু পট্টাৰ মুঠ ৰাজহৰ বা ইস্তফা দিয়া দাগত লগোৱা ৰাজহ বাদ যাব। </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৯. ওপৰত উল্লেখ কৰা নিয়ম বিলাকৰ কোনো নিয়ম ভংগ কৰিলে এই পট্টা ৰদ হ’ব পাৰিব।</p>
                <?php $instalment_year = null;
                $instalment2_year = date('Y', strtotime($patta_basic->installment2));
                $instalment2_month = date('m', strtotime($patta_basic->installment2));
                if ($instalment2_year == date('YYYY')) {
                    if ($instalment2_month < 3) {
                        $instalment_year = $instalment2_year;
                    }
                } elseif ($instalment2_year < date('YYYY')) {
                    $instalment_year = $instalment2_year + 1;
                } ?>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ১০. নতুনকৈ নিৰ্দ্ধাৰিত কৰা ৰাজহ পুনৰ <?= $this->utilityclass->cassnum($instalment_year); ?> চনৰ ৩১
                    মাৰ্চ তাৰিখে পৰিৱৰ্ত্তন হ’ব পাৰিব।
                </p>

                <table class="table table-bordered table_black print-friendly pt-2" style="font-size:1em;">
                    <tr>
                        <td colspan="100%" class="center uni_text" style="font-size:1em;"><b>তপচিল</b></td>
                    </tr>
                    <tr>
                        <td>দাগৰ ক্ৰমিক নম্বৰ</td>
                        <td>প্ৰত্যেক দাগৰ শ্ৰেণী</td>
                        <td>বিঘামতে প্ৰত্যেক দাগৰ মাটিৰ পৰিমাণ</td>
                        <td>প্ৰত্যেক দাগৰ লগোৱা ৰাজহ</td>
                        <td>মন্তব্য</td>
                    </tr>
                    <?php $total_revenue = null;
                    foreach ($patta_basic_dag as $d): ?>
                        <tr>
                            <td><?= $this->utilityclass->cassnum($d->dag_no) ?></td>
                            <td><?= $this->utilityclass->getLandClassCode($d->land_class_code) ?></td>
                            <?php if (in_array($d->dist_code, json_decode(BARAK_VALLEY))): ?>

                                <td><?= $this->utilityclass->cassnum($d->dag_area_b) . " বি: " .
                                    $this->utilityclass->cassnum($d->dag_area_k) . " ক: " .
                                    $this->utilityclass->cassnum(number_format($d->dag_area_lc, 2)) . " চ: " .
                                    $this->utilityclass->cassnum($d->dag_area_g) . " গ: " .
                                    $this->utilityclass->cassnum($d->dag_area_kr) . " কা: " ?>
                                </td>

                            <?php else: ?>
                                <td><?= $this->utilityclass->cassnum($d->dag_area_b) . " বি: " .
                                    $this->utilityclass->cassnum($d->dag_area_k) . " ক: " .
                                    $this->utilityclass->cassnum(number_format($d->dag_area_lc, 2)) . " লে: " ?>
                                </td>
                            <?php endif; ?>
                            <td><?= $this->utilityclass->cassnum($d->dag_revenue) ?></td>
                            <td></td>
                        </tr>
                        <?php ?>
                        <?php $total_revenue = $total_revenue + $d->dag_revenue; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">মুঠ</td>
                        <td><?= $this->utilityclass->cassnum($total_revenue) ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">স্থানীয় কৰ যোগদিয়া</td>
                        <td><?= $this->utilityclass->cassnum(number_format($total_locat_tax, 2)) ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">সৰ্বমুঠ</td>
                        <td><?= $this->utilityclass->cassnum(number_format(($total_revenue + $total_locat_tax), 2)) ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <div class="form-group no-print" style="text-align: center;">
                <button type="submit" class="btn btn-primary" onclick="return myFunction()"><i class="fa fa-print"></i>&nbsp;Print
                    Patta
                </button>
                <a href="<?php echo base_url(); ?>index.php/PattaController/selectPattaView" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i>&nbsp;Go to Search Patta
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    function myFunction() {
        $(".dontshow").hide();

        window.print();
        $(".dontshow").show();
        document.getElementById("mainMenu").disabled = false;
    }
</script>