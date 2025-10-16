$('#m').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $('#sd').val();
    var cir = $('#c').val();
    var mza = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/lotdetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv, cir: cir, mza: mza },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#l').prop('selectedIndex', 0);
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Lot</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
            }
            $('#l').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#l').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#l').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $('#sd').val();
    var cir = $('#c').val();
    var mza = $('#m').val();
    var lot = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/villagedetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv, cir: cir, mza: mza, lot: lot },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#v').prop('selectedIndex', 0);
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Village</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name + '</option>';
            }
            $('#v').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#v').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$(document).on('change', '#v', function (e) {
    e.preventDefault();
    var application_type = $('#application_type').val();
    if (application_type == null || application_type == '') {
        alert("Please select application type.");
        $('#v').prop('selectedIndex', 0);
        return;
    }
    var baseurl = $("#base").val();

    $.ajax({
        url: baseurl + "index.php/PattaController/getPattaTypes",
        method: "POST",
        data: { application_type: application_type },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#patta_type').prop('selectedIndex', 0);
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Patta Type</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].type_code + '>' + data[i].patta_type + '</option>';
            }
            $('#patta_type').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#patta_type').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
})

/**** Get Patta No *******/
$(document).on('change', '#patta_type', function (e) {
    e.preventDefault();
    var baseurl = $("#base").val();
    var patta_type = $("#patta_type").val();
    var vill_code = $('#v').val();
    var mouza_pargona_code = $('#m').val();
    var lot_no = $('#l').val();

    $.ajax({
        url: baseurl + "index.php/PattaController/getPattaNoChitha",
        method: "POST",
        data: { patta_type: patta_type, vill_code: vill_code,mouza_pargona_code:mouza_pargona_code,lot_no:lot_no },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#patta_no').prop('selectedIndex', 0);
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            if (data) {
                var html = '';
                var i;
                html += '<option value="">Select Patta No</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].patta_no + '>' + data[i].patta_no + '</option>';
                }
                $('#patta_no').html(html);
            }
            $.unblockUI();
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#patta_no').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
})

/********** Add more Dag ********/
$(document).on('click', '#add_dag', function (e) {
    e.preventDefault();
    var baseurl = $("#base").val();
    var row_id = $('#row_id').val();
    var dist_code = $('#dist_code').val();
    var vill_code = $('#vill_code').val();
    var patta_type = $('#patta_type').val();
    var patta_no = $('#patta_no').val();
    $.ajax({
        url: baseurl + "index.php/PattaController/getDagNo",
        type: 'POST',
        data: { vill_code: vill_code, patta_type: patta_type, patta_no: patta_no },
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            var dag_no = '';
            var table = '';
            var area_head = '';
            if (data.dag === true) {
                $.each(data.dag_no, function (index, value) {
                    dag_no += '<option value=' + value["dag_no"] + '>' + value["dag_no"] + '</option>'
                });

                if (jQuery.inArray(dist_code, BARAK_VALLEY) !== -1) {
                    area_head = '<tr style="background-color: #769181; color: #fff">' +
                        '<th colspan="2">Bigha</th>' +
                        '<th colspan="2">Katha</th>' +
                        '<th colspan="2">Chatak</th>' +
                        '<th colspan="2">Ganda</th>' +
                        '<th colspan="2">Kranti</th>' +
                        '</tr>';

                    area_td = '<tr>' +
                        '<th colspan="2" id="bigha' + row_id + '">0</th>' +
                        '<th colspan="2" id="katha' + row_id + '">0</th>' +
                        '<th colspan="2" id="lessa' + row_id + '">0</th>' +
                        '<th colspan="2" id="ganda' + row_id + '">0</th>' +
                        '<th colspan="2" id="kranti' + row_id + '">0</th>' +
                        '</tr>';
                } else {
                    area_head = '<tr style="background-color: #769181; color: #fff">' +
                        '<th colspan="3">Bigha :</th>' +
                        '<th colspan="3">Katha</th>' +
                        '<th colspan="4">Lessa</th>' +
                        '</tr>';

                    area_td = '<tr>' +
                        '<th colspan="3" id="bigha' + row_id + '">0</th>' +
                        '<th colspan="3" id="katha' + row_id + '">0</th>' +
                        '<th colspan="4" id="lessa' + row_id + '">0</th>' +
                        '</tr>';
                }

                table =
                    '<table class="table table-striped table-bordered text-bold" id="div_' + row_id + '">' +
                    '<thead>' +
                    '<tr>' +
                    '    <th style="background-color: #769181; color: #fff" colspan="10">' +
                    '        Applicant Dag and Land Area ' +
                    '    </th>' +
                    '</tr>' +
                    '<tr>' +
                    '    <th colspan="3" width="25%">Dag No : <span style="color:red;font-weight:bold; font-size: 18px;">*</span></th>' +
                    '    <th colspan="3" width="25%">' +
                    '        <select class="form-select dag_no_new" data-id="' + row_id + '" id="dag_no' + row_id + '" name="dag_no[]" required>' +
                    '            <option value="" selected>Select Dag No</option>' +
                    dag_no +
                    '        </select>' +
                    '    </th>' +
                    '    <th colspan="2">' +
                    '        <button type="button" class="btn btn-danger dag_row" data-id="' + row_id + '"><i' +
                    '                    class=\'fa fa-trash\'></i>  Delete Dag</button>' +
                    '    </th>' +
                    '    <th colspan="2"></th>' +
                    '</tr>' +
                    area_head +
                    area_td +
                    '</thead>' +
                    '</table>'
                $('#add_more_dag').append(table);
                $('#row_id').val(parseInt(row_id) + 1);
            } else {
                alert('Something went wrong');
            }
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
})


/********** Remove Dag Div ********/
$(document).on('click', '.dag_row', function (e) {
    e.preventDefault();
    if (!confirm("Are you sure want to delete dag no.")) {
        return;
    }
    var baseurl = $("#base").val();
    var row_id = $(this).attr("data-id");
    var existing_id = $(this).data("existing-id");
    if(existing_id){
        $.ajax({
            url: baseurl + "index.php/PattaController/deleteDag",
            type: 'POST',
            data: { dag_id: existing_id},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({
                    message: $('#displayBox'),
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function (data) {
                $.unblockUI();
                $('#div_' + row_id).remove();
            },
            error: function (jqXHR, exception) {
                $.unblockUI();
                alert('Could not Complete your Request ..!, Please Try Again later..!');
            }
        });
    }else{
        $('#div_' + row_id).remove();
    }
})

/***** save application *****/
$("#save_patta_application").submit(function (e) {
    e.preventDefault()
    var baseurl = $("#base").val();
    if (!confirm("Are you sure want to save application.")) {
        return;
    }
    $('#save_form_error_div').hide();
    $('#form_errors').empty();
    $('#save_success_div').hide();
    $.ajax({
        url: baseurl + "index.php/PattaController/storeMainPattaForm",
        type: 'POST',
        data: $('#save_patta_application').serialize(),
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $('#form_errors').empty();
            if (data.error) {
                $.unblockUI();
                alert("Validation-Error, Please validate the form correctly!");
                $('#save_form_error_div').show();
                $('#form_errors').append(data.error);
                return;
            }
            if (data.validation === true) {
                if (data.error_save === true) {
                    $.unblockUI();
                    $('#form_errors').html(data.error_msg);
                    $('#save_form_error_div').show();
                    return;
                }
                if (data.save_data === true) {
                    $('#add_data_div').removeClass('hide');
                    $('#form_success').html('PATTA DETAILS INSERTED SUCCESSFULLY.');
                    $('#save_success_div').show();
                    var case_no = $("#case_no").val();
                    var patta_type = $("#application_type").val();
                    var patta_type_code = $("#patta_type").val();
                    var patta_no = $("#patta_no").val();
                    var mouza_code = $("#mouza_code").val();
                    var lot_no = $("#lot_no").val();
                    var vill_code = $("#vill_code").val();

                    window.location.href = baseurl + "index.php/PattaController/viewPatta?case_no="+case_no+"&patta_type="+patta_type+"&patta_type_code="+patta_type_code+"&patta_no="+patta_no+"&mouza_code="+mouza_code+"&lot_no="+lot_no+"&vill_code="+vill_code;
                    return;
                } else {
                    $.unblockUI();
                    alert('Could not Complete your Request ..!, Please Try Again later..!');
                    return;
                }
            }
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
})

/**** Get Pattadar details *******/
$(document).on('change', '#pattadar_name', function (e) {
    e.preventDefault();
    var baseurl = $("#base").val();
    var pattadar_id = $(this).val();
    var patta_type = $('#patta_type').val();
    var patta_no = $('#patta_no').val();
    var vill_code = $('#vill_code').val();
    $.ajax({
        url: baseurl + "index.php/PattaController/getPattadarDetails",
        type: 'POST',
        data: {
            pattadar_id: pattadar_id,
            patta_type: patta_type,
            patta_no: patta_no,
            vill_code: vill_code
        },
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            $('#guardian_name').val(data);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#pattadar_name').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
})
/***** date *******/
$(function () {

});

/**** Get Dag Details *******/
$(document).on('change', '.dag_no_new', function (e) {
    e.preventDefault();
    var baseurl = $("#base").val();
    var row_id = $(this).attr("data-id");
    var dag_no = $(this).val();
    var vill_code = $('#vill_code').val();
    $.ajax({
        url: baseurl + "index.php/PattaController/getDagArea",
        type: 'POST',
        data: { dag_no :dag_no,vill_code:vill_code },
        dataType: 'json',
        beforeSend: function () {
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            $.unblockUI();
            if (jQuery.inArray(data.dag_no.dist_code, BARAK_VALLEY) !== -1) {
                $('#bigha'+row_id).html(data.dag_no.dag_area_b);
                $('#katha'+row_id).html(data.dag_no.dag_area_k);
                $('#lessa'+row_id).html(data.dag_no.dag_area_lc);
                $('#ganda'+row_id).html(data.dag_no.dag_area_g);
                $('#kranti'+row_id).html(data.dag_no.dag_area_kr);
            } else {
                $('#bigha'+row_id).html(data.dag_no.dag_area_b);
                $('#katha'+row_id).html(data.dag_no.dag_area_k);
                $('#lessa'+row_id).html(data.dag_no.dag_area_lc);
            }
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#dag_no').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
})
/**** Get to date *******/
$(document).on('change', '#time_period', function (e) {
    e.preventDefault();
    var given_year = $(this).val();
    var date = new Date();
    date.setFullYear(date.getFullYear() + parseInt(given_year));
    var dd = date.getDate();
    var mm = date.getMonth();
    var y = date.getFullYear();
    var someFormattedDate = dd + '-' + mm + '-' + y;
    $('#upto_date').val(someFormattedDate);
})