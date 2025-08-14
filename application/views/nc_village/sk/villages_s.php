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
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
            'filter_status': 'pending',
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
                var villages = '<?= json_encode($villages) ?>';
                this.villages = JSON.parse(villages);
                console.log(villages);

            },
            getLots() {
                var self = this;
                this.filter_status = 'pending';
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
            get getPendingVillages() {
                var total = 0;
                this.villages.forEach(element => {
                    if (element.sk_verified != 'Y') {
                        total++;
                    }
                });
                return total;
            },
            getVillages() {
                var self = this;
                this.selected_villages = [];
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageSkController/getVillagesS',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dist_code': self.dist_code,
                        'subdiv_code': self.subdiv_code,
                        'cir_code': self.cir_code,
                        'mouza_pargona_code': self.mouza_pargona_code,
                        'lot_no': self.lot_no,
                        'filter': self.filter_status
                    },
                    success: function(data) {
                        self.villages = data;
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
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
            <div class="row">
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
                        <option value="pending">Pending</option>
                        <option value="sk_verified">Verified</option>
                        <option value="sk_reverted">Reverted</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> VERIFIED VILLAGES BY <?= LM_LABEL ?>
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>, <small class="text-warning" x-text="getPendingVillages+' Pending'"></small>
                            </h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead class="bg-warning">
                                <tr>
                                    <th>Sl</th>
                                    <th>Village Name</th>
                                    <th>Merging Type</th>
                                    <th>Merge Villages Name</th>
                                    <th>Cadastral village name into which the non-cadastral village will be merged</th>
                                    <th><?= LM_LABEL ?> verified at</th>
                                    <th><?= LM_LABEL ?> Note</th>
                                    <th><?= SK_LABEL ?> Note</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr x-show="is_loading">
                                    <td colspan="7" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <template x-for="(village,index) in villages">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <td class="no-wrap" x-text="village.loc_name"></td>
                                        <td class="no-wrap">
                                            <template x-if="village.case_type === 'NC_TO_NC'">
                                                <span>NC to NC</span>
                                            </template>
                                            <template x-if="village.case_type === 'NC_TO_C'">
                                                <span>NC to Cadastral</span>
                                            </template>
                                            <template x-if="village.case_type === 'STAND_ALONE'">
                                                <span>N/A</span>
                                            </template>
                                        </td>
                                        <td >
                                            <template x-if="village.case_type=='NC_TO_NC'">
                                                <span x-text="village.merge_village_names ? village.merge_village_names : 'N.A.'"></span>
                                            </template>
                                            <template x-if="village.case_type!='NC_TO_NC'">
                                                <span>N.A.</span>
                                            </template>
                                        </td>
                                        <td class="no-wrap">
                                            <template x-if="village.case_type=='NC_TO_C'">
                                                <span x-text="village.merge_village_names ? village.merge_village_names : 'N.A.'"></span>
                                            </template>
                                            <template x-if="village.case_type!='NC_TO_C'">
                                                <span>N.A.</span>
                                            </template>
                                        </td>
                                        <td class="no-wrap" x-text="village.lm_verified_at ? (new Date(village.lm_verified_at)).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' }) : ''"></td>
                                        <td x-text="village.lm_note"></td>
                                        <td x-text="village.sk_note"></td>
                                        <td>
                                            <b>
                                                <small class="text-warning" x-show="village.status == 'S'">Pending </small>
                                                <small class="text-success" x-show="village.status == 'T'">Verified</small>
                                            </b>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-info text-white no-wrap" x-bind:href=base_url+"index.php/nc_village/NcVillageSkController/showDags?application_no="+village.application_no>Proceed <i class=" fa fa-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="villages.length == 0">
                                    <td colspan="7" class="text-center">
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