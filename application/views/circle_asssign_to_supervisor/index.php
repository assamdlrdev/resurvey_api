<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            // 'villages': [],
            'subdivs': [],
            'circles': [],
            // 'mouzas': [],
            // 'lots': [],
            // 'surveyor': '',
            'dist_code': '',
            'subdiv_code': '',
            // 'cir_code': '',
            // 'mouza_pargona_code': '',
            // 'lot_no': '',
            'is_loading': false,
            'base_url': "<?= base_url(); ?>",
            'selected_circles': [],
            // 'lms': [],
            'selected_supervisor': '',
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
                    // self.cir_code = '';
                    // self.mouza_pargona_code = '';
                    // self.lot_no = '';
                    // self.vill_townprt_code = '';
                });
                this.$watch('subdiv_code', function() {
                    self.getCircles();
                    // self.cir_code = '';
                    // self.mouza_pargona_code = '';
                    // self.lot_no = '';
                    // self.vill_townprt_code = '';
                });
                // this.$watch('cir_code', function() {
                //     self.getMouzas();
                //     self.mouza_pargona_code = '';
                //     self.lot_no = '';
                //     self.vill_townprt_code = '';
                // });
                // this.$watch('mouza_pargona_code', function() {
                //     self.getLots();
                //     self.lot_no = '';
                //     self.vill_townprt_code = '';
                // });
                // this.$watch('lot_no', function() {
                //     self.getVillages();
                //     // self.getLms();
                //     // self.vill_townprt_code = '';
                //     // self.selected_supervisor = '';
                // });
                this.$watch('selected_supervisor', function() {
                    self.selected_circles = [];
                    if (self.selected_supervisor) {
                        self.getCircles();
                    }
                });
            },
            saveSurveyorVillages() {
                var self = this;
                self.is_saving = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To save supervisor circles',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading_port = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/circle-assign-supervisor-save',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'supervisor': self.selected_supervisor,
                                        'dist_code': self.dist_code,
                                        'subdiv_code': self.subdiv_code,
                                        'circles': self.selected_circles,
                                    },
                                    success: function(data) {
                                        self.is_saving = false;
                                        if (data.success) {
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
                                                            self.getCircles();
                                                        }
                                                    },
                                                }
                                            });
                                        } else {
                                            $.confirm({
                                                title: 'Failed',
                                                content: data.message,
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
                    url: '<?= base_url(); ?>index.php/circle-assign-supervisor-get-subdivisions',
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
                this.is_loading = true;
                this.selected_circles = [];
                $.ajax({
                    url: '<?= base_url(); ?>index.php/circle-assign-supervisor-get-circlesdetails',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        // 'cir': self.cir_code,
                        // 'mza': self.mouza_pargona_code,
                        // 'lot': self.lot_no,
                        'supervisor_code': self.selected_supervisor
                    },
                    success: function(data) {
                        self.circles = data;
                        self.circles.forEach(element => {
                            if (element.supervisor_circle) {
                                self.selected_circles.push(element.cir_code);
                            }
                        });
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
                    }
                });
            },
            // getMouzas() {
            //     var self = this;
            //     $.ajax({
            //         url: '<?= base_url(); ?>index.php/SurveyorAssignerController/mouzadetails',
            //         method: "POST",
            //         async: true,
            //         dataType: 'json',
            //         data: {
            //             'dis': self.dist_code,
            //             'subdiv': self.subdiv_code,
            //             'cir': self.cir_code
            //         },
            //         success: function(data) {
            //             self.mouzas = data;
            //         }
            //     });
            // },
            // getLots() {
            //     var self = this;
            //     $.ajax({
            //         url: '<?= base_url(); ?>index.php/SurveyorAssignerController/lotdetails',
            //         method: "POST",
            //         async: true,
            //         dataType: 'json',
            //         data: {
            //             'dis': self.dist_code,
            //             'subdiv': self.subdiv_code,
            //             'cir': self.cir_code,
            //             'mza': self.mouza_pargona_code
            //         },
            //         success: function(data) {
            //             self.lots = data;
            //         }
            //     });
            // },
            // getVillages() {
            //     var self = this;
            //     this.selected_villages = [];
            //     this.is_loading = true;
            //     $.ajax({
            //         url: '<?= base_url(); ?>index.php/SurveyorAssignerController/villagedetails',
            //         method: "POST",
            //         async: true,
            //         dataType: 'json',
            //         data: {
            //             'dis': self.dist_code,
            //             'subdiv': self.subdiv_code,
            //             'cir': self.cir_code,
            //             'mza': self.mouza_pargona_code,
            //             'lot': self.lot_no,
            //             'surveyor_code': self.selected_supervisor
            //         },
            //         success: function(data) {
            //             self.villages = data;
            //             self.villages.forEach(element => {
            //                 if (element.surveyor_village) {
            //                     self.selected_villages.push(element.vill_townprt_code);
            //                 }
            //             });
            //             self.is_loading = false;
            //         },
            //         error: () => {
            //             self.is_loading = false;
            //         }
            //     });
            // },
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
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">ASSIGN CIRCLES</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">Select Supervisor</label>
                    <select x-model="selected_supervisor" id="supervisor" class="form-control form-control-sm">
                        <option value="">Select Supervisor</option>
                        <?php foreach ($supervisors as $supervisor) : ?>
                            <option value="<?= $supervisor['username'] ?>"><?= $supervisor['name'] . ' ('. $supervisor['username'] .')' ?></option>
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
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> Assign Circle to Supervisor
                                <small class="text-dark" x-text="'Total Circles : ' + circles.length"></small>
                            </h5>
                        </div>
                    </div>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead>
                                <tr class="bg-warning">
                                    <th>Select</th>
                                    <th>Circle Name</th>
                                    <th>Circle Code</th>
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
                                <template x-for="(circle,index) in circles">
                                    <tr>
                                        <td>
                                            <input x-model="selected_circles" type="checkbox" x-bind:id="index+'-'+circle.cir_code" x-bind:value="circle.cir_code">
                                        </td>
                                        <td x-text="circle.loc_name"></td>
                                        <td x-text="circle.cir_code"></td>
                                    </tr>
                                </template>
                                <tr x-show="circles.length == 0">
                                    <td colspan="3" class="text-center">
                                        <span>No circles Found</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <button x-on:click="saveSurveyorVillages" class="btn btn-success" x-bind:class="(selected_circles.length == 0 || is_saving) ? 'disabled' : ''" type="button">SAVE CHANGES</button>
                    <div class="spinner-border text-success" x-show="is_saving" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>