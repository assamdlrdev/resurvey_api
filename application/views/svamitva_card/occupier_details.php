<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" x-data="alpinedata">
    <div class="row mb-2">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name'] . '/Dag - ' . $this->session->userdata('dag_no'); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" align="right">
            <a href="<?= base_url('index.php/SvamitvaCardController/occupants') ?>" class="btn btn-sm btn-info"><i class="fa fa-users"></i> Occupants List</a>
        </div>
    </div>
    <div class="card rounded-0">
        <form class='form-horizontal' method="post" action="">
            <div class="card-header rounded-0 text-center bg-info py-1">
                <h5>
                    <i class="fa fa-map" aria-hidden="true"></i> OCCUPIER DETAILS OF DAG NO - <?php echo ($this->session->userdata('dag_no')) ?>
                </h5>
            </div>
            <div class="card-body">
                <template x-for="(occupier,index) in occupiers" :key="index">
                    <div class="card rounded-0">
                        <div class="card-header bg-warning py-1 text-center">
                            <b>Occupier <span x-text="index + encro_id_start"></span> <button type="button" x-show="occupiers.length > 1" title="Click to Remove" x-on:click="removeOccupier(index)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></b>
                        </div>
                        <div class="card-body">
                            <div class="row border border-info p-3 mb-2">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="occupier_name">Occupier Name: <font color=red>*</font> </label>
                                        <input x-model="occupier.occupier_name" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" x-bind:id="'occupier_name'+index" placeholder="Occupier Name" class="form-control form-control-sm" name="occupier_name[]">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="occupier_guar_name"> Guardian Name: </label>
                                        <input x-model="occupier.occupier_guar_name" name="occupier_guar_name[]" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" x-bind:id="'occupier_guar_name'+index" placeholder="Father/Mother Name" class="form-control form-control-sm ">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="occupier_guar_relation">Relation with Guardian:</label>
                                        <select x-model="occupier.occupier_guar_relation" x-bind:id="'occupier_guar_relation'+index" name="occupier_guar_relation[]" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach ($relname as $row) { ?>
                                                <option value="<?php echo ($row->guard_rel); ?>">
                                                    <?php echo $row->guard_rel_desc_as; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="marital_status"> Married/Unmarried: <font color=red>*</font></label>
                                        <select x-model="occupier.marital_status" name="marital_status[]" x-bind:id="'marital_status'+index" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach (MARITAL_STATUS_LIST as $code => $marital_status) : ?>
                                                <option value="<?= $code ?>"><?= $marital_status ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div x-show="occupier.marital_status == 'm'" class="col-lg-4 col-md-4 col-sm-12 col-xs-12" x-bind:id="'spouse_name_section'+index">
                                    <div class="form-group">
                                        <label for="spouse_name">Spouse Name: <font color=red>*</font> </label>
                                        <input x-model="occupier.spouse_name" name="spouse_name[]" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" type="text" x-bind:id="'spouse_name'+index" placeholder="Spouse Name" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="gender"> Gender: <font color=red>*</font></label>
                                        <select x-model="occupier.gender" name="gender[]" x-bind:id="'gender'+index" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach ($master_genders as $master_gender) : ?>
                                                <option value="<?= $master_gender->short_name ?>"><?= $master_gender->gen_name_ass ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="category"> Caste Category: <font color=red>*</font></label>
                                        <select x-model="occupier.category" name="category[]" x-bind:id="'category'+index" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach ($master_casts as $caste) : ?>
                                                <option value="<?= $caste->caste_id ?>"><?= $caste->caste_name_ass ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div> -->

                                <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="current_occupation"> Current Occupation: <font color=red>*</font></label>
                                        <select x-model="occupier.current_occupation" name="current_occupation[]" x-bind:id="'current_occupation'+index" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach ($master_occupations as $master_occupation) : ?>
                                                <option value="<?= $master_occupation->id ?>"><?= $master_occupation->name_eng ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="address">Occupier Address: </label>
                                        <input x-model="occupier.address" x-bind:id="'address'+index" name="address[]" type="text" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)" placeholder="Occupier Address" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="occupier_class_code">Nature of Occupier's Land: </label>
                                        <select x-model="occupier.occupier_class_code" name="occupier_class_code[]" x-bind:id="'occupier_class_code'+index" class="form-control form-control-sm">
                                            <option selected value="">--Select--</option>
                                            <?php foreach ($classcode as $value) { ?>
                                                <option value="<?= $value['class_code'] ?>"><?= $value['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="property_type_code">Type of Property: </label>
                                        <select x-model="occupier.property_type_code" name="property_type_code[]" class="form-control form-control-sm">
                                            <option value="" selected>--Select--</option>
                                            <?php foreach ($master_property_types as $value) { ?>
                                                <option value="<?= $value->code ?>"><?= $value->name_eng ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="approx_property_value"> Approximate value of the Property (Rs):</label>
                                        <input x-model="occupier.approx_property_value" x-bind:id="'approx_property_value'+index" name="approx_property_value[]" value="0" type="number" step="0.001" placeholder="Approximate value of the Property" class="form-control form-control-sm">
                                    </div>
                                </div> -->
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="occupier_evicted">Occupier Evicted:</label> <br>
                                        <select x-model="occupier.occupier_evicted" name="occupier_evicted[]" x-bind:id="'occupier_evicted'+index" class="form-control">
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" x-show="occupier.occupier_evicted == 'Y'">
                                    <div class="form-group">
                                        <label for="occupier_evic_date">Occupier Evic Date:</label>
                                        <input x-model="occupier.occupier_evic_date" x-bind:id="'occupier_evic_date'+index" name="occupier_evic_date[]" type="date" placeholder="Occupier Evic Date" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="other_description">Any Other Specific Description:</label>
                                        <textarea x-model="occupier.other_description" name="other_description[]" x-bind:id="'other_description'+index" placeholder="Any Other Specific Description" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="d-flex justify-content-end items-center">
                    <button x-on:click="addOccupier" class="btn btn-sm btn-warning" type="button">ADD MORE OCCUPIER <i class="fa fa-plus"></i></button>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center" style="margin-top: 20px">
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                        <button x-show="!is_loading" type='button' class="btn btn-success" x-on:click='submitOccupiers'>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit & Save
                        </button>
                        <div x-show="is_loading" class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
   
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/location_svamitva.js?v=1.1') ?>"></script>
<script src="<?= base_url('assets/js/common.js') ?>"></script>
<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
    <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
<?php } else { ?>
    <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
<?php } ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('alpinedata', () => ({
            'occupiers': [],
            'is_loading': false,
            'base_url': '<?php echo base_url() ?>',
            'encro_id_start':0,
            init() {
                this.encro_id_start = Number('<?php echo $encro_id; ?>');
                this.addOccupier();
            },
            addOccupier() {
                var occupier = {
                    'occupier_name': '',
                    'occupier_guar_name': '',
                    'occupier_guar_relation': '',
                    'marital_status': '',
                    'spouse_name': '',
                    'gender': '',
                    // 'category': '',
                    // 'current_occupation': '',
                    'address': '',
                    'occupier_class_code': '',
                    'property_type_code': '',
                    // 'approx_property_value': '',
                    'occupier_evicted': 'N',
                    'occupier_evic_date': '',
                    'other_description': ''
                };
                this.occupiers.push(occupier);
            },
            removeOccupier(index) {
                var self = this;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please Confirm to Remove the Occupier',
                    buttons: {
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function(confirm) {

                            }
                        },
                        confirm: {
                            text: 'Remove',
                            btnClass: 'btn-success',
                            action: function(confirm) {
                                self.occupiers.splice(index, 1);
                            }
                        }
                    }
                });
            },
            submitOccupiers() {
                var self = this;
                self.is_loading = true;
                var errors = [];
                self.occupiers.forEach((element, index) => {
                    if (element.occupier_evicted == 'Y' && !element.occupier_evic_date) {
                        errors.push('<small style="color:red">Please enter occupier eviction date in occupier ' + (Number(index) + 1) + ' details.</small><br/>');
                    }
                });
                if (errors.length > 0) {
                    $.confirm({
                        theme: 'modern',
                        title: 'Invalid Data !!',
                        content: errors,
                        type: 'red',
                        icon: 'fa fa-warning',
                        typeAnimated: true,
                        buttons: {
                            ok: {
                                text: 'Ok',
                                btnClass: 'btn-info',
                                action: function(confirm) {
                                    self.is_loading = false;
                                    return;
                                }
                            }
                        }
                    });
                } else {
                    $.confirm({
                        theme: 'modern',
                        title: 'Confirm',
                        content: 'Please Confirm to Save Occupier Details',
                        type: 'warning',
                        typeAnimated: true,
                        buttons: {
                            cancel: {
                                text: 'Cancel',
                                btnClass: 'btn-warning',
                                action: function(confirm) {
                                    self.is_loading = false;
                                }
                            },
                            confirm: {
                                text: 'Confirm',
                                btnClass: 'btn-success',
                                action: function(confirm) {
                                    $.ajax({
                                        dataType: "json",
                                        url: self.base_url + "index.php/SvamitvaCardController/occupierEntry",
                                        data: $('form').serialize(),
                                        type: "POST",
                                        success: function(data) {
                                            self.is_loading = false;
                                            if (data.st == 1) {
                                                $.confirm({
                                                    theme: 'modern',
                                                    title: 'Success !!',
                                                    icon: 'fa fa-check',
                                                    content: data.msg,
                                                    type: 'green',
                                                    typeAnimated: true,
                                                    buttons: {
                                                        viewOccupants: {
                                                            text: 'View Occupants',
                                                            btnClass: 'btn-info',
                                                            action: function(confirm) {
                                                                window.location = self.base_url + "index.php/SvamitvaCardController/occupants";
                                                            }
                                                        },
                                                        enterNewDag: {
                                                            text: 'Enter New Dag',
                                                            btnClass: 'btn-success',
                                                            action: function(confirm) {
                                                                window.location = self.base_url + "index.php/SvamitvaCardController/dagDetails";
                                                            }
                                                        }
                                                    }
                                                });
                                            } else {
                                                $.confirm({
                                                    theme: 'modern',
                                                    title: '<small>Invalid Data !!</small>',
                                                    type: 'red',
                                                    icon: 'fa fa-warning',
                                                    typeAnimated: true,
                                                    content: data.msg,
                                                    buttons: {
                                                        ok: {
                                                            text: 'Ok',
                                                            btnClass: 'btn-info',
                                                            action: function(confirm) {}
                                                        }
                                                    }
                                                });
                                            }
                                        },
                                        error: function(error) {
                                            self.is_loading = false;
                                            $.confirm({
                                                theme: 'modern',
                                                title: 'Server Error !!',
                                                content: 'Please Try again Later or Contact the Admin',
                                                type: 'red',
                                                icon: 'fa fa-warning',
                                                typeAnimated: true,
                                                buttons: {
                                                    ok: {
                                                        text: 'Ok',
                                                        btnClass: 'btn-info',
                                                        action: function(confirm) {}
                                                    }
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        }
                    });
                }

            }
        }))
    })
</script>
<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>