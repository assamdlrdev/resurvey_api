<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/pdfjs/pdf.min.js') ?>" type="text/javascript"></script>

<script>
    function alpineData() {
        return {
            'base_url': "<?= base_url(); ?>",
            'villages': [],
            'is_loading': true,
            'notification_no': "<?= $notification_no; ?>",
            'dist_code': "<?= $dist_code; ?>",
            'proposal_no': "<?= $proposal_no; ?>",
            'is_fetching_village': false,
            'is_fetching_village_uuid': '',
            'dhar_village': '',
            'old_name': '',
            'old_name_eng': '',
            'new_name': '',
            'new_name_eng': '',
            'is_updating_name': false,
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

                this.getVillages();
            },
            getVillages() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageNameController/getNotificationVillages',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        csrfName: csrfHash,
                        dist_code: self.dist_code,
                        proposal_no: self.proposal_no
                    },
                    success: function(data) {
                        self.villages = data;
                        self.is_loading = false;
                    }
                });
                this.is_loading = false;
            },
            previewNotification() {
                window.open('<?= API_LINK_ILRMS ?>index.php/nc_village/NcCommonController/getNotificationPdfJsSignPreview/' + this.notification_no);
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Notification Number : <span><?php echo $notification_no ?></span>, Proposal Number : <?php echo $proposal_no ?></div>
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> NOTIFICATION VILLAGES
                                <small class="text-dark" x-text="'Total villages : ' + villages.length"></small>
                            </h5>
                            <button class="btn btn-info btn-sm" type="button" x-on:click="previewNotification">View Notification</button>
                        </div>
                    </div>
                    <div class="table table-responsive">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead class="bg-warning">
                                <tr class="text-sm">
                                    <th>Sl</th>
                                    <th>Dist name</th>
                                    <th>Subdiv Name</th>
                                    <th>Circle Name</th>
                                    <th>Mouza Name</th>
                                    <th>Lot Name</th>
                                    <th>Village Name</th>
                                    <th>Village Name Eng</th>
                                    <th>Village Name (New)</th>
                                    <th>Village Name Eng (New)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr x-show="is_loading">
                                    <td colspan="12" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <template x-for="(village,index) in villages">
                                    <tr class="text-sm">
                                        <td x-text="index+1"></td>
                                        <td x-text="village.dist_name"></td>
                                        <td x-text="village.subdiv_name"></td>
                                        <td x-text="village.cir_name"></td>
                                        <td x-text="village.mouza_name"></td>
                                        <td x-text="village.lot_name"></td>
                                        <td x-text="village.vill_name"></td>
                                        <td x-text="village.vill_name_eng"></td>
                                        <td x-text="village.new_vill_name"></td>
                                        <td x-text="village.new_vill_name_eng"></td>
                                    </tr>
                                </template>
                                <tr x-show="villages.length == 0">
                                    <td colspan="10" class="text-center">
                                        <span>No data Found</span>
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