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
        <i class="fas fa-users"></i> Village List
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
                                <button class="btn btn-sm btn-success upload_daily_progress"
                                        data-previous_progress_url="<?= base_url('index.php/surveyor-village/' . $surveyor_village['id'] . '/get-daily-progresses') ?>" 
                                        data-target="#uploadDailyProgressModal" 
                                        data-toggle="modal">
                                    View Progress
                                </button>
                                <?php if(!$surveyor_village['surveyor_village_revert_id']): ?>
                                    <button class="btn btn-warning btn-sm">Approve</button>
                                    <button class="btn btn-danger btn-sm revert_survey" data-url="<?= base_url('index.php/qa_qc/survey-village/' . $surveyor_village['id'] . '/revert') ?>" data-target="#revertSurveyModal" data-toggle="modal">Revert</button>
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
                <div class="previous_logs"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="revertSurveyModal" role="dialog" aria-labelledby="revertSurveyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revertSurveyModalLabel">Revert To Supervisor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="revertForm" method="POST">
                    <div class="form-group">
                        <label for="">Reason</label>
                        <textarea name="reason" class="form-control" placeholder="Enter the reason"></textarea>
                        <span class="error reason_error text-danger"></span>
                    </div>
                    <button class="btn btn-success submit_btn" type="submit">Save</button>
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
        $('.upload_daily_progress').click(function(){
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

        // $('#dailyProgressForm').on('submit', function(e) {
        //     e.preventDefault();
        //     $('.error').html('');
        //     let submitBtn = $('.submit_btn');
        //     let btnText = submitBtn.text();
        //     submitBtn.text('Please wait...').attr('disabled', true);
        
        //     let formData = new FormData(this);

        //     $.ajax({
        //         method: 'POST',
        //         url: $(this).attr('action'),
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         dataType: 'json',
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
        //                 $(`.${index}_error`).text(error);
        //             });
        //         }
        //     });
        // });

        // $(document).on('click', '.complete_survey', function() {
        //     const $this = $(this);
        //     const actionUrl = $this.data('url');
        //     Swal.fire({
        //         icon: "warning",
        //         title: "Are you sure?",
        //         text: "You want to complete this survey!",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, Mark it complete",
        //     }).then((resp) => {
        //         if(resp.isConfirmed){
        //             $.ajax({
        //                 method: 'POST',
        //                 url: actionUrl,
        //                 dataType: 'json',
        //                 data: {},
        //                 success: function(response) {
        //                     if (response.success) {
        //                         Swal.fire({
        //                             icon: 'success',
        //                             title: response.message
        //                         }).then((response) => {
        //                             location.reload(true);
        //                         });
        //                     } else {
        //                         Swal.fire({
        //                             icon: 'error',
        //                             title: response.message
        //                         });
        //                     }
        //                 },
        //                 error: function(errors) {
        //                     let errorData = errors.responseJSON.errors;
        //                     $.each(errorData, function(index, error){
        //                         $(`.${index}_error`).text(error);
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });

        $(document).on('click', '.revert_survey', function(){
            const actionUrl = $(this).data('url');
            $('#revertForm').attr('action', actionUrl);
        });

        $('.final_upload').click(function(){
            const actionUrl = $(this).data('url');
            $('#finalUploadForm').attr('action', actionUrl);
        });
            
        $('#revertForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.submit_btn');
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

    function getPreviousProgress(previousProgressUrl){
        $('.previous_logs').html('');
        $.ajax({
                method: 'POST',
                url: previousProgressUrl,
                data: {},
                contentType: 'application/x-www-form-urlencoded',
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