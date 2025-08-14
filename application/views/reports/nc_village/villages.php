<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'dist_code': '',
            'subdiv_code': '',
            'subdiv_codes': [],
            'cir_code': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'circles': [],
            'mouzas': [],
            'lots': [],
            'villages': [],
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
            'filter_status': '',
            init() {
                var self = this;
                this.$watch('dist_code', function() {
                    self.subdiv_code = '';
                    self.cir_code = '';
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.subdiv_codes = [];
                    self.circles = [];
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.getSubdiv();
                });
                this.$watch('subdiv_code', function() {
                    self.cir_code = '';
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.circles = [];
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.getCircles();
                    reloadData();
                });
                this.$watch('cir_code', function() {
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.mouzas = [];
                    self.lots = [];
                    self.villages = [];
                    self.getMouzas();
                    reloadData();
                });
                this.$watch('mouza_pargona_code', function() {
                    self.lot_no = '';
                    self.lots = [];
                    self.villages = [];
                    self.getLots();
                    reloadData();
                });
                this.$watch('lot_no', function() {
                    reloadData()
                });
                this.$watch('filter_status', function() {
                    reloadData()
                });
                var dist_code = '<?= $dist_code ?>';
                var type = '<?= $type ?>';

                self.dist_code = dist_code;
                self.filter_status = type;
            },
            get title() {
                if (this.filter_status == 'verified_lm')
                    return ('VERIFIED DRAFT CHITHA AND MAP (LM)');
                else if (this.filter_status == 'certified_co')
                    return ('CERTIFICATION OF DRAFT CHITHA AND MAP(CO)');
                else if (this.filter_status == 'digi_signature_dc')
                    return ('DIGITAL SIGNATURE (DC)');
            },
            getSubdiv() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/common/LocationController/subdivisiondetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                    },
                    success: function(data) {
                        self.subdiv_codes = data;
                    }
                });
            },
            getCircles() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/common/LocationController/circledetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                    },
                    success: function(data) {
                        self.circles = data;
                    }
                });
            },
            getMouzas() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/common/LocationController/mouzadetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        'cir': self.cir_code,
                    },
                    success: function(data) {
                        self.mouzas = data;
                    }
                });
            },
            getLots() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/common/LocationController/lotdetails',
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
            // getVillages() {
            //     var self = this;
            //     this.selected_villages = [];
            //     this.is_loading = true;
            //     $.ajax({
            //         url: '<?= base_url(); ?>index.php/nc_village/NcVillageAdsController/getVillagesG',
            //         method: "POST",
            //         async: true,
            //         dataType: 'json',
            //         data: {
            //             'dist_code': self.dist_code,
            //             'subdiv_code': self.subdiv_code,
            //             'cir_code': self.cir_code,
            //             'mouza_pargona_code': self.mouza_pargona_code,
            //             'lot_no': self.lot_no,
            //             'filter': self.filter_status
            //         },
            //         success: function(data) {
            //             self.villages = data;
            //             self.is_loading = false;
            //         },
            //         error: () => {
            //             self.is_loading = false;
            //         }
            //     });
            // },
        }
    }
</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" x-data="alpineData()">
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> <span x-text="title"></span>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select x-model="dist_code" id="district" class="form-control form-control-sm">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $dis) : ?>
                            <option value="<?= $dis['dist_code'] ?>"><?= $dis['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Sub-Division</label>
                    <select x-model="subdiv_code" id="subdiv" class="form-control form-control-sm">
                        <option value="">Select Sub-division</option>
                        <template x-for="(subdiv_code,index_subdiv_code) in subdiv_codes" :key="index_subdiv_code">
                            <option x-bind:value="subdiv_code.subdiv_code" x-text="subdiv_code.loc_name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Circle</label>
                    <select x-model="cir_code" id="circle" class="form-control form-control-sm">
                        <option value="">Select Circle</option>
                        <template x-for="(circle,index_circle) in circles" :key="index_circle">
                            <option x-bind:value="circle.cir_code" x-text="circle.loc_name"></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Mouza</label>
                    <select x-model="mouza_pargona_code" id="mouza" class="form-control form-control-sm">
                        <option value="">Select Mouza</option>
                        <template x-for="(mouza,index_mouza) in mouzas" :key="index_mouza">
                            <option x-bind:value="mouza.mouza_pargona_code" x-text="mouza.loc_name"></option>
                        </template>
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
                    <label for="">Filter Status</label>
                    <select x-model="filter_status" id="filter_status" class="form-control form-control-sm">
                        <option value="">Select Status</option>
                        <option value="verified_lm">VERIFIED DRAFT CHITHA AND MAP (LM)</option>
                        <option value="certified_co">CERTIFICATION OF DRAFT CHITHA AND MAP (CO)</option>
                        <option value="digi_signature_dc">DIGITAL SIGNATURE (DC)</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm table-bordered display compact">
                            <thead>
                                <tr class="bg-warning">
                                    <th>Village</th>
                                    <th>Circle</th>
                                    <th>Mouza</th>
                                    <th>Lot</th>
                                    <th>DC Verified</th>
                                    <th>DC Note</th>
                                    <th>CO Note</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/only_location.js') ?>"></script>
<script>
    table = $('#datatable').DataTable({
        'processing': true,
        'serverMethod': 'post',
        "ordering": false,
        "lengthMenu": [
            [20, 50, 100, 200],
            [20, 50, 100, 200]
        ],
        'language': {
            "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>'
        },
        'ajax': {
            'url': '<?= base_url() ?>index.php/reports/MapCertifiedVillagesController/getNcVillagesByStatus',
            "type": "POST",
            "data": function(d) {
                d.dist_code = $("#district").val();
                d.subdiv_code = $("#subdiv").val();
                d.cir_code = $("#circle").val();
                d.mouza_pargona_code = $("#mouza").val();
                d.lot_no = $("#lot").val();
                d.filter_status = $("#filter_status").val();
            }
        },
        columns: [{
                "data": "village"
            },
            {
                "data": "circle"
            },
            {
                "data": "mouza"
            },
            {
                "data": "lot"
            },
            {
                "data": "dc_verified_at"
            },
            {
                "data": "dc_note"
            },
            {
                "data": "co_note"
            }
        ]
    });


    function reloadData() {
        table.ajax.reload();
    }
</script>