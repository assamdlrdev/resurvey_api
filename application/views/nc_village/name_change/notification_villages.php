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
            'is_final': "<?= $is_final; ?>",
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
            },
            previewVillage(uuid, old_name, old_name_eng, new_name, new_name_eng) {
                var self = this;
                self.is_fetching_village = true;
                self.is_fetching_village_uuid = uuid;
                self.old_name = old_name;
                self.old_name_eng = old_name_eng;
                self.new_name = new_name;
                self.new_name_eng = new_name_eng;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageNameController/getVillageName',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        csrfName: csrfHash,
                        dist_code: self.dist_code,
                        uuid: self.is_fetching_village_uuid,
                    },
                    success: function(data) {
                        if (data.responseType == '3') {
                            self.is_fetching_village = false;
                            $.confirm({
                                title: 'Error Occurred!!',
                                content: data.data,
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
                        } else {
                            self.dhar_village = data.data;
                            self.is_fetching_village = false;
                            $("#update_village_name").modal('show');
                        }
                    }
                });
            },
            updateVillageName() {
                var self = this;
                this.is_updating_name = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Update the Name on Dharitree',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                self.is_updating_name = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageNameController/updateVillageName',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        csrfName: csrfHash,
                                        dist_code: self.dist_code,
                                        uuid: self.is_fetching_village_uuid,
                                        new_name: self.new_name,
                                        new_name_eng: self.new_name_eng,
                                    },
                                    success: function(data) {
                                        self.is_updating_name = false;
                                        $("#update_village_name").modal('hide');
                                        self.modalClosed();
                                        if (data.responseType == '2') {
                                            $.confirm({
                                                title: 'Success',
                                                content: 'Village Name Updated Successfully.',
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.getVillages();
                                                        }
                                                    },
                                                }
                                            });
                                        } else {
                                            $.confirm({
                                                title: 'Error Occurred!!',
                                                content: data.data,
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
                                    error: function(error) {
                                        $("#update_village_name").modal('hide');
                                        self.modalClosed();
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_updating_name = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_updating_name = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
            },
            modalClosed() {
                this.is_fetching_village = false;
                this.is_fetching_village_uuid = '';
                this.is_updating_name = false;
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
                                    <th class="no-wrap">Dist name</th>
                                    <th class="no-wrap">Subdiv Name</th>
                                    <th class="no-wrap">Circle Name</th>
                                    <th class="no-wrap">Mouza Name</th>
                                    <th class="no-wrap">Lot Name</th>
                                    <th class="no-wrap">Village Name</th>
                                    <th class="no-wrap">Village Name Eng</th>
                                    <th class="no-wrap">Village Name (New)</th>
                                    <th class="no-wrap">Village Name Eng (New)</th>
                                    <th class="no-wrap">Name Updated On Dharitree</th>
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
                                    <tr class="text-sm ">
                                        <td x-text="index+1"></td>
                                        <td class="no-wrap" x-text="village.dist_name + ' - ' + village.dist_code"></td>
                                        <td class="no-wrap" x-text="village.subdiv_name + ' - ' + village.subdiv_code"></td>
                                        <td class="no-wrap" x-text="village.cir_name + ' - ' + village.cir_code"></td>
                                        <td class="no-wrap" x-text="village.mouza_name + ' - ' + village.mouza_pargona_code"></td>
                                        <td class="no-wrap" x-text="village.lot_name + ' - ' + village.lot_no"></td>
                                        <td class="no-wrap" x-text="village.vill_name + ' - ' + village.vill_townprt_code"></td>
                                        <td class="no-wrap" x-text="village.vill_name_eng"></td>
                                        <td class="no-wrap" x-text="village.new_vill_name"></td>
                                        <td class="no-wrap" x-text="village.new_vill_name_eng"></td>
                                        <td>
                                            <span x-bind:class="village.is_name_updated == 'Y' ? 'text-success' : 'text-danger'" x-text="village.is_name_updated == 'Y' ? 'Yes' : 'No'"></span>
                                            <span x-show="is_final=='Y'">
                                                <button x-show="is_fetching_village_uuid != village.uuid && (!is_fetching_village && !is_updating_name)" type="button" x-on:click="previewVillage(village.uuid,village.vill_name,village.vill_name_eng,village.new_vill_name,village.new_vill_name_eng)" class="btn btn-sm btn-success">Update</button>
                                            </span>
                                            <div x-show="is_fetching_village_uuid == village.uuid && (is_fetching_village || is_updating_name)" class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="villages.length == 0">
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

    <div class="modal" id="update_village_name" tabindex="-1" aria-labelledby="update_village_name" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog" style="width: 100% !important;">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title"> <i class="fa fa-file"></i> Update Village Name</h5>
                    <button x-on:click="modalClosed" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Name on Dharitree : <span class="text-success" x-text="dhar_village ? dhar_village.loc_name : ''"></span></p>
                    <p>Name on Dharitree Eng : <span class="text-success" x-text="dhar_village ? dhar_village.locname_eng : ''"></span></p>
                    <p>Old Name : <span class="text-success" x-text='old_name'></span></p>
                    <p>Old Name Eng : <span class="text-success" x-text='old_name_eng'></span></p>
                    <p>New Name to be : <span class="text-success" x-text='new_name'></span></p>
                    <p>New Name Eng to be : <span class="text-success" x-text='new_name_eng'></span></p>
                    <button type="button" x-on:click="updateVillageName" class="btn btn-sm btn-info">UPDATE NAME</button>
                </div>
                <div class="modal-footer" style="display: block;">
                    <div>
                        <button x-on:click="modalClosed" type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">
                            <i class='fa fa-close'></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>