<style>
    table.table-bordered {
        border: 1px solid black;
        margin-top: 20px;
    }

    table.table-bordered>thead>tr>th {
        border: 1px solid black;
    }

    table.table-bordered>tbody>tr>td {
        border: 1px solid black;
    }

    .bg-info-light {
        background-color: #bfdbfe;
    }

    .bg-indigo-light {
        background: #c4b5fd;
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }

    .vendorListHeading th {
        background-color: #1a4567 !important;
        color: white !important;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row mb-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-between align-items-center">
            <div>
                <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
            </div>
            <div>
                <a href="<?= base_url('index.php/reports/DagReportController/
				downloadExcelDagDetails?d=' . $location['dist_code'] . '&s=' . $location['subdiv_code']
                                . '&c=' . $location['cir_code'] . '&m=' . $location['mouza_pargona_code']
                                . '&l=' . $location['lot_no'] . '&v=' . $location['vill_townprt_code']) ?>">
                    <button class="btn btn-sm btn-primary">
                        Download Excel
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                        </svg>
                    </button>
                </a>
                <button class="btn btn-sm btn-default" id="generatePdf">
                    Print PDF
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#909090" d="m24.1 2.072l5.564 5.8v22.056H8.879V30h20.856V7.945L24.1 2.072" />
                        <path fill="#f4f4f4" d="M24.031 2H8.808v27.928h20.856V7.873L24.03 2" />
                        <path fill="#7a7b7c" d="M8.655 3.5h-6.39v6.827h20.1V3.5H8.655" />
                        <path fill="#dd2025" d="M22.472 10.211H2.395V3.379h20.077v6.832" />
                        <path fill="#464648" d="M9.052 4.534H7.745v4.8h1.028V7.715L9 7.728a2.042 2.042 0 0 0 .647-.117a1.427 1.427 0 0 0 .493-.291a1.224 1.224 0 0 0 .335-.454a2.13 2.13 0 0 0 .105-.908a2.237 2.237 0 0 0-.114-.644a1.173 1.173 0 0 0-.687-.65a2.149 2.149 0 0 0-.409-.104a2.232 2.232 0 0 0-.319-.026m-.189 2.294h-.089v-1.48h.193a.57.57 0 0 1 .459.181a.92.92 0 0 1 .183.558c0 .246 0 .469-.222.626a.942.942 0 0 1-.524.114m3.671-2.306c-.111 0-.219.008-.295.011L12 4.538h-.78v4.8h.918a2.677 2.677 0 0 0 1.028-.175a1.71 1.71 0 0 0 .68-.491a1.939 1.939 0 0 0 .373-.749a3.728 3.728 0 0 0 .114-.949a4.416 4.416 0 0 0-.087-1.127a1.777 1.777 0 0 0-.4-.733a1.63 1.63 0 0 0-.535-.4a2.413 2.413 0 0 0-.549-.178a1.282 1.282 0 0 0-.228-.017m-.182 3.937h-.1V5.392h.013a1.062 1.062 0 0 1 .6.107a1.2 1.2 0 0 1 .324.4a1.3 1.3 0 0 1 .142.526c.009.22 0 .4 0 .549a2.926 2.926 0 0 1-.033.513a1.756 1.756 0 0 1-.169.5a1.13 1.13 0 0 1-.363.36a.673.673 0 0 1-.416.106m5.08-3.915H15v4.8h1.028V7.434h1.3v-.892h-1.3V5.43h1.4v-.892" />
                        <path fill="#dd2025" d="M21.781 20.255s3.188-.578 3.188.511s-1.975.646-3.188-.511Zm-2.357.083a7.543 7.543 0 0 0-1.473.489l.4-.9c.4-.9.815-2.127.815-2.127a14.216 14.216 0 0 0 1.658 2.252a13.033 13.033 0 0 0-1.4.288Zm-1.262-6.5c0-.949.307-1.208.546-1.208s.508.115.517.939a10.787 10.787 0 0 1-.517 2.434a4.426 4.426 0 0 1-.547-2.162Zm-4.649 10.516c-.978-.585 2.051-2.386 2.6-2.444c-.003.001-1.576 3.056-2.6 2.444ZM25.9 20.895c-.01-.1-.1-1.207-2.07-1.16a14.228 14.228 0 0 0-2.453.173a12.542 12.542 0 0 1-2.012-2.655a11.76 11.76 0 0 0 .623-3.1c-.029-1.2-.316-1.888-1.236-1.878s-1.054.815-.933 2.013a9.309 9.309 0 0 0 .665 2.338s-.425 1.323-.987 2.639s-.946 2.006-.946 2.006a9.622 9.622 0 0 0-2.725 1.4c-.824.767-1.159 1.356-.725 1.945c.374.508 1.683.623 2.853-.91a22.549 22.549 0 0 0 1.7-2.492s1.784-.489 2.339-.623s1.226-.24 1.226-.24s1.629 1.639 3.2 1.581s1.495-.939 1.485-1.035" />
                        <path fill="#909090" d="M23.954 2.077V7.95h5.633l-5.633-5.873Z" />
                        <path fill="#f4f4f4" d="M24.031 2v5.873h5.633L24.031 2Z" />
                        <path fill="#fff" d="M8.975 4.457H7.668v4.8H8.7V7.639l.228.013a2.042 2.042 0 0 0 .647-.117a1.428 1.428 0 0 0 .493-.291a1.224 1.224 0 0 0 .332-.454a2.13 2.13 0 0 0 .105-.908a2.237 2.237 0 0 0-.114-.644a1.173 1.173 0 0 0-.687-.65a2.149 2.149 0 0 0-.411-.105a2.232 2.232 0 0 0-.319-.026m-.189 2.294h-.089v-1.48h.194a.57.57 0 0 1 .459.181a.92.92 0 0 1 .183.558c0 .246 0 .469-.222.626a.942.942 0 0 1-.524.114m3.67-2.306c-.111 0-.219.008-.295.011l-.235.006h-.78v4.8h.918a2.677 2.677 0 0 0 1.028-.175a1.71 1.71 0 0 0 .68-.491a1.939 1.939 0 0 0 .373-.749a3.728 3.728 0 0 0 .114-.949a4.416 4.416 0 0 0-.087-1.127a1.777 1.777 0 0 0-.4-.733a1.63 1.63 0 0 0-.535-.4a2.413 2.413 0 0 0-.549-.178a1.282 1.282 0 0 0-.228-.017m-.182 3.937h-.1V5.315h.013a1.062 1.062 0 0 1 .6.107a1.2 1.2 0 0 1 .324.4a1.3 1.3 0 0 1 .142.526c.009.22 0 .4 0 .549a2.926 2.926 0 0 1-.033.513a1.756 1.756 0 0 1-.169.5a1.13 1.13 0 0 1-.363.36a.673.673 0 0 1-.416.106m5.077-3.915h-2.43v4.8h1.028V7.357h1.3v-.892h-1.3V5.353h1.4v-.892" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                Dag Report
            </h5>
        </div>
        <div class="card-body pt-0" id="report">
            <div class="table-responsive">
                <table class="table table-sm table-bordered p-0 table-striped">
                    <thead>
                        <tr class="bg-warning">
                            <th>Dag No</th>
                            <th>Land Class</th>
                            <th>Area (B-K-L)</th>
                            <th>Occupiers & Families</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dags as $key => $dag) : ?>
                            <tr class="">
                                <td class="px-1 py-0">
                                    <small>
                                        <?= $dag->dag_no ?>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <?= $dag->full_land_type_name ?>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <?php echo 'B-' . $dag->dag_area_b ?>,
                                        <?php echo 'K-' . $dag->dag_area_k ?>,
                                        <?php echo 'L-' . $dag->dag_area_lc ?>
                                    </small>
                                </td>
                                <td class="px-2 py-0">
                                    <small><?= !$dag->encroachers ? 'No Occupiers' : ''  ?></small>
                                    <?php foreach ($dag->encroachers as $key2 => $encroacher) : ?>
                                        <div class="row mb-1">
                                            <div class="col" style="background-color:#bfdbfe;">
                                                <small>
                                                    <?= ($key2 + 1) . '. ' . $encroacher->encro_name ?>
                                                    (<?php echo 'B-' . $encroacher->encro_land_b ?>,
                                                    <?php echo 'K-' . $encroacher->encro_land_k ?>,
                                                    <?php echo 'L-' . $encroacher->encro_land_lc ?>) <br>
                                                    <b>Guardian</b> : <?= $encroacher->encro_guardian ?> (<?php if (trim($encroacher->encro_guar_relation) == 'f') : ?>
                                                    পিতৃ
                                                <?php elseif (trim($encroacher->encro_guar_relation) == 'm') : ?>
                                                    মাতৃ
                                                <?php elseif (trim($encroacher->encro_guar_relation) == 'h') : ?>
                                                    পতি
                                                <?php elseif (trim($encroacher->encro_guar_relation) == 'w') : ?>
                                                    পত্নী
                                                <?php elseif (trim($encroacher->encro_guar_relation) == 'a') : ?>
                                                    অধ্যক্ষ মাতা
                                                <?php elseif (trim($encroacher->encro_guar_relation) == '') : ?>
                                                    অভিভাৱক
                                                <?php elseif (trim($encroacher->encro_guar_relation) == 'n') : ?>
                                                    নাই
                                                    <?php endif; ?>) <br>
                                                    <b>Mobile</b> : <?= $encroacher->mobile ?>
                                                </small>
                                            </div>
                                            <div class="col bg-indigo-light">
                                                <small><?= !$encroacher->families ? 'No Family Details' : ''  ?>
                                                    <?php foreach ($encroacher->families as $key3 => $family_member) : ?>
                                                        <div class="">
                                                            <?= ($key3 + 1) . '. ' . $family_member->occupier_fmember_name ?>
                                                            (
                                                            <?php if (trim($family_member->occupier_fmember_relation) == 'f') : ?>
                                                                পিতৃ
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'm') : ?>
                                                                মাতৃ
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'h') : ?>
                                                                পতি
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'w') : ?>
                                                                পত্নী
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'a') : ?>
                                                                অধ্যক্ষ মাতা
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == '') : ?>
                                                                অভিভাৱক
                                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'n') : ?>
                                                                নাই
                                                                <?php endif; ?>)
                                                        </div>
                                                    <?php endforeach; ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function generatePDF() {
            window.print();
        }

        $("#generatePdf").on("click", generatePDF);
    });
</script>