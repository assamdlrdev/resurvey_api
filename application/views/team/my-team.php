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
        <i class="fas fa-users"></i> My Teams
    </div>
    <div class="row">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Team Name</th>
                    <th>Members</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($teams)):
                    foreach ($teams as $key => $team):
                ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $team['name'] ?></td>
                        <td>
                            <?php
                                if(isset($team['team_members']) && count($team['team_members'])):
                                    foreach($team['team_members'] as $team_member):
                            ?>
                                        <span class="badge <?= $team_member['user_role'] == $supervisor_role ? 'badge-warning' : 'badge-dark' ?> p-2">
                                            <i class="fa fa-user"></i>
                                            <?= $team_member['name']; ?> 
                                            <?php if($can_check_progress): ?>
                                                <?php if($team_member['user_role'] != $supervisor_role): ?>
                                                    <a href="<?= base_url('index.php/surveyor-village/' . $team_member['team_id'] . '/' . urlencode($team_member['user_code']) . '/list') ?>" title="Check Progress" class="ml-2 text-warning"><i class="fa fa-eye"></i></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($auth_user_code == $team_member['user_code']): ?>
                                                    <a href="<?= base_url('index.php/surveyor-village/' . $team_member['team_id'] . '/' . urlencode($team_member['user_code']) . '/list') ?>" title="Upload Report" class="ml-2 text-warning"><i class="fa fa-upload"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </span>
                                        
                            <?php
                                    endforeach;
                                endif;
                            ?>
                        </td>
                    </tr>
                <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="3">No team found</td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        
    });
</script>