<div class="modal fade" id="revertCoModal" tabindex="-1" aria-labelledby="revertCoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="revertCoModalLabel">Village Land Bank Details- Revert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="remark" class="text-right">Revert Remark : <span class="text-danger">*</span> </label>
                            <textarea x-model="revert_remark" id="remark" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="revertByCo" class="btn btn-success btn-sm">Submit</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>