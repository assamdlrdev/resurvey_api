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
            'dist_code_from': '',
            'subdiv_code_from': '',
            'cir_code_from': '',
            'mouza_pargona_code_from': '',
            'lot_no_from': '',
            'vill_townprt_code_from': '',
            'vill_townprt_code_to': '',
            'is_loading': false,
            'loading_uuid': false,
            'is_loading_port': false,
            'base_url': "<?= base_url(); ?>",
            'send_to_diff_vill': 'N',
            init() {
                var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
                var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

                var self = this;
                this.$watch('dist_code_from', function() {
                    self.subdivs = [];
                    self.circles = [];
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.subdiv_code_from = '';
                    self.cir_code_from = '';
                    self.mouza_pargona_code_from = '';
                    self.lot_no_from = '';
                    self.vill_townprt_code_from = '';
                    self.vill_townprt_code_to = '';
                    if (self.dist_code_from) {
                        self.getSubdivs();
                    }
                });
                this.$watch('subdiv_code_from', function() {
                    self.circles = [];
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.cir_code_from = '';
                    self.mouza_pargona_code_from = '';
                    self.lot_no_from = '';
                    self.vill_townprt_code_from = '';
                    self.vill_townprt_code_to = '';
                    if (self.subdiv_code_from) {
                        self.getCircles();
                    }
                });
                this.$watch('cir_code_from', function() {
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.mouza_pargona_code_from = '';
                    self.lot_no_from = '';
                    self.vill_townprt_code_from = '';
                    self.vill_townprt_code_to = '';
                    if (self.cir_code_from) {
                        self.getMouzas();
                    }
                });
                this.$watch('mouza_pargona_code_from', function() {
                    self.lots = [];
                    self.villages = [];
                    self.lot_no_from = '';
                    self.vill_townprt_code_from = '';
                    self.vill_townprt_code_to = '';
                    if (self.mouza_pargona_code_from) {
                        self.getLots();
                    }
                });
                this.$watch('lot_no_from', function() {
                    self.villages = [];
                    self.vill_townprt_code = '';
                    self.vill_townprt_code_to = '';
                    if (self.lot_no_from) {
                        self.getVillages();
                    }
                });
            },
            portVill(village) {
                var self = this;
                self.loading_uuid = village.uuid;
                if (self.send_to_diff_vill == 'Y') {
                    if (!self.vill_townprt_code_to) {
                        alert('Please Select the Destination Village');
                        return;
                    }
                }
                var data = {};
                data.dist_code = village.dist_code;
                data.subdiv_code = village.subdiv_code;
                data.cir_code = village.cir_code;
                data.mouza_pargona_code = village.mouza_pargona_code;
                data.lot_no = village.lot_no;
                data.vill_townprt_code = village.vill_townprt_code;
                data.send_to_diff_vill = self.send_to_diff_vill;
                if (self.send_to_diff_vill == 'Y') {
                    data.vill_townprt_code_to = self.vill_townprt_code_to;
                }
                data[csrfName] = csrfHash;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To Port Data to Dharitree of '+village.vill_townprt_code,
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading_port = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village/NcVillagePortingController/portVillageToDharitreeNC',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: data,
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
                var data = {};
                data.id = self.dist_code_from;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/subdivisiondetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        self.subdivs = data;
                    }
                });
            },
            getCircles() {
                var self = this;
                var data = {};
                data.dis = self.dist_code_from;
                data.subdiv = self.subdiv_code_from;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/circledetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        self.circles = data;
                    }
                });
            },
            getMouzas() {
                var self = this;
                var data = {};
                data.dis = self.dist_code_from;
                data.subdiv = self.subdiv_code_from;
                data.cir = self.cir_code_from;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/mouzadetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        self.mouzas = data;
                    }
                });
            },
            getLots() {
                var self = this;
                var data = {};
                data.dis = self.dist_code_from;
                data.subdiv = self.subdiv_code_from;
                data.cir = self.cir_code_from;
                data.mza = self.mouza_pargona_code_from;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/Chithacontrol/lotdetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        self.lots = data;
                    }
                });
            },

            getVillages() {
                var self = this;
                this.selected_villages = [];
                this.is_loading = true;
                var data = {};
                data.dis = self.dist_code_from;
                data.subdiv = self.subdiv_code_from;
                data.cir = self.cir_code_from;
                data.mza = self.mouza_pargona_code_from;
                data.lot = self.lot_no_from;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillagePortingController/villagedetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
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
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">Porting Village Data (NC) to Dharitree</div>
    <div class="row">
        <div x-bind:class="send_to_diff_vill=='Y' ? 'col-md-8 col-lg-8' : 'col-md-12 col-lg-12'" class="border">
            <h4>Select Source Village</h4>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label for="">District</label>
                            <select x-model="dist_code_from" id="district_from" class="form-control form-control-sm">
                                <option value="">Select District</option>
                                <?php foreach ($districts as $district) : ?>
                                    <option value="<?= $district['dist_code'] ?>"><?php echo ($district['loc_name']) . ' - ' . $district['dist_code'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="">Sub-Division</label>
                            <select x-model="subdiv_code_from" id="subdiv_from" class="form-control form-control-sm">
                                <option value="">Select Sub Division</option>
                                <template x-for="(subdiv,index) in subdivs" :key="index">
                                    <option x-bind:value="subdiv.subdiv_code"><span x-text="subdiv.loc_name + ' - ' + subdiv.subdiv_code"></span></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="">Circle</label>
                            <select x-model="cir_code_from" id="circle_from" class="form-control form-control-sm">
                                <option value="">Select Circle</option>
                                <template x-for="(circle,index) in circles" :key="index">
                                    <option x-bind:value="circle.cir_code"><span x-text="circle.loc_name + ' - ' + circle.cir_code"></span></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="">Mouza</label>
                            <select x-model="mouza_pargona_code_from" id="mouza_from" class="form-control form-control-sm">
                                <option value="">Select Mouza</option>
                                <template x-for="(mouza,index) in mouzas" :key="index">
                                    <option x-bind:value="mouza.mouza_pargona_code"><span x-text="mouza.loc_name + ' - ' + mouza.mouza_pargona_code"></span></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="">Lot</label>
                            <select x-model="lot_no_from" id="lot_from" class="form-control form-control-sm">
                                <option value="">Select Lot</option>
                                <template x-for="(lot,index) in lots" :key="index">
                                    <option x-bind:value="lot.lot_no"><span x-text="lot.loc_name + ' - ' + lot.lot_no"></span></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="">Send to Different Village</label>
                            <select x-model="send_to_diff_vill" id="send_to_diff_vill" class="form-control form-control-sm">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
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
                            <div class="table-responsive" style="height: 60vh;overflow-y:auto;">
                                <table class="table table-hover table-sm table-bordered table-stripe">
                                    <thead>
                                        <tr class="bg-warning">
                                            <th>Sl</th>
                                            <th>Village Name</th>
                                            <th>Porting Status</th>
                                            <th>Is Alpha/Patta</th>
                                            <th>Is TS Village (for Karimganj)</th>
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
                                            <tr x-bind:class="village.is_alpha > 0 ? 'bg-warning' : ''">
                                                <td x-text="village.uuid"></td>
                                                <td x-html="village.loc_name + ' ' + (village.verified ? village.verified : '') + '-'+village.vill_townprt_code"></td>
                                                <td>
                                                    <template x-if="village.last_port">
                                                        <p>
                                                            Last Ported On : <span x-text="village.last_port.created_at"></span> <br>
                                                            Status : <span x-text="village.last_port.status ? village.last_port.status : 'incomplete'" x-bind:class="village.last_port.status == 'success' ? 'text-success' : (village.last_port.status == 'failed' ? 'text-danger' : 'warning')"></span> <br>
                                                        </p>
                                                    </template>
                                                    <span x-show="!village.last_port">No record</span>
                                                </td>
                                                <td x-text="village.is_alpha > 0 ? 'Yes' : 'No'"></td>
                                                <td x-text="village.show_port == 1 ? 'Yes' : 'No'"></td>
                                                <td>
                                                    <button x-show="!is_loading_port && village.is_alpha == 0 && village.show_port == 1" x-on:click="portVill(village)" class="btn btn-sm btn-success" type="button">Port</button>
                                                    <div x-show="is_loading_port && loading_uuid==village.uuid" class="spinner-border text-primary" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                    <a target="_blank" x-show="village.is_alpha == 0" x-bind:href=base_url+"index.php/nc_village/NcVillagePortingController/villageLog/"+village.dist_code+"-"+village.subdiv_code+"-"+village.cir_code+"-"+village.mouza_pargona_code+"-"+village.lot_no+"-"+village.vill_townprt_code>Log</a>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="villages.length == 0">
                                            <td colspan="6" class="text-center">
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
        </div>
        <div class="col-md-4 col-lg-4 border" x-show="send_to_diff_vill=='Y'">
            <h4>Select Destination Village</h4>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <label for="">District</label>
                    <select disabled x-model="dist_code_from" id="district_to" class="form-control form-control-sm">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $district) : ?>
                            <option value="<?= $district['dist_code'] ?>"><?php echo ($district['loc_name']) . ' - ' . $district['dist_code'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <label for="">Sub-Division</label>
                    <select disabled x-model="subdiv_code_from" id="subdiv_code_from" class="form-control form-control-sm">
                        <option value="">Select Sub Division</option>
                        <template x-for="(subdiv,index) in subdivs" :key="index">
                            <option x-bind:value="subdiv.subdiv_code"><span x-text="subdiv.loc_name + ' - ' + subdiv.subdiv_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <label for="">Circle</label>
                    <select disabled x-model="cir_code_from" id="cir_code_from" class="form-control form-control-sm">
                        <option value="">Select Circle</option>
                        <template x-for="(circle,index) in circles" :key="index">
                            <option x-bind:value="circle.cir_code"><span x-text="circle.loc_name + ' - ' + circle.cir_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6">
                    <label for="">Mouza</label>
                    <select disabled x-model="mouza_pargona_code_from" id="mouza_pargona_code_from" class="form-control form-control-sm">
                        <option value="">Select Mouza</option>
                        <template x-for="(mouza,index) in mouzas" :key="index">
                            <option x-bind:value="mouza.mouza_pargona_code"><span x-text="mouza.loc_name + ' - ' + mouza.mouza_pargona_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6">
                    <label for="">Lot</label>
                    <select disabled x-model="lot_no_from" id="lot_no_from" class="form-control form-control-sm">
                        <option value="">Select Lot</option>
                        <template x-for="(lot,index) in lots" :key="index">
                            <option x-bind:value="lot.lot_no"><span x-text="lot.loc_name + ' - ' + lot.lot_no"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12">
                    <label for="">Village</label>
                    <select x-model="vill_townprt_code_to" id="vill_townprt_code_to" class="form-control form-control-sm">
                        <option value="">Select Village</option>
                        <template x-for="(village,index) in villages" :key="index">
                            <option x-bind:value="village.vill_townprt_code"><span x-text="village.loc_name + ' - ' + village.vill_townprt_code"></span></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>