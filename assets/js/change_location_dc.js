


$('#ds').change(function () {
    var baseurl = $("#base").val();
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/subdivisiondetails",
        method: "POST",
        data: { id: id },
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
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Subdivision</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
            }
            $('#sd').html(html);
            var html_cr = '<option value="">Select Sub Division First</option>';
            $('#cr').html(html_cr);
            $('#cr').prop('selectedIndex', 0);
            var html_mo = '<option value="">Select Circle First</option>';
            $('#mo').html(html_mo);
            $('#mo').prop('selectedIndex', 0);
            var html_lo = '<option value="">Select Mouza First</option>';
            $('#lo').html(html_lo);
            $('#lo').prop('selectedIndex', 0);
            // 
        },
        complete: function (data) {
            
           },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#sd').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});

$('#sd').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#ds').val();
    var subdiv = $(this).val();
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/circledetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#cr').prop('selectedIndex', 0);
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
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Circle</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
            }
            $('#cr').html(html);
            var html_mo = '<option value="">Select Circle First</option>';
            $('#mo').html(html_mo);
            $('#mo').prop('selectedIndex', 0);
            var html_lo = '<option value="">Select Mouza First</option>';
            $('#lo').html(html_lo);
            $('#lo').prop('selectedIndex', 0);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#cr').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#cr').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#ds').val();
    var subdiv = $('#sd').val();
    console.log(subdiv);
    var cir = $(this).val();
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/mouzadetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv, cir: cir },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#mo').prop('selectedIndex', 0);
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
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Mouza</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
            }
            $('#mo').html(html);
            var html_lo = '<option value="">Select Mouza First</option>';
            $('#lo').html(html_lo);
            $('#lo').prop('selectedIndex', 0);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#mo').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
$('#mo').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#ds').val();
    var subdiv = $('#sd').val();
    var cir = $('#cr').val();
    var mza = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/lotdetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv, cir: cir, mza: mza },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#lo').prop('selectedIndex', 0);
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
            $.unblockUI();
            var html = '';
            var i;
            html += '<option value="">Select Lot</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
            }
            $('#lo').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#lo').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});

$('#lo').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#ds').val();
    var subdiv = $('#sd').val();
    var cir = $('#cr').val();
    var mza = $('#mo').val();
    var lot = $(this).val();
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/filterTable",
        method: "POST",
        data: { dist_code: dis,subdiv_code: subdiv,cir_code: cir,mouza_pargona_code: mza,lot_no: lot },
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
            var html = '';
            var i;
            if(data[0]=='0'){
                console.log("yo");
                html = '<td colspan="4" style="text-align:center; font-weight:bold;">No Villages Found</td>'
                $("#villages").html(html);
            }else{

                for (i = 0; i < data.length; i++) {
                    html += '<tr class="text-center">';
                    html += '<td>' + data[i].old_vill_name + '(' + data[i].old_vill_name_eng +')'+ '</td>';
                    html += '<td>' + data[i].new_vill_name + '(' + data[i].new_vill_name_eng +')'+ '</td>';
                    console.log(data[i].status);
                    if(data[i].status == 'D'){
                        html+='<td><small id="status_'+data[i].uuid+'"' +'class="text-warning">Pending</small></td>';
                        html+='<td><button id="'+data[i].uuid+'" class="btn btn-sm btn-info approve text-white" >Approve</button></td>';
                    }else{
                        html+='<td><small ' +'class="text-success">Approved</small></td>';
                        html+='<td><button disabled class="btn btn-sm btn-success approve text-white" >Approved</button></td>';
                    }
                    html += '</tr>';
                }
                $('#villages').html(html);
            }
            $.unblockUI();
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });

  
});

$(document).on('click', '.approve', function()
{
    var baseurl = $("#base").val();
    var id = $(this).attr('id');
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/villagedetails",
        method: "POST",
        data: { id: id },
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
            $.unblockUI();
            $("#approved").prop('disabled',false);
            $("#d").html(''+data.district+'');
            $("#s").html(data.subdiv);
            $("#c").html(data.cir);
            $("#m").html(data.mouza);
            $("#l").html(data.lot);
            $("#ch").html("<s>"+data.change.old_vill_name+"("+data.change.old_vill_name_eng+")"+"</s> To " +data.change.new_vill_name+"("+data.change.new_vill_name_eng+")")
            $("#uuid").val(id);
            $("#new_vill_name").val(data.change.new_vill_name);
            $("#new_vill_name_eng").val(data.change.new_vill_name_eng);
            $("#vill_modal").modal('show'); 
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
});
$(".bluaay").click(function(){
    var baseurl = $("#base").val();
    var id = $(this).attr('id');
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/villagedetails",
        method: "POST",
        data: { id: id },
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
            $.unblockUI();
            $("#d").html(''+data.district+'');
            $("#s").html(data.subdiv);
            $("#c").html(data.cir);
            $("#m").html(data.mouza);
            $("#l").html(data.lot);
            $("#ch").html("<s>"+data.change.old_vill_name+"("+data.change.old_vill_name_eng+")"+"</s> To " +data.change.new_vill_name+"("+data.change.new_vill_name_eng+")")
            $("#uuid").val(id);
            $("#new_vill_name").val(data.change.new_vill_name);
            $("#new_vill_name_eng").val(data.change.new_vill_name_eng);
            $("#vill_modal").modal('show'); 
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
  });


  


  function updateTable(id,subdiv,cir,mz,lot)
  {
    var baseurl = $("#base").val();
    $.ajax({
        url: baseurl + "index.php/change_village/ChangeVillageDcController/filterTable",
        method: "POST",
        data: { dist_code: id,subdiv_code: subdiv,cir_code: cir,mouza_pargona_code: mz,lot_no: lot },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            if(data[0]=='0'){
                console.log("yo");
                html = '<td colspan="4" style="text-align:center; font-weight:bold;">No Villages Found</td>'
                $("#villages").html(html);
            }else{

                for (i = 0; i < data.length; i++) {
                    html += '<tr class="text-center">';
                    html += '<td>' + data[i].old_vill_name + '(' + data[i].old_vill_name_eng +')'+ '</td>';
                    html += '<td>' + data[i].new_vill_name + '(' + data[i].new_vill_name_eng +')'+ '</td>';
                    console.log(data[i].status);
                    if(data[i].status == 'D'){
                        html+='<td><small id="status_'+data[i].uuid+'"' +'class="text-warning">Pending</small></td>';
                        html+='<td><button id="'+data[i].uuid+'" class="btn btn-sm btn-info approve text-white" >Approve</button></td>';
                    }else{
                        html+='<td><small ' +'class="text-success">Approved</small></td>';
                        html+='<td><button disabled class="btn btn-sm btn-success approve text-white" >Approved</button></td>';
                    }
                    html += '</tr>';
                }
                $('#villages').html(html);
            }
        },
        error: function (jqXHR, exception) {
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
}