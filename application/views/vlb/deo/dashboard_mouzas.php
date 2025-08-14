<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="mb-0">
        District : <?= $loc_names['district']->loc_name ?> / Sub Division : <?= $loc_names['subdiv']->loc_name ?> / Circle : <?= $loc_names['circle']->loc_name ?>
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
                                    <th>Mouza</th>
                                    <th class="bg-info">Add Details</th>
                                    <th class="bg-warning">Pending</th>
                                    <th class="bg-danger">Reverted</th>
                                    <th class="bg-success">Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mouzas as $mouza) : ?>
                                    <tr>
                                        <td class="d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('index.php/vlb/VlbController/dashboard_lot/'.$mouza->mouza_pargona_code) ?>"><?= $mouza->loc_name ?></a>
                                            <a href="<?= base_url('index.php/vlb/VlbController/dashboard_lot/'.$mouza->mouza_pargona_code) ?>" class="btn btn btn-sm btn-info">Go <i class="fa fa-arrow-right"></i></a>
                                        </td>
                                        <td><?= $mouza->to_add_count ?></td>
                                        <td><?= $mouza->total_pending ?></td>
                                        <td><?= $mouza->total_reverted ?></td>
                                        <td><?= $mouza->total_approved ?></td>
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