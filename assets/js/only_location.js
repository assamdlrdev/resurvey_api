$('#dist_code').change(function () {
    var baseurl = window.base_url;
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/common/LocationController/getSubdivs",
        method: "POST",
        data: { id: id },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Subdivision</option>';
            if (html) {
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
                }
            }
            $('#subdiv_code').html(html);
        }
    });
    return false;
});
$('#subdiv_code').change(function () {
    var baseurl = window.base_url;
    var dist_code = $('#dist_code').val();
    var subdiv_code = $(this).val();
    $.ajax({
        url: baseurl + "index.php/common/LocationController/getCircles",
        method: "POST",
        data: { dist_code: dist_code, subdiv_code: subdiv_code },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Circle</option>';
            if (html) {
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
                }
            }
            $('#cir_code').html(html);
        }
    });
    return false;
});
$('#cir_code').change(function () {
    var baseurl = window.base_url;
    var dist_code = $('#dist_code').val();
    var subdiv_code = $('#subdiv_code').val();;
    var cir_code = $(this).val();

    $.ajax({
        url: baseurl + "index.php/common/LocationController/getMouzas",
        method: "POST",
        data: { dist_code: dist_code, subdiv_code: subdiv_code, cir_code: cir_code },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Mouza</option>';
            if (html) {
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
                }
            }
            $('#mouza_pargona_code').html(html);
        }
    });
    return false;
});
$('#mouza_pargona_code').change(function () {
    var baseurl = window.base_url;
    var dist_code = $('#dist_code').val();
    var subdiv_code = $('#subdiv_code').val();;
    var cir_code = $('#cir_code').val();
    var mouza_pargona_code = $(this).val();
    $.ajax({
        url: baseurl + "index.php/common/LocationController/getLots",
        method: "POST",
        data: { dist_code: dist_code, subdiv_code: subdiv_code, cir_code: cir_code, mouza_pargona_code: mouza_pargona_code },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Lot</option>';
            if (html) {
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
                }
            }
            $('#lot_no').html(html);
        }
    });
    return false;
});
$('#lot_no').change(function () {
    var baseurl = window.base_url;
    var dist_code = $('#dist_code').val();
    var subdiv_code = $('#subdiv_code').val();;
    var cir_code = $('#cir_code').val();
    var mouza_pargona_code = $('#mouza_pargona_code').val();
    var lot_no = $(this).val();
    $.ajax({
        url: baseurl + "index.php/common/LocationController/getVillages",
        method: "POST",
        data: { dist_code: dist_code, subdiv_code: subdiv_code, cir_code: cir_code, mouza_pargona_code: mouza_pargona_code, lot_no: lot_no },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Village</option>';
            if (html) {
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name + '</option>';
                }
            }
            $('#vill_townprt_code').html(html);
        }
    });
    return false;
});