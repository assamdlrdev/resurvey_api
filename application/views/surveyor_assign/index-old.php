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
            // 'surveyor': '',
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'is_loading': false,
            'base_url': "<?= base_url(); ?>",
            'selected_villages': [],
            'lms': [],
            'selected_surveyor': '',
            'is_saving': false,
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
                    self.mouza_pargona_code = '';
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('mouza_pargona_code', function() {
                    self.getLots();
                    self.lot_no = '';
                    self.vill_townprt_code = '';
                });
                this.$watch('lot_no', function() {
                    self.getVillages();
                    // self.getLms();
                    // self.vill_townprt_code = '';
                    // self.selected_surveyor = '';
                });
                this.$watch('selected_surveyor', function() {
                    if (self.selected_surveyor) {
                        self.getVillages();
                    }
                });
            },
            saveSurveyorVillages() {
                var self = this;
                self.is_saving = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To save Surveyor Villages',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading_port = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/saveSurveyorVillages',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'surveyor': self.selected_surveyor,
                                        'dist_code': self.dist_code,
                                        'subdiv_code': self.subdiv_code,
                                        'cir_code': self.cir_code,
                                        'mouza_pargona_code': self.mouza_pargona_code,
                                        'lot_no': self.lot_no,
                                        'villages': self.selected_villages,
                                    },
                                    success: function(data) {
                                        self.is_saving = false;
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
                                                content: 'Failed to save changes.',
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
                                        self.is_saving = false;
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
                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/subdivisiondetails',
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
                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/circledetails',
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
                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/mouzadetails',
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
                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/lotdetails',
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
                    url: '<?= base_url(); ?>index.php/SurveyorAssignerController/villagedetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        'cir': self.cir_code,
                        'mza': self.mouza_pargona_code,
                        'lot': self.lot_no,
                        'surveyor_code': self.selected_surveyor
                    },
                    success: function(data) {
                        self.villages = data;
                        self.villages.forEach(element => {
                            if (element.surveyor_village) {
                                self.selected_villages.push(element.vill_townprt_code);
                            }
                        });
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
                    }
                });
            },
            // getLms() {
            //     var self = this;
            //     this.is_loading = true;
            //     $.ajax({
            //         url: '<?= base_url(); ?>index.php/village_assign/LmVillAssignController/getLms',
            //         method: "POST",
            //         async: true,
            //         dataType: 'json',
            //         data: {
            //             'dis': self.dist_code,
            //             'subdiv': self.subdiv_code,
            //             'cir': self.cir_code,
            //             'mza': self.mouza_pargona_code,
            //             'lot': self.lot_no
            //         },
            //         success: function(data) {
            //             self.lms = data;
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
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">ASSIGN SURVEYOR</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">Select Surveyor</label>
                    <select x-model="selected_surveyor" id="surveyor" class="form-control form-control-sm">
                        <option value="">Select Surveyor</option>
                        <?php foreach ($surveyors as $surveyor) : ?>
                            <option value="<?= $surveyor['username'] ?>"><?= $surveyor['name'] . ' ('. $surveyor['username'] .')' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                            <h5> Assign Villages to Surveyor
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
                            </h5>
                        </div>
                    </div>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead>
                                <tr class="bg-warning">
                                    <th>Select</th>
                                    <th>Village Name</th>
                                    <th>Village Code</th>
                                </tr>
                            </thead>
                            <tbody style="height: 60vh;overflow-y:auto;">
                                <tr x-show="is_loading">
                                    <td colspan="3" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <template x-for="(village,index) in villages">
                                    <tr>
                                        <td>
                                            <input x-model="selected_villages" type="checkbox" x-bind:id="index+'-'+village.vill_townprt_code" x-bind:value="village.vill_townprt_code">
                                        </td>
                                        <td x-text="village.loc_name"></td>
                                        <td x-text="village.vill_townprt_code"></td>
                                    </tr>
                                </template>
                                <tr x-show="villages.length == 0">
                                    <td colspan="3" class="text-center">
                                        <span>No villages Found</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <button x-on:click="saveSurveyorVillages" class="btn btn-success" x-bind:class="(selected_villages.length == 0 || is_saving) ? 'disabled' : ''" type="button">SAVE CHANGES</button>
                    <div class="spinner-border text-success" x-show="is_saving" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>