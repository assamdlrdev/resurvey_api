<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card height">
                <div class="card-body" style="font-weight:500; background-color: #a29bfe">
                    TOTAL DATA ENTERED
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('index.php/count/DCCountController/getDataEnteredCountforDCDistrictWise') ?>"
                        title="Click here for a detailed view" class="card-title"><small class="text-muted"><i
                                class="fa fa-suitcase" aria-hidden="true"></i>
                            <?= $total_count ?></small></a>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <!-- <div class="col-lg-4">
            <div class="card height">
                <div class="card-body" style="font-weight:500; background-color: #a29bfe">
                    TOTAL DOCUMENT SCANNED
                </div>
                <div class="card-footer">
                    <a href="https://basundhara.assam.gov.in/ilrmsdash/index.php/Svamitva/SvamitvaDashboardController/getDataEnteredDistrict/"
                        title="Click here for a detailed view" class="card-title"><small class="text-muted"><img
                                src="https://basundhara.assam.gov.in/ilrmsdash/assets/officenew.png"
                                alt="Office">0</small></a>
                </div>
            </div>
        </div> -->
        <!-- ./col -->
    </div>
    <!-- /.row -->

</div><!-- /.container-fluid -->