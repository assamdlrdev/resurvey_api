<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                    <th>Pending</th>
                                    <td>
                                        <span class="badge badge-warning"><?php echo ($total_pending) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('index.php/vlb/VlbCoController/pending_co') ?>" class="btn btn btn-sm btn-info">View <i class="fa fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Approved</th>
                                    <td>
                                        <span class="badge badge-success"><?php echo ($total_approved) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('index.php/vlb/VlbCoController/approved_co') ?>" class="btn btn btn-sm btn-info">View <i class="fa fa-arrow-right"></i></a>
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