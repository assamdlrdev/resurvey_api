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
        <i class="fas fa-users"></i> Team Management

        <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#createTeamModal">
            <i class="fas fa-plus"></i> Create Team
        </button>
    </div>
    <div class="row">
        <?php
        if (count($teams)):
            foreach ($teams as $team):
                $teammemberIds = [];
                if(isset($team['team_members']) && count($team['team_members'])):
                    foreach ($team['team_members'] as $team_member):
                        array_push($teammemberIds, $team_member['user_code']);
                    endforeach;
                endif;
        ?>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-gradient-navy">
                            <h6>
                                <?= $team['name'] ?>
                                <a href="javascript:void(0)" 
                                    data-url="<?= base_url('index.php/team/'. $team['id'] .'/delete') ?>" 
                                    class="delete_team float-right text-danger ml-2">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a href="javascript:void(0)" 
                                    data-url="<?= base_url('index.php/team/'. $team['id'] .'/update') ?>" 
                                    data-name="<?= $team['name'] ?>" 
                                    data-member_ids='<?= json_encode($teammemberIds); ?>' 
                                    data-toggle="modal" data-target="#editTeamModal"
                                    class="edit_team float-right">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($team['team_members']) && count($team['team_members'])):
                                foreach ($team['team_members'] as $team_member):
                                    if ($team_member['user_role'] == $supervisor_role):
                            ?>
                                        <div class="text-primary text-bold">
                                            <i class="fas fa-user"></i> <?= $team_member['name'] ?>
                                        </div>
                                    <?php
                                    else:
                                    ?>
                                        <div class="text-warning">
                                            <i class="fas fa-user"></i> <?= $team_member['name'] ?>
                                            <a href="<?= base_url('index.php/surveyor-village/' . $team_member['team_id'] . '/' . urlencode($team_member['user_code']) . '/list') ?>" title="Check Progress" class="ml-2 text-warning"><i class="fa fa-eye"></i></a>
                                        </div>
                                    <?php
                                    endif;
                                    ?>

                            <?php
                                endforeach;
                            endif;
                            ?>
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
<div class="modal fade" id="createTeamModal" role="dialog" aria-labelledby="createTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTeamModalLabel">Create Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('index.php/team-save') ?>" method="POST" id="createTeamForm">
                    <div class="form-group">
                        <label for="team_name">Name</label>
                        <input type="text" class="form-control" name="name" id="team_name" placeholder="Enter Team Name">
                        <span class="error text-danger name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="supervisorSelect">Select Supervisor</label>
                        <select class="form-control" name="supervisor" id="supervisorSelect">
                            <option value="">Select Supervisor(s)</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?= $supervisor['username'] ?>"><?= $supervisor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger supervisor_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="surveyorSelect">Select Surveyor(s)</label><br>
                        <select class="form-control" name="surveyor[]" id="surveyorSelect" multiple>
                            <option value="">Select Surveyor(s)</option>
                            <?php foreach ($surveyors as $surveyor): ?>
                                <option value="<?= $surveyor['username'] ?>"><?= $surveyor['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error text-danger surveyor_error"></span>
                    </div>
                    <button type="submit" class="btn btn-success submit_btn">Save changes</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editTeamModal" role="dialog" aria-labelledby="editTeamModalLabel" aria-hidden="true">
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
</div>

<script>
    $(document).ready(function() {
        $('#supervisorSelect').select2({
            placeholder: "Select Surveyor(s)",
            width: '100%',
        });
        $('#edit_supervisorSelect').select2({
            placeholder: "Select Surveyor(s)",
            width: '100%',
        });
        $('#surveyorSelect').select2({
            placeholder: "Select Surveyor(s)",
            width: '100%',
        });
        $('#edit_surveyorSelect').select2({
            placeholder: "Select Surveyor(s)",
            width: '100%',
        });

        $('#createTeamForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.submit_btn');
            let btnText = submitBtn.text();
            submitBtn.text('Please wait...').attr('disabled', true);
        
            let teamName = $('#team_name').val();
            let superVisorArr = $('#supervisorSelect').val();
            let surveyorsArr = $('#surveyorSelect').val();
            
            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: {
                            'name' : teamName,
                            'supervisor' : superVisorArr,
                            'surveyor' : surveyorsArr,
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
        });

        $('.edit_team').on('click', function(){
            let $this = $(this);
            let actionUrl = $this.data('url');
            let teamName = $this.data('name');
            let memberIds = $this.data('member_ids');
            // let memberIds = JSON.parse($this.data('member_ids'));
            
            $('#editTeamForm').attr('action', actionUrl);
            $('#edit_team_name').val(teamName);
            $('#edit_supervisorSelect').val(memberIds);
            $('#edit_surveyorSelect').val(memberIds);

            $('#edit_surveyorSelect, #edit_supervisorSelect').trigger('change');
        });

        $('#editTeamForm').on('submit', function(e) {
            e.preventDefault();
            $('.error').html('');
            let submitBtn = $('.edit_submit_btn');
            let btnText = submitBtn.text();
            submitBtn.text('Please wait...').attr('disabled', true);
        
            let teamName = $('#edit_team_name').val();
            let superVisorArr = $('#edit_supervisorSelect').val();
            let surveyorsArr = $('#edit_surveyorSelect').val();
            
            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: {
                            'name' : teamName,
                            'supervisor' : superVisorArr,
                            'surveyor' : surveyorsArr,
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
                        $(`.${index}_edit_error`).text(error);
                    });
                }
            });
        });

        $('.delete_team').on('click', function(){
            if(confirm('Are you sure? You want to delete!')){
                $.ajax({
                        method: 'POST',
                        url: $(this).data('url'),
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
                            submitBtn.text(btnText).attr('disabled', false);
                            let errorData = errors.responseJSON.errors;
                            $.each(errorData, function(index, error){
                                $(`.${index}_edit_error`).text(error);
                            });
                        }
                    });
            }
        });
    });
</script>