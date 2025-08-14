$('#dist_code').change(function () {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    $.ajax({
        url: baseurl + "index.php/DataController/getSubDivisions",
        method: "POST",
        data: {
            'dist_code': dist_code
        },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#subdiv_code').prop('selectedIndex', 0);
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
            html += '<option value="">Select Sub-division</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name+'-'+ data[i].subdiv_code + '</option>';
            }
            $('#subdiv_code').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#subdiv_code').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#subdiv_code').change(function () {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    var subdiv_code = $("#subdiv_code").val();
    $.ajax({
        url: baseurl + "index.php/DataController/getCircles",
        method: "POST",
        data: {
            'dist_code': dist_code,
            'subdiv_code': subdiv_code
        },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#cir_code').prop('selectedIndex', 0);
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
            html += '<option value="">Select Circle</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name+'-'+ data[i].cir_code + '</option>';
            }
            $('#cir_code').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#cir_code').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#cir_code').change(function () {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    var subdiv_code = $("#subdiv_code").val();
    var cir_code = $("#cir_code").val();
    $.ajax({
        url: baseurl + "index.php/DataController/getMouzas",
        method: "POST",
        data: {
            'dist_code': dist_code,
            'subdiv_code': subdiv_code,
            'cir_code': cir_code
        },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#mouza_pargona_code').prop('selectedIndex', 0);
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
            html += '<option value="">Select Mouza</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name+'-'+ data[i].mouza_pargona_code + '</option>';
            }
            $('#mouza_pargona_code').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#mouza_pargona_code').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#mouza_pargona_code').change(function () {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    var subdiv_code = $("#subdiv_code").val();
    var cir_code = $("#cir_code").val();
    var mouza_pargona_code = $("#mouza_pargona_code").val();
    $.ajax({
        url: baseurl + "index.php/DataController/getLotNos",
        method: "POST",
        data: {
            'dist_code': dist_code,
            'subdiv_code': subdiv_code,
            'cir_code': cir_code,
            'mouza_pargona_code': mouza_pargona_code
        },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#lot_no').prop('selectedIndex', 0);
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
                html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name+'-'+ data[i].lot_no + '</option>';
            }
            $('#lot_no').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#lot_no').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#lot_no').change(function () {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    var subdiv_code = $("#subdiv_code").val();
    var cir_code = $("#cir_code").val();
    var mouza_pargona_code = $("#mouza_pargona_code").val();
    var lot_no = $("#lot_no").val();
    $.ajax({
        url: baseurl + "index.php/DataController/getVillages",
        method: "POST",
        data: {
            'dist_code': dist_code,
            'subdiv_code': subdiv_code,
            'cir_code': cir_code,
            'mouza_pargona_code': mouza_pargona_code,
            'lot_no': lot_no
        },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#vill_townprt_code').prop('selectedIndex', 0);
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
                html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name+'-'+ data[i].vill_townprt_code + '</option>';
            }
            $('#vill_townprt_code').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#vill_townprt_code').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
function submitForm() {
    $('#error_div').hide();
    hideSpinner();
    var baseurl = $("#base").val();
    var formdata = createFormData();
    $.ajax({
        url: baseurl + "index.php/DataController/getDbDetails",
        method: "POST",
        data: formdata,
        async: true,
        dataType: 'json',

        success: function (data) {
            Swal.fire({
                title: '',
                html:data,
                icon:'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    confirmSubmit();
                } 
            });
        },
        error: function (jqXHR, exception) {
            hideSpinner();
            Swal.fire({
                title:'Failed',
                text:'Internal Error',
                icon:'danger'
            });
            return;
        }
    });
    
}
function confirmSubmit() {
    showSpinner();
    $('#error_div').hide();
    var baseurl = $("#base").val();
    var formdata = createFormData();
    $.ajax({
        url: baseurl + "index.php/DataController/startProcess",
        method: "POST",
        data: formdata,
        async: true,
        dataType: 'json',

        success: function (data) {
            $('#form_errors').empty();
            hideSpinner();
            if (data.status == '422') {
                alert("Validation-Error, Please validate the form correctly!");
                $('#error_div').show();
                $('#form_errors').append(data.message);
            }
            if(data.status == '200'){
                Swal.fire({
                    title:data.message,
                    html:data.description,
                    icon:'success'
                });
            }
            if(data.status == '105'){
                Swal.fire({
                    title:data.message,
                    icon:'danger'
                });
            }
        },
        error: function (jqXHR, exception) {
            hideSpinner();
            Swal.fire({
                title:'Failed',
                text:'Internal Error',
                icon:'danger'
            });
            return;
        }
    });
    return;
}
function createFormData() {
    var formdata = {};
    var dist_code = $('#dist_code').val();
    var subdiv_code = $('#subdiv_code').val();
    var cir_code = $('#cir_code').val();
    var mouza_pargona_code = $('#mouza_pargona_code').val();
    var lot_no = $('#lot_no').val();
    var vill_townprt_code = $('#vill_townprt_code').val();
    var password = $('#password').val();


    formdata.dist_code = dist_code;
    formdata.subdiv_code = subdiv_code;
    formdata.cir_code = cir_code;
    formdata.mouza_pargona_code = mouza_pargona_code;
    formdata.lot_no = lot_no;
    formdata.vill_townprt_code = vill_townprt_code;
    formdata.password = password;

    return formdata;
}
function hideSpinner() {
    $('#porting_message').show();
    $("#spinner").removeClass('spinner');
    $("#submit_btn").show();
}
function showSpinner() {
    $('#porting_message').hide();
    $('#porting_message').html('');
    $("#spinner").addClass('spinner');
    $("#submit_btn").hide();
}