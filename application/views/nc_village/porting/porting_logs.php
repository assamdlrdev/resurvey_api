<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>

</script>
<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Porting Village Data Logs</div>
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> Porting Logs
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr>
                                <th>Date Time</th>
                                <th>Porting Status</th>
                                <th>Messages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td ><?php echo $log->created_at ?></td>
                                    <td ><?php echo($log->status ? $log->status : 'Incomplete') ?></td>
                                    <td style="font-size:14px;"><?php print($log->msgs ? $log->msgs : 'N/A')  ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>