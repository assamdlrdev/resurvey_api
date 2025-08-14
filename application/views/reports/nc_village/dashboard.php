<div class="col-md-12">
    <span class="text-muted float-right"><i> Last Updated at : <?= date('d F Y',strtotime($updated_at)) ?>, <?= date('h:i a',strtotime($updated_at_time)) ?></i></span>
</div>
<div class="col-md-4">
    <div class="small-box bg-secondary">
        <div class="inner">
            <h3><?= $verified_lm_count ?></h3>
            <p>VERIFIED DRAFT CHITHA AND MAP <br> (LM)</p>
        </div>
        <div class="icon">
            <i class="ion ion-bag"></i>
        </div>
        <a href="<?= base_url('index.php/reports/MapCertifiedVillagesController/districts/verified_lm') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-md-4">
    <div class="small-box bg-info">
        <div class="inner">
            <h3><?= $certified_co_count ?></h3>
            <p>CERTIFICATION OF DRAFT CHITHA AND MAP <br> (CO)</p>
        </div>
        <div class="icon">
            <i class="ion ion-bag"></i>
        </div>
        <a href="<?= base_url('index.php/reports/MapCertifiedVillagesController/districts/certified_co') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-md-4">
    <div class="small-box bg-success">
        <div class="inner">
            <h3><?= $digi_signature_dc_count ?></h3>
            <p>DIGITAL SIGNATURE <br> (DC)</p>
        </div>
        <div class="icon">
            <i class="ion ion-bag"></i>
        </div>
        <a href="<?= base_url('index.php/reports/MapCertifiedVillagesController/districts/digi_signature_dc') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
