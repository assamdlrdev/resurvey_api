<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'dist_code': '<?= $dist_code; ?>',
            'subdiv_code': '<?= $subdiv_code; ?>',
            'cir_code': '<?= $cir_code; ?>',
            'mouza_pargona_code': '<?= $mouza_pargona_code; ?>',
            'lot_no': '<?= $lot_no; ?>',
            'vill_townprt_code': '<?= $vill_townprt_code; ?>',
            'is_loading': false,
            'base_url': "<?= base_url(); ?>",
            init() {
                var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
                var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

                $(document).ajaxSend(function(event, jqxhr, settings) {
                    if (settings.type.toLowerCase() === "post") {
                        if (settings.data && typeof settings.data === 'string') {
                            settings.data += '&' + csrfName + '=' + csrfHash;
                        } else {
                            settings.data = csrfName + '=' + csrfHash;
                        }
                    }
                });
            },
            syncVill() {
                var self = this;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To Sync Data chitha_basic_nc Dags and Bhunaksa Dags',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/utility/ChithaBasicNcSyncController/syncChithaBasicNcWithBhunaksa',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'dist_code': self.dist_code,
                                        'subdiv_code': self.subdiv_code,
                                        'cir_code': self.cir_code,
                                        'mouza_pargona_code': self.mouza_pargona_code,
                                        'lot_no': self.lot_no,
                                        'vill_townprt_code': self.vill_townprt_code,
                                    },
                                    success: function(data) {
                                        self.is_loading = false;
                                        if (data.st == 'success') {
                                            $.confirm({
                                                title: 'Success',
                                                content: data.msgs,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            window.location.reload();
                                                        }
                                                    },
                                                }
                                            });
                                        } else {
                                            $.confirm({
                                                title: 'Failed',
                                                content: data.msgs,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {}
                                                    },
                                                }
                                            });
                                        }
                                    },
                                    error: function(err) {
                                        self.is_loading = false;
                                        $.confirm({
                                            title: 'Failed',
                                            content: 'Something went wrong. Please try again later.',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {}
                                                },
                                            }
                                        });
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-default',
                            action: function() {

                            }
                        }
                    }
                });
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Sync chitha_basic_nc with bhunaksa</div>
    <div class="text-center pb-1">
        <span><?php echo $village ? $village->loc_name : ''; ?> - </span>
        <?php if ($is_synced_all == 'Y'): ?>
            <note class="text-success text-center">
                All Data Are Synced
            </note>
        <?php else: ?>
            <note class="text-danger">
                Some Data Are Not Synced
            </note>
        <?php endif; ?>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> CHITHA BASIC NC DAGS
                            </h5>
                        </div>
                    </div>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-hover table-sm table-bordered table-striped">
                            <thead>
                                <tr class="bg-warning">
                                    <th>Sl</th>
                                    <th>Dag</th>
                                    <th>Bigha</th>
                                    <th>Katha</th>
                                    <th>Lessa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chitha_basic_nc_dags as $index => $chitha_basic_nc_dag): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $chitha_basic_nc_dag->dag_no ?></td>
                                        <td><?= $chitha_basic_nc_dag->dag_area_b ?></td>
                                        <td><?= $chitha_basic_nc_dag->dag_area_k ?></td>
                                        <td><?= $chitha_basic_nc_dag->dag_area_lc ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> BHUNAKSA DAGS
                            </h5>
                        </div>
                    </div>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-hover table-sm table-bordered table-striped">
                            <thead>
                                <tr class="bg-warning">
                                    <th>Sl</th>
                                    <th>Dag</th>
                                    <th>Bigha</th>
                                    <th>Katha</th>
                                    <th>Lessa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bhunaksa_dags as $index => $bhunaksa_dag): ?>
                                    <tr class="<?= $bhunaksa_dag['is_synced'] != 'Y' ? 'bg-danger' : ''; ?>">
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $bhunaksa_dag['dag_no'] ?></td>
                                        <td><?= $bhunaksa_dag['dag_area_b'] ?></td>
                                        <td><?= $bhunaksa_dag['dag_area_k'] ?></td>
                                        <td><?= $bhunaksa_dag['dag_area_lc'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($is_synced_all != 'Y'): ?>
                        <button x-show="!is_loading" x-on:click="syncVill" class="btn btn-sm btn-success" type="button">Sync Village</button>
                    <?php endif; ?>
                    <div x-show="is_loading" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>