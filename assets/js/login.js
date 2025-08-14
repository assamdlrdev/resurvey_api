
function hashWithSHA256(form) {
    console.log(form);
    var pwd = $("#password").val();
    var v1 = $.sha1(pwd);
    var salt = $("#salt").val();
    var salt_pwd = salt + v1;
    var hashedpwd = $.sha1(salt_pwd);
    $("#hashedPassword").val(hashedpwd);
    $("#password").val("");
    $("#salt").val("*******");

}
function logincheck(form) {
    hashWithSHA256(form);
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Login/LoginSubmit",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1 && data.ut == 00) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/SvamitvaCardController/location";
                    });
            } else if (data.st == 1 && data.ut == 01) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/Login/adminindex";
                    });
            }
            else if (data.st == 1 && data.ut == 02) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/Login/superadminindex";
                    });
            } else if (data.st == 1 && (data.ut == 3 || data.ut == 4 || data.ut == 5)) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/Login/dashboard";
                    });
            } else if (data.st == 1 && data.ut == 9) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/reports/DagReportController/index";
                    });
            }
            else if(data.st == 1 && data.ut == '13') {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        // window.location = baseurl + "index.php/Login/usercreationIndex";
                        window.location = baseurl + "index.php/survey/home";
                    });
            }else if(data.st == 1 && data.ut == '10'){
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        // window.location = baseurl + "index.php/Login/supervisorIndex";
                        window.location = baseurl + "index.php/survey/home";
                    });
            }else if(data.st == 1 && data.ut == '11'){
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        // window.location = baseurl + "index.php/Login/surveyorIndex";
                        window.location = baseurl + "index.php/survey/home";
                    });
            }else if(data.st == 1 && data.ut == '12'){
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        // window.location = baseurl + "index.php/Login/surveyorIndex";
                        window.location = baseurl + "index.php/survey/home";
                    });
            }else if(data.st == 1 && data.ut == '14'){
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/survey/home";
                    });
            }
            else {
                swal.fire("", data.msg, "info")
                    .then((value) => {
                        window.location = baseurl + "index.php/Login/index";
                    });
            }
        }
    });
}

function userDetailsSubmit() {
    var baseurl = $("#base").val();
    var user_code = $('#user_code').val();
    if(user_code != '') {
        $.ajax({
            dataType: "json",
            url: baseurl + "index.php/Login/AdminLoginSubmit",
            data: $('form').serialize(),
            type: "POST",
            success: function (data) {
                if (data.st == 1) {
                    swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Login/adminindex";
                        });
                } else {
                    swal.fire("", data.msg, "info");
    
                }
            }
        });
    }
}
function superadminuserDetailsSubmit() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Login/SuperAdminLoginSubmit",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal.fire("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/Login/superadminindex";
                    });
            } else {
                swal.fire("", data.msg, "info");

            }
        }
    });
}
$('#d').change(function () {
    var baseurl = $("#base").val();
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Login/subdivisiondetailsall",
        method: "POST",
        data: { id: id },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Subdivision</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
            }
            $('#sd').html(html);
        }
    });
    return false;
});
$('#sd').change(function () {
    var baseurl = $("#base").val();
    var dis = $('#d').val();
    var subdiv = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Login/circledetailsall",
        method: "POST",
        data: { dis: dis, subdiv: subdiv },
        async: true,
        dataType: 'json',
        success: function (data) {
            var html = '';
            var i;
            html += '<option value="">Select Circle</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
            }
            $('#c').html(html);
        }
    });
    return false;
});

