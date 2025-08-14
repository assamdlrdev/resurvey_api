<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
    function alpineData() {
        return {
            'dags': [],
            'base_url': "<?= base_url(); ?>",
            'application_no': '',
            'nc_village': '',
            'selected_dag': '',
            'sk_name': '',
            'is_loading': false,
            'co_certification': '',
            'co_note': '',
            'verified': '',
            'change_village_name': '',
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
                var nc_village = '<?= json_encode($nc_village) ?>';
                var nc_village = nc_village.replace('\n', '\\n');
                var nc_village = JSON.parse(nc_village);
                this.application_no = nc_village.application_no;
                this.nc_village = nc_village;
                this.verified = '<?= $verified ?>';
                this.change_village_name = <?php if (($change_vill)) : ?>true<?php else : ?> ''
            <?php endif; ?>;
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
                    if (element.co_verified == 'Y') {
                        total++;
                    }
                });
                return total;
            },
            getDags() {
                var self = this;
                this.is_loading = true;
                $.ajax({
                    url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCommonController/getDags',
                    method: "POST",
                    async: true,
                    dataType: 'json',
                    data: {
                        'application_no': self.application_no
                    },
                    success: function(data) {
                        self.dags = data.dags;
                        self.sk_name = data.sk_name;
                        self.is_loading = false;
                        if (data.dags) {
                            self.co_certification = 'This chitha and map has been prepared under Govt. of Assam vide notification No.ECF. 206672/2022/26 Dt. 31-05-2022 and corrected upto 2022-23.';
                        }
                    }
                });
                self.is_loading = false;
            },
            // verifyDag() {
            //     var self = this;
            //     this.is_loading = true;
            //     $.confirm({
            //         title: 'Confirm',
            //         content: 'Please confirm to Verify the Draft chitha and Draft Map of this Dag',
            //         type: 'orange',
            //         typeAnimated: true,
            //         buttons: {
            //             Confirm: {
            //                 text: 'Confirm',
            //                 btnClass: 'btn-success',
            //                 action: function() {
            //                     $.ajax({
            //                         url: '<?= base_url(); ?>index.php/nc_village_v2/NcVillageCoController/verifyDag',
            //                         method: "POST",
            //                         async: true,
            //                         dataType: 'json',
            //                         data: {
            //                             'application_no': self.application_no,
            //                             'dag_no': self.selected_dag.dag_no,
            //                             'patta_no': self.selected_dag.patta_no,
            //                             'patta_type_code': self.selected_dag.patta_type_code,
            //                         },
            //                         success: function(data) {
            //                             if (data.submitted == 'Y') {
            //                                 self.selected_dag.co_verified = 'Y';
            //                                 $.confirm({
            //                                     title: 'Success',
            //                                     content: data.msg,
            //                                     type: 'green',
            //                                     typeAnimated: true,
            //                                     buttons: {
            //                                         Ok: {
            //                                             text: 'OK',
            //                                             btnClass: 'btn-info',
            //                                             action: function() {
            //                                                 self.getDags();
            //                                                 self.is_loading = false;
            //                                             }
            //                                         },
            //                                     }
            //                                 });
            //                                 if (self.dags.length == self.dags_verified) {
            //                                     self.verified = 'Y';
            //                                 }
            //                             } else {
            //                                 $.confirm({
            //                                     title: 'Error Occured!!',
            //                                     content: data.msg,
            //                                     type: 'red',
            //                                     typeAnimated: true,
            //                                     buttons: {
            //                                         Ok: {
            //                                             text: 'OK',
            //                                             btnClass: 'btn-info',
            //                                             action: function() {
            //                                                 self.is_loading = false;
            //                                             }
            //                                         },
            //                                     }
            //                                 });
            //                             }

            //                             self.is_loading = false;
            //                         },
            //                         error: function(error) {
            //                             $.confirm({
            //                                 title: 'Error Occurred!!',
            //                                 content: 'Please contact the system admin',
            //                                 type: 'red',
            //                                 typeAnimated: true,
            //                                 buttons: {
            //                                     Ok: {
            //                                         text: 'OK',
            //                                         btnClass: 'btn-info',
            //                                         action: function() {
            //                                             self.is_loading = false;
            //                                         }
            //                                     },
            //                                 }
            //                             });
            //                             self.is_loading = false;
            //                         }
            //                     });
            //                 }
            //             },
            //             cancel: {
            //                 text: 'Cancel',
            //                 btnClass: 'btn-warning',
            //                 action: function() {
            //                     self.is_loading = false;
            //                 }
            //             },
            //         }
            //     });
            //     this.is_loading = false;
            // },
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
					<form action="<?= base_url()?>index.php/nc_village_v2/NcVillageCommonController/viewBhunaksaMap" method="post">
						<span style="font-size: 20px;"> DAGS <span id="application_no" class="bg-gradient-danger text-white">
                        </span> (Total Dags : <b x-text="dags.length"></b>)
						</span>
                        <span>
							<button type="button" class="btn btn-primary" onclick="villDagsChithaButton()">
                                <i class="fa fa-file"></i> View Chitha</button>
						</span>

                        <?php if (sizeof($maps) > 0) : ?>
                            <button type="button" class="btn btn-info py-2" style="color: white" onclick="viewMaps()">
                                <i class='fa fa-eye'></i> View Map
                            </button>
                        <?php endif; ?>
						<span>
							<input type="hidden" name="location" value="<?=$locations['dist']['dist_code'].'_'.
							$locations['subdiv']['subdiv_code'].'_'.
							$locations['circle']['cir_code'].'_'.$locations['mouza']['mouza_pargona_code'].'_'.
							$locations['lot']['lot_no'].'_'.$locations['village']['vill_townprt_code']?>">
							<input type="hidden" name="vill_name" value="<?=$locations['village']['loc_name']?>">
							<input type="hidden" name="dags" :value="nc_village.bhunaksa_total_dag">
							<input type="hidden" name="area" :value="nc_village.bhunaksa_total_area_skm">
							 <button type="submit" class="btn btn-secondary py-2" style="color: white;">
								<i class='fa fa-eye'></i> View Bhunaksha Map
							</button>
							 <button type="button" class="btn btn-secondary py-2" style="color: white;" data-target="#view_full_case_history" data-toggle="modal">
								<i class='fa fa-eye'></i> View Case Activities
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
                                    <!-- <th>Verified By LRS <br>(Draft Chitha & Map)</th>
                                    <th>Verified By LRS</th> -->
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
                                        <!-- <td x-text="dag.sk_verified == 'Y' ? 'Yes' : 'No'" :class="dag.sk_verified == 'Y' ? 'text-success' : 'text-danger'">
                                        </td>
                                        <td>
                                            <span x-text="sk_name"></span>
                                        </td> -->
                                        <td>
                                            <!-- <span class="text-success" x-show="dag.co_verified == 'Y'"><b>Verified</b></span>
                                            <button x-show="dag.co_verified != 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-success" type="button">View</button> -->
                                            <!-- <button x-show="dag.co_verified == 'Y'" x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-info" type="button">View Dag</button> -->
                                            <button x-on:click="openModal(dag)" data-toggle="modal" data-target="#chitha_and_map" class="btn btn-sm btn-info" type="button">View Dag</button>
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
                    <div class="border-top border-dark col-lg-12 col-md-12 py-3">
                        <!-- <div class="form-group">
                            <label class="form-label">CO Certification Remarks <span class="text-red">*</span></label>
                            <textarea x-model="co_certification" placeholder="CO Certification" rows="2" class="form-control" readonly></textarea>
                        </div> -->
                        <div class="form-group">
                            <label for="" class="form-label">Change Village Name <span class="text-red">*</span></label>
                            <div class="form-group mt-2 row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="sel1">Existing Village Name:</label>
                                        <input name="village_name" type="text" class="form-control" value="<?php echo ($locations["village"]["loc_name"]); ?>" id="village_name" disabled required>
                                    </div>
                                </div>
                                <?php if (($change_vill)) : ?>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sel1">New Village Name:</label>
                                            <input value="<?php echo $change_vill->new_vill_name ?>" name="new_village_name" type="text" class="form-control" id="new_village_name" disabled required>
                                        </div>
                                    </div>

                                <?php else : ?>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sel1">New Village Name:</label>
                                            <input x-model="change_village_name" value="" name="new_village_name" type="text" class="form-control" id="new_village_name" disabled required>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- <div x-show="nc_village.co_verified != 'Y' && nc_village.status != 'H'" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <button style="margin-top: 1.9rem ;" class="btn change btn-success" type="button"><i class="fa fa-check"></i> Change Village Name</button>
                                    </div>
                                </div> -->
                                <!-- <div class="col-lg-12 col-sm-12 col-xs-12" x-show="nc_village.co_verified == 'Y'">
                                    <textarea disabled rows="3" class="form-control">The survey of village "<?php echo ($change_vill->old_vill_name) ?>" is done and its new updated name will be "<?php echo ($change_vill->new_vill_name . ' (' . $change_vill->new_vill_name_eng . ')') ?>" as per government OM No.ECF.213444/2022/ Dated Dispur, the 03-01-2024.</textarea>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="vill_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Changed Village Name</h5>
                    </div>
                    <div class="modal-body">
                        <div class="card rounded-0">
                            <div class="card-body">
                                <form action="">
                                    <div class="row border border-info p-3">
                                        <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">District:</label>
                                                <h6 id="d"><?php echo ($locations["dist"]["loc_name"]); ?></h6>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">Sub-Div:</label>
                                                <h6 id="s"><?php echo ($locations["subdiv"]["loc_name"]); ?></h6>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">Circle:</label>
                                                <h6 id="cir"><?php echo ($locations["circle"]["loc_name"]); ?></h6>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">Mouza/Porgona:</label>
                                                <h6 id="mza"><?php echo ($locations["mouza"]["loc_name"]); ?></h6>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">Lot:</label>
                                                <h6 id="lot"><?php echo ($locations["lot"]["loc_name"]); ?></h6>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="sel1">Village:</label>
                                                <h6 id="vill"><?php echo ($locations["village"]["loc_name"]); ?></h6>

                                            </div>
                                        </div>

                                        <input style="display: none;" value="<?php echo ($locations["dist"]["dist_code"]); ?>" name="dist" type="text" class="form-control" id="dist" required>
                                        <input style="display: none;" value="<?php echo ($locations["subdiv"]["subdiv_code"]); ?>" name="sub" type="text" class="form-control" id="sub" required>
                                        <input style="display: none;" value="<?php echo ($locations["circle"]["cir_code"]); ?>" name="cr" type="text" class="form-control" id="cr" required>
                                        <input style="display: none;" value="<?php echo ($locations["mouza"]["mouza_pargona_code"]); ?>" name="mouza" type="text" class="form-control" id="mouza" required>
                                        <input style="display: none;" value="<?php echo ($locations["lot"]["lot_no"]); ?>" name="lt" type="text" class="form-control" id="ltNo" required>
                                        <input style="display: none;" value="<?php echo ($locations["village"]["uuid"]); ?>" name="vuuid" type="text" class="form-control" id="vuuid" required>

                                        <?php if (($change_vill)) : ?>
                                            <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input value="<?php echo $change_vill->old_vill_name_eng ?>" name="old_vill_name_eng" type="text" class="form-control" id="vill_name_engg" required>
                                                </div>
                                            </div>
                                            <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input value="<?php echo $change_vill->old_vill_name ?>" name="old_vill_name" type="text" class="form-control" id="old_vill_name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name:</label>
                                                    <input value="<?php echo $change_vill->new_vill_name ?>" name="new_vill_name" type="text" class="form-control" id="new_vill_name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name (Eng):</label>
                                                    <input value="<?php echo $change_vill->new_vill_name_eng ?>" name="new_vill_name_eng" type="text" class="form-control" id="new_vill_name_eng" required>
                                                </div>
                                            </div>
                                            <div id="template" class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <textarea disabled id="templateTA" rows="3" class="form-control" aria-label="With textarea">The survey of village <?php echo ($locations["village"]["loc_name"]); ?> is completed and its new updated name will be <?php echo $change_vill->new_vill_name ?> (<?php echo $change_vill->new_vill_name_eng ?>).</textarea>
                                                </div>
                                            </div>

                                        <?php else : ?>

                                            <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input value="<?php echo ($locations["village"]["locname_eng"]); ?>" name="old_vill_name_eng" type="text" class="form-control" id="vill_name_engg" required>
                                                </div>
                                            </div>
                                            <div style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input value="<?php echo ($locations["village"]["loc_name"]); ?>" name="old_vill_name" type="text" class="form-control" id="old_vill_name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name:</label>
                                                    <input value="<?php echo ($locations["village"]["loc_name"]); ?>" name="new_vill_name" type="text" class="form-control" id="new_vill_name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">New Village Name (Eng):</label>
                                                    <input value="<?php echo ($locations["village"]["locname_eng"]); ?>" name="new_vill_name_eng" type="text" class="form-control" id="new_vill_name_eng" required>
                                                </div>
                                            </div>
                                            <div id="template" class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <textarea disabled id="templateTA" class="form-control" aria-label="With textarea">The survey of village <?php echo ($locations["village"]["loc_name"]); ?> is done and its new updated name will be <?php echo ($locations["village"]["loc_name"]); ?> (<?php echo ($locations["village"]["locname_eng"]); ?>) as per government OM No.ECF.213444/2022/ Dated Dispur, the 03-01-2024.</textarea>
                                                </div>
                                            </div>
                                        <?php endif; ?>


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="uuid" id="uuid" value="" />
                    <input style="display: none;" type="text" name="new_vill_name" id="new_vill_name" value="" />
                    <input style="display: none;" type="text" name="new_vill_name_eng" id="new_vill_name_eng" value="" />


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type='button' class="btn btn-info" name="submit" onclick='checkloc();' value="Submit">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="chitha_and_map" tabindex="-1" aria-labelledby="chitha_and_map" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <button type="button" class="close" data-dismiss="modal" x-on:click="closeModal" aria-label="Close">
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
                            <a x-show="selected_dag" x-bind:href=base_url+"index.php/Chithacontrol/generateDagChitha?case_no=4&dag="+selected_dag.dag_no+"&dist="+nc_village.dist_code+"&sub_div="+nc_village.subdiv_code+"&cir="+nc_village.cir_code+"&m="+nc_village.mouza_pargona_code+"&l="+nc_village.lot_no+"&v="+nc_village.vill_townprt_code+"&p="+selected_dag.patta_type_code target=" _blank" class="btn btn-sm btn-info">View Chitha</a>
                        </h5>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="close_modal" class="btn btn-danger" data-dismiss="modal" x-on:click="closeModal"> <i class='fa fa-close'></i> Close
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
                        <div><iframe width='100%' height='500px;' src='<?= base_url() . $nc_village->chitha_dir_path."?rand=".rand(10,10000) ?>'></iframe></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeModal()" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i> Close
                        </button>
                        <!-- <button x-show="verified != 'Y' && (nc_village.status == 'T' || nc_village.status == 'J')" type="button" @click="co_verified()" class="btn btn-primary"><i class="fa fa-check"></i> Verify Draft Chitha</button> -->
                        <!-- <h3 x-show="verified == 'Y' || (dags.length == dags_verified)" class="text-success">Draft Chitha Already Verified by CO.</h3> -->

                    </div>
                </div>
            </div>
        </div>
        
        <!--	Modal for village dag chitha verify-->
        <div class="modal" id="view_full_case_history" tabindex="-1" aria-labelledby="view_full_case_history" aria-hidden="true">
            <div class="modal-dialog modal-xl" style="width: 100% !important;">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title"> <i class="fa fa-file"></i> Case Activities</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" onclick="closeModal()">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Note</th>
                                        <th>Previous User</th>
                                        <th>Current User</th>
                                        <th>Action Date</th>
                                        <th>Process Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($full_histories)): ?>
                                        <?php foreach($full_histories as $key => $full_history): ?>
                                            <tr class="<?= $full_history['status'] == 0 ? 'bg-danger' : ''; ?> ">
                                                <td><?= $key+1 ?>. </td>
                                                <td><?= $full_history['note'] ?></td>
                                                <td><?= $full_history['pre_user_dig'] ?></td>
                                                <td><?= $full_history['cur_user_dig'] ?></td>
                                                <td><?= $full_history['created_at'] ?></td>
                                                <td><?= $full_history['proccess_type'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>No history found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        
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
                            <?php foreach ($maps as $key => $map_single) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <a href="<?= base_url() . 'index.php/nc_village_v2/NcVillageCommonController/viewUploadedMap?id=' . $map_single->id ?>" class="btn btn-info py-2" style="color: white" target="_blank">
                                            View Map
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($maps) == 0) : ?>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <span>No Map Found</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
<script src="<?= base_url('assets/js/change_location.js') ?>"></script>
<script>
    /** VIEW VILLAGE DAGS CHITHA **/
    function villDagsChithaButton() {
        $('#modal_vill_dag_chitha').modal('show');
    }

    function viewMaps() {
        $('#modal_show_map_list').modal('show');
    }
</script>
