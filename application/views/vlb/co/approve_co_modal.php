<div class="modal fade" id="approveCoModal" tabindex="-1" aria-labelledby="approveCoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveCoModalLabel">Village Land Bank Details - Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="remark" class="text-right">Approval Remark : <span class="text-danger">*</span> </label>
                            <textarea x-model="co_remark" id="remark" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="approveByCo" class="btn btn-success btn-sm">Submit</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>