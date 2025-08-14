<style>
    .flex-right {
        display: flex;
        justify-content: end;
        align-items: center;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Delete Dag no and view Pattadar
            </h5>
        </div>
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <div class="card-body">
            <strong class="text-danger">Check the Dag no and click on 'Check and Delete' button to delete a Dag. <br>To delete only Pattadar
                click on ' Pattadars ' button.</strong>
            <div class="row border border-info p-3">
                <div class="col-md-12">
                    <input type="checkbox" id="check_all" onclick="checkAll()"> Select All
                    <button type="button" onclick="deleteSelected()" class="btn btn-sm btn-danger">Delete Selected</button>

                </div>
                <div class="table table-responsive">
                    <form id="dags_form" action="" method="post" enctype="multipart/form-data">
                        <div class="flex-right mb-2">
                            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        </div>
                        <table id="datatable" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Select
                                    </th>
                                    <th>Dag No</th>
                                    <th>Patta No</th>
                                    <th>Patta Type</th>
                                    <th>Pattadars</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
<script type="text/javascript">
    function deleteSelected() {
        Swal.fire({
            title: '',
            text: 'Please confirm to DELETE the Dag(s)?',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '',
                    text: 'Please CONFIRM again to DELETE the Dag(s)?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var baseurl = $("#base").val();
                        var selected_dags = $("input[name='selected_dags[]']:checked")
                            .map(function() {
                                return $(this).val();
                            }).get();
                        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
                        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
                        var data = {};
                        data[tokenName] = tokenHash;
                        data.selected_dags = selected_dags;
                        $.ajax({
                            url: baseurl + 'index.php/dag/DagController/deleteDags',
                            type: 'POST',
                            data:data,
                            beforeSend: function() {
                                $.blockUI({
                                    message: $('#displayBox'),
                                    css: {
                                        border: 'none',
                                        backgroundColor: 'transparent'
                                    }
                                });
                            },
                            error: function() {
                                $.unblockUI();
                                Swal.fire("", 'Something is wrong.', "error");
                            },
                            success: function(data) {
                                $.unblockUI();
                                var data = JSON.parse(data);
                                if (data.status == 'success') {
                                    Swal.fire({
                                        title: '',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        window.location.reload();
                                    })
                                } else {
                                    Swal.fire("", data.message, "error");
                                }
                            }
                        });
                    }
                })
            }
        })
    }

    function checkAll() {
        if ($('#check_all').is(":checked")) {
            $('input:checkbox').prop('checked', true);
        } else {
            $('input:checkbox').prop('checked', false);
        }
    }
    $(document).ready(function() {
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

        $('#datatable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            "ordering": true,
            'ajax': {
                'url': '<?= base_url() ?>index.php/dag/DagController/getDags',
                "data": function(d) {
                    d[csrfName] = csrfHash;
                },
            },
            'columns': [{
                    data: 'checkinput'
                }, {
                    data: 'dag_no'
                },
                {
                    data: 'patta_no'
                },
                {
                    data: 'patta_type'
                },
                {
                    data: 'pattadar_view_btn'
                }
            ]
        });
    });
</script>