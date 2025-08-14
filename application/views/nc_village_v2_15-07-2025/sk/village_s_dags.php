<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    let LMS = '<?= json_encode($all_lms) ?>';
        LMS = JSON.parse(LMS);
    function alpineData() {
        return {
            'dags': [],
            'base_url': "<?= base_url(); ?>",
            'application_no': '',
            'nc_village': '',
            'selected_dag': '',
            'lm_name': '',
            'is_loading': false,
            'sk_note': '',
            'forward_to_user': '',
            'verified': '',
            'lms': LMS,
            init() {
                var nc_village = '<?= json_encode($nc_village) ?>';
                var nc_village = nc_village.replace('\n', '\\n');
                var nc_village = JSON.parse(nc_village);
                this.application_no = nc_village.application_no;
                this.nc_village = nc_village;
                this.verified = '<?= $verified ?>';

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
                this.getDags();
            },
            openModal(dag) {
                this.selected_dag = dag;
            },
            closeModal() {
                $('#close_modal').trigger('click');
            },
            get dags_verified() {
                var total = 0;
                this.dags.forEach(element => {
                    if (element.sk_verified == 'Y') {
                        total++;
                    }
                });
                return total;
            },
            getDags() {
                var self = this;
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageSkController/getDags',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'application_no': self.application_no
                    },
                    success: function(data) {
                        self.dags = data.dags;
                        self.lm_name = data.lm_name;
                        self.is_loading = false;
                    }
                });
                self.is_loading = false;
            },
            verifyDag() {
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Verify the Draft chitha and Draft Map of this Dag',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/verifyDag',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no,
                                        'dag_no': self.selected_dag.dag_no,
                                        'patta_no': self.selected_dag.patta_no,
                                        'patta_type_code': self.selected_dag.patta_type_code,
                                    },
                                    success: function(data) {
                                        if (data.submitted == 'Y') {
                                            self.selected_dag.sk_verified = 'Y';
                                            $.confirm({
                                                title: 'Success',
                                                content: data.msg,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.getDags();
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                            if (self.dags.length == self.dags_verified) {
                                                self.verified = 'Y';
                                            }
                                        } else {
                                            $.confirm({
                                                title: 'Error Occured!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                        }

                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
                this.is_loading = false;
            },
            certifyVillage() {

                if (!this.forward_to_user) {
                    alert('Please select CO');
                    return;
                }
                if (!this.sk_note) {
                    alert('Please enter the LRS Note');
                    return;
                }
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Verify the Draft Chitha and Draft Map of this Village',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageSkController/certifyVillage',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'forward_to_user': self.forward_to_user,
                                        'application_no': self.application_no,
                                        'remark': self.sk_note,
                                    },
                                    success: function(data) {
                                        if (data.submitted != 'Y') {
                                            $.confirm({
                                                title: 'Error Occurred!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                            return;
                                        }
                                        if (data.submitted == 'Y') {
                                            $.confirm({
                                                title: 'Success',
                                                content: 'The draft Chitha and map of this village have been verified successfully.',
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                            self.nc_village = data.nc_village;
                                                        }
                                                    },
                                                }
                                            });
                                        }
                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occurred!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
            },
            revertVillage() {
                var self = this;
                this.is_loading = true;
                let optionsHtml = '<option value="">Please Select LRA</option>';
                self.lms.forEach(function(lm) {
                    optionsHtml += '<option value="' + lm.user_code + '">' + lm.name + '</option>';
                });
                let contentHtml =
                    '<form id="remarkForm">' +
                        '<h3>Please confirm to Revert Back this Village to LRA</h3>' + 
                        '<div class="row">' +
                            '<div class="col-lg-12 mb-2">' +
                                '<label class="form-check-label" for="co_user_code">LRA</label>' +
                                '<select id="lm_user_code" name="lm_user_code" class="form-control">' +
                                    optionsHtml+
                                '</select>' +
                            '</div>'  +
                            '<div class="col-lg-12">' +
                                '<label class="form-check-label" for="lm_remark">Note</label>' +
                                '<textarea id="lm_remark" name="remark" placeholder="Note" class="form-control" required ></textarea>' +
                            '</div>' +
                        '</div>' +
                    '</form>';
                $.confirm({
                    title: 'Confirm',
                    content: contentHtml,
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-danger',
                            action: function() {
                                let lm_user_code = $('#lm_user_code').val().trim();
                                let remark = $('#lm_remark').val().trim();
                                if (lm_user_code == '') {
                                    alert('Please select LRA.');
                                    self.is_loading = false;
                                    return false;
                                }
                                if (remark == '') {
                                    alert('Please enter note.');
                                    self.is_loading = false;
                                    return false;
                                }

                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageSkController/revertVillage',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no,
                                        'remark': remark,
                                        'user': lm_user_code,
                                    },
                                    success: function(data) {
                                        if (data.st == '0' || data.submitted != 'Y') {
                                            $.confirm({
                                                title: 'Error Occured!!',
                                                content: data.msg,
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                        }
                                                    },
                                                }
                                            });
                                            return;
                                        }
                                        if (data.submitted == 'Y') {
                                            $.confirm({
                                                title: 'Success',
                                                content: data.msg,
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {
                                                            self.is_loading = false;
                                                            self.nc_village = data.nc_village;
                                                        }
                                                    },
                                                }
                                            });
                                        }
                                        self.is_loading = false;
                                    },
                                    error: function(error) {
                                        $.confirm({
                                            title: 'Error Occured!!',
                                            content: 'Please contact the system admin',
                                            type: 'red',
                                            typeAnimated: true,
                                            buttons: {
                                                Ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-info',
                                                    action: function() {
                                                        self.is_loading = false;
                                                    }
                                                },
                                            }
                                        });
                                        self.is_loading = false;
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
            },
            sk_verified() {
                var self = this;
                this.is_loading = true;
                $.confirm({
                    title: 'Confirm',
                    content: 'Please confirm to Verify the Draft Chitha',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        Confirm: {
                            text: 'Confirm',
                            btnClass: 'btn-success',
                            action: function() {
                                $.ajax({
                                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageSkController/verifyDraftChitha',
                                    method: "POST",
                                    async: true,
                                    dataType: 'json',
                                    data: {
                                        'application_no': self.application_no
                                    },
                                    success: function(data) {
                                        if (data.error === false) {
                                            self.verified = 'Y';
                                            $.confirm({
                                                title: 'Success',
                                                content: 'Draft Chitha Verified Successfully.',
                                                type: 'green',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {}
                                                    },
                                                }
                                            });

                                        } else {
                                            $.confirm({
                                                title: 'Error',
                                                content: 'Please contact the system admin',
                                                type: 'red',
                                                typeAnimated: true,
                                                buttons: {
                                                    Ok: {
                                                        text: 'OK',
                                                        btnClass: 'btn-info',
                                                        action: function() {}
                                                    },
                                                }
                                            });
                                        }

                                        $('#modal_vill_dag_chitha').modal('hide');
                                        self.getDags()
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-warning',
                            action: function() {
                                self.is_loading = false;
                            }
                        },
                    }
                });
                this.is_loading = false;
            }
        }
    }
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label for="">District</label>
                    <select id="dist_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Sub-Division</label>
                    <select id="subdiv_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Circle</label>
                    <select id="cir_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Mouza</label>
                    <select id="mouza_pargona_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['mouza']['mouza_pargona_code'] ?>"><?= $locations['mouza']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Lot</label>
                    <select id="lot_no" class="form-control form-control-sm">
                        <option selected value="<?= $locations['lot']['lot_no'] ?>"><?= $locations['lot']['loc_name'] ?></option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="">Village</label>
                    <select id="vill_townprt_code" class="form-control form-control-sm">
                        <option selected value="<?= $locations['village']['vill_townprt_code'] ?>"><?= $locations['village']['loc_name'] ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url() ?>index.php/nc_village_v2/NcVillageCommonController/viewBhunaksaMap" method="post">
                        <span style="font-size: 20px;">
                            DAGS <span id="application_no" class="bg-gradient-danger text-white">
                            </span> (Total Dags : <b x-text="dags.length"></b> , Verified :
                            <b class="text-success" x-text="dags_verified"></b>)
                        </span>
                        <span>
                            <button type="button" class="btn btn-primary" onclick="villDagsChithaButton()">
                                <i class="fa fa-file"></i> View Chitha</button>
                        </span>

                        <?php if (!empty($maps)) : ?>
                            <button type="button" class="btn btn-info py-2" style="color: white" onclick="viewMaps()">
                                <i class='fa fa-eye'></i> View Map
                            </button>
                        <?php endif; ?>
                        <span>
                            <input type="hidden" name="location" value="<?= $locations['dist']['dist_code'] . '_' .
                                                                            $locations['subdiv']['subdiv_code'] . '_' .
                                                                            $locations['circle']['cir_code'] . '_' . $locations['mouza']['mouza_pargona_code'] . '_' .
                                                                            $locations['lot']['lot_no'] . '_' . $locations['village']['vill_townprt_code'] ?>">
                            <input type="hidden" name="vill_name" value="<?= $locations['village']['loc_name'] ?>">
                            <input type="hidden" name="dags" :value="nc_village.bhunaksa_total_dag">
                            <input type="hidden" name="area" :value="nc_village.bhunaksa_total_area_skm">
                            <button type="submit" class="btn btn-secondary py-2" style="color: white;">
                                <i class='fa fa-eye'></i> View Bhunaksha Map
                            </button>
                        </span>
                    </form>
                    <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                        <table class="table table-striped table-hover table-sm table-bordered">
                            <thead style="position: sticky;top:0;" class="bg-warning">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Dag</th>
                                    <th>Land Class</th>
                                    <th>Occupiers</th>
                                    <th>Area(B-K-L)</th>
                                    <th>Verified By LRA <br>(Draft Chitha & Map)</th>
                                    <th>Verified By LRA</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(dag,index) in dags">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <td x-text="dag.dag_no"></td>
                                        <td x-text="dag.full_land_type_name"></td>
                                        <td>
                                            <template x-for="(occupier,index) in dag.occupiers" :key="index">
                                                <p class="small" x-text="occupier.encro_name"></p>
                                            </template>
                                        </td>
                                        <td x-text="dag.dag_area_b+'-'+dag.dag_area_k+'-'+dag.dag_area_lc"></td>
                                        <td x-text="dag.lm_verified == 'Y' ? 'Yes' : 'No'" :class="dag.lm_verified == 'Y' ? 'text-success' : 'text-danger'">
                                        </td>
                                        <td>
                                            <span x-text="lm_name"></span>
                                        </td>
                                        <td>
                                            <span class="text-success" x-show="dag.sk_verified == 'Y'"><b>Verified</b></span>
                                            <button x-show="dag.sk_verified != 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-success" type="button">View</button>
                                            <button x-show="dag.sk_verified == 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-info" type="button">View Dag</button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="dags.length == 0">
                                    <td colspan="4" class="text-center">
                                        <span x-show="!is_loading">No dags Found</span>
                                        <!-- <div class="d-flex justify-content-center" x-show="is_loading">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div> -->
                                        <p class="text-danger">
                                        <h5>Data will not be available if this is a reverted case. Chitha data will be populated from Bhunaksha once LRA process the case. Otherwise please wait till we fetch the data...</h5>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th colspan="2" style="background-color: #136a6f; color: #fff">
                                Village Details
                            </th>
                            <?php
                                if(count($merge_village_requests)):
                            ?>
                            <?php
                                else:
                            ?>
                                    <!-- <th colspan="2" style="background-color: #136a6f; color: #fff">
                                        Chitha Details
                                    </th>
                                    <th colspan="2" style="background-color: #136a6f; color: #fff">
                                        Bhunaksha Details
                                    </th> -->
                            <?php
                                endif;
                            ?>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="25%">Total Dags</td>
                                <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_dag"></td>
                            </tr>
                            <tr>
                                <td width="25%"> Area (sq km)</td>
                                <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_area_skm"></td>
                            </tr>
                            <tr>
                                <td class="text-danger font-weight-bold" colspan="2">
                                    <span x-show="nc_village.bhunaksa_total_area_skm < 2">
                                        The area is less than 2 (Square kilometre)
                                    </span>
                                </td>
                            </tr>
                            <?php
                                if(count($merge_village_requests)):
                            ?>
                            <?php
                                else:
                            ?>
                                    <!-- <tr>
                                        <td width="25%">Total Dags</td>
                                        <td width="25%" style="color:red" x-text="nc_village.chitha_total_dag"></td>
                                        <td width="25%">Total Dags</td>
                                        <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_dag"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Chitha Area (sq km)</td>
                                        <td width="25%" style="color:red" x-text="nc_village.chitha_total_area_skm"></td>
                                        <td width="25%">Bhunaksha Area (sq km)</td>
                                        <td width="25%" style="color:red" x-text="nc_village.bhunaksa_total_area_skm"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger font-weight-bold" colspan="2">
                                            <span x-show="nc_village.chitha_total_area_skm < 2">
                                                The Chitha area is less than 2 (Square kilometre)
                                            </span>
                                        </td>
                                        <td class="text-danger font-weight-bold" colspan="2">
                                            <span x-show="nc_village.bhunaksa_total_area_skm < 2">
                                                The Bhunaksha area is less than 2 (Square kilometre)
                                            </span>
                                        </td>
                                    </tr> -->
                            <?php
                                endif;
                            ?>
                        </tbody>
                    </table>
                    <?php
                        if(count($merge_village_requests)):
                    ?>
                        <div id="merge_village_data">
                            <h4>Village list to be merged</h4>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr style="background-color: #136a6f; color: #fff">
                                        <th>Sl No</th>
                                        <th>Village Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($merge_village_requests as $key => $merge_village_request):
                                ?>
                                        <tr>
                                            <td><?= ($key+1) ?></td>
                                            <td><?= $merge_village_request['vill_loc']['village']['loc_name'] ?></td>
                                        </tr>
                                <?php
                                    endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        endif;
                    ?>
                    <div class="border-top border-dark col-lg-12 col-md-12 py-3" x-show="nc_village.sk_verified != 'Y' && nc_village.status != 'U'">
                        <div class="form-group">
                            <label for="" class="form-label">CO <span class="text-red">*</span></label>
                            <select x-model="forward_to_user" class="form-control">
                                <option value="">Please Select CO</option>
                                <?php
                                if(!empty($all_cos)){
                                    foreach($all_cos as $co){
                                ?>
                                    <option value="<?php echo $co['user_code']; ?>"><?php echo $co['name']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">LRS Note <span class="text-red">*</span></label>
                            <textarea x-model="sk_note" placeholder="LRS Note" rows="2" class="form-control"></textarea>
                        </div>
                        <button x-on:click="certifyVillage" class="btn btn-success" type="button"><i class="fa fa-check"></i> Verify Draft Chitha and Map</button>
                        <button x-on:click="revertVillage" class="btn btn-danger" type="button"><i class="fa fa-backward"></i> Revert Back to LRA</button>
                    </div>
                    <div x-show="nc_village.sk_verified == 'Y'">
                        <h5 class="text-success mt-5 text-center">The Draft Chitha and Map of this village has already been verified.</h5>
                    </div>
                    <div x-show="nc_village.status == 'U'">
                        <h5 class="text-danger mt-5 text-center">This village has been reverted back to LRA</h5>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal" id="chitha_and_map" tabindex="-1" aria-labelledby="chitha_and_map" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <button type="button" class="close" data-dismiss="modal" onclick="closeModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h5 class="reza-title">
                            <i class="fa fa-map-marker"></i> Location Information
                        </h5>
                        <div class="tableCard ">
                            <table class="table table-bordered">
                                <tr>
                                    <th>District Name:</th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="dist_name" class="form-control input-sm" value='<?= $this->utilityclass->getDistrictName($locations['dist']['dist_code']) ?>' readonly>
                                        </strong>
                                    </td>
                                    <th>Subdivision Name:</th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="subdiv_name" class="form-control input-sm" value='<?= $this->utilityclass->getSubDivName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code']) ?>' readonly>
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Circle Name: </th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="circle_name" value='<?= $this->utilityclass->getCircleName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code']) ?>' class="form-control input-sm" readonly>
                                        </strong>
                                    </td>
                                    <th>Mouza Name: </th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="mouza_name" class="form-control input-sm" value='<?= $this->utilityclass->getMouzaName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code']) ?>' readonly>
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lot Name: </th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="lot_name" value='<?= $this->utilityclass->getLotLocationName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code'], $locations['lot']['lot_no']) ?>' class="form-control input-sm" readonly>
                                        </strong>
                                    </td>
                                    <th>Village Name: </th>
                                    <td class="text-warning">
                                        <strong class="alert-warning">
                                            <input type="text" name="village_name" value='<?= $this->utilityclass->getVillageName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code'], $locations['lot']['lot_no'], $locations['village']['vill_townprt_code']) ?>' class="form-control input-sm" readonly>
                                        </strong>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <h5 class="reza-title">
                            <i class="fa fa-file"></i> View Chitha
                            <a x-show="selected_dag" x-bind:href="base_url+'index.php/Chithacontrol/generateDagChitha?case_no=4&dag='+selected_dag.dag_no+'&dist='+nc_village.dist_code+'&sub_div='+nc_village.subdiv_code+'&cir='+nc_village.cir_code+'&m='+nc_village.mouza_pargona_code+'&l='+nc_village.lot_no+'&v='+nc_village.vill_townprt_code+'&p='+selected_dag.patta_type_code" target="_blank" class="btn btn-sm btn-info">View Chitha</a>
                        </h5>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="close_modal" class="btn btn-danger" data-dismiss="modal" onclick="closeModal()"> <i class='fa fa-close'></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--	Modal for village dag chitha verify-->
        <div class="modal" id="modal_vill_dag_chitha" tabindex="-1" aria-labelledby="modal_vill_dag_chitha" aria-hidden="true">
            <div class="modal-dialog modal-xl" style="width: 100% !important;">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title"> <i class="fa fa-file"></i> Village Draft Chitha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="closeModal()">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div><iframe width='100%' height='500px;' src='<?= base_url() . $nc_village->chitha_dir_path . "?rand=" . rand(10, 10000) ?>'></iframe></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeModal()" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i> Close
                        </button>
                        <button x-show="verified != 'Y' && (nc_village.status == 'S' || nc_village.status == 'H')" type="button" @click="sk_verified()" class="btn btn-primary"><i class="fa fa-check"></i> Verify Draft Chitha</button>
                        <h3 x-show="verified == 'Y' || (dags.length == dags_verified)" class="text-success">Draft Chitha Already Verified by LRS.</h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--	Modal for Map View-->
<div class="modal" id="modal_show_map_list" tabindex="-1" aria-labelledby="modal_show_map_list" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width: 100% !important;">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title"> <i class="fa fa-file"></i> View Maps</h5>
            </div>
            <div class="modal-body">
                <div class="border mb-2" style="height: 60vh;overflow-y:auto;">
                    <table class="table table-striped table-hover table-sm table-bordered">
                        <thead style="position: sticky;top:0;" class="bg-warning">
                            <tr>
                                <th>Sl.No.</th>
                                <th>View Map</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($maps)){
                                foreach ($maps->map_lists as $key => $map_single){ 
                            ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <a href="<?= base_url() . 'index.php/nc_village_v2/NcVillageSkController/viewUploadedMap?id=' . $map_single->id ?>" class="btn btn-info py-2" style="color: white" target="_blank">
                                                View Map
                                            </a>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            } else{
                            ?>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <span>No Map Found</span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="closeBtn" data-dismiss="modal">
                    <i class='fa fa-close'></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script>
    /** VIEW VILLAGE DAGS CHITHA **/
    function villDagsChithaButton() {
        $('#modal_vill_dag_chitha').modal('show');
    }

    function viewMaps() {
        $('#modal_show_map_list').modal('show');
    }
</script>