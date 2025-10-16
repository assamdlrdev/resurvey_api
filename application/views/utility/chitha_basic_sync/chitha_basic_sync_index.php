<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'villages': [],
            'subdivs': [],
            'circles': [],
            'mouzas': [],
            'lots': [],
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'vill_townprt_code': '',
            'is_loading': false,
            'loading_uuid': false,
            'is_loading_port': false,
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
                var self = this;
                this.$watch('dist_code', function() {
                    self.getSubdivs();
                    self.subdiv_code = '';
                    self.cir_code = '';
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('subdiv_code', function() {
                    self.getCircles();
                    self.cir_code = '';
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('cir_code', function() {
                    self.getMouzas();
                    self.getVillages();
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('mouza_pargona_code', function() {
                    self.getLots();
                    self.getVillages();
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('lot_no', function() {
                    self.getVillages();
                    self.vill_townprt_code = '';
                });
            },
            syncVill(village) {
                var self = this;
                self.loading_uuid = village.uuid;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To Sync Data Chitha Basic and Chitha Basic NC of '+village.vill_townprt_code,
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading_port = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/utility/ChithaBasicNcSyncController/syncData',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'dist_code': village.dist_code,
                                        'subdiv_code': village.subdiv_code,
                                        'cir_code': village.cir_code,
                                        'mouza_pargona_code': village.mouza_pargona_code,
                                        'lot_no': village.lot_no,
                                        'vill_townprt_code': village.vill_townprt_code,
                                    },
                                    success: function(data) {
                                        self.is_loading_port = false;
                                        self.loading_uuid = '';
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
                                                            self.getVillages();
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
                                        self.is_loading_port = false;
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
            },
            getSubdivs() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/subdivisiondetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'id': self.dist_code,
                    },
                    success: function(data) {
                        self.subdivs = data;
                    }
                });
            },
            getCircles() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/circledetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code
                    },
                    success: function(data) {
                        self.circles = data;
                    }
                });
            },
            getMouzas() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/mouzadetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        'cir': self.cir_code
                    },
                    success: function(data) {
                        self.mouzas = data;
                    }
                });
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
                this.selected_villages = [];
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/utility/ChithaBasicNcSyncController/villagedetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        'cir': self.cir_code,
                        'mza': self.mouza_pargona_code,
                        'lot': self.lot_no
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

        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Sync chitha_basic_nc with chitha_basic</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select x-model="dist_code" id="district" class="form-control form-control-sm">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $district) : ?>
                            <option value="<?= $district['dist_code'] ?>"><?php echo ($district['loc_name']) .' - '. $district['dist_code'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Sub-Division</label>
                    <select x-model="subdiv_code" id="subdiv" class="form-control form-control-sm">
                        <option value="">Select Sub Division</option>
                        <template x-for="(subdiv,index) in subdivs" :key="index">
                            <option x-bind:value="subdiv.subdiv_code"><span x-text="subdiv.loc_name + ' - ' + subdiv.subdiv_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Circle</label>
                    <select x-model="cir_code" id="circle" class="form-control form-control-sm">
                        <option value="">Select Circle</option>
                        <template x-for="(circle,index) in circles" :key="index">
                            <option x-bind:value="circle.cir_code"><span x-text="circle.loc_name + ' - ' + circle.cir_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Mouza</label>
                    <select x-model="mouza_pargona_code" id="mouza" class="form-control form-control-sm">
                        <option value="">Select Mouza</option>
                        <template x-for="(mouza,index) in mouzas" :key="index">
                            <option x-bind:value="mouza.mouza_pargona_code"><span x-text="mouza.loc_name + ' - ' + mouza.mouza_pargona_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Lot</label>
                    <select x-model="lot_no" id="lot" class="form-control form-control-sm">
                        <option value="">Select Lot</option>
                        <template x-for="(lot,index) in lots" :key="index">
                            <option x-bind:value="lot.lot_no"><span x-text="lot.loc_name + ' - ' + lot.lot_no"></span></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> Porting Village Data to Dharitree
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small></small>
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-striped">
                        <thead >
                            <tr>
                                <th>Village Name</th>
                                <th>Porting Status</th>
                                <th>Is Synced</th>
                                <th>Is NC Village Exists</th>
                                <th>Is DC Signed</th>
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
                                <tr x-bind:class="(village.is_nc_village_exists &&  village.is_nc_village_exists.dc_chitha_sign == 'Y') ? '' : ''">
                                    <td x-html="village.loc_name +'-'+village.vill_townprt_code"></td>
                                    <td>
                                       <span x-show="village.last_port">
                                        Last Ported On : <span x-text="village.last_port.created_at"></span> <br>
                                        Status : <span x-text="village.last_port.status ? village.last_port.status : 'incomplete'" x-bind:class="village.last_port.status == 'success' ? 'text-success' : (village.last_port.status == 'failed' ? 'text-danger' : 'warning')"></span> <br>
                                       </span>
                                       <span x-show="!village.last_port">No record</span>
                                    </td>
                                    <td x-bind:class="(village.is_synced=='Y') ? 'text-success' : ''" x-text="village.is_synced=='Y' ? 'Yes' : 'No'"></td>
                                    <td x-bind:class="(village.is_nc_village_exists) ? 'text-success' : 'text-danger'" x-text="village.is_nc_village_exists ? 'Yes' : 'No'"></td>
                                    <td x-bind:class="(village.is_nc_village_exists &&  village.is_nc_village_exists.dc_chitha_sign == 'Y') ? 'text-success' : ''" x-text="(village.is_nc_village_exists &&  village.is_nc_village_exists.dc_chitha_sign == 'Y') ? 'Yes' : 'No'"></td>
                                    <td>
                                        <button x-show="(village.is_nc_village_exists &&  village.is_nc_village_exists.dc_chitha_sign == 'Y')" x-on:click="syncVill(village)"  class="btn btn-sm btn-success" type="button">Sync</button>
                                        <div x-show="is_loading_port && loading_uuid==village.uuid" class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <a target="_blank" class="btn btn-info btn-sm" x-show="village.is_nc_village_exists" x-bind:href=base_url+"index.php/utility/ChithaBasicNcSyncController/viewVillage/"+village.dist_code+"/"+village.subdiv_code+"/"+village.cir_code+"/"+village.mouza_pargona_code+"/"+village.lot_no+"/"+village.vill_townprt_code+"/"+village.uuid>View Dags</a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="villages.length == 0">
                                <td colspan="5" class="text-center">
                                    <span>No villages Found</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>