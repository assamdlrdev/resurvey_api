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
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Add Details</th>
                                    <td>
                                        <span class="badge badge-primary"><?php echo ($to_add_count) ?></span>
                                    </td>
                                    <td class="text-right">
                                        <!-- <a href="<?= base_url('index.php/vlb/VlbController/show_dags') ?>" class="btn btn btn-sm btn-info">Go <i class="fa fa-arrow-right"></i></a> -->
                                        <a href="<?= base_url('index.php/vlb/VlbController/dashboard_mouza') ?>" class="btn btn btn-sm btn-info">Go <i class="fa fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pending</th>
                                    <td>
                                        <span class="badge badge-warning"><?php echo ($total_pending) ?></span>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= base_url('index.php/vlb/VlbController/pending_deo') ?>" class="btn btn btn-sm btn-info">View <i class="fa fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reverted</th>
                                    <td>
                                        <span class="badge badge-danger"><?php echo ($total_reverted) ?></span>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= base_url('index.php/vlb/VlbController/reverted_deo') ?>" class="btn btn btn-sm btn-info">View <i class="fa fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Approved</th>
                                    <td>
                                        <span class="badge badge-success"><?php echo ($total_approved) ?></span>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= base_url('index.php/vlb/VlbController/approved_deo') ?>" class="btn btn btn-sm btn-info">View <i class="fa fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>