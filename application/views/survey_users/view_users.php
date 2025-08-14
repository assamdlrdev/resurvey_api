
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"> -->
<link rel="stylesheet"
        href="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

<div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header bg-primary">
            <h5>Survey Users</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" id="surveytable">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone No</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user->username; ?></td>
                            <td><?php echo $user->name; ?></td>
                            <td><?php echo $user->role_name; ?></td>
                            <td><?php echo $user->mobile_no; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($user->date_of_creation));  ?></td>
                            <td>
                                <?php if($user->user_role != $this->UserModel::$SURVEY_SUPER_ADMIN_CODE): ?>
                                <a href="<?= base_url('index.php/survey-user/' . urlencode($user->username) . '/edit'); ?>">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->


<script>
    $('#surveytable').DataTable({
        "pagination": true,
        // "pageLength": 10, // Number of records per page
        // "lengthChange": false, // Remove page length dropdown if not needed
        // // other options
    });
</script>