<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'lots': [],
            'villages': [],
            'lms': [],
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
            'filter_status': 'co_pending',
            'view_map_lists_village': '',
            'is_forwarding': false,
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
                this.$watch('mouza_pargona_code', function() {
                    self.lot_no = '';
                    self.lots = [];
                    self.villages = [];
                    self.getLots();
                    self.getVillages();
                });
                this.$watch('lot_no', function() {
                    self.getVillages();
                });
                this.$watch('filter_status', function() {
                    self.getVillages();
                });

                var locations = '<?= json_encode($locations) ?>';
                var locations = JSON.parse(locations);
                this.dist_code = locations.dist.dist_code;
                this.subdiv_code = locations.subdiv.subdiv_code;
                this.cir_code = locations.circle.cir_code;
                var villages = '<?= json_encode($maps) ?>';
                this.villages = JSON.parse(villages);
            },
            getLots() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/lotdetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        'cir': self.cir_code,
                        'mza': self.mouza_pargona_code
                    },
                    success: function(data) {
                        self.lots = data;
                    }
                });
            },
            getVillages() {
                var self = this;
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageCoController/getVillagesDraftMap',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'mouza_pargona_code': self.mouza_pargona_code,
                        'lot_no': self.lot_no,
                        'filter_status':self.filter_status
                    },
                    success: function(data) {
                        self.villages = data;
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
                    }
                });
            },
            closeModal() {
                $('#close_modal').trigger('click');
            },
            openMapListsModel(village) {
                this.view_map_lists_village = village;
            },
            getAllLm(village) {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/getAllLm',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'map_id': village.id
                    },
                    success: function(data) {
                        if (data.status == '0') {
                            $.confirm({
                                title: 'Error Occurred!!',
                                content:'Something went wrong. Please try again later.',
                                type: 'orange',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {
                                            self.is_forwarding = false;
                                        }
                                    },
                                }
                            });
                        } else if (data.status == '2') {
                            $.confirm({
                                title: 'Error Occurred!!',
                                content: data.message,
                                type: 'orange',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {
                                            self.is_forwarding = false;
                                        }
                                    },
                                }
                            });
                        } else if (data.status == '1') {
                            self.lms = data?.data?.users;
                            self.forwardToLm(village)
                        }
                        self.is_forwarding = false;
                    },
                    error: function(error) {
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
                                        self.is_forwarding = false;
                                    }
                                },
                            }
                        });
                        self.is_forwarding = false;
                    }
                });
            },
            forwardToLm(village) {
                var self = this;
                this.is_forwarding = true;
                let optionsHtml = '<option value="">Please Select LRA</option>';
                self.lms.forEach(function(lm) {
                    optionsHtml += '<option value="' + lm.user_code + '">' + lm.name + '</option>';
                });
                let contentHtml =
                    '<form id="mapRemarkForm">' +
                        '<div class="row">' +
                            '<div class="col-lg-12 mb-2">' +
                                '<label class="form-check-label" for="co_user_code">LRA</label>' +
                                '<select id="lm_user_code" name="lm_user_code" class="form-control">' +
                                    optionsHtml+
                                '</select>' +
                            '</div>'  +
                            '<div class="col-lg-12">' +
                                '<label class="form-check-label" for="remark">Note</label>' +
                                '<textarea id="remark" name="remark" placeholder="Note" class="form-control" required >Village map checked and forwarded to Lot Mondal for verification.</textarea>' +
                            '</div>' +
                        '</div>' +
                    '</form>';
                $.confirm({
                    title: 'Forward',
                    content: contentHtml,
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Verify & Forward to LRA',
                            btnClass: 'btn-success',
                            action: function() {
                                if (!$('#lm_user_code').val()) {
                                    alert('Please select LRA.');
                                    self.is_forwarding = false;
                                    return;
                                }
                                if (!$('#remark').val()) {
                                    alert('Please enter note.');
                                    self.is_forwarding = false;
                                    return;
                                }
                                var lm_user_code = $('#lm_user_code').val();
                                var remark = $('#remark').val();

                                $.confirm({
                                    title: 'Confirm',
                                    content: 'Please confirm to Forward',
                                    type: 'orange',
                                    typeAnimated: true,
                                    buttons: {
                                        Confirm: {
                                            text: 'Confirm',
                                            btnClass: 'btn-success',
                                            action: function() {
                                                $.ajax({
                                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/forwardToLm',
                                                    method: "POST",
                                                    async: true,
                                                    dataType: 'json',
                                                    data: {
                                                        'flag': 'B',
                                                        'map_id': village.id,
                                                        'lm_user_code': lm_user_code,
                                                        'remark': remark
                                                    },
                                                    success: function(data) {
                                                        if (data.status == '0') {
                                                            $.confirm({
                                                                title: 'Error Occurred!!',
                                                                content:'Something went wrong. Please try again later.',
                                                                type: 'orange',
                                                                typeAnimated: true,
                                                                buttons: {
                                                                    Ok: {
                                                                        text: 'OK',
                                                                        btnClass: 'btn-info',
                                                                        action: function() {
                                                                            self.is_forwarding = false;
                                                                        }
                                                                    },
                                                                }
                                                            });
                                                        } else if (data.status == '1') {
                                                            $.confirm({
                                                                title: 'Success',
                                                                content: 'Map Forwarded successfully.',
                                                                type: 'green',
                                                                typeAnimated: true,
                                                                buttons: {
                                                                    Ok: {
                                                                        text: 'OK',
                                                                        btnClass: 'btn-info',
                                                                        action: function() {
                                                                            self.getVillages();
                                                                            self.is_forwarding = false;
                                                                        }
                                                                    },
                                                                }
                                                            });
                                                        } 
                                                        self.is_forwarding = false;
                                                    },
                                                    error: function(error) {
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
                                                                        self.is_forwarding = false;
                                                                    }
                                                                },
                                                            }
                                                        });
                                                        self.is_forwarding = false;
                                                    }
                                                });
                                            }
                                        },
                                        cancel: {
                                            text: 'Cancel',
                                            btnClass: 'btn-warning',
                                            action: function() {
                                                self.is_forwarding = false;
                                            }
                                        },
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_forwarding = false;
                            }
                        },
                    }
                });
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row d-none">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select x-model="dist_code" id="district" class="form-control form-control-sm">
                        <option value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Sub-Division</label>
                    <select x-model="subdiv_code" id="subdiv" class="form-control form-control-sm">
                        <option value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Circle</label>
                    <select x-model="cir_code" id="circle" class="form-control form-control-sm">
                        <option value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Mouza</label>
                    <select x-model="mouza_pargona_code" id="mouza" class="form-control form-control-sm">
                        <option value="">Select Mouza</option>
                        <?php foreach ($mouzas as $mouza) : ?>
                            <option value="<?= $mouza['mouza_pargona_code'] ?>"><?= $mouza['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Lot</label>
                    <select x-model="lot_no" id="lot" class="form-control form-control-sm">
                        <option value="">Select Lot</option>
                        <template x-for="(lot,index_lot) in lots" :key="index_lot">
                            <option x-bind:value="lot.lot_no" x-text="lot.loc_name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Filter</label>
                    <select x-model="filter_status" id="filter_status" class="form-control form-control-sm">
                        <option value="co_pending">Pending</option>
                        <option value="lm_forwarded">Forwarded</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> MAPS UPLOADED BY JDS
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr>
                                <th>Village Name</th>
                                <th>Merge Villages Name</th>
                                <th>Mouza</th>
                                <th>Maps</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr x-show="is_loading">
                                <td colspan="5" class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <template x-for="(village,index) in villages">
                                <tr>
                                    <td x-text="village.village_name"></td>
                                    <td x-text="village.has_merge_village_request ? village.requested_merged_villages_name : 'N.A.'"></td>
                                    <td x-text="village.mouza_name"></td>
                                    <td>
                                        <button data-toggle="modal" data-target="#modal_views_map_lists" x-on:click="openMapListsModel(village)" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> View Maps</button>
                                    </td>
                                    <td>
                                        <button x-bind:disabled="is_forwarding" type="button" x-on:click="getAllLm(village)" class="btn btn-sm btn-info">Verify & Forward to <?= LM_LABEL ?><i class="fa fa-chevron-right"></i></button>
                                        <!-- <span class="text-success" x-show="village.flag != 'F'">Maps Forwarded To <?= LM_LABEL ?></span> -->
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="villages.length == 0">
                                <td colspan="5" class="text-center">
                                    <span>No <span x-show="filter_status=='co_pending'">Pending</span> <span x-show="filter_status=='lm_forwarded'">Forwarded</span> Maps Found.</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modal_views_map_lists" tabindex="-1" aria-labelledby="modal_views_map_lists" aria-hidden="true">
        <div class="modal-dialog" style="width: 100% !important;">
            <div class="modal-content">
                <div class="modal-header p-2" x-show="view_map_lists_village">
                    <h5 class="modal-title"> <i class="fa fa-file"></i> Maps List of Village <span x-show="view_map_lists_village" class="text-info" x-text="view_map_lists_village.village_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="closeModal()">&times;</span>
                    </button>
                </div>
                <div class="modal-body" x-show="view_map_lists_village">
                    <table class="table table-striped table-hover table-sm table-bordered">
                        <thead style="position: sticky;top:0;" class="bg-warning">
                            <tr>
                                <th>Sl.No.</th>
                                <th>View Map</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(list,index) in view_map_lists_village.map_lists">
                                <tr>
                                    <td x-text="index + 1"></td>
                                    <td>
                                        <a x-bind:href="'<?= base_url() ?>index.php/nc_village_v2/NcVillageCoController/viewUploadedMap?id=' + list.id" class="btn btn-info py-2" style="color: white" target="_blank">
                                            View Map
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="view_map_lists_village && view_map_lists_village.map_lists.length == 0">
                                <td colspan="4" class="text-center">
                                    <span>No Map Found</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
