
<div class="col-lg-12 col-md-12">
    <div  class="row justify-content-center">
        <div class="col-lg-12">
            <div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif"></div>
            <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5>LIST OF CHANGE VILLAGE NAME REQUEST</h5>
                        </div>
                    </div>
                    <table class="table table-hover table-sm table-bordered table-stripe">
                        <thead class="bg-warning">
                            <tr class="text-center">
                                <th>Old Village Name</th>
                                <th>New Village Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="villages">
                            <?php foreach ($villages as $value) {?>

                                <tr class="text-center">
                                    <td><?=$value['old_vill_name']?> (<?=$value['old_vill_name_eng']?>)</td>
                                    <td><?=$value['new_vill_name']?> (<?=$value['new_vill_name_eng']?>)</td>
                                    <?php if ($value['status'] === 'D'): ?>
                                        <td>
                                            <small id="status_<?=$value['uuid']?>" class="text-warning">Pending</small>
                                        </td>
                                    <?php else: ?>
                                        <td>
                                            <small class="text-success">Approved</small>
                                        </td>
                                    <?php endif;?>

                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>