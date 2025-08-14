<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script src="<?php echo base_url('assets/plugins/dsign/js/dsc-signer.js') ?>?v=1.1" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/dsign/js/dscapi-conf.js') ?>?v=1.1" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/plugins/dsign/css/dsc-signer.css') ?>">
<script src="<?php echo base_url('assets/plugins/pdfjs/pdf.min.js') ?>" type="text/javascript"></script>

<script>
    const ncVillage = JSON.parse('<?= json_encode($nc_village) ?>');
    let COS = '<?= json_encode($all_cos) ?>';
        COS = JSON.parse(COS);
    
    function alpineData() {
        return {
            'dags': [],
            'base_url': "<?= base_url(); ?>",
            'application_no': '',
            'nc_village': '',
            'selected_dag': '',
            'co_name': '',
            'is_loading': false,
            'dc_certification': '',
            'dc_note': '',
            'verified': '',
            'base_sf': '',
            'dscapibaseurl': "https://basundhara.assam.gov.in/dscapi/",
            'map_list': [],
            'uuid': '',
            'sign_type': '',
            'sign_map_id': '',
            'sign_x': 200,
            'sign_y': 200,
            'pdf_h': '',
            'pdf_w': '',
            'sign_map': '',
            'is_name_forwarding': false,
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'vill_townprt_code': '',
            'chitha_dir_path': '',
            'change_vill': '',
            'rand': "?rand=" + Math.floor(Math.random() * 100000),
            'is_signing_map': false,
            'o_pref': '',
            'canvas_height': '',
            'canvas_width': '',
            'is_adhaar_signing': false,
            'cos': COS,
            'old_maps': [],
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
                self.application_no = '<?= $application_no ?>';
                self.nc_village = ncVillage;
                self.dist_code = ncVillage.dist_code;
                self.subdiv_code = ncVillage.subdiv_code;
                self.cir_code = ncVillage.cir_code;
                self.mouza_pargona_code = ncVillage.mouza_pargona_code;
                self.lot_no = ncVillage.lot_no;
                self.vill_townprt_code = ncVillage.vill_townprt_code;
                self.chitha_dir_path = ncVillage.chitha_dir_path;
                self.uuid = ncVillage.uuid;

                // $.ajax({
                //     url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/getShowDagsVillage',
                //     method: "POST",
                //     async: true,
                //     dataType: 'json',
                //     data: {
                //         'application_no': self.application_no
                //     },
                //     success: function(data) {
                //         if (data) {
                //             self.nc_village = data;
                //             self.dist_code = data.dist_code;
                //             self.subdiv_code = data.subdiv_code;
                //             self.cir_code = data.cir_code;
                //             self.mouza_pargona_code = data.mouza_pargona_code;
                //             self.lot_no = data.lot_no;
                //             self.vill_townprt_code = data.vill_townprt_code;
                //             self.chitha_dir_path = data.chitha_dir_path;
                //             self.uuid = data.uuid;
                //         }
                //     }
                // });
                this.verified = '<?= $verified ?>';
                var map_list = '<?= json_encode($map) ?>';
                    this.map_list = JSON.parse(map_list);
                var old_maps = '<?= json_encode($old_maps) ?>';
                    this.old_maps = JSON.parse(old_maps);
                var change_vill = '<?= json_encode($change_vill) ?>';
                var change_vill = change_vill.replace('\n', '\\n');
                var change_vill = JSON.parse(change_vill);
                this.change_vill = change_vill;
                this.getDags();
                $(document).ready(function() {
                    document.getElementById('preview_map_for_sign').addEventListener('click', function(event) {
                        const rect = this.getBoundingClientRect();
                        const x = event.clientX - rect.left;
                        const y = event.clientY - rect.top;
                        self.sign_x = self.pdf_w - x;
                        self.sign_y = self.pdf_h - y;
                        $.confirm({
                            title: 'Select Sign Type',
                            content: '',
                            type: 'orange',
                            typeAnimated: true,
                            theme: 'supervan',
                            buttons: {
                                Adhaar: {
                                    text: 'Adhaar E-Sign',
                                    btnClass: 'btn-green',
                                    action: function() {
                                        self.signMapConfirm('adhaar');
                                        return;
                                    }
                                },
                                Digital: {
                                    text: 'Digital Signature(NICD Signer)',
                                    btnClass: 'btn-blue',
                                    action: function() {
                                        self.signMapConfirm('nicd');
                                        return;
                                    }
                                },
                                Cancel: {
                                    text: 'Cancel',
                                    btnClass: 'btn-orange',
                                    action: function() {
                                        return;
                                    }
                                },
                            }
                        });
                    });
                });
            },
            initDSign(type) {
                $('#verifyPdfBtn').hide();
                var self = this;
                var initConfig = {
                    "preSignCallback": function() {
                        // do something
                        // based on the return sign will be invoked
                        return true;
                    },
                    "postSignCallback": function(alias, sign, key) {
                        // Implement signed pdf upload and pdf Download here
                        var requestData = {
                            action: "DECRYPT",
                            en_sig: sign,
                            ek: key
                        };

                        var sign_key = key;

                        $.ajax({
                                url: dscapibaseurl + "/pdfsignature",
                                type: "post",
                                dataType: "json",
                                contentType: 'application/json',
                                data: JSON.stringify(requestData),
                                async: false
                            })
                            .done(
                                function(data) {
                                    if (data.status_cd == 1) {
                                        var jsonData = JSON.parse(atob(data.data));
                                        if (jsonData.status === "SUCCESS") {
                                            if (type == 'chitha_sign') {
                                                self.storeSignedChitha(jsonData, sign_key);
                                            } else if (type == 'map_sign') {
                                                self.storeSignedMap(jsonData, sign_key);
                                            }
                                        }

                                    } else {
                                        if (data.error.error_cd == 1002) {
                                            alert(data.error.message);
                                            return false;
                                        } else {
                                            alert("Decryption Failed for Signed PDF File");
                                            return false;
                                        }

                                    }
                                }).fail(
                                function(jqXHR, textStatus,
                                    errorThrown) {
                                    alert(textStatus);
                                });
                    },
                    signType: 'pdf',
                    mode: 'nostampingv2'
                    //"certificateSno" : 13705892,
                };
                dscSigner.configure(initConfig);
            },
            signChitha() {
                let self = this;
                this.sign_x = 200;
                this.sign_y = 200;
                this.sign_type = 'chitha';
                this.initDSign('chitha_sign');
                $('#closeBtn').prop('disabled', true);
                $('#signPdf').prop('disabled', true);
                $('#loader2').removeClass('invisible');
                var dc_certification = $('#dc_certification').val();

                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/getBaseSFile',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'pdf_url': this.chitha_dir_path,
                        'dist_code': this.dist_code,
                        'subdiv_code': this.subdiv_code,
                        'cir_code': this.cir_code,
                        'mouza_pargona_code': this.mouza_pargona_code,
                        'lot_no': this.lot_no,
                        'vill_townprt_code': this.vill_townprt_code,
                        'application_no': this.application_no,
                        'dc_certification': dc_certification,
                    },
                    success: function(data) {
                        $('#closeBtn').prop('disabled', false);
                        $('#signPdf').prop('disabled', false);
                        $('#loader2').addClass('invisible');
                        if (data != null || data != '') {
                            dscSigner.sign(data);
                            // let jsonData = {sig: data};
                            // self.storeSignedChitha(jsonData, 'sign_key');
                        }
                    }
                });
            },
            storeSignedChitha(jsonData, sign_key) {
                var self = this;
                //get pdf data
                var pdfData = jsonData.sig;

                var pdf_url = self.chitha_dir_path;

                $('#loader2').removeClass('invisible');

                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/storeSignedPdf',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'pdf_url': pdf_url,
                        'pdfbase': pdfData,
                        'application_no': self.application_no,
                        'sign_key': sign_key
                    },
                    success: function(data) {
                        $('#loader2').addClass('invisible');
                        if (data.status == '1' && data.update == '1') {
                            $.confirm({
                                title: 'Success!!',
                                content: data.msg,
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {

                                        }
                                    },
                                }
                            });
                            $('#signPdf').hide();

                            self.nc_village = data.nc_village;
                        } else {
                            $.confirm({
                                title: 'Failed!!',
                                content: data.msg,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {

                                        }
                                    },
                                }
                            });
                        }
                    }
                });
            },
            signMap(map) {
                $("#modal_show_map_list").modal('hide');
                $("#modal_preview_map_for_sign").modal('show');
                $('#preview_map_for_sign').html('');
                this.loadPreviewForSign('<?= base_url() ?>index.php/nc_village_v2/NcVillageDcController/getMapForSign?id=' + map.id);
                this.sign_type = 'map';
                this.sign_map_id = map.id;
                this.sign_map = map;
                this.o_pref = map.o_pref;
            },
            signMapConfirm(type) {
                this.is_signing_map = true;
                if (type == 'nicd') {
                    var self = this;
                    this.initDSign('map_sign');
                    $('#closeBtn').prop('disabled', true);
                    $('.signMap').prop('disabled', true);
                    $('#loader2').removeClass('invisible');

                    $.ajax({
                        url: '<?= base_url() ?>index.php/nc_village_v2/NcVillageDcController/viewUploadedMapBase',
                        method: "POST",
                        async: true,
                        data: {
                            'map_id': self.sign_map_id,
                        },
                        success: function(data) {
                            $('#closeBtn').prop('disabled', false);
                            $('.signMap').prop('disabled', false);
                            $('#loader2').addClass('invisible');
                            if (data != null || data != '') {
                                dscSigner.sign(data);
                                // let jsonData = {sig: data};
                                // self.storeSignedMap(jsonData, 'sign_key');
                            } else {
                                alert('Something went wrong. Please try again later.');
                            }
                        },
                        error: function(request, error) {
                            console.log(error);
                            this.is_signing_map = false;
                        }
                    });
                    this.is_signing_map = false;
                } else if (type == 'adhaar') {
                    this.initAdhaarSign();
                    this.is_signing_map = false;
                }

            },
            initAdhaarSign() {
                var self = this;
                self.txn_id = '';
                var data = {};
                data[csrfName] = csrfHash;
                data.type = 'nc_map';
                data.nc_map_id = self.sign_map_id;
                data.sign_x = self.sign_x;
                data.sign_y = self.sign_y;
                data.sign_name='nc_map_sign';
                data.nc_village_id = self.nc_village.id;
                if(self.o_pref == '' || self.o_pref == undefined) {
                    alert('Please select the orientation preference');
                    return;
                }

                let layout_type = 'P'; // Portrait
                if(self.canvas_width >= self.canvas_height){
                    layout_type = 'L'; // Landscape
                }
                if(self.o_pref != layout_type){
                    // let confirmMsg = 'Are you sure, you want to proceed with your selected orientation preference. System is recognizing the map as ' + (layout_type == 'P' ? 'Portrait.' : 'Landscape.');
                    let confirmMsg = 'Please choose the correct orientation preference';
                    alert(confirmMsg);
                    return false;
                    // if(!confirm(confirmMsg)){
                    // }
                }else{
                    let confirmMsg = 'Are you sure, you want to sign the map as ' + (layout_type == 'P' ? 'Portrait' : 'Landscape') + ' orientation preference.';
                    if(!confirm(confirmMsg)){
                        return false;
                    }
                }
                
                data.o_pref = self.o_pref;
                self.is_adhaar_signing = true;
                $('#loader2').removeClass('invisible');
                // Disable click on preview_map_for_sign
                $('#preview_map_for_sign').css('pointer-events', 'none');
                $.ajax({
                    url: '<?= base_url(); ?>index.php/adhaar-sign-process-v2',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.data.txn_id) {
                            $("#eSignRequest").val(data.data.esign_request);
                            $("#aspTxnID").val(data.data.txn_id);
                            $("#adhaar_sign_form").submit();
                        } else {
                            alert('error');
                        }
                    },
                    error: function(request, error) {
                        alert('Something went wrong. Please try again later.');
                        self.is_adhaar_signing = false;
                        $('#loader2').addClass('invisible');
                        // Enable click on preview_map_for_sign
                        $('#preview_map_for_sign').css('pointer-events', 'auto');
                    }
                });
            },
            loadPreviewForSign(url) {
                var self = this;
                pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                    // Get the first page.
                    pdfDoc.getPage(1).then(function(page) {
                        var scale = 1;
                        var viewport = page.getViewport({
                            scale: scale
                        });

                        // Prepare canvas using PDF page dimensions.
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        self.canvas_height = viewport.height;
                        self.canvas_width = viewport.width;

                        self.pdf_h = viewport.height;
                        self.pdf_w = viewport.width;
                        // Render PDF page into canvas context.
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        var renderTask = page.render(renderContext);
                        renderTask.promise.then(function() {
                            console.log("Page rendered");
                        });

                        // Append the canvas to the div container.
                        document.getElementById('preview_map_for_sign').appendChild(canvas);
                    });
                }).catch(function(error) {
                    console.log(error);
                });
            },
            storeSignedMap(jsonData, sign_key) {
                var self = this;
                //get pdf data
                var pdfData = jsonData.sig;

                $('#loader2').removeClass('invisible');

                $.ajax({

                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/storeSignedMap',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'pdfbase': pdfData,
                        'id': self.sign_map_id,
                        'sign_key': sign_key,
                        'application_no': self.application_no
                    },
                    success: function(data) {
                        $('#loader2').addClass('invisible');
                        if (data.status == '1' && data.update == '1') {
                            $.confirm({
                                title: 'Success!!',
                                content: data.msg,
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {

                                        }
                                    },
                                }
                            });

                            self.map_list = data.map_list;
                        } else {
                            $.confirm({
                                title: 'Failed!!',
                                content: data.msg,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    Ok: {
                                        text: 'OK',
                                        btnClass: 'btn-info',
                                        action: function() {

                                        }
                                    },
                                }
                            });
                        }
                        $("#modal_show_map_list").modal('show');
                        $("#modal_preview_map_for_sign").modal('hide');
                    }
                });
            },
            openModal(dag) {
                this.selected_dag = dag;
            },
            closeModal() {
                $('#close_modal').trigger('click');
            },
            get dags_verified() {
                var total = 0;
                this.dags.forEach(element => {
                    if (element.dc_verified == 'Y') {
                        total++;
                    }
                });
                return total;
            },
            getDags() {
                var self = this;
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/getDags',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'application_no': self.application_no
                    },
                    success: function(data) {
                        self.dags = data.dags;
                        self.co_name = data.co_name;
                        self.is_loading = false;
                        if (data.dags) {
                            self.dc_certification = 'This chitha and map has been prepared under Govt. of Assam vide notification No.ECF. 206672/2022/26 Dt. 31-05-2022 and corrected upto 2022-23.';
                        }
                    }
                });
                self.is_loading = false;
            },
            verifyDag() {
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Verify the Draft chitha and Draft Map of this Dag',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageDcController/verifyDag',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no,
                                        'dag_no': self.selected_dag.dag_no,
                                        'patta_no': self.selected_dag.patta_no,
                                        'patta_type_code': self.selected_dag.patta_type_code,
                                    },
                                    success: function(data) {
                                        if (data.submitted == 'Y') {
                                            self.selected_dag.dc_verified = 'Y';
                                            $.confirm({
                                                title: 'Success',
                                                content: data.msg,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.getDags();
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                        } else {
                                            $.confirm({
                                                title: 'Error Occurred!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                        }

                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
                this.is_loading = false;
            },
            certifyVillage() {
                var is_map_signed = true;
                this.map_list.forEach(element => {
                    if (element.dc_signed != 'Y') {
                        is_map_signed = false;
                    }
                });
                if (!is_map_signed) {
                    alert('Please Sign the Maps to Certify');
                    return;
                }
                if (!this.dc_note) {
                    alert('Please enter the DC Note');
                    return;
                }
                if (!this.dc_certification) {
                    alert('Please enter the DC Certification');
                    return;
                }
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Certify the Draft chitha and Draft Map of this Village',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/certifyVillage',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no,
                                        'dc_certification': self.dc_certification,
                                        'remark': self.dc_note,
                                        'change_vill_remark': $('#templateTA').val(),
                                        'uuid': self.uuid,
                                    },
                                    success: function(data) {
                                        if (data.st == '0' || data.submitted != 'Y') {
                                            $.confirm({
                                                title: 'Error Occurred!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                            return;
                                        }
                                        if (data.submitted == 'Y') {
                                            $.confirm({
                                                title: 'Success',
                                                content: 'Successfully Submitted',
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                            self.nc_village = data.nc_village;
                                                            window.location.href = '<?= base_url() ?>index.php/nc_village_v2/NcVillageDcController/dashboard';
                                                        }
                                                    },
                                                }
                                            });
                                        }
                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
            },
            revertVillage() {
                var self = this;
                this.is_loading = true;
                let optionsHtml = '<option value="">Please Select CO</option>';
                self.cos.forEach(function(co) {
                    optionsHtml += '<option value="' + co.user_code + '">' + co.name + '</option>';
                });
                let contentHtml =
                    '<form id="remarkForm">' +
                        '<h3>Please confirm to Revert Back this Village to CO</h3>' + 
                        '<div class="row">' +
                            '<div class="col-lg-12 mb-2">' +
                                '<label class="form-check-label" for="co_user_code">CO</label>' +
                                '<select id="co_user_code" name="co_user_code" class="form-control">' +
                                    optionsHtml+
                                '</select>' +
                            '</div>'  +
                            '<div class="col-lg-12">' +
                                '<label class="form-check-label" for="co_remark">Note</label>' +
                                '<textarea id="co_remark" name="remark" placeholder="Note" class="form-control" required ></textarea>' +
                            '</div>' +
                        '</div>' +
                    '</form>';
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: contentHtml,
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-danger',
                            action: function() {
                                let co_user_code = $('#co_user_code').val().trim();
                                let remark = $('#co_remark').val().trim();
                                if (co_user_code == '') {
                                    alert('Please select LRS.');
                                    self.is_loading = false;
                                    return false;
                                }
                                if (remark == '') {
                                    alert('Please enter note.');
                                    self.is_loading = false;
                                    return false;
                                }

                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageDcController/revertVillage',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no,
                                        'remark': remark,
                                        'user': co_user_code,
                                    },
                                    success: function(data) {
                                        if (data.st == '0' || data.submitted != 'Y') {
                                            $.confirm({
                                                title: 'Error Occurred!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                            return;
                                        }
                                        if (data.submitted == 'Y') {
                                            $.confirm({
                                                title: 'Success',
                                                content: data.msg,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                            self.nc_village = data.nc_village;
                                                        }
                                                    },
                                                }
                                            });
                                        }
                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
            },
            dc_verified() {
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Verify / Sign the Draft chitha of all Dags',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village/NcVillageDcController/verifyDraftChitha',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no
                                    },
                                    success: function(data) {
                                        if (data.error === false) {
                                            self.verified = 'Y';
                                            $.confirm({
                                                title: 'Success',
                                                content: 'Draft Chitha Verified Successfully.',
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {}
                                                    },
                                                }
                                            });

                                        } else {
                                            $.confirm({
                                                title: 'Error',
                                                content: data.msg,
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

                                        $('#modal_vill_dag_chitha').modal('hide');
                                        self.getDags()
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
                this.is_loading = false;
            },
            changeVillageName() {
                var self = this;
                self.is_name_forwarding = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Submit Name Change',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                let jds_user = $('#jds_id').val();
                                if(jds_user == ''){
                                    alert('Please select JDS');
                                    return false;
                                }
                                $.ajax({
                                    dataType: 'json',
                                    url: self.base_url + "index.php/nc_village_v2/change_village/ChangeVillageDcController/changeVillageSubmit",
                                    data: {
                                        'dist_code': self.dist_code,
                                        'vill_uuid': self.uuid,
                                        'new_vill_name': self.change_vill.new_vill_name,
                                        'new_vill_name_eng': self.change_vill.new_vill_name_eng,
                                        'application_no': self.application_no,
                                        'change_vill_remark': 'The survey of village ' + self.change_vill.old_vill_name + ' is completed and its new updated name will be ' + self.change_vill.new_vill_name + ' (' + self.change_vill.new_vill_name_eng + ')',
                                        'user': jds_user,
                                    },
                                    type: "POST",
                                    success: function(data) {
                                        $("#vill_modal").modal('hide');
                                        self.is_name_forwarding = false;
                                        if (data.st == 1) {
                                            swal("", data.msg, "success")
                                                .then((value) => {
                                                    window.location.reload();
                                                });
                                        } else {
                                            swal("", data.msg, "info");

                                        }
                                    },
                                    error: function(err) {
                                        $("#vill_modal").modal('hide');
                                        swal("", 'Server Error. Please try again later.', "info");
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_name_forwarding = false;
                            }
                        },
                    }
                });
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <input type="hidden" value="<?= $nc_village->application_no ?>" id="application_no">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">

        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select id="dist_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Sub-Division</label>
                    <select id="subdiv_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Circle</label>
                    <select id="cir_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Mouza</label>
                    <select id="mouza_pargona_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['mouza']['mouza_pargona_code'] ?>"><?= $locations['mouza']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Lot</label>
                    <select id="lot_no" class="form-control form-control-sm">
                        <option selected value="<?= $locations['lot']['lot_no'] ?>"><?= $locations['lot']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Village</label>
                    <select id="vill_townprt_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['village']['vill_townprt_code'] ?>"><?= $locations['village']['loc_name'] ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url() ?>index.php/nc_village_v2/NcVillageCommonController/viewBhunaksaMap" method="post">
                        <span style="font-size: 20px;"> DAGS
                            <span id="application_no" class="bg-gradient-danger text-white"></span>
                            (Total Dags : <b x-text="dags.length"></b> , Verified : <b class="text-success" x-text="dags_verified"></b>)
                        </span>
                        <span>
                            <button type="button" class="btn btn-primary" onclick="villDagsChithaButton()">
                                <i class="fa fa-file"></i> View Chitha</button>
                        </span>
                        <?php if (sizeof($map) > 0) : ?>
                            <button type="button" class="btn btn-info py-2" style="color: white" onclick="viewMaps()">
                                <i class='fa fa-eye'></i> View Map
                            </button>
                        <?php endif; ?>
                        <span>
                            <input type="hidden" name="location" value="<?= $locations['dist']['dist_code'] . '_' .
                                                                            $locations['subdiv']['subdiv_code'] . '_' .
                                                                            $locations['circle']['cir_code'] . '_' . $locations['mouza']['mouza_pargona_code'] . '_' .
                                                                            $locations['lot']['lot_no'] . '_' . $locations['village']['vill_townprt_code'] ?>">
                            <input type="hidden" name="vill_name" value="<?= $locations['village']['loc_name'] ?>">
                            <input type="hidden" name="dags" :value="nc_village.bhunaksa_total_dag">
                            <input type="hidden" name="area" :value="nc_village.bhunaksa_total_area_skm">
                            <button type="submit" class="btn btn-secondary py-2" style="color: white;">
                                <i class='fa fa-eye'></i> View Bhunaksha Map
                            </button>
                        </span>
                        <?php if ($approve_proposal) : ?>
                            <a class="btn btn-success py-2" href="<?= base_url() . NC_VILLAGE_PROPOSAL_DIR . "co/" . $approve_proposal->proposal_no ?>.pdf" target="_blank"><i class='fa fa-eye'></i> View Proposal</i></a>
                        <?php endif; ?>
                    </form>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-striped table-hover table-sm table-bordered">
                            <thead style="position: sticky;top:0;" class="bg-warning">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Dag</th>
                                    <th>Land Class</th>
                                    <th>Occupiers</th>
                                    <th>Area(B-K-L)</th>
                                    <th>Certified By CO <br>(Draft Chitha & Map)</th>
                                    <th>Certified By CO</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(dag,index) in dags">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <td x-text="dag.dag_no"></td>
                                        <td x-text="dag.full_land_type_name"></td>
                                        <td>
                                            <template x-for="(occupier,index) in dag.occupiers" :key="index">
                                                <p class="small" x-text="occupier.encro_name"></p>
                                                <p class="small" x-text="occupier.encro_name"></p>
                                            </template>
                                        </td>
                                        <td x-text="dag.dag_area_b+'-'+dag.dag_area_k+'-'+dag.dag_area_lc"></td>
                                        <td x-text="dag.co_verified == 'Y' ? 'Yes' : 'No'" :class="dag.co_verified == 'Y' ? 'text-success' : 'text-danger'">
                                        </td>
                                        <td>
                                            <span x-text="co_name"></span>
                                        </td>
                                        <td>
                                            <span class="text-success" x-show="dag.dc_verified == 'Y'"><b>Verified</b></span>
                                            <button x-show="dag.dc_verified != 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-success" type="button">View</button>
                                            <button x-show="dag.dc_verified == 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-info" type="button">View Dag</button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="dags.length == 0">
                                    <td colspan="4" class="text-center">
                                        <span x-show="!is_loading">No dags Found</span>
                                        <!-- <div class="d-flex justify-content-center" x-show="is_loading">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div> -->
                                        <p class="text-danger">
                                        <h5>Data will not be available as this is a reverted case. Chitha data will be populated from Bhunaksha once LRA process the case.</h5>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <th colspan="2" style="background-color: #136a6f; color: #fff">
                                Village Details
                            </th>
                            <?php
                                if(count($merge_village_requests)):
                            ?>
                            <?php
                                else:
                            ?>
                                    <!-- <th colspan="2" style="background-color: #136a6f; color: #fff">
                                        Chitha Details
                                    </th>
                                    <th colspan="2" style="background-color: #136a6f; color: #fff">
                                        Bhunaksha Details
                                    </th> -->
                            <?php
                                endif;
                            ?>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="25%">Total Dags</td>
                                <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_dag"></td>
                            </tr>
                            <tr>
                                <td width="25%"> Area (sq km)</td>
                                <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_area_skm"></td>
                            </tr>
                            <tr>
                                <td class="text-danger font-weight-bold" colspan="2">
                                    <span x-show="nc_village.bhunaksa_total_area_skm < 2">
                                        The area is less than 2 (Square kilometre)
                                    </span>
                                </td>
                            </tr>
                            <?php
                                if(count($merge_village_requests)):
                            ?>        
                            <?php
                                else:
                            ?>
                                    
                                    <!-- <tr>
                                        <td width="25%">Total Dags</td>
                                        <td width="25%" style="color:red" x-text="nc_village.chitha_total_dag"></td>
                                        <td width="25%">Total Dags</td>
                                        <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_dag"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Chitha Area (sq km)</td>
                                        <td width="25%" style="color:red" x-text="nc_village.chitha_total_area_skm"></td>
                                        <td width="25%">Bhunaksha Area (sq km)</td>
                                        <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_area_skm"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger font-weight-bold" colspan="2">
                                            <span x-show="nc_village.chitha_total_area_skm < 2">
                                                The Chitha area is less than 2 (Square kilometre)
                                            </span>
                                        </td>
                                        <td class="text-danger font-weight-bold" colspan="2">
                                            <span x-show="nc_village.bhunaksa_total_area_skm < 2">
                                                The Bhunaksha area is less than 2 (Square kilometre)
                                            </span>
                                        </td>
                                    </tr> -->
                            <?php
                                endif;
                            ?>
                        </tbody>
                    </table>

                    <?php
                        if(count($merge_village_requests)):
                    ?>
                        <div id="merge_village_data">
                            <h4>Village list to be merged</h4>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr style="background-color: #136a6f; color: #fff">
                                        <th>Sl No</th>
                                        <th>Village Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($merge_village_requests as $key => $merge_village_request):
                                ?>
                                        <tr>
                                            <td><?= ($key+1) ?></td>
                                            <td><?= $merge_village_request['vill_loc']['village']['loc_name'] ?></td>
                                        </tr>
                                <?php
                                    endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        endif;
                    ?>

                    <div class=" border-top border-dark mt-3 pt-2 form-group">
                        <label for="" class="form-label">Edited Village Name <span class="text-red">*</span></label>
                        <div class="form-group">
                            <div class="form-group mt-2 row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="sel1">Existing Village Name:</label>
                                        <input name="village_name" type="text" class="form-control" value="<?php echo ($locations["village"]["loc_name"]); ?>" id="village_name" disabled required>
                                    </div>
                                </div>
                                <?php if (($change_vill)) : ?>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sel1">New Village Name:</label>
                                            <input value="<?php echo $change_vill->new_vill_name ?>" name="new_village_name" type="text" class="form-control" id="new_village_name" disabled required>
                                        </div>
                                    </div>

                                <?php else : ?>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sel1">New Village Name:</label>
                                            <input x-model="change_village_name" value="" name="new_village_name" type="text" class="form-control" id="new_village_name" disabled required>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div x-show="nc_village.status == 'G' ||nc_village.status == 'B' " class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button style="margin-top: 2.2rem ;" class="btn change btn-success" type="button"><i class="fa fa-check"></i> Change Village Name</button>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12" x-show="nc_village.dc_verified == 'Y'">
                                    <textarea disabled rows="3" class="form-control">The survey of village "<?php echo ($change_vill->old_vill_name) ?>" is done and its new updated name will be "<?php echo ($change_vill->new_vill_name . ' (' . $change_vill->new_vill_name_eng . ')') ?>" as per government OM No.ECF.213444/2022/ Dated Dispur, the 03-01-2024.</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="pb-3" x-show="nc_village.dc_verified != 'Y' && (nc_village.status == 'G' || nc_village.status == 'f')">
                            <div class="form-group">
                                <label class="form-label">DC Certification Remarks <span class="text-red">*</span></label>
                                <textarea x-model="dc_certification" id="dc_certification" placeholder="DC Certification" rows="2" class="form-control" readonly></textarea>
                                <label for="" class="form-label">DC Note <span class="text-red">*</span></label>
                                <textarea x-model="dc_note" placeholder="DC Note" rows="2" class="form-control"></textarea>
                            </div>
                            <button x-on:click="certifyVillage" class="btn btn-success" type="button"><i class="fa fa-check"></i> Certify Draft Chitha and Map</button>
                            <button x-on:click="revertVillage" class="btn btn-danger" type="button"><i class="fa fa-backward"></i> Revert Back To CO</button>
                        </div>
                        <div class="pb-3" x-show="nc_village.dc_verified != 'Y' && nc_village.status == 'B'">
                            <div class="form-group">
                                <label class="form-label">DC Certification Remarks <span class="text-red">*</span></label>
                                <textarea x-model="dc_certification" id="dc_certification" placeholder="DC Certification" rows="2" class="form-control" readonly></textarea>
                                <label for="" class="form-label">DC Note <span class="text-red">*</span></label>
                                <textarea x-model="dc_note" placeholder="DC Note" rows="2" class="form-control"></textarea>
                            </div>
                            <button x-on:click="certifyVillage" class="btn btn-success" type="button"><i class="fa fa-check"></i> Certify Draft Chitha and Map</button>
                            <button x-on:click="revertVillage" class="btn btn-danger" type="button"><i class="fa fa-backward"></i> Revert Back to CO</button>
                        </div>
                        <div x-show="nc_village.dc_verified == 'Y'">
                            <h5 class="text-success">The Draft Chitha and Map of this village has already been certified.</h5>
                        </div>
                        <div x-show="nc_village.status == 'J'">
                            <h5 class="text-danger">This village has been reverted back to CO</h5>
                        </div>
                        <div x-show="nc_village.status == 'M'">
                            <h5 class="text-danger">This village has been forwarded to JDS for Village Name Change on Map</h5>
                        </div>
                        <?php if (sizeof($map) == 0) : ?>
                            <div class="border-top border-dark py-2">
                                <h5 class="text-danger">The Assistant Director of Surveys (ADS) has not yet uploaded the map.</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="modal" id="chitha_and_map" tabindex="-1" aria-labelledby="chitha_and_map" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <button type="button" class="close" data-dismiss="modal" x-on:click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <h5 class="reza-title">
                                <i class="fa fa-map-marker"></i> Location Information
                            </h5>
                            <div class="tableCard ">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>District Name:</th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="dist_name" class="form-control input-sm" value='<?= $this->utilityclass->getDistrictName($locations['dist']['dist_code']) ?>' readonly>
                                            </strong>
                                        </td>
                                        <th>Subdivision Name:</th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="subdiv_name" class="form-control input-sm" value='<?= $this->utilityclass->getSubDivName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code']) ?>' readonly>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Circle Name: </th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="circle_name" value='<?= $this->utilityclass->getCircleName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code']) ?>' class="form-control input-sm" readonly>
                                            </strong>
                                        </td>
                                        <th>Mouza Name: </th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="mouza_name" class="form-control input-sm" value='<?= $this->utilityclass->getMouzaName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code']) ?>' readonly>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lot Name: </th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="lot_name" value='<?= $this->utilityclass->getLotLocationName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code'], $locations['lot']['lot_no']) ?>' class="form-control input-sm" readonly>
                                            </strong>
                                        </td>
                                        <th>Village Name: </th>
                                        <td class="text-warning">
                                            <strong class="alert-warning">
                                                <input type="text" name="village_name" value='<?= $this->utilityclass->getVillageName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code'], $locations['lot']['lot_no'], $locations['village']['vill_townprt_code']) ?>' class="form-control input-sm" readonly>
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <h5 class="reza-title">
                                <i class="fa fa-file"></i> View Chitha
                                <a x-show="selected_dag" x-bind:href="'<?= base_url() ?>index.php/Chithacontrol/generateDagChitha?case_no=4&dag='+selected_dag.dag_no+'&dist='+nc_village.dist_code+'&sub_div='+nc_village.subdiv_code+'&cir='+nc_village.cir_code+'&m='+nc_village.mouza_pargona_code+'&l='+nc_village.lot_no+'&v='+nc_village.vill_townprt_code+'&p='+selected_dag.patta_type_code" target="_blank" class="btn btn-sm btn-info">View Chitha</a>
                            </h5>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="close_modal" class="btn btn-danger" data-dismiss="modal" x-on:click="closeModal"> <i class='fa fa-close'></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="vill_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Submit Changed Village Name</h5>
                        </div>
                        <div class="modal-body">
                            <div class="card rounded-0">
                                <div class="card-body border border-info p-3">
                                    <form action="">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">District:</label>
                                                    <h6 id="d"><?php echo ($locations["dist"]["loc_name"]); ?></h6>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Sub-Div:</label>
                                                    <h6 id="s"><?php echo ($locations["subdiv"]["loc_name"]); ?></h6>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Circle:</label>
                                                    <h6 id="cir"><?php echo ($locations["circle"]["loc_name"]); ?></h6>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Mouza/Porgona:</label>
                                                    <h6 id="mza"><?php echo ($locations["mouza"]["loc_name"]); ?></h6>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Lot:</label>
                                                    <h6 id="lot"><?php echo ($locations["lot"]["loc_name"]); ?></h6>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Village:</label>
                                                    <h6 id="vill"><?php echo ($locations["village"]["loc_name"]); ?></h6>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" x-show="change_vill">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name:</label>
                                                    <input x-model="change_vill.new_vill_name" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name (Eng):</label>
                                                    <input x-model="change_vill.new_vill_name_eng" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <textarea disabled rows="3" class="form-control" aria-label="With textarea" x-text="'The survey of village ' +change_vill.old_vill_name + ' is completed and its new updated name will be ' + change_vill.new_vill_name+ ' ('+change_vill.new_vill_name_eng+')'"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="jds_id">Select JDS</label>
                                                    <select name="" id="jds_id" class="form-control">
                                                        <option value="">Select JDS</option>
                                                        <?php 
                                                            if(count($all_jds)): 
                                                                foreach($all_jds as $jds):
                                                        ?>
                                                                    <option value="<?= $jds['unique_user_id'] ?>"><?= $jds['name'] ?></option>
                                                        <?php 
                                                                endforeach;
                                                            endif; 
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" x-show="!is_name_forwarding" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button type='button' x-show="!is_name_forwarding" class="btn btn-info" x-on:click='changeVillageName()' value="Submit">
                                <i class="fa fa-check-square-o" aria-hidden="true"></i> Forward To JDS
                            </button>
                            <div x-show="is_name_forwarding" class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modal_vill_dag_chitha" tabindex="-1" aria-labelledby="modal_vill_dag_chitha" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="width: 100% !important;">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="modal-title"> <i class="fa fa-file"></i> Village Draft Chitha</h5>
                        </div>
                        <div class="modal-body">
                            <div><iframe id="chitha_pdf_view" width='100%' height='500px;' src='<?= base_url() . $nc_village->chitha_dir_path . "?rand=" . rand(10, 10000) ?>'></iframe></div>
                            <template x-if="sign_type =='chitha'">
                                <div id="panel" class="bg-white"></div>
                            </template>
                            <label class="form-label">CO Certification Remark <span class="text-red">*</span></label>
                            <textarea placeholder="CO Certification" rows="2" class="form-control" readonly><?= $nc_village->co_certification; ?></textarea>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" id="signingReason" name="signingReason" maxlength="20" />
                            <input type="hidden" id="signingLocation" name="signingLocation" maxlength="20" />
                            <input type="hidden" id="stampingX" name="stampingX" maxlength="20" x-model="sign_x" />
                            <input type="hidden" id="stampingY" name="stampingY" maxlength="20" x-model="sign_y" />
                            <input type="hidden" id="tsaURL" name="tsaURL" value="" maxlength="100" style="width: 400px;" />
                            <input type="hidden" id="timeServerURL" name="timeServerURL" value="https://basundhara.assam.gov.in/dscapi/getServerTime" maxlength="100" style="width: 400px;" />
                            <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal"><i class='fa fa-close'></i> Close
                            </button>
                            <button type="button" x-show="nc_village.dc_chitha_sign != 'Y' && (nc_village.status == 'G' || nc_village.status == 'f')" x-on:click="signChitha" class="btn btn-primary"><i class="fa fa-pencil"></i> Sign Draft Chitha</button>
                            <a x-show="nc_village.dc_chitha_sign == 'Y'" class="btn btn-info" :href="base_url + nc_village.chitha_dir_path + rand" target="_blank"><i class='fa fa-download'></i> Download Signed PDF File</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="modal" id="modal_show_map_list_old" tabindex="-1" aria-labelledby="modal_show_map_list_old" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="width: 100% !important;">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="modal-title"> <i class="fa fa-file"></i> View Maps</h5>
                        </div>
                        <div class="modal-body">

                            <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                                <table class="table table-striped table-hover table-sm table-bordered">
                                    <thead style="position: sticky;top:0;" class="bg-warning">
                                        <tr>
                                            <th>Sl.No.</th>
                                            <th>View Map</th>
                                            <th>Sign Map</th>
                                            <th>Orientation Preference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(list,index) in map_list">
                                            <tr>
                                                <td x-text="index + 1"></td>
                                                <td>
                                                    <a x-bind:href="'<?= base_url() ?>index.php/nc_village_v2/NcVillageDcController/viewUploadedMap?id=' + list.id" class="btn btn-info py-2" style="color: white" target="_blank">
                                                        View <span x-show="list.dc_signed == 'Y'">Signed</span> Map
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" x-show="list.dc_signed != 'Y'" x-on:click="signMap(list)" class="btn btn-primary signMap"><i class="fa fa-pencil"></i> Sign Map</button>
                                                    <span class="text-success" x-show="list.dc_signed == 'Y'">Map Signed</span>
                                                </td>
                                                <td>
                                                    <select class="form-control abc" x-model="list.o_pref" x-show="list.dc_signed != 'Y'">
                                                        <option value="">--Select Orientation Preference--</option>
                                                        <option value="P">Portrait</option>
                                                        <option value="L">Landscape</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="map_list.length == 0">
                                            <td colspan="3" class="text-center">
                                                <span x-show="!is_loading">No Map Found</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">
                                <i class='fa fa-close'></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="modal" id="modal_show_map_list" tabindex="-1" aria-labelledby="modal_show_map_list" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="width: 100% !important;">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="modal-title">
                                <i class="fa fa-file"></i> View Maps
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Current Maps Section -->
                                <div class="col-lg-12 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <b>Current Maps</b>
                                        </div>
                                        <div class="card-body p-2">
                                            <table class="table table-striped table-hover table-sm table-bordered mb-0">
                                                <thead class="bg-warning" style="position: sticky;top:0;">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Map Name</th>
                                                        <th>View Map</th>
                                                        <th>Sign Map</th>
                                                        <th>Orientation Preference</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(list,index) in map_list" :key="list.id">
                                                        <tr>
                                                            <td x-text="index + 1"></td>
                                                            <td>
                                                                <span x-text="list.map_name ? list.map_name : 'Map ' + (index + 1)"></span>
                                                            </td>
                                                            <td>
                                                                <a :href="'<?= base_url() ?>index.php/nc_village/NcVillageDcController/viewUploadedMap?id=' + list.id"
                                                                   class="btn btn-info btn-sm" target="_blank">
                                                                    <i class="fa fa-eye"></i> View <span x-show="list.dc_signed == 'Y'">Signed</span> Map
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <button type="button" x-show="list.dc_signed != 'Y'" x-on:click="signMap(list)" class="btn btn-primary btn-sm signMap">
                                                                    <i class="fa fa-pencil"></i> Sign Map
                                                                </button>
                                                                <span class="text-success" x-show="list.dc_signed == 'Y'">
                                                                    <i class="fa fa-check"></i> Map Signed
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm" x-model="list.o_pref" x-show="list.dc_signed != 'Y'">
                                                                    <option value="">--Select Orientation--</option>
                                                                    <option value="P">Portrait</option>
                                                                    <option value="L">Landscape</option>
                                                                </select>
                                                                <span x-show="list.dc_signed == 'Y'" class="badge badge-success" x-text="list.o_pref == 'P' ? 'Portrait' : (list.o_pref == 'L' ? 'Landscape' : '')"></span>
                                                            </td>
                                                            <td>
                                                                <span x-show="list.dc_signed == 'Y'" class="badge badge-success">Signed</span>
                                                                <span x-show="list.dc_signed != 'Y'" class="badge badge-warning">Pending</span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <tr x-show="map_list.length == 0">
                                                        <td colspan="6" class="text-center text-danger">
                                                            <span x-show="!is_loading">No Current Map Found</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Old Maps Section -->
                                <div class="col-lg-12">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-secondary text-white">
                                            <b>Old Maps (Previous Uploads)</b>
                                        </div>
                                        <div class="card-body p-2">
                                            <table class="table table-striped table-hover table-sm table-bordered mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Map Name</th>
                                                        <th>View Map</th>
                                                        <th>Uploaded On</th>
                                                        <th>Dc Signed</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(omap, idx) in old_maps" :key="omap.id">
                                                        <tr>
                                                            <td x-text="idx + 1"></td>
                                                            <td>
                                                                <span x-text="omap.map_name ? omap.map_name : 'Old Map ' + (idx + 1)"></span>
                                                            </td>
                                                            <td>
                                                                <a :href="'<?= base_url() ?>index.php/nc_village/NcVillageDcController/viewUploadedMap?id=' + omap.id"
                                                                   class="btn btn-outline-info btn-sm" target="_blank">
                                                                    <i class="fa fa-eye"></i> View Map
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span x-text="omap.created_at ? omap.created_at : '-'"></span>
                                                            </td>
                                                            <td>
                                                                <span x-show="omap.dc_signed == 'Y'" class="badge badge-success">Signed</span>
                                                                <span x-show="omap.dc_signed != 'Y'" class="badge badge-warning">No</span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <tr x-show="old_maps.length == 0">
                                                        <td colspan="5" class="text-center text-muted">
                                                            <span>No Old Maps Found</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="small text-muted mt-2">
                                                <i class="fa fa-info-circle"></i> Old maps are previous uploads for this village, kept for reference.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">
                                <i class='fa fa-close'></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modal_preview_map_for_sign" tabindex="-1" aria-labelledby="modal_preview_map_for_sign" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="width: 100% !important;">
                    <div class="modal-content">
                        <div class="modal-header p-2">
                            <h5 class="modal-title"> <i class="fa fa-file"></i> <span class="text-danger"><b>Click on the Position where the Sign to be placed</b></span></h5>
                        </div>
                        <div class="modal-body bg-secondary" style="overflow: scroll;cursor:move;">
                            <template x-if="sign_type =='map'">
                                <div id="panel" class="bg-white"></div>
                            </template>
                            <div id="preview_map_for_sign"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">
                                <i class='fa fa-close'></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="loader2" class="btn btn-primary invisible">
        <span class="spinner-border spinner-border-sm"></span>
        Loading..
    </button>
    <form action="<?php echo ESIGN_URL; ?>" method="post" id="adhaar_sign_form">
        <input type="hidden" id="eSignRequest" name="eSignRequest"  />
        <input type="hidden" id="aspTxnID" name="aspTxnID"  />
        <input type="hidden" id="Content-Type" name="Content-Type" value="application/xml" />
    </form>
    <style>
        #loader2 {
            position: fixed;
            z-index: 999999;
            /* High z-index so it is on top of the page */
            top: 50%;
            right: 50%;
            /* or: left: 50%; */
            margin-top: -..px;
            /* half of the elements height */
            margin-right: -..px;
            /* half of the elements width */
        }
    </style>

    <script>
        /** VIEW VILLAGE DAGS CHITHA **/
        function villDagsChithaButton() {
            $('#modal_vill_dag_chitha').modal('show');
        }

        function viewMaps() {
            $('#modal_show_map_list').modal('show');
        }
    </script>

    <script type="text/javascript">
        function myFunction() {
            var x = document.getElementById("tsaurls").value;
            if (x != 0) {
                document.getElementById("tsaURL").value = x;
            } else {
                document.getElementById("tsaURL").value = "";
            }
        }
    </script>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/change_location.js') ?>"></script>
</div>
