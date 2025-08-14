<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>

<script src="<?php echo base_url('assets/plugins/dsign/js/dsc-signer.js?v=1.1') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/dsign/js/dscapi-conf.js?v=1.1') ?>" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/plugins/dsign/css/dsc-signer.css') ?>">
<script src="<?php echo base_url('assets/plugins/pdfjs/pdf.min.js') ?>" type="text/javascript"></script>

<script>
    function alpineData() {
        return {
            'dist_code': '',
            'subdiv_code': '',
            'cir_code': '',
            'request_type': '',
            'mouza_pargona_code': '',
            'lot_no': '',
            'lots': [],
            'selected_cases': [],
            'villages': [],
            'base_url': "<?= base_url(); ?>",
            'NC_VILLAGE_PROPOSAL_DIR': "<?= NC_VILLAGE_PROPOSAL_DIR . 'co/'; ?>",
            'is_loading': false,
            'filter_status': 'pending',
            'sign_type': '',
            'approve_proposal': [],
            'sign_x': 200,
            'sign_y': 200,
            'pdf_h': '',
            'pdf_w': '',
            'base64_data': '',
            'co_note': '',
            'user': '',
            'is_signing': false,
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
                var data = {};
                data[csrfName] = csrfHash;
                var self = this;
                var locations = '<?= json_encode($locations) ?>';
                var locations = JSON.parse(locations);
                this.dist_code = locations.dist.dist_code;
                this.subdiv_code = locations.subdiv.subdiv_code;
                this.cir_code = locations.circle.cir_code;
                // var villages = '<?= json_encode($villages) ?>';
                // var villages = villages.replace('\n', '\\n');
                // this.villages = JSON.parse(villages);
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/getProposalVillages',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        self.villages = data;
                    }
                });
                var approve_proposal = '<?= json_encode($approve_proposal) ?>';
                this.approve_proposal = JSON.parse(approve_proposal);

                this.$watch('request_type', function() {
                    self.selected_cases = [];
                    if(self.request_type == 'nc_to_nc_merge_village_request'){

                        $('.merge_vill_col, .merge_vill_note_tr').show();
                        $('.non_merge_vill_col').hide();

                    }else if(self.request_type == 'nc_to_c_merge_village_request'){

                        $('.merge_vill_col, .merge_vill_note_tr').show();
                        $('.non_merge_vill_col').hide();


                    }else if(self.request_type == 'non_merge_village_request'){
                        $('.non_merge_vill_col').show();
                        $('.merge_vill_col, .merge_vill_note_tr').hide();
                    }
                });

                $(document).ready(function() {
                    document.getElementById('preview_proposal_for_sign').addEventListener('click', function(event) {
                        const rect = this.getBoundingClientRect();
                        const x = event.clientX - rect.left;
                        const y = event.clientY - rect.top;
                        self.sign_x = Math.floor(self.pdf_w - x);
                        self.sign_y = Math.floor(self.pdf_h - y);
                        $.confirm({
                            title: 'Select Sign Type',
                            content: '',
                            type: 'orange',
                            typeAnimated: true,
                            theme: 'supervan',
                            buttons: {
                                // Adhaar: {
                                //     text: 'Adhaar E-Sign',
                                //     btnClass: 'btn-green',
                                //     action: function() {
                                //         self.signProposalConfirm('adhaar');
                                //         return;
                                //     }
                                // },
                                Digital: {
                                    text: 'Digital Signature(NICD Signer)',
                                    btnClass: 'btn-blue',
                                    action: function() {
                                        self.signProposalConfirm('nicd');
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

                    let col_text = $('#proposalTable tr:first td:eq(4)').text().trim();
                    if($('.merge_vill_note_tr').length < 0){
                        $('.merge_vill_note_table').html(`<tr class="merge_vill_note_tr">
                                                                <td style="text-align: justify;" width="100%">
                                                                    The non-cadastral villages are merged together as the individual area is less 2 Square kilometer.
                                                                </td>
                                                            </tr>`);
                    }
                    // console.log(col_text)
                    if(col_text == 'Merged Existing NC Villages Name'){
                        $('#proposalTable tr:first td:eq(4)').after(`<td style="border: 1px solid black;" class="non_merge_vill_col">
                                                                        Existing Village Name
                                                                    </td>`);
                    }else{
                        $('#proposalTable tr:first td:eq(4)').after(`<td style="border: 1px solid black;" class="merge_vill_col">
                                                                        Merged Existing NC Villages Name
                                                                    </td>`);
                    }

                });
            },
            cancelSign() {
                this.sign_x = 200;
                this.sign_y = 200;
                this.pdf_h = '';
                this.pdf_w = '';
                this.sign_type = '';
                this.base64_data = '';
                var parentElement = document.getElementById('preview_proposal_for_sign');
                if (parentElement.lastChild) {
                    parentElement.removeChild(parentElement.lastChild);
                }
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
                                url: dscapibaseurl + "pdfsignature",
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
                                            if (type == 'proposal') {
                                                self.storeSignedProposal(jsonData, sign_key);
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
            signProposal() {
                this.sign_type = 'proposal';
                this.initDSign('proposal');
                $('#closeBtn').prop('disabled', true);
                $('#signPdf').prop('disabled', true);
                $('#loader2').removeClass('invisible');
                var self = this;
                var data = {};
                data[csrfName] = csrfHash;
                data['cases'] = self.selected_cases;
                data['co_note'] = self.co_note;
                data['user'] = self.user;
                
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/saveProposalPdf',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if(data){
                            $('#closeBtn').prop('disabled', false);
                            $('#signPdf').prop('disabled', false);
                            $('#loader2').addClass('invisible');
                            if (data != null || data != '') {

                                self.base64_data = data;
                                self.loadPreviewForSign('<?= base_url() ?>index.php/nc_village_v2/NcVillageCoController/getProposalBase');

                            }
                        }else{
                            alert('Something went wrong, please try again later');
                        }
                    }
                });
            },
            signProposalConfirm(type) {
                this.is_signing = true;
                if (type == 'nicd') {
                    this.initDSign('proposal');
                    $('#closeBtn').prop('disabled', false);
                    $('#signPdf').prop('disabled', false);
                    $('#loader2').addClass('invisible');
                    if (this.base64_data) {
                        dscSigner.sign(this.base64_data);
                        // let jsonData = {sig: this.base64_data};
                        // this.storeSignedProposal(jsonData, 'sign_key');
                    }
                    this.is_signing = false;
                } else if (type == 'adhaar') {
                    this.initAdhaarSign();
                    this.is_signing = false;
                }
            },
            initAdhaarSign() {
                var self = this;
                self.txn_id = '';
                var data = {};
                data[csrfName] = csrfHash;
                data.sign_name = 'nc_proposal';
                data.sign_x = self.sign_x;
                data.sign_y = self.sign_y;
                data.user = self.user;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/adhaar-sign-process-v2',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if(data.data.txn_id){
                            $("#eSignRequest").val(data.data.esign_request);
                            $("#aspTxnID").val(data.data.txn_id);
                            $("#adhaar_sign_form").submit();
                        }else{
                            alert('error');
                        }
                    }
                });
            },
            loadPreviewForSign(url) {
                var self = this;
                pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                    // Get the first page.
                    pdfDoc.getPage(pdfDoc.numPages).then(function(page) {
                        var scale = 1;
                        var viewport = page.getViewport({
                            scale: scale
                        });

                        // Prepare canvas using PDF page dimensions.
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
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
                        document.getElementById('preview_proposal_for_sign').appendChild(canvas);
                    });
                }).catch(function(error) {
                    console.log(error);
                });
            },
            storeSignedProposal(jsonData, sign_key) {
                var self = this;
                //get pdf data
                var pdfData = jsonData.sig;

                $('#loader2').removeClass('invisible');

                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/storeSignedProposal',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'pdfbase': pdfData,
                        'sign_key': sign_key,
                        'co_note': self.co_note,
                        'user': self.user
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
                                            window.location.reload();
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
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9; color: white">NC VILLAGE (Proposal for approval and notification)</div>
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
                <div class="col-lg-3">
                    <label for="">Please Select Village Type</label>
                    <select x-model="request_type" id="request_type" class="form-control form-control-sm">
                        <option value="">All type of village</option>
                        <!-- <option value="merge_village_request">Merge Village Request</option> -->
                        <option value="non_merge_village_request">Non-merged village</option>
                        <option value="nc_to_nc_merge_village_request">Non cadastral to  non cadastral merged village</option>
                        <option value="nc_to_c_merge_village_request">Non cadastral to cadastral merged village</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> PENDING VILLAGES (Proposal for approval and notification)
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
                            </h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr>
                                <th>#</th>
                                <th>Village Name</th>
                                <th>Merge Villages Name</th>
                                <th><?= SK_LABEL ?> verified at</th>
                                <th><?= SK_LABEL ?> Note</th>
                                <th>CO Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr x-show="is_loading">
                                <td colspan="8" class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <template x-for="(village,index) in villages" :key="index">
                                <tr x-show="request_type == '' ||
                                            (request_type == 'non_merge_village_request' && village.merge_village_names == '') || 
                                            (request_type == 'nc_to_nc_merge_village_request' && village.merge_village_names != '' && !village.is_end_village_cadastral_village) || 
                                            (request_type == 'nc_to_c_merge_village_request' && village.merge_village_names != '' && village.is_end_village_cadastral_village)
                                            "
                                >
                                    <td>
                                        <!-- <template x-if="village.merge_village_names != ''">
                                            <input type="checkbox" :value="village.application_no" x-model="selected_cases" x-show="request_type == 'merge_village_request'">
                                        </template> -->
                                        <template x-if="village.merge_village_names != '' && village.is_end_village_cadastral_village">
                                            <input type="checkbox" :value="village.application_no" x-model="selected_cases" x-show="request_type == 'nc_to_c_merge_village_request'">
                                        </template>
                                        <template x-if="village.merge_village_names != '' && !village.is_end_village_cadastral_village">
                                            <input type="checkbox" :value="village.application_no" x-model="selected_cases" x-show="request_type == 'nc_to_nc_merge_village_request'">
                                        </template>
                                        <template x-if="village.merge_village_names == ''">
                                            <input type="checkbox" :value="village.application_no" x-model="selected_cases" x-show="request_type == 'non_merge_village_request'">
                                        </template>
                                    </td>
                                    <td x-text="village.loc_name"></td>
                                    <td x-text="village.merge_village_names != '' ? village.merge_village_names : 'N.A.'"></td>
                                    <td x-text="village.sk_verified_at"></td>
                                    <td x-text="village.sk_note"></td>
                                    <td x-text="village.co_note"></td>
                                    <td>
                                        <b>
                                            <small class="text-warning">Pending Proposal</small>
                                        </b>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-info text-white" x-bind:href=base_url+"index.php/nc_village_v2/NcVillageCoController/showDags?application_no="+village.application_no target=" _blank">View <i class=" fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="villages.length == 0">
                                <td colspan="8" class="text-center">
                                    <span>No villages Found</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="mt-3">
						<div class="form-group">
                            <label for="" class="form-label">Proposal Forwarded To <span class="text-red">*</span></label>
                            <select x-model="user" class="form-control">
								<option value="">Select DC</option>
								<?php 
									if(count($users) > 0): 
										foreach($users as $user):
								?>
											<option value="<?= $user['user_code'] ?>"><?= $user['name'] ?></option>
								<?php
										endforeach;
									endif; 
								?>
							</select>
                        </div>
					</div>
                    <div class="mt-3" x-show="villages.length > 0">
                        <div class="form-group">
                            <label for="" class="form-label">CO Note <span class="text-red">*</span></label>
                            <textarea x-model="co_note" placeholder="CO Note" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mt-3" x-show="villages.length > 0">
                        <button class="btn  btn-md btn-success" x-bind:class="((selected_cases.length === 0) || (co_note.trim() === '') || user === '') ? 'disabled' : ''" x-on:click="((selected_cases.length > 0) && (co_note.trim() !== '')  && user !== '') ? approveProposal : 'javascript:void(0)'">
                            <i class="fa fa-check"></i> Send Proposal for approval and notification
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> List of verified proposals</h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr>
                                <th>District</th>
                                <th>Proposal No</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr x-show="is_loading">
                                <td colspan="5" class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <template x-for="(ap,index) in approve_proposal">
                                <tr>
                                    <td><?= $locations['dist']['loc_name'] ?></td>
                                    <td x-text="ap.proposal_no"></td>
                                    <td x-text="ap.created_at"></td>
                                    <td>
                                        <a class="btn btn-sm btn-info text-white" x-bind:href=base_url+NC_VILLAGE_PROPOSAL_DIR+ap.proposal_no+'.pdf' target="_blank">View <i class=" fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="approve_proposal.length == 0">
                                <td colspan="6" class="text-center">
                                    <span>No Verified Proposal Found</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--	Modal for village dag chitha verify-->
        <div class="modal" id="modal_proposal" tabindex="-1" aria-labelledby="modal_proposal" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-xl" style="width: 100% !important;">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title text-danger" x-show="sign_type =='proposal'"> <i class="fa fa-file"></i> Please Click on the Position where the Signature to be Placed.(Mouse Clicked Position would the starting point of the Signature)</h5>
                        <h5 class="modal-title" x-show="sign_type !='proposal'"> <i class="fa fa-file"></i> Proposal for approval and notification</h5>
                    </div>
                    <div class="modal-body " id="proposal_view" x-show="sign_type !='proposal'">
                        <div class="p-3">
                            <?= $proposal; ?>
                        </div>
                    </div>
                    <div class="modal-body bg-secondary" style="cursor:move" x-show="sign_type =='proposal'">
                        <div id="panel" class="bg-white"></div>
                        <div id="preview_proposal_for_sign"></div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="signingReason" name="signingReason" maxlength="20" />
                        <input type="hidden" id="signingLocation" name="signingLocation" maxlength="20" />
                        <input type="hidden" id="stampingX" name="stampingX" maxlength="20" x-model="sign_x" />
                        <input type="hidden" id="stampingY" name="stampingY" maxlength="20" x-model="sign_y" />
                        <input type="hidden" id="tsaURL" name="tsaURL" value="" maxlength="100" style="width: 400px;" />
                        <input type="hidden" id="timeServerURL" name="timeServerURL" value="https://basundhara.assam.gov.in/dscapi/getServerTime" maxlength="100" style="width: 400px;" />
                        <button x-on:click="cancelSign" x-show="!is_signing" type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal"><i class='fa fa-close'></i> Close / Cancel
                        </button>
                        <button type="button" x-show="sign_type !='proposal' && !is_signing" x-on:click="signProposal" class="btn btn-primary"><i class="fa fa-pencil"></i> Sign & Approve Proposal</button>
                        <div x-show="is_signing" class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="<?php echo ESIGN_URL; ?>" method="post" id="adhaar_sign_form">
            <input type="hidden" id="eSignRequest" name="eSignRequest" />
            <input type="hidden" id="aspTxnID" name="aspTxnID"   />
            <input type="hidden" id="Content-Type" name="Content-Type" value="application/xml" />
        </form>
    </div>
</div>
<button id="loader2" class="btn btn-primary invisible">
    <span class="spinner-border spinner-border-sm"></span>
    Loading..
</button>
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
    /** Proposal **/
    function approveProposal() {
        $('#modal_proposal').modal('show');
        let counter = 0;
        $('.modal_sl').each(function(){
            const $this = $(this);
            const closest_tr = $this.closest('tr');
            if(closest_tr.is(':visible')){
                counter++;
                $this.text(counter);
            }
        });
    }
</script>
