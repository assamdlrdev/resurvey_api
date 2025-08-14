<head>
    <style>
        /* .select2-container{
            width: 467px !important;
        } */
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/select2/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2.min.css') ?>">
    <script rel="stylesheet" src="<?= base_url('assets/select2/select2.min.js') ?>"></script>
    <script rel="stylesheet" src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>
</head>
<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">
        <i class="fas fa-toolbox"></i> Instrument Assigner

        <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#assignInstrumentModal">
            <i class="fas fa-plus"></i> Assign Instrument
        </button>
    </div>
    <div class="row">
        <?php
        if (count($user_instruments)):
            foreach ($user_instruments as $user_instrument):
        ?>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-gradient-navy">
                            <h6>
                                <?= $user_instrument['name'] ?>
                                <!-- <a href="javascript:void(0)" 
                                    data-url="" 
                                    class="delete_team float-right text-danger ml-2">
                                    <i class="fa fa-trash"></i>
                                </a> -->
                                <a href="javascript:void(0)" 
                                    data-url="<?= base_url('index.php/instrument-assingner/' . $user_instrument['id'] . '/update') ?>" 
                                    data-user_code="<?= $user_instrument['user_code'] ?>"
                                    data-serial_no="<?= $user_instrument['serial_no'] ?>"
                                    data-controller_no="<?= $user_instrument['controller_no'] ?>"
                                    data-assigned_date="<?= date('Y-m-d', strtotime($user_instrument['assigned_date'])) ?>"
                                    data-toggle="modal" data-target="#editUserInstrumentModal"
                                    class="edit_user_instrument float-right">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <p>
                                <strong>SERIAL NO: </strong> <?= $user_instrument['serial_no'] ?>
                            </p>
                            <p>
                                <strong>CONTROLLER NO: </strong> <?= $user_instrument['controller_no'] ?>
                            </p>
                            <p>
                                <strong>ASSIGNED DATE: </strong> <?= date('d/m/Y', strtotime($user_instrument['assigned_date'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="assignInstrumentModal" role="dialog" aria-labelledby="assignInstrumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignInstrumentModalLabel">Assign Instrument</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('index.php/instrument-assingner-save') ?>" method="POST" id="assignInstrumentForm">
                    <div class="form-group">
                        <label for="userCodeSelect">Select Surveyor</label>
                        <select class="form-control" name="user_code" id="userCodeSelect">
                            <option value="">Select Surveyor</option>
                            <?php foreach ($surveyors as $surveyor): ?>
                                <option value="<?= $surveyor['username'] ?>"><?= $surveyor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger user_code_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="serial_no">Serial No</label>
                        <input type="text" class="form-control" name="serial_no" id="serial_no" placeholder="Enter Serial No">
                        <span class="error text-danger serial_no_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="controller_no">Controller No</label>
                        <input type="text" class="form-control" name="controller_no" id="controller_no" placeholder="Enter Controller No">
                        <span class="error text-danger controller_no_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="assign_date">Assign Date</label>
                        <input type="date" class="form-control" name="assign_date" id="assign_date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
                        <span class="error text-danger assign_date_error"></span>
                    </div>
                    <button type="submit" class="btn btn-success submit_btn">Assign</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editUserInstrumentModal" role="dialog" aria-labelledby="editUserInstrumentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserInstrumentLabel">Edit Assigned Instrument</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editUserInstrumentForm">
                    <div class="form-group">
                        <label for="edit_userCodeSelect">Select Surveyor</label>
                        <select class="form-control" name="user_code" id="edit_userCodeSelect" disabled>
                            <option value="">Select Surveyor</option>
                            <?php foreach ($surveyors as $surveyor): ?>
                                <option value="<?= $surveyor['username'] ?>"><?= $surveyor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger edit_user_code_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_serial_no">Serial No</label>
                        <input type="text" class="form-control" name="serial_no" id="edit_serial_no" placeholder="Enter Serial No">
                        <span class="error text-danger serial_no_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_controller_no">Controller No</label>
                        <input type="text" class="form-control" name="controller_no" id="edit_controller_no" placeholder="Enter Controller No">
                        <span class="error text-danger edit_controller_no_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_assign_date">Assign Date</label>
                        <input type="date" class="form-control" name="assign_date" id="edit_assign_date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
                        <span class="error text-danger edit_assign_date_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea type="date" class="form-control" name="note" id="edit_note" placeholder="Enter Note"></textarea>
                        <span class="error text-danger edit_note_error"></span>
                    </div>
                    <button type="submit" class="btn btn-success edit_submit_btn">Update</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#userCodeSelect').select2({
            placeholder: "Select Surveyor",
            width: '100%',
        });
        // $('#edit_userCodeSelect').select2({
        //     placeholder: "Select Surveyor",
        //     width: '100%',
        // });

        $('#assignInstrumentForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.submit_btn');
            let actionUrl = $(this).attr('action');
            Swal.fire({
                icon: "warning",
                title: "Make sure all the data about the instrument are correct",
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then((resp) => {
                if(resp.isConfirmed){
                    let btnText = submitBtn.text();
                    submitBtn.text('Please wait...').attr('disabled', true);
                
                    let user_code = $('#userCodeSelect').val();
                    let serial_no = $('#serial_no').val();
                    let controller_no = $('#controller_no').val();
                    let assign_date = $('#assign_date').val();
                    
                    $.ajax({
                        method: 'POST',
                        url: actionUrl,
                        dataType: 'json',
                        data: {
                                    'user_code' : user_code,
                                    'serial_no' : serial_no,
                                    'controller_no' : controller_no,
                                    'assign_date' : assign_date,
                                },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message
                                }).then((response) => {
                                    location.reload(true);
                                });
                            } else {
                                submitBtn.text(btnText).attr('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        },
                        error: function(errors) {
                            submitBtn.text(btnText).attr('disabled', false);
                            let errorData = errors.responseJSON.errors;
                            $.each(errorData, function(index, error){
                                $(`.${index}_error`).text(error);
                            });
                        }
                    });
                }
            });
        });

        $('.edit_user_instrument').on('click', function(){
            let $this = $(this);
            let actionUrl = $this.data('url');
            let userCode = $this.data('user_code');
            let serialNo = $this.data('serial_no');
            let controllerNo = $this.data('controller_no');
            let assignedDate = $this.data('assigned_date');
            
            $('#editUserInstrumentForm').attr('action', actionUrl);
            $('#edit_serial_no').val(serialNo);
            $('#edit_controller_no').val(controllerNo);
            $('#edit_assign_date').val(assignedDate);
            $('#edit_userCodeSelect').val(userCode);

            $('#edit_userCodeSelect').trigger('change');
        });

        $('#editUserInstrumentForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.edit_submit_btn');
            let actionUrl = $(this).attr('action');
            Swal.fire({
                icon: "warning",
                title: "Make sure all the data about the instrument are correct",
                showCancelButton: true,
                confirmButtonText: "Update",
            }).then((resp) => {
                if(resp.isConfirmed){
                    let btnText = submitBtn.text();
                    submitBtn.text('Please wait...').attr('disabled', true);
                
                    let serial_no = $('#edit_serial_no').val();
                    let controller_no = $('#edit_controller_no').val();
                    let assign_date = $('#edit_assign_date').val();
                    let note = $('#edit_note').val();
                    
                    $.ajax({
                        method: 'POST',
                        url: actionUrl,
                        dataType: 'json',
                        data: {
                                    'note' : note,
                                    'serial_no' : serial_no,
                                    'controller_no' : controller_no,
                                    'assign_date' : assign_date,
                                },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message
                                }).then((response) => {
                                    location.reload(true);
                                });
                            } else {
                                submitBtn.text(btnText).attr('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        },
                        error: function(errors) {
                            submitBtn.text(btnText).attr('disabled', false);
                            let errorData = errors.responseJSON.errors;
                            $.each(errorData, function(index, error){
                                $(`.edit_${index}_error`).text(error);
                            });
                        }
                    });
                }
            })
        });

    });
</script>