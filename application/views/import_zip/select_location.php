<div class="col-md-12 col-lg-12" x-data="alpineData()">
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-3">
            <div class="d-flex flex-row-reverse align-items-center">
                <div>
                    <button class="btn btn-sm btn-success actn_btn" title="Add  Foreign Key" data-toggle="modal" data-target="#fk_modal" x-on:click="openModal('add')"><i class="fa fa-plus"></i> ADD FK</button>
                    <button class="btn btn-sm btn-danger actn_btn" title="Remove  Foreign Key" data-toggle="modal" data-target="#fk_modal" x-on:click="openModal('remove')"><i class="fa fa-trash"></i> REMOVE FK</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card rounded-0">
                <div class="card-header rounded-0 text-center bg-info py-1">
                    <h5>
                        <i class="fa fa-users" aria-hidden="true"></i> Import Village Data From Zip
                    </h5>
                </div>
                <div class="card-body col-md-6 col-lg-6">
                    <form id="form" action="<?php echo base_url() ?>index.php/zip_table/ImportZipController/ImportZip" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleInputFile">Select Zip</label>
                            <input name="zip" type="file" accept=".zip" class="form-control form-control-sm" id="exampleInputFile">
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                            <button :disabled="is_loading" type="button" @click="submitForm()" class="btn btn-sm btn-info">Submit</button>
                        </div>
                    </form>
                    <?php if ($_SESSION['success']) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['success'] ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['error']) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include(APPPATH . 'views/common/fk_modal.php'); ?>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script defer src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/fk.js') ?>"></script>

<script>
    function alpineData() {
        return {
            'transfer': 'village',
            'fk_edit_mode': '',
            'queries_selected': [],
            'queries_all': [],
            'is_all_add': false,
            'is_loading': false,
            'fks': <?php echo (ALL_FKS); ?>,
            init() {
                var fks = '<?= json_encode($fks); ?>';
                this.fks = JSON.parse(fks);
            },
            toggleAllSelect() {
                if (this.is_all_add) {
                    this.queries_selected = this.fks;
                } else {
                    this.queries_selected = [];
                }
            },
            openModal(type) {
                this.fk_edit_mode = type;
            },
            get fk_modal_title() {
                if (this.fk_edit_mode == 'add') {
                    return 'Add Foreign Key';
                }
                if (this.fk_edit_mode == 'remove') {
                    return 'Remove Foreign Key';
                }
            },
            submitFkEdit() {
                $('#fk_modal').modal('hide');
                if (this.fk_edit_mode == 'add') {
                    addFk(this.queries_selected);
                }
                if (this.fk_edit_mode == 'remove') {
                    removeFk(this.queries_selected)
                }
            },
            submitForm() {
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: '',
                    content: 'Please Confirm to Submit',
                    type: 'green',
                    buttons: {
                        confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function(confirm) {
                                $("#form").submit();
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger',
                            action: function(confirm) {

                            }
                        }
                    }
                });
            }
        }
    }
</script>