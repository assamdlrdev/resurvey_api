
function RemarkHomeEntry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkHomeSubmit",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            data.msg = 'Proceed to next Step';
            if (data.st == 1 && data.rt == 01) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/remarkForm";
                        });
            } else if (data.st == 1 && data.rt == 02) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/LmNoteForm";
                        });
            } else if (data.st == 1 && data.rt == 03) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/SkNoteForm";
                        });
            } else if (data.st == 1 && data.rt == 04) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/EncroacherForm";
                        });
            }  else if (data.st == 1 && data.rt == 06) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/ArchaeologicalHistoryForm";
                        });
            } else {
                swal.fire("", data.msg, "info");

            }
        }
    });
}

function LMnoteentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/LmNoteFormSubmit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering More Notes ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/LmNoteForm";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkHome";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function SKnoteentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/SKNoteFormSubmit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering More Notes ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/SkNoteForm";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkHome";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;


function archHistoricalEntry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/saveArchaeologicalHistoricalData",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                {

                    Swal.fire({
                        //title: 'Are you sure?',
                        text: "Do you want to continue entering More ?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                    window.location = baseurl + "index.php/Remark/ArchaeologicalHistoryForm";
                } else
                    {
                        window.location = baseurl + "index.php/Remark/remarkHome";
                    }
                });
                }
            });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function encroacherentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/EncroacherFormSubmit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering More Details ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/EncroacherForm";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkHome";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function remarkentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkFormsubmit",
        data: $('form').serialize(),
        type: "POST",
        success: function (data) {
            if (data.st == 1) {
                swal.fire("", data.msg, "success")
                        .then((value) => {
                            window.location = baseurl + "index.php/Remark/remarkForm_in_favor_of";
                        });
            } else {
                swal.fire("", data.msg, "info");

            }
        }
    });
}

function infavorentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkForm_in_favor_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Persons ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/remarkForm_in_favor_of";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkForm_along_with";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function infavorentrySkip() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/Remark/remarkForm_along_with";
}

function alongwithentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkForm_along_with_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Persons ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/remarkForm_along_with";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkForm_in_place_of";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;
function alongwithentrySkip() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/Remark/remarkForm_in_place_of";
}
function inplaceofentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkForm_in_place_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Persons ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/remarkForm_in_place_of";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkForm_on_behalf_of";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function inplaceofentrySkip() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/Remark/remarkForm_on_behalf_of";
}
function onbehalfofentry() {
    var baseurl = $("#base").val();
    $.ajax({
        dataType: "json",
        url: baseurl + "index.php/Remark/remarkForm__on_behalf_of_submit",
        data: $('form').serialize(),
        type: 'POST',
        success: function (data) {
            if (data.st == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.msg,
                }).then((result) => {
                    if (result.isConfirmed)
                    {

                        Swal.fire({
                            //title: 'Are you sure?',
                            text: "Do you want to continue entering data for more Persons ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = baseurl + "index.php/Remark/remarkForm_on_behalf_of";
                            } else
                            {
                                window.location = baseurl + "index.php/Remark/remarkHome";
                            }
                        });
                    }
                });

            } else if (data.st == 0) {
                swal.fire("", data.msg, "info");


            }
        }
    });
}
;

function onbehalfofentrySkip() {
    var baseurl = $("#base").val();
    window.location = baseurl + "index.php/Remark/remarkForm";
}

function rmkexit(){
	var baseurl = $("#base").val();	
	Swal.fire({
		//title: 'Are you sure?',
		text: "Do you want to exit ?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes',
		cancelButtonText: 'No'
	  }).then((result) => {
		if (result.isConfirmed) {
		  window.location = baseurl + "index.php/Chithacontrol/index";
		}
		else
		{
		  window.location = baseurl + "index.php/Remark/remarkHome";
		}
	  });
}