<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script>
    function alpineData() {
        return {
            'subdivs': [],
            'circles': [],
            'gis_assistant': '',
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'is_loading': false,
            'base_url': "<?= base_url(); ?>",
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
                    self.cir_code = '';
                    // self.mouza_pargona_code = '';
                    // self.lot_no = '';
                    // self.vill_townprt_code = '';
                });
            },
            saveGisCircle() {
                var self = this;
                if(self.cir_code.length == 0 || self.is_saving || self.gis_assistant.length == 0){
                    return false;
                }
                self.is_saving = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm To save GIS Assistant circle',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                self.is_loading_port = true;
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/gis/circle-assign/save',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'gis_assistant': self.gis_assistant,
                                        'dist_code': self.dist_code,
                                        'subdiv_code': self.subdiv_code,
                                        'cir_code': self.cir_code,
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
                                                            // self.getCircles();
                                                            location.reload(true);
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
                                self.is_saving = false;
                            }
                        }
                    }
                });
            },
            getSubdivs() {
                var self = this;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/gis/circle-assign-get-sub-div',
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
                // this.selected_circles = [];
                $.ajax({
                    url: '<?= base_url(); ?>index.php/gis/circle-assign-get-circle',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'dis': self.dist_code,
                        'subdiv': self.subdiv_code,
                        // 'cir': self.cir_code,
                        // 'mza': self.mouza_pargona_code,
                        // 'lot': self.lot_no,
                        // 'supervisor_code': self.selected_supervisor
                    },
                    success: function(data) {
                        self.circles = data;
                        // self.circles.forEach(element => {
                        //     if (element.supervisor_circle) {
                        //         self.selected_circles.push(element.cir_code);
                        //     }
                        // });
                        self.is_loading = false;
                    },
                    error: () => {
                        self.is_loading = false;
                    }
                });
            },
        }
    }

    $(document).on('click', '.delete_gis_circle', function(){
        const $this = $(this);
        const actionUrl = $this.data('url');
        $.confirm({
                    title: 'Confirm',
                    content: 'Are you sure? You want to delete this.',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Ok: {
                            text: 'Confirm',
                            btnClass: 'btn-info',
                            action: function() {
                                $.ajax({
                                    url: actionUrl,
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {},
                                    contentType: 'application/x-www-form-urlencoded',
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
                                                            location.reload(true);
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
    });
    
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">ASSIGN CIRCLES</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">Select GIS Assistant</label>
                    <select x-model="gis_assistant" id="gis_assistant" class="form-control form-control-sm">
                        <option value="">Select GIS Assistants</option>
                        <?php foreach ($gis_assistants as $gis_assistant) : ?>
                            <option value="<?= $gis_assistant['username'] ?>"><?= $gis_assistant['name'] . ' (' . $gis_assistant['username'] . ')' ?></option>
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
                    <select x-model="cir_code" id="cir_code" class="form-control form-control-sm">
                        <option value="">Select Circle</option>
                        <template x-for="(circle,index) in circles" :key="index">
                            <option x-bind:value="circle.cir_code"><span x-text="circle.loc_name + ' - ' + circle.cir_code"></span></option>
                        </template>
                    </select>
                </div>
                <div class="col-lg-3 pt-4">
                    <button x-on:click="saveGisCircle" class="btn btn-success" x-bind:class="(cir_code.length == 0 || is_saving || gis_assistant.length == 0) ? 'disabled' : ''" type="button">SAVE CHANGES</button>
                    <div class="spinner-border text-success" x-show="is_saving" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> Assigned Circle to GIS Assistant</h5>
                        </div>
                    </div>
                    <div class="border mb-2" style="overflow-y:auto;">
                        <table class="table table-hover table-sm table-bordered table-stripe" id="gis_assistant_table">
                            <thead>
                                <tr class="bg-warning">
                                    <th>#</th>
                                    <th>District</th>
                                    <th>Sub-division</th>
                                    <th>Circle</th>
                                    <th>GIS Assistant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="overflow-y:auto;">
                                <?php
                                    if(count($gis_circles)):
                                        foreach($gis_circles as $key => $gis_circle):
                                ?>
                                            <tr>
                                                <td><?= ($key + 1) ?></td>
                                                <td><?= $gis_circle['district_name'] ?></td>
                                                <td><?= $gis_circle['subdivision_name'] ?></td>
                                                <td><?= $gis_circle['circle_name'] ?></td>
                                                <td><?= $gis_circle['user_name'] ?></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="text-danger delete_gis_circle" data-url="<?= base_url('index.php/gis/circle-assign/' . $gis_circle['id'] . '/delete'); ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                <?php
                                        endforeach;
                                    else:
                                ?>
                                    <tr>
                                        <td>No data found</td>
                                    </tr>
                                <?php
                                    endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>