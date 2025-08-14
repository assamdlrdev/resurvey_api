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
        <i class="fas fa-users"></i> Supervisor Re-assign
    </div>
    <div>
        <div class="card p-2">
            <form action="">
                <div class="row m-2">
                    <div class="col-md-5 p-3 card form-group supervisor_wrap">
                        <label for="from_supervisor">From Supervisor</label>
                        <select name="from_supervisor" id="from_supervisor" class="form-control supervisor">
                            <option value="">Select Supervisor</option>
                            <?php 
                                if(count($supervisors)): 
                                    foreach($supervisors as $supervisor):
                            ?>
                                        <option value="<?= $supervisor['username'] ?>"><?= $supervisor['name'] ?></option>
                            <?php 
                                    endforeach; 
                                endif; 
                            ?>
                        </select>
                        <div class="error text-danger from_supervisor_error"></div>
                        <div class="linked_modules mt-3"></div>
                    </div>
                    <div class="col-md-1 text-lg text-blue">
                        <i class="fa fa-arrow-right m-4 pt-3"></i>
                    </div>
                    <div class="col-md-5 p-3 card form-group supervisor_wrap">
                        <label for="to_supervisor">To Supervisor</label>
                        <select name="to_supervisor" id="to_supervisor" class="form-control supervisor">
                            <option value="">Select Supervisor</option>
                        </select>
                        <div class="error text-danger to_supervisor_error"></div>
                        <div class="linked_modules mt-3 to_sup_linked_modules"></div>
                    </div>
                </div>
                <button class="btn btn-success float-right">Reassign</button>
            </form>
        </div>
    </div>
</div>


<script>
    const GET_ASSIGNED_MODULE_URL = "<?= base_url('index.php/re-assign/supervisor/get-assigned-module'); ?>"
    $(document).ready(function() {
        $('#from_supervisor').on('change', function(){
            $('.linked_modules').html('');
            const $this = $(this);
            const supervisorWrap = $this.closest('.supervisor_wrap');
            const fromSupervisor = $this.val();
            $('#to_supervisor').html(`<option value="">Select Supervisor</option>`);
            if(fromSupervisor != ''){
                let formData = {
                    supervisor: fromSupervisor,
                    need_to_supervisors: 'Yes'
                };
                
                getAssignedModules(formData, supervisorWrap);
            }
        });
        
        $('#to_supervisor').on('change', function(){
            $('.to_sup_linked_modules').html('');
            const $this = $(this);
            const supervisorWrap = $this.closest('.supervisor_wrap');
            const toSupervisor = $this.val();
            if(toSupervisor != ''){
                let formData = {
                    supervisor: toSupervisor,
                };
                
                getAssignedModules(formData, supervisorWrap);
            }
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

        $(document).on('click', '.complete_survey', function() {
            const $this = $(this);
            const actionUrl = $this.data('url');
            Swal.fire({
                icon: "warning",
                title: "Are you sure?",
                text: "You want to complete this survey!",
                showCancelButton: true,
                confirmButtonText: "Yes, Mark it complete",
            }).then((resp) => {
                if (resp.isConfirmed) {
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
            });
        });

    });

    function getAssignedModules(formData, supervisorWrap){
        $.ajax({
                method: 'POST',
                url: GET_ASSIGNED_MODULE_URL,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // console.log(response);
                        if(formData.need_to_supervisors != undefined && formData.need_to_supervisors == 'Yes'){
                            const supervisors = response.supervisors;
                            let supOptions = `<option value="">Select Supervisor</option>`;
                            $.each(supervisors, function(index, supervisor){
                                supOptions += `<option value="${supervisor.username}">${supervisor.name}</option>`;
                            });
                            $('#to_supervisor').html(supOptions);
                        }

                        const assignedCircles = response.circles;
                        let circleHtml = `<div class="card">
                                            <div class="card-header">
                                                Assigned Circle(s)
                                            </div>
                                            <div class="card-body">
                                        `;
                            if(assignedCircles.length > 0){
                                $.each(assignedCircles, function(index, assignedCircle){
                                    circleHtml += `<strong class="badge badge-pill badge-warning m-1 p-2">${assignedCircle.circle}</strong>`;
                                });
                            }else{
                                circleHtml += `<strong class="text-muted">No circle found</strong>`;
                            }
                            circleHtml += `</div></div>`;

                        const assignedTeams = response.teams;
                        let teamHtml = `<div class="card">
                                            <div class="card-header">
                                                Associated Team(s)
                                            </div>
                                            <div class="card-body">
                                        `;
                            if(assignedTeams.length > 0){
                                $.each(assignedTeams, function(index, assignedTeam){
                                    teamHtml += `<strong class="badge badge-pill badge-primary m-1 p-2">${assignedTeam.name}</strong>`;
                                });
                            }else{
                                teamHtml += `<strong class="text-muted">No team found</strong>`;
                            }
                            teamHtml += `</div></div>`;

                        $('.linked_modules', supervisorWrap).append(circleHtml);
                        $('.linked_modules', supervisorWrap).append(teamHtml);
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
                    if (errors.responseJSON.message) {
                        Swal.fire({
                            icon: 'error',
                            title: errors.responseJSON.message
                        });
                    }
                }
            });
    }
</script>