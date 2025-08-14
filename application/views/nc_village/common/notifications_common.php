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
                self.filters.push('js_sign');
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
                        self.notifications = data;
                    }
                });
            },
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE NOTIFICATIONS</div>
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead class="bg-warning">
                                <tr class="text-sm">
                                    <th>Sl</th>
                                    <th>Proposal</th>
                                    <th>Notification no</th>
                                    <th>Created On</th>
                                    <th>Asst section officer verified</th>
                                    <th>Section officer verified</th>
                                    <th>Joint secretary verified</th>
                                    <th>Secretary verified</th>
                                    <th>PS verified</th>
                                    <th>Minister verified</th>
                                    <th>PS sign</th>
                                    <th>JS sign</th>
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
                                        <td class="no-wrap" x-text="notification.created_at"></td>
                                        <td x-bind:class="notification.asst_section_officer_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.asst_section_officer_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.section_officer_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.section_officer_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.joint_secretary_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.joint_secretary_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.secretary_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.secretary_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.ps_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.ps_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.minister_verified == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.minister_verified == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.ps_sign == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.ps_sign == 'Y' ? 'Yes' : 'No'"></td>
                                        <td x-bind:class="notification.js_sign == 'Y' ? 'text-success' : 'text-danger'" x-text="notification.js_sign == 'Y' ? 'Yes' : 'No'"></td>
                                        <td>
                                        <a x-bind:href=base_url+"index.php/nc_village/NcVillageCommonController/notificationView/"+notification.notification_no+'/'+notification.dist_code+"/"+notification.proposal_no class="btn btn-sm btn-info">View</a>
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