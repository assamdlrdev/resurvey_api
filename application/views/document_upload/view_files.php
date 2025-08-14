<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <style>
        .card {
            margin: 0 auto;
            /* Added */
            float: none;
            /* Added */
            margin-bottom: 10px;
            /* Added */
            /* margin-top: 50px; */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card col-md-10" id="loc_save">
            <div class="card-body">
                <form class="form-horizontal unicode" name="form" method='post' action="<?php echo $base ?>index.php/get-jamabandi-details-report">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px">
                                Location Details For Document Upload
                            </h4>
                        </div>

                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <th>District</th>
                                    <th>Subdivision</th>
                                    <th>Circle</th>
                                    <th>Mouza</th>
                                    <th>Lot No</th>
                                    <th>Village</th>
                                    <th>Dag No</th>
                                </tr>
                                <tr>

                                    <td><?php echo $namedata[0]->district; ?></td>
                                    <td><?php echo $namedata[1]->subdiv; ?></td>
                                    <td><?php echo $namedata[2]->circle; ?></td>
                                    <td><?php echo $namedata[3]->mouza; ?></td>
                                    <td><?php echo $namedata[4]->lot_no; ?></td>
                                    <td><?php echo $namedata[5]->village; ?></td>
                                    <td><?php echo $dag_no; ?></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <?php //var_dump($dag);
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>
                    <br>
                    <div class="row">

                        <input type="hidden" name="loc_code" id="loc_code" value='<?php echo $loc_code ?>' />
                        <input type="hidden" name="dag_no" id="dag_no" value='<?php echo $dag_no ?>' />
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />

                        <input type="hidden" name="uuid" id="uuid" value='<?php echo $uuid ?>' />

                        <!--/////////////upload docs///////////-->



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                        <div class="col-lg-12 text-bold text-red" id="alert_message"></div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <label><u>Uploaded Documents</u></label>
                            &nbsp;
                            <i class="fa fa-info-circle text-red" title="1. Uploaded file types should be jpeg|jpg|png|pdf only.
                2. Uploaded file size should not be more than 4MB"></i>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <table class="table table-striped table-bordered">
                                <tbody id='certi_tab'>

                                    <tr>
                                        <td><span class="text-bold"> <input type="hidden" required="" id="doc1" name="doc1" value="chitha" />Chitha</span>
                                        </td>

                                        <td>
                                            <?php if (!empty($doc1_id)) {
                                                if ($doc1_id->doc_flag != '' || $doc1_id->doc_flag != null) { ?>
                                                    <div id="div_death">
                                                        <button class="btn btn-sm btn-info" type="button"><a style="color: red; text-decoration: none;" href='<?php echo base_url() ?>index.php/Chithacontrol/downloadDocuments?doc_flag=<?php echo $doc1_id->doc_flag ?>&uuid=<?php echo $doc1_id->uuid ?>&dag_no=<?php echo $doc1_id->dag_no ?>' target="_blank">VIEW <?= $doc1_id->file_name ?></a></button>&nbsp;&nbsp;
                                                        <b </div>
                                                        <?php }
                                                } else { ?>
                                                        No Documents uploaded yet<?php } ?>
                                                        <div id="file_1"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="text-bold"> <input type="hidden" required="" id="doc2" name="doc2" value="jamabandi" />Jamabandi</span>
                                        </td>

                                        <td>

                                            <?php if (!empty($doc2_id)) {
                                                if ($doc2_id->doc_flag != '' || $doc2_id->doc_flag != null) { ?>
                                                    <div id="div_noc">
                                                        <button class="btn btn-sm btn-info" type="button"><a style="color: red; text-decoration: none;" href='<?php echo base_url() ?>index.php/Chithacontrol/downloadDocuments?doc_flag=<?php echo $doc1_id->doc_flag ?>&uuid=<?php echo $doc1_id->uuid ?>&dag_no=<?php echo $doc1_id->dag_no ?>' target="_blank">VIEW <?= $doc2_id->file_name ?></a></button>&nbsp;&nbsp;

                                                    </div>
                                                <?php }
                                            } else { ?>
                                                No Documents uploaded yet<?php } ?>
                                                <div id="file_2"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="text-bold"> <input type="hidden" required="" id="doc3" name="doc3" value="khatian" />Khatian register</span>
                                        </td>

                                        <td>

                                            <?php if (!empty($doc3_id)) {
                                                if ($doc3_id->doc_flag != '' || $doc3_id->doc_flag != null) { ?>
                                                    <div id="div_kha">
                                                        <button class="btn btn-sm btn-info" type="button"><a style="color: red; text-decoration: none;" href='<?php echo base_url() ?>index.php/Chithacontrol/downloadDocuments?doc_flag=<?php echo $doc1_id->doc_flag ?>&uuid=<?php echo $doc1_id->uuid ?>&dag_no=<?php echo $doc1_id->dag_no ?>' target="_blank">VIEW <?= $doc3_id->file_name ?></a></button>&nbsp;&nbsp;

                                                    </div>
                                                <?php }
                                            } else { ?>
                                                No Documents uploaded yet<?php } ?>
                                                <div id="file_3"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="text-bold"> <input type="hidden" required="" id="doc4" name="doc4" value="touzi" />Tauzi Bahira register</span>
                                        </td>

                                        <td>

                                            <?php if (!empty($doc4_id)) {
                                                if ($doc4_id->doc_flag != '' || $doc4_id->doc_flag != null) { ?>
                                                    <div id="div_touzi">
                                                        <button class="btn btn-sm btn-info" type="button"><a style="color: red; text-decoration: none;" href='<?php echo base_url() ?>index.php/Chithacontrol/downloadDocuments?doc_flag=<?php echo $doc1_id->doc_flag ?>&uuid=<?php echo $doc1_id->uuid ?>&dag_no=<?php echo $doc1_id->dag_no ?>' target="_blank">VIEW <?= $doc4_id->file_name ?></a></button>&nbsp;&nbsp;

                                                    </div>
                                                <?php }
                                            } else { ?>
                                                No Documents uploaded yet<?php } ?>
                                                <div id="file_4"></div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered">
                                <tbody id=''><span style="color: black;font-weight: bold;">Additional Document (if Any)</span>
                                    <tr>
                                        <td><span class="text-bold"> <input type="text" required="" id="doc5" name="doc5" placeholder="Enter document name" value="" /></span>

                                        <td>

                                            <?php if (!empty($doc5_id)) {
                                                if ($doc5_id->doc_flag != '' || $doc5_id->doc_flag != null) { ?>
                                                    <div id="div_addi">
                                                        <button class="btn btn-sm btn-info" type="button"><a style="color: red; text-decoration: none;" href='<?php echo base_url() ?>index.php/Chithacontrol/downloadDocuments?doc_flag=<?php echo $doc1_id->doc_flag ?>&uuid=<?php echo $doc1_id->uuid ?>&dag_no=<?php echo $doc1_id->dag_no ?>' target="_blank">VIEW <?= $doc5_id->file_name ?></a></button>&nbsp;&nbsp;

                                                    </div>
                                                <?php }
                                            } else { ?>
                                                No Documents uploaded yet<?php } ?>
                                                <div id="file_5"></div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>

                        <!----------------->


                        <br>
                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <br>
                            <a href="<?php echo base_url(); ?>index.php/set-location-for-jamabandi" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i>&nbsp; BACK TO MAIN MENU
                            </a>

                        </div>
                    </div>


                </form>

            </div>
        </div>
        <br>
    </div>

</body>

<script>
    $('#pattaNumber').on('change', function() {
        var patta_no = this.value;
        var baseurl = $("#base").val();

        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
        var data = {};
        data[tokenName] = tokenHash;
        data.patta_no = patta_no;

        $.ajax({
            url: baseurl + 'index.php/get-patta-type-jamabandi-report',
            type: 'POST',
            data: data,
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {

                var pattaType = '<option selected disabled>Select</option>';

                $.each(JSON.parse(data), function(item, value) {
                    pattaType += '<option value="' + value.type_code + '">' + value.patta_type + '</option>'
                });

                $("#getPattaType").html(pattaType);
            }
        });

    });
</script>


<script>
    ////////
    $('.uploadOMutDocumentCO').click(function() {
        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
        var data = {};
        data[tokenName] = tokenHash;
        
        flag = $(this).attr('id');

        var formdata = new FormData();
        //alert(flag);

        if (flag == 1) {
            formdata.append("doc1_file", $('#doc1_file')[0].files[0]);
        }
        if (flag == 2) {
            formdata.append("doc2_file", $('#doc2_file')[0].files[0]);
        }
        if (flag == 3) {
            formdata.append("doc3_file", $('#doc3_file')[0].files[0]);
        }
        if (flag == 4) {
            formdata.append("doc4_file", $('#doc4_file')[0].files[0]);
        }

        if (flag == 5) {
            formdata.append("doc5_file", $('#doc5_file')[0].files[0]);
        }

        // formdata.append("case_no", $('#case_no').val());
        formdata.append("flag", $(this).attr('id'));
        formdata.append("loc_code", $('#loc_code').val());
        formdata.append("doc1", $('#doc1').val());
        formdata.append("doc2", $('#doc2').val());
        formdata.append("doc3", $('#doc3').val());
        formdata.append("doc4", $('#doc4').val());
        formdata.append("doc5", $('#doc5').val());
        formdata.append("dag", $('#dag_no').val());
        formdata.append("uuid", $('#uuid').val());
        formdata.append(tokenName, tokenHash);

        var baseurl = $("#base").val();

        console.log(formdata);

        $.ajax({
            url: baseurl + "index.php/Chithacontrol/uploadSupportiveDocs",
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",


            success: function(data) {
                console.log(data);

                if (data.doc_update === true) {
                    alert("Old uploaded file will be replaced!");
                }

                if (data.img_upload === true) {
                    alert("File has successfully uploaded..");
                }

                if (data.flag_set == '1') {
                    $('#div_death').html('');
                    $('#file_1').html('<a class="btn btn-sm btn-info" type="button" style="color: red; text-decoration: none;" href="' + baseurl + 'index.php/Chithacontrol/downloadDocuments/' + data.doc_id + '" target="_blank">VIEW ' + data.filename + '</a>' + ' ' + '<button type="button" class="btn btn-sm btn-danger removeOMutReportDocumentCO" id="1">Remove&nbsp;<i class="fa fa-minus-square"></i></button>');
                }
                if (data.flag_set == '2') {
                    $('#div_noc').html('');
                    $('#file_2').html('<a class="btn btn-sm btn-info" type="button" style="color: red; text-decoration: none;" href="' + baseurl + 'index.php/Chithacontrol/downloadDocuments/' + data.doc_id + '" target="_blank">VIEW ' + data.filename + '</a>' + ' ' + '<button type="button" class="btn btn-sm btn-danger removeOMutReportDocumentCO" id="2">Remove&nbsp;<i class="fa fa-minus-square"></i></button>');
                }

                if (data.flag_set == '3') {
                    $('#div_kha').html('');
                    $('#file_3').html('<a class="btn btn-sm btn-info" type="button" style="color: red; text-decoration: none;" href="' + baseurl + 'index.php/Chithacontrol/downloadDocuments/' + data.doc_id + '" target="_blank">VIEW ' + data.filename + '</a>' + ' ' + '<button type="button" class="btn btn-sm btn-danger removeOMutReportDocumentCO" id="3">Remove&nbsp;<i class="fa fa-minus-square"></i></button>');
                }

                if (data.flag_set == '4') {
                    $('#div_touzi').html('');
                    $('#file_4').html('<a class="btn btn-sm btn-info" type="button" style="color: red; text-decoration: none;" href="' + baseurl + 'index.php/Chithacontrol/downloadDocuments/' + data.doc_id + '" target="_blank">VIEW ' + data.filename + '</a>' + ' ' + '<button type="button" class="btn btn-sm btn-danger removeOMutReportDocumentCO" id="4">Remove&nbsp;<i class="fa fa-minus-square"></i></button>');
                }

                if (data.flag_set == '5') {
                    $('#div_addi').html('');
                    $('#file_5').html('<a class="btn btn-sm btn-info" type="button" style="color: red; text-decoration: none;" href="' + baseurl + 'index.php/Chithacontrol/downloadDocuments/' + data.doc_id + '" target="_blank">VIEW ' + data.filename + '</a>' + ' ' + '<button type="button" class="btn btn-sm btn-danger removeOMutReportDocumentCO" id="5">Remove&nbsp;<i class="fa fa-minus-square"></i></button>');
                }

                if (data.img_upload === false) {
                    alert("File Uploading Failed..");
                }
                if (data.error != null) {
                    $('#alert_message').html('');
                    var error_message = '';

                    $.each(data.error, function(index, value) {
                        $('#alert_message').fadeIn();
                        error_message += '<li>' + value['message'] + '</li>'
                    });
                    $('#alert_message')
                        .html('<div class="bg-gradient-danger p-2 rounded">' + error_message +
                            '<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">&nbsp;</div></div>');
                    setTimeout(function() {
                        $('#alert_message').fadeOut();
                    }, 5000);

                    return false;
                }

            }
        });
    });

    $(document).on('click', '.removeOMutReportDocumentCO', function() {

        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
        var data = {};

        flag = $(this).attr('id');
        doc1 = $('#doc1').val();
        doc2 = $('#doc2').val();
        doc3 = $('#doc3').val();
        doc4 = $('#doc4').val();
        doc5 = $('#doc5').val();
        dag = $('#dag_no').val();
        loc_code = $('#loc_code').val();
        uuid = $('#uuid').val();
        //alert(dag);
        data = {
            flag: flag,
            dag: dag,
            doc1: doc1,
            doc2: doc2,
            doc3: doc3,
            doc4: doc4,
            doc5: doc5,
            loc_code: loc_code,
            uuid: uuid
        }
        data[tokenName] = tokenHash;
        if (flag == 1) {
            certificate = doc1;
        }
        if (flag == 2) {
            certificate = doc2;
        }
        if (flag == 3) {
            certificate = doc3;
        }
        if (flag == 4) {
            certificate = doc4;
        }
        if (flag == 5) {
            certificate = 'Document 5';
        }
        var baseurl = $("#base").val();

        if (confirm("Are you sure to delete " + certificate + " ?")) {

            $.ajax({
                url: baseurl + "index.php/Chithacontrol/removeSupportiveDocs/",
                type: 'POST',
                data: data,
                dataType: "json",

                success: function(data) {
                    console.log(data);
                    if (data.flag == '1') {
                        $('#file_1').html('');
                        $('#div_death').html('');
                    }
                    if (data.flag == '2') {
                        $('#file_2').html('');
                        $('#div_noc').html('');
                    }
                    if (data.flag == '3') {
                        $('#file_3').html('');
                        $('#div_kha').html('');
                    }
                    if (data.flag == '4') {
                        $('#file_4').html('');
                        $('#div_touzi').html('');
                    }
                    if (data.flag == '5') {
                        $('#file_5').html('');
                        $('#div_addi').html('');
                    }
                }
            });
        }
    });
</script>



</html>


<script src="<?= base_url('assets/js/location_document.js') ?>"></script>