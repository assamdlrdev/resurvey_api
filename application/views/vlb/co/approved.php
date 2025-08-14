<script>
    function alpineData() {
        return {
            vlb_type: 'approved',
            land_bank_details_id: '',
            dist_code: '',
            subdiv_code: '',
            cir_code: '',
            mouza_pargona_code: '',
            lot_no: '',
            vill_townprt_code: '',
            dag_no: '',
            nature_of_reservation: '',
            whether_encroached: '',
            en_area_b: 0,
            en_area_k: 0,
            en_area_lc: 0,
            en_area_g: 0,
            en_area_kr: 0,
            longitude: '',
            latitude: '',
            encroachers: [],
            genders: [],
            castes: [],
            land_used_types: [],
            encroacher_types: [],
            get modalTitle() {
                if (this.vlb_type == 'reverted') {
                    return 'View and Update Reverted Land Bank Details';
                } else if (this.vlb_type == 'approved') {
                    return 'View Approved Land Bank Details';
                } else if (this.vlb_type == 'pending') {
                    return 'View Pending Land Bank Details';
                } else {
                    return 'Land Bank Details';
                }
            },
            get modalHeaderBg() {
                if (this.vlb_type == 'reverted') {
                    return 'bg-danger text-white';
                } else if (this.vlb_type == 'approved') {
                    return 'bg-success text-white';
                } else if (this.vlb_type == 'pending') {
                    return 'bg-warning';
                } else {
                    return 'bg-info text-white';
                }
            },
            resetData() {
                this.land_bank_details_id = '';
                this.dag_no = '';
                this.en_area_b = 0;
                this.en_area_k = 0;
                this.en_area_lc = 0;
                this.en_area_g = 0;
                this.en_area_kr = 0;
                this.longitude = '';
                this.latitude = '';
                this.encroachers = [];
                this.co_remark = '';
            },

            addEncroacher(enc = null) {
                var encroacher = {
                    'name': enc ? enc.name : '',
                    'fathers_name': enc ? enc.fathers_name : '',
                    'gender': enc ? enc.gender : '',
                    'encroachment_from': enc ? enc.encroachment_from : '',
                    'encroachment_to': enc ? enc.encroachment_to : '',
                    'landless_indigenous': enc ? enc.landless_indigenous : '',
                    'landless': enc ? enc.landless : '',
                    'caste': enc ? enc.caste : '',
                    'erosion': enc ? enc.erosion : '',
                    'landslide': enc ? enc.landslide : '',
                    'type_of_land_use': enc ? enc.type_of_land_use : '',
                    'type_of_encroacher': enc ? enc.type_of_encroacher : ''
                };
                if (enc) {
                    encroacher.id = enc.id;
                }
                this.encroachers.push(enc);
            },
            viewDetailsModalOpen(land_bank_id) {
                this.resetData();
                var formdata = new FormData();
                formdata.append('land_bank_id', land_bank_id);
                formdata.append('vlb_type', this.vlb_type);
                var self = this;
                $.ajax({
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    url: window.base_url + "index.php/vlb/VlbController/getLandBankDetailsById",
                    data: formdata,
                    type: "POST",
                    success: function(data) {
                        if (data.land_bank_det) {
                            self.land_bank_details_id = data.land_bank_det.id;
                            self.nature_of_reservation = data.land_bank_det.nature_of_reservation;
                            self.whether_encroached = data.land_bank_det.whether_encroached;
                            self.en_area_b = data.land_bank_det.en_area_b;
                            self.en_area_k = data.land_bank_det.en_area_k;
                            self.en_area_lc = data.land_bank_det.en_area_lc;
                            self.en_area_g = data.land_bank_det.en_area_g;
                            self.en_area_kr = data.land_bank_det.en_area_kr;
                            self.latitude = data.land_bank_det.latitude;
                            self.longitude = data.land_bank_det.longitude;

                            if (data.encroachers.length > 0) {
                                data.encroachers.forEach(enc => {
                                    self.addEncroacher(enc);
                                });
                            }
                            $("#viewDetailsModal").modal("show");

                        }
                    },
                    error: function(error) {

                    }
                });
            },


            init() {
                var location_names = '<?= json_encode($location_names) ?>';
                var district = JSON.parse(location_names).district;
                var subdiv = JSON.parse(location_names).subdiv;
                var circle = JSON.parse(location_names).circle;

                this.dist_code = district.dist_code;
                this.subdiv_code = subdiv.subdiv_code;
                this.cir_code = circle.cir_code;

                var genders = '<?php echo ($genders) ?>';
                var castes = '<?php echo ($castes) ?>';
                var LB_ENC_TYPE_OF_LAND_USE = '<?php echo (LB_ENC_TYPE_OF_LAND_USE) ?>';
                var TYPE_OF_ENCROACHER = '<?php echo (TYPE_OF_ENCROACHER) ?>';

                this.genders = JSON.parse(genders);
                this.castes = JSON.parse(castes);
                this.land_used_types = JSON.parse(LB_ENC_TYPE_OF_LAND_USE);
                this.encroacher_types = JSON.parse(TYPE_OF_ENCROACHER);
            },

        }
    }
</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" x-data="alpineData()">
    <div class="d-flex justify-content-end align-items-center mb-2">
        <a href="<?= base_url('index.php/vlb/VlbCoController/dashboard') ?>" class="btn btn-sm btn-info">Land Bank Dashboard</a>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-success py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Village Land Bank - (Approved-List)
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-md-2">
                    <select x-model="dist_code" id="dist_code" class="form-control form-control-sm">
                        <option value="">--District--</option>
                        <option selected value="<?= $location_names['district']->dist_code ?>"><?= $location_names['district']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="subdiv_code" id="subdiv_code" class="form-control form-control-sm">
                        <option value="">--Sub Division--</option>
                        <option selected value="<?= $location_names['subdiv']->subdiv_code ?>"><?= $location_names['subdiv']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="cir_code" id="cir_code" class="form-control form-control-sm">
                        <option value="">--Circle--</option>
                        <option selected value="<?= $location_names['circle']->cir_code ?>"><?= $location_names['circle']->loc_name ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="mouza_pargona_code" id="mouza_pargona_code" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Mouza--</option>
                        <?php foreach ($mouzas as $mouza) : ?>
                            <option value="<?= $mouza['mouza_pargona_code'] ?>"><?= $mouza['loc_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="lot_no" id="lot_no" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Lot--</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select x-model="vill_townprt_code" id="vill_townprt_code" onchange="reloadData()" class="form-control form-control-sm">
                        <option value="">--Village--</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatable" class="display compact table-bordered">
                    <thead>
                        <tr class="bg-success">
                            <th>Village Name</th>
                            <th>Dag No</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
        <?php include(APPPATH . 'views/vlb/includes/view_details_modal.php'); ?>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/only_location.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script>
    var cols = [{
            "data": "village"
        },
        {
            "data": "dag_no"
        },
        {
            "data": "created_by"
        },
        {
            "data": "created_at"
        },
        {
            "data": "action"
        }
    ];
    var table = $('#datatable').DataTable({
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
            'url': '<?= base_url() ?>index.php/vlb/VlbCoController/getApprovedListsCo',
            "type": "POST",
            "data": function(d) {
                d.dist_code = $("#dist_code").val();
                d.subdiv_code = $("#subdiv_code").val();
                d.cir_code = $("#cir_code").val();
                d.mouza_pargona_code = $("#mouza_pargona_code").val();
                d.lot_no = $("#lot_no").val();
                d.vill_townprt_code = $("#vill_townprt_code").val();
            }
        },
        columns: cols
    });

    function reloadData() {
        table.ajax.reload();
    }
</script>