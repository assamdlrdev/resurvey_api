<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row mb-1">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'] . '/Dag - ' . $this->session->userdata('dag_no'); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" align="right">
            <a href="<?= base_url('index.php/SvamitvaCardController/occupierDetails') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add New</a>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header rounded-0 text-center bg-info py-1">
            <h5>
                <i class="fa fa-users" aria-hidden="true"></i> OCCUPANTS
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Encro ID</th>
                            <th>Name</th>
                            <th>Guardian Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($occupants as $occupant) : ?>
                            <tr class="bg-light">
                                <td><?php echo ($occupant->encro_id) ?></td>
                                <td><?php echo ($occupant->encro_name) ?></td>
                                <td><?php echo ($occupant->encro_guardian) ?></td>
                                <td>
                                    <a href="<?= base_url('index.php/SvamitvaCardController/editOccupierDetails/' . $occupant->encro_id) ?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                                    <button onclick="deleteOccupant('<?php echo ($occupant->encro_id) ?>')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$occupants) : ?>
                            <tr>
                                <td colspan="4" class="text-center">No occupants added yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>
<script>
    function deleteOccupant(encro_id) {
        var baseurl = "<?php echo ($this->config->item('base_url')); ?>";
        Swal.fire({
            title: '',
            text: 'Please confirm to Delete Occupant',
            showDenyButton: true,
            confirmButtonText: 'Confirm',
            denyButtonText: 'Cancel',
        }).then((result) => {
            if (result.isDenied) {

            } else {
                $.ajax({
                    dataType: "json",
                    url: baseurl + "index.php/SvamitvaCardController/deleteOccupant",
                    data: {
                        'encro_id': encro_id
                    },
                    type: "POST",
                    success: function(data) {
                        if (data.st == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: '',
                                text: data.msg,
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                window.location.reload();
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                text: data.msg,
                            })

                        }
                    }
                });
            }
        })
    }
</script>