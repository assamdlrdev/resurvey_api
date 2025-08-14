function addFk(fks) {
    $.confirm({
        title: 'Please confirm to Add FK',
        type: 'orange',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Confirm!',
                btnClass: 'btn-orange',
                action: function (confirm) {
                    confirmAddFk(fks);
                }
            },
            cancel: function () {

            }
        }
    });
}
function confirmAddFk(fks) {
    $.ajax({
        url: window.base_url + "index.php/common/FkController/addFk",
        method: "POST",
        async: true,
        dataType: 'json',
        data:{
            'fks':fks
        },
        success: function (data) {
            $.confirm({
                title: data.message,
                content: data.description,
                type: 'green',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: 'Ok',
                        btnClass: 'btn-green',
                        action: function () {

                        }
                    }
                }
            });
        },
        error: function (jqXHR, exception) {
            $.confirm({
                title: 'Failed',
                content: 'Internal Error',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: 'Ok',
                        action: function () {

                        }
                    }
                }
            });
            return;
        }
    });
    return;
}
function removeFk(fks) {
    $.confirm({
        title: 'Please confirm to Remove FK',
        type: 'orange',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Confirm!',
                btnClass: 'btn-orange',
                action: function (confirm) {
                    confirmRemoveFk(fks);
                }
            },
            cancel: function () {

            }
        }
    });
}
function confirmRemoveFk(fks) {
    $.ajax({
        url: window.base_url + "index.php/common/FkController/removeFk",
        method: "POST",
        async: true,
        dataType: 'json',
        data:{
            'fks':fks
        },
        success: function (data) {
            $.confirm({
                title: data.message,
                content: data.description,
                type: 'green',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: 'Ok',
                        btnClass: 'btn-green',
                        action: function () {

                        }
                    }
                }
            });
        },
        error: function (jqXHR, exception) {
            $.confirm({
                title: 'Failed',
                content: 'Internal Error',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: 'Ok',
                        action: function () {

                        }
                    }
                }
            });
            return;
        }
    });
    return;
}