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
            'proposal_base': "<?= base_url() . NC_VILLAGE_PROPOSAL_DIR ?>",
            'view_maps': [],
            'is_loading_maps': false,
            'view_map_vill_name': '',
            'view_map_uuid': '',
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
                });
                this.$watch('subdiv_code', function() {
                    self.getCircles();
                    self.cir_code = '';
                    self.mouza_pargona_code = '';
                });
                this.$watch('cir_code', function() {
                    self.getMouzas();
                    self.mouza_pargona_code = '';
                    self.getNcVillages();
                });
                this.$watch('mouza_pargona_code', function() {
                    self.getNcVillages();
                });
            },
            get excelDownloadUrl(){
                var url = '<?= base_url('index.php/nc_village/NcVillageReportController/ncVillagesExcelDownload?d=') ?>';
                url += this.dist_code;
                if(this.subdiv_code){
                    url += '&s='+this.subdiv_code;
                }
                if(this.cir_code){
                    url += '&c='+this.cir_code;
                }
                if(this.mouza_pargona_code){
                    url += '&m='+this.mouza_pargona_code;
                }
                if(this.lot_no){
                    url += '&l='+this.lot_no;
                }
                return url;
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
            getNcVillages() {
                var self = this;
                this.selected_villages = [];
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageReportController/getNcVillages',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dist_code': self.dist_code,
                        'subdiv_code': self.subdiv_code,
                        'cir_code': self.cir_code,
                        'mouza_pargona_code': self.mouza_pargona_code
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
            viewMaps(dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, vill_name, uuid) {
                $("#view_maps").modal('hide');
                var self = this;
                self.view_maps = [];
                self.is_loading_maps = true;
                self.view_map_vill_name = vill_name;
                self.view_map_uuid = uuid;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageReportController/getNcMaps',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dist_code': dist_code,
                        'subdiv_code': subdiv_code,
                        'cir_code': cir_code,
                        'mouza_pargona_code': mouza_pargona_code,
                        'lot_no': lot_no,
                        'vill_townprt_code': vill_townprt_code
                    },
                    success: function(data) {
                        console.log(data);
                        if (data != 'NA') {
                            self.view_maps = data.maps;
                            $("#view_maps").modal('show');
                        } else {
                            self.view_maps = [];
                        }
                        self.is_loading_maps = false;
                        self.view_map_uuid = '';
                    },
                    error: function(err) {
                        self.is_loading_maps = false;
                        self.view_map_uuid = '';
                    }
                });
            }

        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2 bg-primary" style="font-size:18px; font-weight: bold;">NC Village Progress Track</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select x-model="dist_code" id="district" class="form-control form-control-sm">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $district) : ?>
                            <option value="<?= $district['dist_code'] ?>"><?php echo ($district['loc_name']) . ' - ' . $district['dist_code'] ?></option>
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
                <div class="col-lg-2" x-show="dist_code && subdiv_code && cir_code">
                    <label for=""></label>
                    <div class="pt-2">
                    <a x-bind:href="excelDownloadUrl">
                        <button class="btn btn-sm btn-primary">
                            Download Excel
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                            </svg>
                        </button>
                    </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> NC Village Progress Track
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small></small>
                            </h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                            <table class="table table-hover table-sm table-bordered table-striped">
                                <thead>
                                    <tr class="bg-warning">
                                        <th>Circle</th>
                                        <th>Mouza</th>
                                        <th>Lot</th>
                                        <th class="no-wrap">Village Name</th>
                                        <th class="no-wrap">Current User</th>
                                        <th class="no-wrap">Previous User</th>
                                        <th class="no-wrap">LM Verified</th>
                                        <th class="no-wrap">SK Verified</th>
                                        <th class="no-wrap">CO Verified</th>
                                        <th class="no-wrap">CO Chitha Certified</th>
                                        <th class="no-wrap">CO Proposal Sent</th>
                                        <th class="no-wrap">DC Verified</th>
                                        <th class="no-wrap">DC Chitha Certified</th>
                                        <th class="no-wrap">DC Chitha Sign</th>
                                        <th class="no-wrap">DC Map Sign</th>
                                        <th class="no-wrap">DC Proposal Sent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr x-show="is_loading">
                                        <td colspan="11" class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <template x-for="(village,index) in villages">
                                        <tr>
                                            <td x-html="village.circle_name"></td>
                                            <td x-html="village.mouza_name"></td>
                                            <td x-html="village.lot_name"></td>
                                            <td x-html="village.loc_name"></td>
                                            <td x-html="village.cu_user"></td>
                                            <td x-html="village.pre_user"></td>
                                            <td x-html="village.lm_verified == 'Y' ? 'Yes' : 'No'"></td>
                                            <td x-html="village.sk_verified == 'Y' ? 'Yes' : 'No'"></td>
                                            <td x-html="village.co_verified == 'Y' ? 'Yes' : 'No'"></td>
                                            <td x-html="village.co_chitha_verified > 0  ? 'Yes' : 'No'"></td>
                                            <td>
                                                <span x-text="village.co_proposal == 'Y' ? 'Yes' : 'No'"></span>
                                                <a target="_blank" x-show="village.co_proposal == 'Y'" x-bind:href=proposal_base+"co/"+village.proposal_no+".pdf">View</a>
                                            </td>
                                            <td x-html="village.dc_verified == 'Y' ? 'Yes' : 'No'"></td>
                                            <td x-html="village.dc_chitha_verified > 0 ? 'Yes' : 'No'"></td>
                                            <td x-html="village.dc_chitha_sign == 'Y' ? 'Yes' : 'No'"></td>
                                            <td>
                                                <button type="button" x-show="view_map_uuid!=village.uuid && !is_loading_maps" class="btn btn-sm btn-info" x-on:click="viewMaps(village.dist_code,village.subdiv_code,village.cir_code,village.mouza_pargona_code,village.lot_no,village.vill_townprt_code,village.loc_name,village.uuid)">View Map(s)</button>
                                                <div x-show="view_map_uuid==village.uuid && is_loading_maps" class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span x-text="village.dc_proposal == 'Y' ? 'Yes' : 'No'"></span>
                                                <a target="_blank" x-show="village.dc_proposal == 'Y'" x-bind:href=proposal_base+"dc/"+village.proposal_no_dc+".pdf">View</a>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="villages.length == 0">
                                        <td colspan="11" class="text-center">
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
    <!-- Modal -->
    <div class="modal fade" id="view_maps" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Maps of <b x-text="view_map_vill_name"></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover table-sm table-bordered">
                        <thead style="position: sticky;top:0;" class="bg-warning">
                            <tr>
                                <th>Sl.No.</th>
                                <th>View Map</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(map,index) in view_maps">
                                <tr>
                                    <td x-text="index + 1"></td>
                                    <td>
                                        <a x-bind:href="'<?= base_url() ?>index.php/nc_village/NcVillageReportController/viewUploadedMap?id=' + map.id" class="btn btn-info py-2" style="color: white" target="_blank">
                                            View Map
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="view_maps.length == 0">
                                <td colspan="3" class="text-center">
                                    <span x-show="!is_loading">No Map Found</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>