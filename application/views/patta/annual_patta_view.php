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
            <h2 style="text-align: center;margin-top: 16px;">ANNUAL KHIRAJ PATTA</h2>
            <p style="text-align: center;">(For use in the tribal Belts & blocks)<br>
             <span class="uni_text"> বছৰেকীয়া খেৰাজী পট্টা নং</span>
                : <?= $this->utilityclass->cassnum($patta_basic->patta_no) ?>
                <br>
                (জনজাতীয় বেষ্টনী আৰু খণ্ডৰ বাবে)
            </p>
            <div style="line-height: 30px;margin-top: 40px;margin-bottom: 30px;">
                <p class='uni_text' style="font-size:1em; text-align:justify;">
                    <p style="float: right;">তাৰিখঃ <?= $this->utilityclass->cassnum(date('d-m-y')) ?></p>
                    <br><br>
                    জিলা <b><?= $this->utilityclass->getDistrictName($patta_basic->dist_code) ?></b> মৌজা <b><?php
                        echo $this->utilityclass->getMouzaName($patta_basic->dist_code, $patta_basic->subdiv_code,
                            $patta_basic->cir_code, $patta_basic->mouza_pargona_code) ?></b> গাওঁ <b><?php
                        echo $this->utilityclass->getVillageName($patta_basic->dist_code, $patta_basic->subdiv_code, $patta_basic->cir_code,
                            $patta_basic->mouza_pargona_code, $patta_basic->lot_no, $patta_basic->vill_townprt_code) ?>
                        ।</b>
                    <br><br>

                    <b><?= $patta_basic->pattadar_name ?></b> অভিভাৱক <b><?= $patta_basic->guardian_name ?></b>
                    <br><br>
                    যিহেতু অসম ভূমি ও ৰাজহ আইনৰ বিধি ব্যৱস্থা অনুসাৰে আৰু বিশেষকৈ দশম অধ্যায়ৰ সংশোধিত আইনমতে (অসম
                    সংশোধিত আইন ১৫/১৯৪৭ চন) ইয়াৰ
                    লগত দিয়া তপচিলত লিখা মাটি আপুনি দখল কৰিছে, তলত লিখা চৰ্ত্ত মতে আপোনাক,
                    ইং <?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->created_date))) ?> পৰা
                    <?= $this->utilityclass->cassnum(date('d-m-y', strtotime($patta_basic->upto_date))) ?> ৰাজহ
                    <?= $this->utilityclass->cassnum($patta_basic->time_period) ?> বছৰৰ
                    বাবে এই পাট্টা দিয়া হ’ল।
                </p>

                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ১. ইয়াৰ লগত দিয়া তপচিল বৰ্ণিত খাজনা আৰু স্থানীয় কৰ আপুনি নিৰ্ধাৰিত সময়ত আদায় দিব লাগিব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ২. এই বছৰৰ কাৰণে তপচিলত বৰ্ণিত মাটিত আপোনাৰ ব্যৱহাৰ ও দখলি স্বত্ব থাকিব, কিন্তু হস্তান্তৰ কৰিবৰ
                    ক্ষমতা নাথাকিব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৩. কথিত বছৰৰ বাহিৰে তপচিলত লিখা মাটিত আপোনাৰ কোনো প্ৰকাৰ স্বত্ব হক নাথাকিব। এই মাটি আপুনি অইন লোকক
                    দান,
                    বিক্ৰী বা দখল স্বত্ব হস্তান্তৰ কৰিব নোৱাৰিব। হস্তান্তৰ কৰিলে আনকি পট্টাৰ চলিত বছৰৰ ভিতৰতে পট্টা ৰদ
                    হ’ব পাৰে আৰু
                    তেতিয়া আপুনি মাটিৰ ব্যৱহাৰ দখল স্বত্ব হেৰুৱাব। জনজাতীয় বেষ্টনী আৰু খণ্ডৰ ভিতৰত প্ৰচলিত ভূমি নীতিসমূহ
                    ভংগ নকৰাকৈ
                    বা নিজ পৰিয়ালৰ লোকে যদি এই মাটি ভোগ দখল কৰি থাকে, নিয়মিতভাবে খাজনা পৰিশোধ কৰি থাকে বা ইতিমধ্যে বা ২৮
                    ফেব্ৰুৱাৰীৰ আগতে এই মাটিৰ ইস্তফা দিবলৈ আপুনি আবেদন নকৰে তেতিয়া হ’লে জিলাৰ উপায়ুক্তই ইয়াৰ পিছৰ বছৰতো
                    আপোনাৰ নামত পট্টাখন পুনৰ এবছৰৰ কাৰণে দিবলৈ দায়ী থাকিব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৪. তপচিলত লিখা মাটি খেতিৰ উপযোগী কৰিবলৈ আপুনি তাত থকা জঙ্গল চাফা কৰিব পাৰিব, কিন্তু বেৰে এক ফুটতকৈ
                    ডাঙৰ শিমলু গছ বা ডাল কাটিব নোৱাৰিব আৰু পুৰা নিৰিখে কাঠৰ মাচুল আগেয়ে আদায় নকৰাকৈ কোনো কাঠ
                    বিক্ৰী কিম্বা বিক্ৰীৰ বাবে স্থানান্তৰ কৰিব নোৱাৰিব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৫. এই পট্টাৰ ম্যাদ চলি থাকোতে যদি তপচিলত লিখা সমূদায় মাটি নাইবা তাৰ কোনো অংশ চৰকাৰী কামৰ বাবে
                    প্ৰয়োজন হয়
                    তেতিয়া হ’লে সেই মাটি আৰু ওপৰত থকা ফচলৰ বাবে বিধি সন্মতভাবে ক্ষতিপূৰণ দি আপোনাৰ পৰা এৰুৱাই লোৱা হ’ব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify;">
                    ৬. এই পট্টাৰ ম্যাদ চলি থাকোতে যদি আপোনাৰ মৃত্যু হয় আপোনাৰ উত্তৰাধিকাৰী সকলে সেই বছৰৰ বাকী সময়ৰ কাৰণে
                    আপোনাৰ স্বত্ব পাব।
                </p>
                <p class='uni_text' style="font-size:1em; text-align:justify; ">
                    ৭. অসম ভূমি ও ৰাজহ আইনৰ দশম (১০) অধ্যায়ৰ নিৰ্দ্দেশ অনুযায়ী এই পট্টা দিয়া হ’ল। এই অধ্যায়ৰ ধাৰাসমূহ
                    আৰু সময়ে সময়ে
                    প্ৰৱৰ্ত্তন কৰা নিয়ম সমূহ আপুনি মানিব লাগিব। অন্যথা এই মাটিৰ স্বত্ব ও দখল হেৰুৱাব লাগিব।
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
                    <?php $total_revenue = $total_locat_tax = null;
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
                        <?php $total_revenue = $total_revenue + $d->dag_revenue;
                        $total_locat_tax = $total_locat_tax + $d->dag_local_tax; ?>
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