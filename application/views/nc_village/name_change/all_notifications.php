<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'notifications': [],
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
            'filters': [],
            'total_villages': 0,
            'total_notified_villages': 0,
            'total_final_notifications': 0,
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
                this.getNotifications();
                var self = this;
                this.$watch('filters', function() {
                    self.getNotifications();
                });
            },
            getNotifications() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageNameController/getNotifications',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        csrfName: csrfHash,
                        filters: self.filters
                    },
                    success: function(data) {
                        self.notifications = data.notifications;
                        self.total_villages = data.total_villages;
                        self.total_notified_villages = data.total_notified_villages;
                        self.total_final_notifications = data.total_final_notifications;
                    }
                });
            },
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="asst_section_officer_verified" value="asst_section_officer_verified"> <label for="asst_section_officer_verified">asst_section_officer_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="section_officer_verified" value="section_officer_verified"> <label for="section_officer_verified">section_officer_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="joint_secretary_verified" value="joint_secretary_verified"> <label for="joint_secretary_verified">joint_secretary_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="secretary_verified" value="secretary_verified"> <label for="secretary_verified">secretary_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="ps_verified" value="ps_verified"> <label for="ps_verified">ps_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="minister_verified" value="minister_verified"> <label for="minister_verified">minister_verified</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="ps_sign" value="ps_sign"> <label for="ps_sign">ps_sign</label>
            </span>
            <span class="p-1 bg-info border">
                <input type="checkbox" x-model="filters" id="js_sign" value="js_sign"> <label for="js_sign">js_sign</label>
            </span>
            <div class="alert alert-info mt-3" role="alert">
                <strong>Note:</strong> A notification shall be deemed final only if it bears the signatures of both the Principal Secretary and the Joint Secretary.
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">ALL NOTIFICATIONS(Draft and Final)
                                <small class="text-dark" x-text="' : ' + notifications.length"></small>
                            </h5>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge rounded-pill bg-primary px-3 py-2" style="font-size: 1rem;">
                                <i class="fa fa-list"></i>
                                Total Villages: <span x-text="total_villages"></span>
                            </span>
                            <span class="badge rounded-pill bg-success px-3 py-2" style="font-size: 1rem;">
                                <i class="fa fa-check-circle"></i>
                                Notified Villages: <span x-text="total_notified_villages"></span>
                            </span>
                            <span class="badge rounded-pill bg-warning px-3 py-2" style="font-size: 1rem;">
                                <i class="fa fa-bell"></i>
                                Final Notifications: <span x-text="total_final_notifications"></span>
                            </span>
                            <span class="badge rounded-pill bg-danger px-3 py-2 ms-2" style="font-size: 1rem;">
                                <i class="fa fa-clock"></i>
                                Pending Notifications:
                                <span x-text="total_villages - total_final_notifications"></span>
                            </span>
                            <style>
                                .d-flex.gap-2>.badge {
                                    margin-right: 0.5rem;
                                }

                                .d-flex.gap-2>.badge:last-child {
                                    margin-right: 0;
                                }
                            </style>
                        </div>
                    </div>
                    <div class="table table-responsive">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead class="bg-warning">
                                <tr class="text-sm">
                                    <th>Sl</th>
                                    <th>Proposal</th>
                                    <th>notification_no</th>
                                    <th>Status</th>
                                    <th class="no-wrap">Total Villages</th>
                                    <th>District</th>
                                    <th>Created</th>
                                    <th>asst_section_officer_verified</th>
                                    <th>section_officer_verified</th>
                                    <th>joint_secretary_verified</th>
                                    <th>secretary_verified</th>
                                    <th>ps_verified</th>
                                    <th>minister_verified</th>
                                    <th>ps_sign</th>
                                    <th>js_sign</th>
                                    <th>Action</th>
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
                                <template x-for="(notification,index) in notifications">
                                    <tr class="text-sm">
                                        <td x-text="index+1"></td>
                                        <td x-text="notification.proposal_no"></td>
                                        <td x-text="notification.notification_no"></td>
                                        <td x-text="notification.status"></td>
                                        <td x-text="notification.village_count"></td>
                                        <td x-text="notification.dist_code"></td>
                                        <td class="no-wrap" x-text="notification.created_at"></td>
                                        <td x-text="notification.asst_section_officer_verified"></td>
                                        <td x-text="notification.section_officer_verified"></td>
                                        <td x-text="notification.joint_secretary_verified"></td>
                                        <td x-text="notification.secretary_verified"></td>
                                        <td x-text="notification.ps_verified"></td>
                                        <td x-text="notification.minister_verified"></td>
                                        <td x-text="notification.ps_sign"></td>
                                        <td x-text="notification.js_sign"></td>
                                        <td>
                                            <span x-show="notification.ps_sign=='Y' && notification.js_sign=='Y'">
                                                <a x-bind:href=base_url+"index.php/nc_village/NcVillageNameController/notificationView/"+notification.notification_no+'/'+notification.dist_code+"/"+notification.proposal_no+'/Y' class="btn btn-sm btn-info">View</a>
                                            </span>
                                            <span x-show="notification.ps_sign!='Y' || notification.js_sign!='Y'">
                                                <a x-bind:href=base_url+"index.php/nc_village/NcVillageNameController/notificationView/"+notification.notification_no+'/'+notification.dist_code+"/"+notification.proposal_no+'/N' class="btn btn-sm btn-info">View</a>
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="notifications.length == 0">
                                    <td colspan="12" class="text-center">
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