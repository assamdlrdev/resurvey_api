<div class="col-md-6">
    <div class="card card-info">
        <div class="card-header">
            <?php
            if ($type == 'verified_lm')
                echo ('VERIFIED DRAFT CHITHA AND MAP (LM)');
            else if ($type == 'certified_co')
                echo ('CERTIFICATION OF DRAFT CHITHA AND MAP(CO)');
            else if ($type == 'digi_signature_dc')
                echo ('DIGITAL SIGNATURE (DC)');
            ?>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <?php foreach ($districts as $dist_code => $data) : ?>
                    <?php
                    if ($type == 'verified_lm')
                        $count = ($data->verified_lm_count);
                    else if ($type == 'certified_co')
                        $count = ($data->certified_co_count);
                    else if ($type == 'digi_signature_dc')
                        $count = ($data->digi_signature_dc_count);
                    ?>
                    <a href="<?= base_url('index.php/reports/MapCertifiedVillagesController/index/').$type.'/'.$dist_code ?>">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $data->loc_name['dist']['loc_name'].'('.$count.')' ?>
                            <i class="fa fa-chevron-right"></i>
                        </li>
                    </a>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>