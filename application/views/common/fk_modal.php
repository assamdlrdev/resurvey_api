<div class="modal fade" id="fk_modal" tabindex="-1" aria-labelledby="fk_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="fk_modalLabel"><span x-text="fk_modal_title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all" value="1" x-on:change="toggleAllSelect()" x-model="is_all_add"> <label for="">All</label> </th>
                                <th>Foreign Key</th>
                                <th>Table</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($add_queries as $q) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="add_key" x-model="queries_selected" value="<?php echo($q['name']) ?>">
                                    </td>
                                    <td>
                                        <?php echo($q['name']) ?>
                                    </td>
                                    <td>
                                        <?php echo($q['table']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" x-on:click="submitFkEdit" class="btn btn-primary">SUBMIT</button>
            </div>
        </div>
    </div>
</div>