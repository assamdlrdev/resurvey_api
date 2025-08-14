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
    <script src="<?php echo base_url('assets/plugins/ol/ol.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/ol/proj4.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/ol/32646.js') ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/ol/ol.css') ?>">
</head>
<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">
        <i class="fas fa-users"></i> Surveyor Village List for <span class="badge badge-dark"><?= $team->name ?></span>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>District</th>
                <th>Subdivision</th>
                <th>Circle</th>
                <th>Mouza</th>
                <th>Lot</th>
                <th>Village</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(count($surveyor_villages)): 
                    foreach($surveyor_villages as $key => $surveyor_village):
            ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $surveyor_village['dist_name'] ?></td>
                            <td><?= $surveyor_village['subdiv_name'] ?></td>
                            <td><?= $surveyor_village['circle_name'] ?></td>
                            <td><?= $surveyor_village['mouza_name'] ?></td>
                            <td><?= $surveyor_village['lot_name'] ?></td>
                            <td><?= $surveyor_village['village_name'] ?></td>
                            <td>
                                <?php if($can_upload_data): ?>
                                    <button class="btn btn-sm btn-warning upload_daily_progress" 
                                            data-url="<?= base_url('index.php/surveyor-village/' . $surveyor_village['id'] . '/upload-daily-progress') ?>" 
                                            data-previous_progress_url="<?= base_url('index.php/surveyor-village/' . $surveyor_village['id'] . '/get-daily-progresses') ?>" 
                                            data-is_survey_completed="<?= $surveyor_village['is_survey_completed'] ? 1 : 0 ?>" 
                                            data-target="#uploadDailyProgressModal" 
                                            data-toggle="modal">
                                        <?php if($surveyor_village['is_survey_completed']): ?>
                                            View Progress
                                        <?php else: ?>
                                            Upload Daily Progress
                                        <?php endif; ?>
                                    </button>
                                    <?php if($surveyor_village['is_survey_completed'] && $surveyor_village['final_report_uploaded'] == 0): ?>
                                        <?php if(ENABLE_FINAL_UPLOAD): ?>
                                            <button class="btn btn-sm btn-primary final_upload" data-url="<?= base_url('index.php/surveyor-village/' . $surveyor_village['id'] . '/final-upload') ?>"
                                                    data-target="#uploadFinalDataModal" 
                                                    data-toggle="modal">
                                                    Upload Final Data
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-primary" disabled>
                                                Upload Final Data
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success upload_daily_progress"
                                            data-previous_progress_url="<?= base_url('index.php/surveyor-village/' . $surveyor_village['id'] . '/get-daily-progresses') ?>" 
                                            data-target="#uploadDailyProgressModal" 
                                            data-toggle="modal">
                                        View Progress
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
            <?php 
                    endforeach;
                else: 
            ?>
                <tr>
                    <td colspan="8" class="text-center">No villages have been assigned</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadDailyProgressModal" role="dialog" aria-labelledby="createTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTeamModalLabel">Survey Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($can_upload_data): ?>
                    <div class="form_sections card p-2">
                        <form action="" method="POST" id="dailyProgressForm" enctype="multipart/form-data">
                            <h6>Daily Progress</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="shape_file">Surveyed File (.dxf)</label>
                                        <input type="file" class="form-control" name="shape_file" id="shape_file">
                                        <span class="error text-danger shape_file_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="land_parcel_survey">Land Parcel Survey</label>
                                        <input type="text" class="form-control" name="land_parcel_survey" id="land_parcel_survey" placeholder="Enter Land Parcel Survey">
                                        <span class="error text-danger land_parcel_survey_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="area_surveyed">Area Surveyed (in Sq. Mtr.)</label>
                                        <input type="text" class="form-control" name="area_surveyed" id="area_surveyed" placeholder="Enter Area Surveyed (in Sq. Mtr.)">
                                        <span class="error text-danger area_surveyed_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-2">
                                    <button type="submit" class="btn btn-success submit_btn mt-4">Save</button>
                                </div>
                            </div>
                        </form>
                        <form action="" method="POST" id="dailyProgressEditForm" enctype="multipart/form-data">
                            <h6>Edit Daily Progress</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="shape_file">Surveyed File (.dxf)</label>
                                        <input type="file" class="form-control" name="shape_file" id="shape_file">
                                        <span class="error text-danger edit_shape_file_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="land_parcel_survey">Land Parcel Survey</label>
                                        <input type="text" class="form-control" name="land_parcel_survey" id="edit_land_parcel_area" placeholder="Enter Land Parcel Survey">
                                        <span class="error text-danger edit_land_parcel_survey_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="area_surveyed">Area Surveyed (in Sq. Mtr.)</label>
                                        <input type="text" class="form-control" name="area_surveyed" id="edit_area_surveyed" placeholder="Enter Area Surveyed (in Sq. Mtr.)">
                                        <span class="error text-danger edit_area_surveyed_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-2">
                                    <button type="submit" class="btn btn-success submit_btn mt-4">Update</button>
                                    <button type="button" class="btn btn-danger cancel_edit_dly_prgrss mt-4">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="previous_logs"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadFinalDataModal" role="dialog" aria-labelledby="finalUploadDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finalUploadDataModalLabel">Final Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($can_upload_data): ?>
                    <form action="" method="POST" id="finalUploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="final_surveyed_data">Final Surveyed Data (.dxf)</label>
                            <input type="file" class="form-control" name="final_surveyed_data" id="final_surveyed_data">
                            <span class="error text-danger final_surveyed_data_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="field_survey_completion_report"> Field Survey Completion Report (.pdf/.jpeg)</label>
                            <input type="file" class="form-control" name="field_survey_completion_report" id="field_survey_completion_report">
                            <span class="error text-danger field_survey_completion_report_error"></span>
                        </div>
                        <button type="submit" class="btn btn-success final_submit_btn mt-4">Save</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="editTeamModal" role="dialog" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeamModalLabel">Edit Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editTeamForm">
                    <div class="form-group">
                        <label for="edit_team_name">Name</label>
                        <input type="text" class="form-control" name="name" id="edit_team_name" placeholder="Enter Team Name">
                        <span class="error text-danger name_edit_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_supervisorSelect">Select Supervisor</label>
                        <select class="form-control" name="supervisor" id="edit_supervisorSelect">
                            <option value="">Select Supervisor(s)</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?= $supervisor['username'] ?>"><?= $supervisor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger supervisor_edit_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit_surveyorSelect">Select Surveyor(s)</label><br>
                        <select class="form-control" name="surveyor[]" id="edit_surveyorSelect" multiple>
                            <option value="">Select Surveyor(s)</option>
                            <?php foreach ($surveyors as $surveyor): ?>
                                <option value="<?= $surveyor['username'] ?>"><?= $surveyor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger surveyor_edit_error"></span>
                    </div>
                    <button type="submit" class="btn btn-success edit_submit_btn">Update</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

<script>
    $(document).ready(function() {
        $('.upload_daily_progress').click(function(){
            resetDailyProgressFormSection();
            const isSurveyCompleted = $(this).data('is_survey_completed');
            const actionUrl = $(this).data('url');
            const previousProgressUrl = $(this).data('previous_progress_url');

            $('#dailyProgressForm').attr('action', actionUrl);
            if(isSurveyCompleted == 1){
                $('.form_sections').hide();
            }else{
                $('.form_sections').show();
            }
            getPreviousProgress(previousProgressUrl);
        });

        $(document).on('click', '.edit_dly_report', function(){
            const $this = $(this);
            const actionUrl = $this.data('url');
            const landParcelArea = $this.data('land_parcel_area');
            const areaSurveyed = $this.data('area_surveyed');
            $('#dailyProgressEditForm').attr('action', actionUrl);
            $('#edit_land_parcel_area').val(landParcelArea);
            $('#edit_area_surveyed').val(areaSurveyed);
            $('#dailyProgressForm').hide();
            $('#dailyProgressEditForm').show();
        });

        $(document).on('click', '.cancel_edit_dly_prgrss', function(){
            resetDailyProgressFormSection();
        });

        $('#dailyProgressForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.submit_btn', $(this));
            let btnText = submitBtn.text();
            submitBtn.text('Please wait...').attr('disabled', true);
        
            let formData = new FormData(this);

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
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
                    if(errors.responseJSON.message){
                        Swal.fire({
                                icon: 'error',
                                title: errors.responseJSON.message
                            });
                    }
                }
            });
        });
        
        $('#dailyProgressEditForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.submit_btn', $(this));
            let btnText = submitBtn.text();
            submitBtn.text('Please wait...').attr('disabled', true);
        
            let formData = new FormData(this);

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
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
                    if(errors.responseJSON.message){
                        Swal.fire({
                                icon: 'error',
                                title: errors.responseJSON.message
                            });
                    }
                }
            });
        });

        $(document).on('click', '.complete_survey', function() {
            const $this = $(this);
            const actionUrl = $this.data('url');
            const securityCode = Math.floor(100000 + Math.random() * 900000);
            Swal.fire({
                icon: "warning",
                title: "Are you sure?",
                text: "You want to complete this survey!",
                showCancelButton: true,
                confirmButtonText: "Yes, Mark it complete",
            }).then(async (resp) => {
                if(resp.isConfirmed){
                    const { value: enteredCode } = await Swal.fire({
                        title: "Enter the code",
                        input: "text",
                        inputLabel: `Enter "${securityCode}" to complete`,
                        inputPlaceholder: "Enter the code",
                        confirmButtonText: 'Submit',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return "You need to enter the above code";
                            }else if(value != securityCode){
                                return "Invalid code";
                            }
                        }
                        });
                        if (enteredCode) {
                            $.ajax({
                                method: 'POST',
                                url: actionUrl,
                                dataType: 'json',
                                data: {},
                                contentType: 'application/x-www-form-urlencoded',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: response.message
                                        }).then((response) => {
                                            location.reload(true);
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: response.message
                                        });
                                    }
                                },
                                error: function(errors) {
                                    let errorData = errors.responseJSON.errors;
                                    $.each(errorData, function(index, error){
                                        $(`.${index}_error`).text(error);
                                    });
                                }
                            });
                        }
                }
            });
        });

        $('.final_upload').click(function(){
            const actionUrl = $(this).data('url');
            $('#finalUploadForm').attr('action', actionUrl);
        });
            
        $('#finalUploadForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.final_submit_btn');
            let btnText = submitBtn.text();
            submitBtn.text('Please wait...').attr('disabled', true);
        
            let formData = new FormData(this);

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
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
        });

        // $('.edit_team').on('click', function(){
        //     let $this = $(this);
        //     let actionUrl = $this.data('url');
        //     let teamName = $this.data('name');
        //     let memberIds = $this.data('member_ids');
        //     // let memberIds = JSON.parse($this.data('member_ids'));
            
        //     $('#editTeamForm').attr('action', actionUrl);
        //     $('#edit_team_name').val(teamName);
        //     $('#edit_supervisorSelect').val(memberIds);
        //     $('#edit_surveyorSelect').val(memberIds);

        //     $('#edit_surveyorSelect, #edit_supervisorSelect').trigger('change');
        // });

        // $('#editTeamForm').on('submit', function(e) {
        //     e.preventDefault();
        //     $('.error').html('');
        //     let submitBtn = $('.edit_submit_btn');
        //     let btnText = submitBtn.text();
        //     submitBtn.text('Please wait...').attr('disabled', true);
        
        //     let teamName = $('#edit_team_name').val();
        //     let superVisorArr = $('#edit_supervisorSelect').val();
        //     let surveyorsArr = $('#edit_surveyorSelect').val();
            
        //     $.ajax({
        //         method: 'POST',
        //         url: $(this).attr('action'),
        //         dataType: 'json',
        //         data: {
        //                     'name' : teamName,
        //                     'supervisor' : superVisorArr,
        //                     'surveyor' : surveyorsArr,
        //                 },
        //         success: function(response) {
        //             if (response.success) {
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: response.message
        //                 }).then((response) => {
        //                     location.reload(true);
        //                 });
        //             } else {
        //                 submitBtn.text(btnText).attr('disabled', false);
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: response.message
        //                 });
        //             }
        //         },
        //         error: function(errors) {
        //             submitBtn.text(btnText).attr('disabled', false);
        //             let errorData = errors.responseJSON.errors;
        //             $.each(errorData, function(index, error){
        //                 $(`.${index}_edit_error`).text(error);
        //             });
        //         }
        //     });
        // });
    });

    function resetDailyProgressFormSection(){
        $('#dailyProgressForm').show();
        $('#dailyProgressEditForm').hide();
    }

    function getPreviousProgress(previousProgressUrl){
        $('.previous_logs').html('');
        $.ajax({
                method: 'POST',
                url: previousProgressUrl,
                data: {},
                contentType: 'application/x-www-form-urlencoded',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.previous_logs').html(response.html);
                    } else {
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
</script>