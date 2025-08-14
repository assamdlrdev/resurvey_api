<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'villages': [],
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
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
                var self = this;
                this.getVillages();
            },

            getVillages() {
                var self = this;
                this.selected_villages = [];
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/getVillagesH',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    success: function(data) {
                        self.villages = data;
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
                    }
                });
            },
            forwardVillages() {
                if (this.selected_villages.length == 0) {
                    alert('Please select a village');
                    return;
                }
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Forward the selected villages',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/SettlementSvamitvaCo/generateCasesForVillage',
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
                                        'villages': JSON.stringify(self.selected_villages),
                                    },
                                    success: function(data) {
                                        if (data.responseType == '3') {
                                            $.confirm({
                                                title: 'Error Occured!!',
                                                content: data.message,
                                                type: 'orange',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                        } else if (data.responseType == '2') {
                                            $.confirm({
                                                title: 'Success',
                                                content: data.message,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.getVillages();
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                        } else if (data.responseType == '1') {
                                            var msgs = '';
                                            data.validation.forEach(error => {
                                                msgs += '<p>' + error.message + '</p>';
                                            });
                                            $.confirm({
                                                title: 'Error !!',
                                                content: msgs,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });

                                        }
                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occured!!',
                                            content: 'Please contact the system admiin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
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

            }
        }
    }
</script>
<div class="col-lg-12 mt-3" x-data="alpineData()">
	<div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    <h5> REVERTED VILLAGES BY SK
                        <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
                    </h5>
                </div>
            </div>
            <table class="table table-hover table-sm">
                <thead class="bg-warning">
                    <tr>
                        <th>Village Name</th>
                        <th>LM Verified at</th>
                        <th>LM Note</th>
                        <th>SK Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="is_loading">
                        <td colspan="6" class="text-center">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <template x-for="(village,index) in villages">
                        <tr>
                            <td x-text="village.loc_name"></td>
                            <td x-text="village.lm_verified_at"></td>
                            <td x-text="village.lm_note"></td>
                            <td x-text="village.sk_note"></td>

                            <td>
                                <a class="btn btn-sm btn-info text-white" x-bind:href=base_url+"index.php/nc_village/NcVillageLmController/ncVillages/"+village.vill_townprt_code+"/"+village.case_type>Proceed <i class=" fa fa-chevron-right"></i></a>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="villages.length == 0">
                        <td colspan="4" class="text-center">
                            <span>No villages Found</span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
