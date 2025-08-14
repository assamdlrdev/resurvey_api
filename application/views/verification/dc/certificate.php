<style>
    .icon-box {
        box-shadow: 0 0 25px 0 #572ede;
        background: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
        position: relative;
        -ms-transition: 0.4s;
        -o-transition: 0.4s;
        -moz-transition: 0.4s;
        -webkit-transition: 0.4s;
        transition: 0.4s;
    }

    .icon-box:hover {
        box-shadow: 0 0 25px 0 #45de2e;
        -webkit-transform: translateY(30px);
        transform: translateY(10px);
    }
</style>

<div class="container mx-5">
    <div class="text-right">
        <?php
            if(!$chitha_verification_certificate || !$chitha_verification_certificate->signed_by):
        ?>
        <button title="Print" class="btn btn-sm btn-info" type="button" onclick="printDiv('printableDiv')">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 15 15">
                <path fill="currentColor" d="M3 1.5A1.5 1.5 0 0 1 4.5 0h6A1.5 1.5 0 0 1 12 1.5V5H3zM1.5 6A1.5 1.5 0 0 0 0 7.5v4A1.5 1.5 0 0 0 1.5 13H2V9h11v4h.5a1.5 1.5 0 0 0 1.5-1.5v-4A1.5 1.5 0 0 0 13.5 6z" />
                <path fill="currentColor" d="M3 10h9v5H3z" />
            </svg>
        </button>
        <!-- <button class="btn btn-warning" title="E-Sign" data-toggle="modal" data-target="#verifyModal"> -->
        <button class="btn btn-warning esignInit" title="E-Sign" data-success="<?= $validationStatus['success'] ? 1 : 0; ?>" data-location="<?= $validationStatus['locations']; ?>" data-message="<?= $validationStatus['message']; ?>">
            <i class="fas fa-signature"></i>
        </button>
        <?php
            endif;
        ?>
    </div>
</div>
<div class="container-fluid px-5 mx-5 py-5 mt-3 mb-5 bg-white printable-div" id="printableDiv">
    <?php 
        if(!$chitha_verification_certificate):
            include APPPATH . '/views/verification/dc/certificate-partials.php';
        elseif($chitha_verification_certificate->signed_by):
    ?>
            <iframe src="<?= base_url(VERIFICATION_CERTICATE_ESIGN_PATH . $chitha_verification_certificate->signed_file_name); ?>"
            frameborder="0" style="overflow:hidden;height:800px;width:100%" height="800" width="100%">
    <?php
        else:
    ?>
            <iframe src="<?= base_url(VERIFICATION_CERTICATE_ESIGN_PATH . $chitha_verification_certificate->file_name); ?>"
            frameborder="0" style="overflow:hidden;height:800px;width:100%" height="800" width="100%">
    <?php
        endif;
    ?>
</div>

<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">AADHAAR HOLDER CONSENT</h5>
                <button type="button" class="close closeVerifyModalButton" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-bold">
                                        Please check the box to provide your consent to the below option.
                                    </p>
                                    <?= $addhaar_consent_content; ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="accept" id="consentAgreed">
                                        <label class="form-check-label text-bold" for="consentAgreed">
                                            <?= $aadhaar_consent_checkbox_text; ?>
                                        </label>
                                        <div class="text-danger" id="consentAgreedError"></div>
                                    </div>
                                    <button class="btn btn-primary" id="btn-esign">Verify Using Aadhaar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-center text-bold">Verify Using E-Sign</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeVerifyModalButton" data-dismiss="modal">Close <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>


<!-- E=sign error Modal -->
<div class="modal fade" id="verifyErrModal" tabindex="-1" role="dialog" aria-labelledby="verifyErrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyErrModalLabel">There is some error!</h5>
                <button type="button" class="close closeVerifyModalButton" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-bold verifyErrMsg">
                                        Please check the box to provide your consent to the below option.
                                    </p>
                                    <div>
                                        <strong>Locations:</strong>
                                        <p class="dtlLocs"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                        <div class="icon-box">
                            <div class="icon-box-content table-cell">
                                <div class="icon-box-content" style="padding-top: 10px;">
                                    <p class="text-center text-bold">Verify Using E-Sign</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeVerifyModalButton" data-dismiss="modal">Close <i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.esignInit', function(){
        const $this = $(this);
        const status = $this.data('success');
        if(status == 0){
            const msg = $this.data('message');
            const locations = $this.data('location');

            $('.verifyErrMsg').html(msg);
            
            const locArr = locations.split(' | ');
            let html = `<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Location</th>
                                </tr>
                            </thead><tbody>`;
            $.each(locArr, function(key, loc){
                $('.dtlLocs').append(`<div>${loc}</div>`);
                html += `<tr>
                            <td>${key + 1}</td>
                            <td>${loc}</td>
                        </tr>`;
            });

            html += `</tbody></table>`;

            $('.dtlLocs').html(html);

            $('#verifyErrModal').modal('show');
        }else{
            $('#verifyModal').modal('show');
        }
    });
    
    $(document).on('click', '#btn-esign', (e) => {
        $('#consentAgreedError').text('');
        if(!$('#consentAgreed').prop('checked')){
            $('#consentAgreedError').text('Please check the checkbox');
            return false;
        }
        
        $('.loader-wrap').show();
        // var dist_code = $('#dist_code').val();
        // var subdiv_code = $('#subdiv_code').val();
        // var cir_code = $('#cir_code').val();
        // var mouza_pargona_code = $('#mouza_pargona_code').val();
        // var lot_no = $('#lot_no').val();
        // var vill_townprt_code = $('#vill_townprt_code').val();
        // var patta_no = $('#patta_no').val();
        // var patta_type_code = $('#patta_type_code').val();
        // var dag_no = $('#dag_no').val();
        // var baseurl = $('#base').val();
        var acceptConsent = $('#consentAgreed').prop('checked');
        $.ajax({
            url: '<?= base_url('index.php/verification/DCController/eSignInit') ?>',
            data: {
                // dist_code:dist_code, 
                // subdiv_code:subdiv_code, 
                // cir_code:cir_code, 
                // mouza_pargona_code:mouza_pargona_code, 
                // lot_no:lot_no, 
                // vill_townprt_code:vill_townprt_code, 
                // patta_no:patta_no,
                // patta_type_code:patta_type_code,
                // dag_no:dag_no,
                accept_consent: acceptConsent
            },
            type: 'POST',
            success: (data) => {
                var response = JSON.parse(data);
                if(response.status == "FAILED" && response.responseType == 1) {
                    $('.loader-wrap').hide();
                    alert(response.msg);
                }
                else if(response.status == "SUCCESS" && response.responseType == 0) {
                    console.log(response.data);
                    // loadESign(response.data.url);
                    // window.location.href = 'D:\\laragon\\www\\esign/index.php?dc=22&sc=01&cc=04&mc=01&lc=05&vc=10004&d=1';
                    window.location.href = response.data.url;
                }else{
                    $('.loader-wrap').hide();
                }
            },
            error: function(errors){
                $('.loader-wrap').hide();
            }
        });

        // console.log(dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, patta_no, patta_type_code, dag_no);
    });

    function printDiv(divId) {
        var divToPrint = document.getElementById(divId);

        var newWin = window.open('', 'Print-Window');

        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();

        setTimeout(function() {
            newWin.close();
        }, 10);
    }
</script>