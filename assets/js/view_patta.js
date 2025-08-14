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
            $('#patta_type_code').prop('selectedIndex', 0);
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
            $('#patta_type_code').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#patta_type_code').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
})

/**** Get Patta No *******/
$(document).on('change', '#patta_type_code', function (e) {
    e.preventDefault();
    var baseurl = $("#base").val();
    var patta_type = $("#patta_type_code").val();
    var vill_code = $('#v').val();
    var mouza_pargona_code = $('#m').val();
    var lot_no = $('#l').val();


    $.ajax({
        url: baseurl + "index.php/PattaController/getPattaNo",
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