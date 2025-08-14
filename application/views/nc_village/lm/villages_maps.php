<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'villages': [],
            'base_url': "<?= base_url(); ?>",
            'is_loading': false,
            init() {
                var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
                var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

                $(document).ajaxSend(function(event, jqxhr, settings) {
                    if (settings.type.toLowerCase() === "post") {
                        if (settings.data && typeof settings.data === 'string') {
                            settings.data += '&' + csrfName + '=' + csrfHash;
                        } else {
                            settings.data = csrfName + '=' + csrfHash;
                        }
                    }
                });
                this.villages = JSON.parse('<?= $maps ?>');
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <h5> MAPS FORWARDED FROM CO
                                <small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
                            </h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-bordered table-stripe">
                            <thead class="bg-warning">
                                <tr class="text-sm">
                                    <th>Sl</th>
                                    <th>Village Name</th>
                                    <th>Merging Type</th>
                                    <th>Merge Villages Name</th>
                                    <th>Cadastral village name into which the non-cadastral village will be merged</th>
                                    <th>Lot</th>
                                    <th>Mouza</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr x-show="is_loading">
                                    <td colspan="5" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <template x-for="(village,index) in villages">
                                    <tr class="text-sm">
                                        <td x-text="index + 1"></td>
                                        <td x-text="village.village_name"></td>
                                        <td>
                                            <template x-if="village.case_type === 'NC_TO_NC'">
                                                <span>NC to NC</span>
                                            </template>
                                            <template x-if="village.case_type === 'NC_TO_C'">
                                                <span>NC to Cadastral</span>
                                            </template>
                                            <template x-if="village.case_type === 'STAND_ALONE'">
                                                <span>N/A</span>
                                            </template>
                                        </td>
                                        <td>
                                            <template x-if="village.case_type=='NC_TO_NC'">
                                                <span x-text="village.has_merge_village_request ? village.requested_merged_villages_name : 'N.A.'"></span>
                                            </template>
                                            <template x-if="village.case_type!='NC_TO_NC'">
                                                <span>N.A.</span>
                                            </template>
                                        </td>
                                        <td>
                                            <template x-if="village.case_type=='NC_TO_C'">
                                                <span x-text="village.has_merge_village_request ? village.requested_merged_villages_name : 'N.A.'"></span>
                                            </template>
                                            <template x-if="village.case_type!='NC_TO_C'">
                                                <span>N.A.</span>
                                            </template>
                                        </td>
                                        <td x-text="village.lot_name"></td>
                                        <td x-text="village.mouza_name"></td>
                                        <td>
                                            <a x-bind:href="village.proceed_url" class="btn btn-sm btn-info text-white no-wrap">Verify <i class="fa fa-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="villages.length == 0">
                                    <td colspan="5" class="text-center">
                                        <span>No Pending Maps Found</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>