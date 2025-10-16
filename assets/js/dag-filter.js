

$('#d').change(function () {
    var baseurl = $("#base").val();
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/subdivisiondetails",
        method: "POST",
        data: {id: id},
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#sd').prop('selectedIndex', 0);
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
        },
        success: function (data) {
            updateTable(id,'00','00','00','00');
            var html = '';
            var i;
            html += '<option value="">Select Subdivision</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
            }
            $('#sd').html(html);
            $.unblockUI();
        }
    });
    $.unblockUI();
    return false;
});
$('#sd').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/circledetails",
        method: "POST",
        data: {dis: dis,subdiv:subdiv},
        async: true,
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
            updateTable(dis,subdiv,'00','00','00');
            var html = '';
            var i;
            html += '<option value="">Select Circle</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
            }
            $('#c').html(html);
            $.unblockUI();

        }
    });
    $.unblockUI();
    return false;

});
$('#c').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $('#sd').val();
    var cir = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/mouzadetails",
        method: "POST",
        data: {dis: dis,subdiv:subdiv,cir:cir},
        async: true,
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
            updateTable(dis,subdiv,cir,'00','00');
            var html = '';
            var i;
            html += '<option value="">Select Mouza</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
            }
            $('#m').html(html);
            $.unblockUI();
        }
    });
    $.unblockUI();
    return false;
});
$('#m').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $('#sd').val();
    var cir = $('#c').val();
    var mza = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/lotdetails",
        method: "POST",
        data: {dis: dis,subdiv:subdiv,cir:cir,mza:mza},
        async: true,
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
            updateTable(dis,subdiv,cir,mza,'00');
            var html = '';
            var i;
            html += '<option value="">Select Lot</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
            }
            $('#l').html(html);
            $.unblockUI();

        }
    });
    return false;
    $.unblockUI();

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
        data: {dis: dis,subdiv:subdiv,cir:cir,mza:mza,lot:lot},
        async: true,
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
            updateTable(dis,subdiv,cir,mza,lot);
            $.unblockUI();
        }
    });
    $.unblockUI();
    return false;
});

function updateTable(dis,subdiv,cir,mza,lot)
{
    var baseurl = $("#base").val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/filterTable",
        method: "POST",
        data: { dist_code: dis,subdiv_code: subdiv,cir_code: cir,mouza_pargona_code: mza,lot_no: lot },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            if(data[0]=='0'){
                html = '<td colspan="5" style="text-align:center; font-weight:bold;">No Villages Found</td>'
                $('#count').html('0');
                $("#villages").html(html);
            }else{

                for (i = 0; i < data.length; i++) {
                    html += '<tr class="text-center">';
                    html += '<td>' + data[i].loc_name + '</td>';
                    html += '<td>' + data[i].patta_type + '</td>';
                    html += '<td>' + data[i].patta_no + '</td>';
                    html += '<td>' + data[i].dag_no + '</td>';
                    html += '<td>' + data[i].date_entry + '</td>';
                    html += '</tr>';
                }
                $('#count').html(data.length);
                $('#villages').html(html);
            }
        },
        error: function (jqXHR, exception) {
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
}
