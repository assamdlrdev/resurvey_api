

//added loader on location dropdown by udita on 23-03-2023
$('#d').change(function () {
    var baseurl = $("#base").val();
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/subdivisiondetails",
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
            var html = '';
            var i;
            html += '<option value="">Select Subdivision</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
            }
            $('#sd').html(html);
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
    var dis = $('#d').val();
    var subdiv = $(this).val();
    $.ajax({
        url: baseurl + "index.php/Chithacontrol/circledetails",
        method: "POST",
        data: { dis: dis, subdiv: subdiv },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#c').prop('selectedIndex', 0);
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
                html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
            }
            $('#c').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#c').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
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
        data: { dis: dis, subdiv: subdiv, cir: cir },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#m').prop('selectedIndex', 0);
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
                html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
            }
            $('#m').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#m').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
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
        url: baseurl + "index.php/SvamitvaCardController/getSvamitvaVillages",
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

// 23/11/2022 new code by Masud Reza for SVAMITVA

$('#block').change(function () {
    var baseurl = $("#base").val();
    var id = $(this).val();
    $.ajax({
        url: baseurl + "index.php/SvamitvaCardController/getAllGramPanchayatByBlockCode",
        method: "POST",
        data: { id: id },
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('#gp').prop('selectedIndex', 0);
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
            html += '<option value="">Select Gram Panchayat</option>';
            for (i = 0; i < data.length; i++) {
                html += '<option value=' + data[i].panch_code + '>' + data[i].panch_name + '</option>';
            }
            $('#gb').html(html);
        },
        error: function (jqXHR, exception) {
            $.unblockUI();
            $('#gp').prop('selectedIndex', 0);
            alert('Could not Complete your Request ..!, Please Try Again later..!');
        }
    });
    return false;
});
function checkloc() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/SvamitvaCardController/submitLocation",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/SvamitvaCardController/dagDetails";
                    });
            }
            else {
                swal("", data.msg, "info");

            }
        }
    });
}

function dagentry() {
    var baseurl = $("#base").val();
    Swal.fire({
        title: '',
        text: 'Please confirm to Submit',
        showDenyButton: true,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
    }).then((result) => {
        if (result.isDenied) {

        } else {
            $.ajax({
                dataType: "json",
                url: baseurl + "index.php/SvamitvaCardController/dagEntrySvamitva",
                data: $('form').serialize(),
                type: "POST",
                success: function (data) {
                    if (data.st == 1) {
                        swal("", data.msg, "success")
                            .then((value) => {
                                window.location = baseurl + "index.php/SvamitvaCardController/occupants";
                            });
                    } else {
                        swal("", data.msg, "info");

                    }
                }
            });
        }
    })

}
//new function for occupant(chitha_rmk_encro table) entry to svamitva card by udipta on 23-03-2023
function occupierEntry() {
    var baseurl = $("#base").val();

    Swal.fire({
        title: '',
        text: 'Please confirm to Submit',
        showDenyButton: true,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
    }).then((result) => {
        if (result.isDenied) {

        } else {
            $.ajax({
                dataType: "json",
                url: baseurl + "index.php/SvamitvaCardController/occupierEntry",
                data: $('form').serialize(),
                type: "POST",
                success: function (data) {

                    if (data.st == 1) {
                        Swal.fire({
                            title: '',
                            text: data.msg,
                            showDenyButton: true,
                            showCancelButton: true,
                            cancelButtonText: 'View Occupants',
                            confirmButtonText: 'Add More',
                            denyButtonText: 'Ok',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/SvamitvaCardController/occupierDetails";
                            } else if (result.isDenied) {
                                window.location = baseurl + "index.php/SvamitvaCardController/location";
                            } else {
                                window.location = baseurl + "index.php/SvamitvaCardController/occupants";
                            }
                        })
                    } else {
                        Swal.fire("", data.msg, "warning");
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later'
                    })
                }
            });
        }
    })

}
function occupierUpdate() {
    var baseurl = $("#base").val();
    Swal.fire({
        title: '',
        text: 'Please confirm to Submit',
        showDenyButton: true,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
    }).then((result) => {
        if (result.isDenied) {

        } else {
            $.ajax({
                dataType: "json",
                url: baseurl + "index.php/SvamitvaCardController/occupierUpdate",
                data: $('form').serialize(),
                type: "POST",
                success: function (data) {

                    if (data.st == 1) {
                        Swal.fire({
                            title: '',
                            text: data.msg,
                            showDenyButton: true,
                            confirmButtonText: 'View Occupants',
                            denyButtonText: 'Ok',
                        }).then((result) => {
                            if (result.isDenied) {
                                location.reload();
                            } else {
                                window.location = baseurl + "index.php/SvamitvaCardController/occupants";
                            }
                        })
                    } else {
                        Swal.fire("", data.msg, "info");
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later'
                    })
                }
            });
        }
    })

}
function deleteMember(family_member_id, encro_id) {
    var baseurl = $("#base").val();
    Swal.fire({
        title: '',
        text: 'Please confirm to Delete Member',
        showDenyButton: true,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
    }).then((result) => {
        if (result.isDenied) {

        } else {
            $.ajax({
                dataType: "json",
                url: baseurl + "index.php/SvamitvaCardController/deleteMember",
                data: { 'family_member_id': family_member_id, 'encro_id': encro_id },
                type: "POST",
                success: function (data) {

                    if (data.st == 1) {
                        Swal.fire({
                            title: '',
                            text: data.msg,
                            showDenyButton: false,
                            confirmButtonText: 'Ok',
                            // denyButtonText: 'Ok',
                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        Swal.fire("", data.msg, "info");
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later'
                    })
                }
            });
        }
    })
}
function updateMember() {
    var baseurl = $("#base").val();
    Swal.fire({
        title: '',
        text: 'Please confirm to Update Member Details',
        showDenyButton: true,
        confirmButtonText: 'Confirm',
        denyButtonText: 'Cancel',
    }).then((result) => {
        if (result.isDenied) {

        } else {
            $.ajax({
                dataType: "json",
                url: baseurl + "index.php/SvamitvaCardController/updateMember",
                data: $('#edit_member_form').serialize(),
                type: "POST",
                success: function (data) {

                    if (data.st == 1) {
                        Swal.fire({
                            title: '',
                            text: data.msg,
                            showDenyButton: false,
                            confirmButtonText: 'Ok',
                            // denyButtonText: 'Ok',
                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        Swal.fire("", data.msg, "info");
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later'
                    })
                }
            });
        }
    })
}
function dagamtcal() {
    var dagrev = parseFloat($("#dag_land_revenue").val());
    var ltax = parseFloat(1 / 4 * dagrev);
    $("#dag_local_tax").val(ltax);
}

function gpattadar() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Chithacontrol/pattadarothdag",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            //if (data.st == 1) {
            //alert(data.msg);
            $("#r1").hide('blind');
            $("#r2").hide('blind');
            $("#r2").show('blind');
            $("#r2").html(data.msg);
            //} else {

            //}
        }
    });
}

function patins() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/inserpdarfexitSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                $("#r4").hide('blind');
                $("#r3").show('blind');
                $("#r3").html(data.msg);
            } else {
                //alert(data.msg);
                swal("", data.msg, "info");
            }
        }
    });
}



function pdarentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/pdarEntrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Pattadar ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/pattadarDetailsSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/orderdetSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};

function getpdarentry() {

    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/pdarexentrySvamitva";
}



function gorder() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/orderdetSvamitva";
}

function getocuupant() {
    var baseurl = $("#base").val();
    var occupnm = $("#occp_nm").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Chithacontrol/checkoccup",
        //data: {p1: dcode, p2: scode, p3: ccode, p4: mcode, p5: lcode, p6: vcode, p7: dagcode, p8: pcode, p9: ptype},
        data: { p1: occupnm },
        type: "POST",
        success: function (data) {

            if (data.length != 0) {
                $("#guardian").val(data[0]);
                $("#relation").val(data[1]);
                $("#bigha").val(data[2]);
                $("#katha").val(data[3]);
                $("#lessa").val(data[4]);
                $("#pdarrel").val(data[5]);
                $("#occupantnm").val(data[6]);
            } /*else {
			    $("#guardian").val("");
				$("#relation").val("");
				$("#bigha").val("");
				$("#katha").val("");
				$("#lessa").val("");
                $("#pdarrel").val("");
			}*/
        }
    });

}


$("form[id='pmod'] #orddet").click(function (e) {
    e.preventDefault()
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/orderdetSvamitva";
});

function orderent() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/orderdetentrySvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/ChithaSvamitvaController/occupantSvamitva";
                    });
            } else {
                swal("", data.msg, "info");

            }
        }
    });
}


function occupent() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/occupdetentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to enter another occupant ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/occupantSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/inplaceSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};

function inplacent() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/inplacentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to enter another order ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/orderdetSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/tenantSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};

function pdarexupdate() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/pdarexupdateSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal("", data.msg, "success")
                    .then((value) => {
                        window.location = baseurl + "index.php/ChithaSvamitvaController/pattadarDetModSvamitva";
                    });
            } else {
                swal("", data.msg, "info");

            }
        }
    });

}

function newdagchk() {
    var baseurl = $("#base").val();
    var dist_code = $("#dist_code").val();
    var subdiv_code = $("#subdiv_code").val();
    var cir_code = $("#cir_code").val();
    var mouza_pargona_code = $("#mouza_pargona_code").val();
    var lot_no = $("#lot_no").val();
    var vill_townprt_code = $("#vill_townprt_code").val();
    var dag_no = $("#dag_no").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/SvamitvaCardController/checknewdag",
        data: { 
            'dist_code': dist_code, 
            'subdiv_code': subdiv_code, 
            'cir_code': cir_code, 
            'mouza_pargona_code': mouza_pargona_code, 
            'lot_no': lot_no, 
            'vill_townprt_code': vill_townprt_code, 
            'dag_no': dag_no 
        },
        type: "POST",
        success: function (data) {
            if (data) {
                $("#dag_area_bigha").val(data.dag_area_b);
                $("#dag_area_katha").val(data.dag_area_k);
                $("#dag_area_lessa").val(data.dag_area_lc);
                $("#dag_area_ganda").val(data.dag_area_g);
                $("#dag_land_revenue").val(data.dag_revenue);
                $("#dag_local_tax").val(data.dag_local_tax);
                $("#land_class_code").val(data.land_class_code);
                $("#old_dag_no").val(data.old_dag_no);
                $("#patta_no").val(data.patta_no);
                $("#patta_type_code").val(data.patta_type_code);
                $("#grant_no").val(data.dag_nlrg_no);
                $("#patta_no_old").val(data.old_patta_no);
                $("#zonal_value").val(data.zonal_value);
                $("#revenue_paid").val(data.revenue_paid_upto);

                $("#north_descp").val(data.dag_n_desc);
                $("#south_descp").val(data.dag_s_desc);
                $("#east_descp").val(data.dag_e_desc);
                $("#west_descp").val(data.dag_w_desc);
                $("#dag_no_north").val(data.dag_n_dag_no);
                $("#dag_no_south").val(data.dag_s_dag_no);
                $("#dag_no_east").val(data.dag_e_dag_no);
                $("#dag_no_west").val(data.dag_w_dag_no);
                $("#police_station").val(data.police_station);
                $("#newpatta").val('N');
                var b = data.dag_area_b;
                var k = data.dag_area_k;
                var l = data.dag_area_lc;
                var g = data.dag_area_g;
                if (b == '') b = 0;
                if (k == '') k = 0;
                if (l == '') l = 0;
                if (g == '') g = 0;

                var area_lessa = parseFloat(b) * 100 + parseFloat(k) * 20 + parseFloat(l);
                var area_ganda = parseFloat(b) * 6400 + parseFloat(k) * 320 + parseFloat(l) * 20 + parseFloat(g);

                var area_are = area_lessa * (100 / 747.45);
                var area_are_b = area_ganda * (13.37804 / 6400);

                var total_area_are = parseFloat(area_are).toFixed(5);
                var total_area_are_b = parseFloat(area_are_b).toFixed(5);

                $("#dag_area_are").val(total_area_are);
                $("#dag_area_are_b").val(total_area_are_b);
            }else {
                $("#dag_area_bigha").val('');
                $("#dag_area_katha").val('');
                $("#dag_area_lessa").val('');
                $("#dag_area_ganda").val('');
                $("#dag_land_revenue").val(0);
                $("#dag_local_tax").val(0);
                $("#land_class_code").val('');
                $("#old_dag_no").val('');
                $("#patta_no").val('');
                $("#patta_type_code").val('');
                $("#grant_no").val('');
                $("#patta_no_old").val('');
                $("#zonal_value").val(0);
                $("#revenue_paid").val('');

                $("#north_descp").val('');
                $("#south_descp").val('');
                $("#east_descp").val('');
                $("#west_descp").val('');
                $("#dag_no_north").val('');
                $("#dag_no_south").val('');
                $("#dag_no_east").val('');
                $("#dag_no_west").val('');
                $("#police_station").val('');
                $("#newpatta").val('N');
                $("#dag_area_are").val('');
                $("#dag_area_are_b").val('');
            }
        }
    });

}



function tenantentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/tenantentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Tenant ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/tenantSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/subtenantSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};


function subtenantentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/subtenantentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Subtenant ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/subtenantSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/cropSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};


function cropentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/cropentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Crop ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/cropSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/noncropSvamitva";
                            }
                        });
                    }
                });

            }

            else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }

        }
    });
};


function noncropentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/noncropentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Noncrop ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/noncropSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/fruitSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};



function fruitentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/fruitentrySvamitva",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Fruit ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/fruitSvamitva";
                            }
                            else {
                                window.location = baseurl + "index.php/ChithaSvamitvaController/remarkHomeSvamitva";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                Swal.fire("", data.msg, "info");

            }
        }
    });
};

$("#select_patta_type").change(function (e) {
    var baseurl = $("#base").val();
    var patta_type_code = $(this).val();

    $.ajax({
        url: baseurl + "index.php/ChithaReport/getDags/" + patta_type_code,
        success: function (d) {
            var object = JSON.parse(d);
            var template = "<option disabled selected>Select</option>";
            for (var i = 0; i < object.length; i++) {
                template += "<option value='" + object[i].dag_no_int + "'>" + object[i].dag + "</option>";
            }
            $("select[name='dag_no_lower']").html(template);
            //$("select[name='dag_no_upper']").html(template);
        }
    });
});

$("#selectlw").change(function (e) {
    var baseurl = $("#base").val();
    patta_type_code = $('#select_patta_type').val();
    selectlw = $(this).val();
    //  alert(patta_type_code);
    //alert(selectlw);
    $.ajax({
        url: baseurl + "index.php/ChithaReport/getDagslower/" + selectlw + "/" + patta_type_code,
        success: function (d) {
            var object = JSON.parse(d);
            var template = "";
            for (var i = 0; i < object.length; i++) {
                template += "<option value='" + object[i].dag_no_int + "'>" + object[i].dag + "</option>";
            }
            //$("select[name='dag_no_lower']").html(template);
            $("select[name='dag_no_upper']").html(template);
        }
    });
});

function ordexit() {
    var baseurl = $("#base").val();
    Swal.fire({
        //title: 'Are you sure?',
        text: "Do you want to continue entering dag for the same village ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = baseurl + "index.php/Chithacontrol/dagbasic";
        }
        else {
            window.location = baseurl + "index.php/Chithacontrol/index";
        }
    });
}

function tntentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/tenantSvamitva";
}
function subtentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/subtenantSvamitva";
}
function crpentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/cropSvamitva";
}
function ncrpentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/noncropSvamitva";
}
function frtentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/fruitSvamitva";
}
function rmkentry() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/remarkHomeSvamitva";
}

function edittenant() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/tenanteditSvamitva";
}



function tenantmodfy() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/tenantmodentrySvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                Swal.fire("", data.msg, "success")


            } else {
                Swal.fire("", data.msg, "info");

            }
        }
    });

}


// newly by Masud 11/05/2022

function subTenantList() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/editSubTenantListSvamitva";
}

function subTenantModify() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/updateSubTenantDetailsSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                Swal.fire("", data.msg, "success")


            } else {
                Swal.fire("", data.msg, "info");

            }
        }
    });

}


function cropList() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/editCropListSvamitva";
}

function updateCropDetails() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/updateCropDetailsDataSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                Swal.fire("", data.msg, "success")


            } else {
                Swal.fire("", data.msg, "info");

            }
        }
    });
}


function nonCropList() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/editNonCropListSvamitva";
}

function updateNonCropDetails() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/updateNonCropDetailsDataSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                Swal.fire("", data.msg, "success")

            } else {
                Swal.fire("", data.msg, "info");

            }
        }
    });
}


function fruitList() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/ChithaSvamitvaController/editFruitListSvamitva";
}

function fruitDetailsUpdate() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/ChithaSvamitvaController/updateFruitDetailsDataSvamitva",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                Swal.fire("", data.msg, "success")

            } else {
                Swal.fire("", data.msg, "info");

            }
        }
    });
}




// 22/05/2022 new code by Masud Reza for Jamabandi





