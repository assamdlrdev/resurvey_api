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
                <th>Surveyor</th>
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
            if (count($surveyor_villages)):
                foreach ($surveyor_villages as $key => $surveyor_village):
            ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $surveyor_village['name'] ?></td>
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
                <div class="form_sections card p-2">
                    <form action="" method="POST" id="dailyProgressForm" enctype="multipart/form-data">
                        <h6>QAQC Progress</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shape_file">Surveyed File (.dxf, .shp)</label>
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
                                <button type="button" class="btn btn-danger cancel_qaqc_dly_prgrss mt-4">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <form action="" method="POST" id="dailyProgressEditForm" enctype="multipart/form-data">
                        <h6>Edit QAQC Progress</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shape_file">Surveyed File (.dxf, .shp)</label>
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
                                <button type="button" class="btn btn-danger cancel_qaqc_dly_prgrss mt-4">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="previous_logs"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.upload_daily_progress').click(function() {
            const isSurveyCompleted = $(this).data('is_survey_completed');
            const actionUrl = $(this).data('url');
            const previousProgressUrl = $(this).data('previous_progress_url');
            // $('#dailyProgressForm').attr('action', actionUrl);
            // if (isSurveyCompleted == 1) {
            //     $('.form_sections').hide();
            // } else {
            //     $('.form_sections').show();
            // }
            resetDailyProgressFormSection();
            getPreviousProgress(previousProgressUrl);
        });

        $(document).on('click', '.add_qa_qc', function() {
            const actionUrl = $(this).data('url');
            $('.form_sections, #dailyProgressForm').show();
            $('#dailyProgressForm').attr('action', actionUrl);
        });

        $(document).on('click', '.edit_qa_qc', function() {
            const $this = $(this);
            const actionUrl = $this.data('url');
            const landParcelArea = $this.data('land_parcel_area');
            const areaSurveyed = $this.data('area_surveyed');
            $('#dailyProgressEditForm').attr('action', actionUrl);
            $('#edit_land_parcel_area').val(landParcelArea);
            $('#edit_area_surveyed').val(areaSurveyed);
            $('#dailyProgressForm').hide();
            $('#dailyProgressEditForm, .form_sections').show();
        });

        $(document).on('click', '.cancel_qaqc_dly_prgrss', function() {
            resetDailyProgressFormSection();
        });

        $('#dailyProgressForm').on('submit', function(e) {
            e.preventDefault();
            let $this = $(this);
            $('.error').html('');
            let submitBtn = $('.submit_btn', $this);
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
                    $.each(errorData, function(index, error) {
                        $(`.${index}_error`).text(error);
                    });
                    if (errors.responseJSON.message) {
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
                    $.each(errorData, function(index, error) {
                        $(`.edit_${index}_error`).text(error);
                    });
                    if (errors.responseJSON.message) {
                        Swal.fire({
                            icon: 'error',
                            title: errors.responseJSON.message
                        });
                    }
                }
            });
        });

        $(document).on('click', '.complete_gis_qaqc', function() {
            const $this = $(this);
            const actionUrl = $this.data('url');
            const securityCode = Math.floor(100000 + Math.random() * 900000);
            Swal.fire({
                icon: "warning",
                title: "Are you sure?",
                text: "You want to complete this QAQC!",
                showCancelButton: true,
                confirmButtonText: "Yes, Mark it complete",
            }).then(async (resp) => {
                if (resp.isConfirmed) {
                    const {
                        value: enteredCode
                    } = await Swal.fire({
                        title: "Enter the code",
                        input: "text",
                        inputLabel: `Enter "${securityCode}" to complete`,
                        inputPlaceholder: "Enter the code",
                        confirmButtonText: 'Submit',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return "You need to enter the above code";
                            } else if (value != securityCode) {
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
                                $.each(errorData, function(index, error) {
                                    $(`.${index}_error`).text(error);
                                });
                            }
                        });
                    }
                }
            });
        });

    });

    function resetDailyProgressFormSection() {
        $('.form_sections, #dailyProgressForm, #dailyProgressEditForm').hide();
    }

    function getPreviousProgress(previousProgressUrl) {
        $('.previous_logs').html('');
        
        $.ajax({
            method: 'POST',
            url: previousProgressUrl,
            data: {},
            dataType: 'json',
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
                $.each(errorData, function(index, error) {
                    $(`.${index}_error`).text(error);
                });
            }
        });
    }
</script>