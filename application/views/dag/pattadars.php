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
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'] . '/ Patta Type - ' . $locationname['patta_type'] . '/ Patta No - ' . $locationname['patta_no'] . '/ Dag No - ' . $locationname['dag_no']; ?>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> Pattadars
            </h5>
        </div>
        <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
        <div class="card-body">
            <div class="row border border-info p-3">
                <div class="table table-responsive">
                    <form id="dags_form" action="#" method="post" enctype="multipart/form-data">
                        <div class="flex-right mb-2">
                            <a href="<?php echo $base ?>index.php/dag/DagController/showDags/true" class="btn btn-sm btn-info mr-2"><i class="fa fa-arrow-left"></i> Go Back</a>
                            <button type="button" onclick="deleteSelected()" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Check and Delete </button>
                            <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                        </div>
                        <table id="datatable" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Pattadar Name</th>
                                    <th>Pattadar ID</th>
                                    <th>Dag No</th>
                                    <th>Father Name</th>
                                    <th><input type="checkbox" id="check_all" onclick="checkAll()"> All</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pattadars as $pattadar) : ?>
                                    <tr>
                                        <td><?php echo ($pattadar->pdar_name) ?></td>
                                        <td><?php echo ($pattadar->pdar_id) ?></td>
                                        <td><?php echo ($pattadar->dag_no) ?></td>
                                        <td><?php echo ($pattadar->pdar_father) ?></td>
                                        <td><input type="checkbox" value="<?php echo ($pattadar->pdar_id) ?>" name="selected_pattadars[]"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>

<script type="text/javascript">
    function deleteSelected() {
        Swal.fire({
            title: '',
            text: 'Please confirm to DELETE the Pattadar(s)?',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '',
                    text: 'Please Confirm again to DELETE the Pattadar(s)?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var baseurl = $("#base").val();
                        var selected_pattadars = $("input[name='selected_pattadars[]']:checked")
                            .map(function() {
                                return $(this).val();
                            }).get();
                        var tokenName = $('meta[name="csrf-token-name"]').attr('content');
                        var tokenHash = $('meta[name="csrf-token-hash"]').attr('content');
                        var data = {};
                        data[tokenName] = tokenHash;
                        data.selected_pattadars = selected_pattadars;

                        $.ajax({
                            url: baseurl + 'index.php/dag/DagController/deletePattadars',
                            type: 'POST',
                            data: data,
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
        $('#datatable').DataTable({

        });
    });
</script>