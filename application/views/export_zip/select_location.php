<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            dist_code: '',
            subdiv_code: '',
            cir_code: '',
            mouza_pargona_code: '',
            lot_no: '',
            vill_townprt_code: '',
            isLoading: false,
            files: [],
            get showSubmit() {
                if (this.dist_code && this.subdiv_code && this.cir_code && this.mouza_pargona_code && this.lot_no && this.vill_townprt_code) {
                    return true;
                } else {
                    return false;
                }
            },
            init() {
                this.dist_code = "<?php echo ($this->session->userdata('dcode')) ?>";
                this.$watch('lot_no', lot_no => {
                    this.getFiles();
                });
            },
            getFiles() {
                this.files = [];
                var self = this;
                var data = {
                    dist_code: self.dist_code,
                    subdiv_code: self.subdiv_code,
                    cir_code: self.cir_code,
                    mouza_pargona_code: self.mouza_pargona_code,
                    lot_no: self.lot_no
                };
                $.ajax({
                    url: window.base_url + 'index.php/zip_table/ExportZipController/list_files_from_directory',
                    method: "POST",
                    data: data,
                    async: true,
                    dataType: 'json',
                    success: function(data) {
                        if (data.villages) {
                            data.villages.forEach(element => {
                                var vill = {};
                                vill.file = element.file;
                                vill.file_name = element.file_name;
                                vill.vill_name = element.vill_name;
                                self.files.push(vill);
                            });
                        }
                    }
                });
            },
            exportData() {
                var self = this;
                $.confirm({
                    title: 'Confirm!',
                    content: 'Please confirm to export data!',
                    buttons: {
                        confirm: {
                            text: 'Confirm!',
                            btnClass: 'btn-success',
                            action: function(confirm) {
                                self.isLoading = true;
                                var data = {
                                    dist_code: self.dist_code,
                                    subdiv_code: self.subdiv_code,
                                    cir_code: self.cir_code,
                                    mouza_pargona_code: self.mouza_pargona_code,
                                    lot_no: self.lot_no,
                                    vill_townprt_code: self.vill_townprt_code,
                                    submit_type: 'ask_confirm'
                                };
                                $.ajax({
                                    url: window.base_url + 'index.php/zip_table/ExportZipController/exportToZip',
                                    method: "POST",
                                    data: data,
                                    async: true,
                                    dataType: 'json',
                                    success: function(data) {
                                        alert(data.msg);
                                        self.isLoading = false;
                                        self.getFiles();
                                    },
                                    failed:function(error){
                                        self.isLoading = false;
                                    }
                                });
                            }
                        },
                        cancel: function() {

                        }
                    }
                });
                self.isLoading = false;
            },
            deleteZip(file) {
                var self = this;
                $.confirm({
                    title: 'Confirm!',
                    content: 'Please confirm to delete zip! File will be replaced If already exists.',
                    buttons: {
                        confirm: {
                            text: 'Confirm!',
                            btnClass: 'btn-success',
                            action: function(confirm) {
                                self.isLoading = true;
                                $.ajax({
                                    url: window.base_url + 'index.php/zip_table/ExportZipController/deleteZip',
                                    method: "POST",
                                    data: {'file':file},
                                    async: true,
                                    dataType: 'json',
                                    success: function(data) {
                                        alert(data.msg);
                                        self.isLoading = false;
                                        self.getFiles();
                                    }
                                });
                            }
                        },
                        cancel: function() {

                        }
                    }
                });
            }
        }
    }
</script>
<div class="col-md-12 col-lg-12" x-data="alpineData()">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div class="card rounded-0">
                <div class="card-header rounded-0 text-center bg-info py-1">
                    <h5>
                        <i class="fa fa-users" aria-hidden="true"></i> Select Village
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-md-3">
                            <label for="">District</label>
                            <select class="form-control form-control-sm" x-model="dist_code" id="dist_code" class="mr-3">
                                <option value="">--District--</option>
                                <?php foreach ($district as $dist) : ?>
                                    <option value="<?= $dist['dist_code'] ?>"><?= $dist['loc_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Sub Division</label>
                            <select class="form-control form-control-sm" x-model="subdiv_code" id="subdiv_code" class="mr-3">
                                <option value="">--Sub Division--</option>
                                <?php foreach ($sub_divs as $subdiv) : ?>
                                    <option value="<?= $subdiv['subdiv_code'] ?>"><?= $subdiv['loc_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Circle</label>
                            <select class="form-control form-control-sm" x-model="cir_code" id="cir_code" class="mr-3">
                                <option value="">--Circle--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Mouza</label>
                            <select class="form-control form-control-sm" x-model="mouza_pargona_code" id="mouza_pargona_code" class="mr-3">
                                <option value="">--Mouza--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Lot</label>
                            <select class="form-control form-control-sm" x-model="lot_no" id="lot_no" class="mr-3">
                                <option value="">--Lot No--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Village</label>
                            <select class="form-control form-control-sm" x-model="vill_townprt_code" id="vill_townprt_code">
                                <option value="">--Village--</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button :disabled="isLoading" x-show="showSubmit" type="button" x-on:click="exportData()" class="btn btn-sm btn-info">Submit</button>
                            <div x-show="isLoading" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="card rounded-0">
                <div class="card-header rounded-0 text-center bg-success py-1">
                    <h5>
                        <i class="fa fa-download" aria-hidden="true"></i> Download Zips
                    </h5>
                </div>
                <div class="card-body" style="height: 80vh;overflow-y:auto;">
                    <ul class="list-group">
                        <template x-for="(file,index) in files" :key="index">
                            <li class="list-group-item list-group-item d-flex justify-content-between align-items-center"><span x-text="file.vill_name"></span><a :href="file.file"><i class="fa fa-download"></i></a> <button type="button" @click="deleteZip(file.file_name)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button> </li>
                        </template>
                    </ul>
                    <p x-show="files.length == 0" class="text-center">No Records</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/only_location.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    window.showAlert = function(msg, type) {
        $.confirm({
            title: '',
            content: msg,

            buttons: {
                confirm: {
                    text: 'Ok',
                    btnClass: 'btn-info',
                    action: function(confirm) {

                    }
                }
            }
        });
    }
</script>