<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="mb-0">
        District : <?= $loc_names['district']->loc_name ?> / Sub Division : <?= $loc_names['subdiv']->loc_name ?> / Circle : <?= $loc_names['circle']->loc_name ?> / Mouza : <?= $loc_names['mouza']->loc_name ?> / Lot : <?= $loc_names['lot']->loc_name ?>
    </p>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-primary py-1">
            <h5>
                Village Land Bank
            </h5>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Village</th>
                                    <th class="bg-info">Add Details</th>
                                    <th class="bg-warning">Pending</th>
                                    <th class="bg-danger">Reverted</th>
                                    <th class="bg-success">Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($villages as $village) : ?>
                                    <tr>
                                        <td class="d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('index.php/vlb/VlbController/show_dags/').$village->mouza_pargona_code.'/'.$village->lot_no.'/'.$village->vill_townprt_code ?>"><?= $village->loc_name ?></a>
                                            <a href="<?= base_url('index.php/vlb/VlbController/show_dags/').$village->mouza_pargona_code.'/'.$village->lot_no.'/'.$village->vill_townprt_code ?>" class="btn btn btn-sm btn-info">Go <i class="fa fa-arrow-right"></i></a>
                                        </td>
                                        <td><?= $village->to_add_count ?></td>
                                        <td><?= $village->total_pending ?></td>
                                        <td><?= $village->total_reverted ?></td>
                                        <td><?= $village->total_approved ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>