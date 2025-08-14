<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
     window.showError = function(msg,type) {
        $.confirm({
            title: '',
            content: msg,
            type: type,
            buttons: {
                ok: {
                    text: 'Ok',
                    btnClass: 'btn-info',
                    action: function() {
                        return;
                    }
                },
            }
        });
    }
    function alpineData() {
        return {
            vlb_type:'add',
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
                }else if (this.vlb_type == 'approved') {
                    return 'View Approved Land Bank Details';
                }else if (this.vlb_type == 'pending') {
                    return 'View Pending Land Bank Details';
                }else if (this.vlb_type == 'add') {
                    return 'Land Bank - Add Land Details';
                } else {
                    return 'Land Bank Details';
                }
            },
            get modalHeaderBg() {
                if (this.vlb_type == 'reverted') {
                    return 'bg-danger text-white';
                }else if (this.vlb_type == 'approved') {
                    return 'bg-success text-white';
                }else if (this.vlb_type == 'pending') {
                    return 'bg-warning';
                }else if (this.vlb_type == 'add') {
                    return 'bg-primary text-white';
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
            },
            addDetailsModalOpen(dag_no) {

                this.resetData();
                this.dag_no = dag_no;
                var formdata = new FormData();
                formdata.append('dist_code', this.dist_code);
                formdata.append('subdiv_code', this.subdiv_code);
                formdata.append('cir_code', this.cir_code);
                formdata.append('mouza_pargona_code', this.mouza_pargona_code);
                formdata.append('lot_no', this.lot_no);
                formdata.append('vill_townprt_code', this.vill_townprt_code);
                formdata.append('dag_no', this.dag_no);
                var self = this;
                $.ajax({
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    url: window.base_url + "index.php/vlb/VlbController/getLandBankDetails",
                    data: formdata,
                    type: "POST",
                    success: function(data) {
                        if (data.land_bank_det) {
                            $.confirm({
                                title: 'Notice',
                                content: 'Data exists for this daag, If details are re-submited then status will be changed to pending for this daag!',
                                type: 'red',
                                buttons: {
                                    ok: {
                                        text: 'Ok',
                                        btnClass: 'btn-success',
                                        action: function() {
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
                                            $("#addDetailModal").modal("show");
                                        }
                                    },
                                    cancel: {
                                        text: 'Cancel',
                                        btnClass: 'btn-warning',
                                        action: function() {
                                            return;
                                        }
                                    },
                                }
                            });

                        } else {
                            $("#addDetailModal").modal("show");
                        }
                    },
                    error: function(error) {
                        window.showError(error,'red');
                    }
                });
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
            removeEncroacher(index) {
                var self = this;
                var encroacher = this.encroachers[index];
                if (encroacher.id && this.land_bank_details_id) {
                    $.confirm({
                        title: 'Confirm',
                        content: 'This will permanently delete the encroacher.',
                        type: 'warning',
                        buttons: {
                            ok: {
                                text: 'Ok',
                                btnClass: 'btn-success',
                                action: function() {
                                    var formdata = new FormData();
                                    formdata.append('encroacher_id', encroacher.id);
                                    formdata.append('land_bank_details_id', self.land_bank_details_id);
                                    $.ajax({
                                        dataType: "json",
                                        processData: false,
                                        contentType: false,
                                        url: window.base_url + "index.php/vlb/VlbController/deleteEncroacher",
                                        data: formdata,
                                        type: "POST",
                                        success: function(data) {
                                            $.confirm({
                                                title: '',
                                                content: data.msg,
                                                type: 'green',
                                                buttons: {
                                                    ok: {
                                                        text: 'Ok',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.encroachers.splice(index, 1);
                                                        }
                                                    },
                                                }
                                            });

                                        },
                                        error: function(error) {
                                            window.showError(error,'red');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Cancel',
                                btnClass: 'btn-danger',
                                action: function() {

                                }
                            },
                        }
                    });

                } else {
                    this.encroachers.splice(index, 1);
                }
            },
            init() {
                var location_names = '<?= json_encode($location_names) ?>';
                var district = JSON.parse(location_names).district;
                var subdiv = JSON.parse(location_names).subdiv;
                var circle = JSON.parse(location_names).circle;
                var mouza = JSON.parse(location_names).mouza;
                var lot = JSON.parse(location_names).lot;
                var village = JSON.parse(location_names).village;


                this.dist_code = district.dist_code;
                this.subdiv_code = subdiv.subdiv_code;
                this.cir_code = circle.cir_code;
                this.mouza_pargona_code = mouza.mouza_pargona_code;
                this.lot_no = lot.lot_no;
                this.vill_townprt_code = village.vill_townprt_code;


                var genders = '<?php echo ($genders) ?>';
                var castes = '<?php echo ($castes) ?>';
                var LB_ENC_TYPE_OF_LAND_USE = '<?php echo (LB_ENC_TYPE_OF_LAND_USE) ?>';
                var TYPE_OF_ENCROACHER = '<?php echo (TYPE_OF_ENCROACHER) ?>';

                this.genders = JSON.parse(genders);
                this.castes = JSON.parse(castes);
                this.land_used_types = JSON.parse(LB_ENC_TYPE_OF_LAND_USE);
                this.encroacher_types = JSON.parse(TYPE_OF_ENCROACHER);
            },
            submitForm() {
                var self = this;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Submit Land Bank Details',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        confirm: {
                            text: 'Confirm!',
                            btnClass: 'btn-orange',
                            action: function(confirm) {
                                var formdata = new FormData();
                                formdata.append('dist_code', self.dist_code);
                                formdata.append('subdiv_code', self.subdiv_code);
                                formdata.append('cir_code', self.cir_code);
                                formdata.append('mouza_pargona_code', self.mouza_pargona_code);
                                formdata.append('lot_no', self.lot_no);
                                formdata.append('vill_townprt_code', self.vill_townprt_code);
                                formdata.append('dag_no', self.dag_no);
                                formdata.append('nature_of_reservation', self.nature_of_reservation);
                                formdata.append('whether_encroached', self.whether_encroached);
                                formdata.append('en_area_b', self.en_area_b ? self.en_area_b : '0');
                                formdata.append('en_area_k', self.en_area_k ? self.en_area_k : '0');
                                formdata.append('en_area_lc', self.en_area_lc ? self.en_area_lc : '0');
                                formdata.append('en_area_g', self.en_area_g ? self.en_area_g : '0');
                                formdata.append('en_area_kr', self.en_area_kr ? self.en_area_kr : '0');
                                formdata.append('longitude', self.longitude);
                                formdata.append('latitude', self.latitude);
                                formdata.append('encroachers', JSON.stringify(self.encroachers));
                                $.ajax({
                                    dataType: "json",
                                    processData: false,
                                    contentType: false,
                                    url: window.base_url + "index.php/vlb/VlbController/storeLandBankDetails",
                                    data: formdata,
                                    type: "POST",
                                    success: function(data) {
                                        if (data.status == '1') {
                                            $.confirm({
                                                title: '',
                                                content: data.msg,
                                                type: 'green',
                                                buttons: {
                                                    ok: {
                                                        text: 'Ok',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            $("#addDetailModal").modal("hide");
                                                            window.location.reload();
                                                        }
                                                    },
                                                }
                                            });
                                        } else {
                                            window.showError(data.msg,'red');
                                        }

                                    },
                                    error: function(error) {
                                        window.showError(data.msg,'red');
                                    }
                                });
                            }
                        },
                        cancel: function() {
                            window.showError(data.msg,'red');
                        }
                    }
                });
            }
        }
    }
</script>