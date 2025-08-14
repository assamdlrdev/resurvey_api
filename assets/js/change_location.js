
$(document).on('click', '.change', function () {
    $("#vill_modal").modal('show');

});

$('#new_vill_name').change(function () {
    var vil = $('#new_vill_name').val();
    var vil_eng = $('#new_vill_name_eng').val();
    var o_vil = $('#old_vill_name').val();
    $('#templateTA').val('The survey of village "' + o_vil + '" is done and its new updated name will be "' + vil + '" (' + vil_eng + ')  as per government OM No.ECF.213444/2022/ Dated Dispur, the 03-01-2024.');
});
$('#new_vill_name_eng').change(function () {
    var vil = $('#new_vill_name').val();
    var vil_eng = $('#new_vill_name_eng').val();
    var o_vil = $('#old_vill_name').val();
    $('#templateTA').val('The survey of village "' + o_vil + '" is done and its new updated name will be "' + vil + '" (' + vil_eng + ')  as per government OM No.ECF.213444/2022/ Dated Dispur, the 03-01-2024.');
});


function checkloc() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: 'json',
        url: baseurl + "index.php/change_village/ChangeVillageCoController/changeVillageSubmit",
        data: {
            'dist_code': $('#dist').val(),
            'subdiv_code': $('#sub').val(),
            'cir_code': $('#cr').val(),
            'mouza_pargona_code': $('#mouza').val(),
            'lot_no': $('#ltNo').val(),
            'vill_uuid': $('#vuuid').val(),
            'old_vill_name': $('#old_vill_name').val(),
            'old_vill_name_eng': $('#vill_name_engg').val(),
            'new_vill_name': $('#new_vill_name').val(),
            'new_vill_name_eng': $('#new_vill_name_eng').val(),
            'application_no': $('#application_no').val(),
        },
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal("", data.msg, "success")
                    .then((value) => {
                        window.location.reload();
                    });
            } else {
                swal("", data.msg, "info");

            }
        }
    });
}
