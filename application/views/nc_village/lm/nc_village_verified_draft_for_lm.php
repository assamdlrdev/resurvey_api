<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<div class="text-center p-2 mb-2" style="font-size:18px; color: white; font-weight: bold; background-color: #4298c9; width: 100%;">NC VILLAGE</div>
<div class="col-lg-12">
	<div class="row">
		<div class="col-lg-2">
			<label for="">District</label>
			<select id="dist_code" class="form-control form-control-sm">
				<option value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
			</select>
		</div>
		<div class="col-lg-2">
			<label for="">Sub-Division</label>
			<select id="subdiv_code" class="form-control form-control-sm">
				<option value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
			</select>
		</div>
		<div class="col-lg-2">
			<label for="">Circle</label>
			<select id="cir_code" class="form-control form-control-sm">
				<option value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
			</select>
		</div>
		<div class="col-lg-2">
			<label for="">Mouza</label>
			<select id="mouza_pargona_code" class="form-control form-control-sm">
				<option value="<?= $locations['mouza']['mouza_pargona_code'] ?>"><?= $locations['mouza']['loc_name'] ?></option>
			</select>
		</div>
		<div class="col-lg-2">
			<label for="">Lot</label>
			<select id="lot_no" class="form-control form-control-sm">
				<option value="<?= $locations['lot']['lot_no'] ?>"><?= $locations['lot']['loc_name'] ?></option>
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
			<?php echo form_open('nc_village/NcVillageCommonController/viewBhunaksaMap'); ?>

			<span style="font-size: 20px;">
				NC VILLAGE <span id="application_no" class="bg-gradient-danger text-white"></span> (Total Dags :
				<b id="total_dags">0</b> , Verified : <b class="text-success" id="total_verified">0</b>)
			</span>
			<span id="vill_dags_chitha_button"></span>
			<?php if (sizeof($maps) > 0): ?>
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
				<input type="hidden" name="dags" id="dags">
				<input type="hidden" name="area" id="area">
				<input type="hidden" name="case_type" id="case_type" value="<?= $case_type ?>">
				<?php if ($case_type == 'NC_TO_C'): ?>
					<input type="hidden" name="merge_with_c_village" id="merge_with_c_village" value="<?= htmlspecialchars(json_encode($merge_village_requests[0] ?? []), ENT_QUOTES, 'UTF-8') ?>">
					<input type="hidden" name="map_row" id="map_row" value="<?= htmlspecialchars(json_encode($map_row ?? []), ENT_QUOTES, 'UTF-8') ?>">
				<?php endif; ?>
				<button type="submit" class="btn btn-secondary py-2" style="color: white;">
					<i class='fa fa-eye'></i> View Bhunaksha Map
				</button>
			</span>
			<?php if ($case_type != 'NC_TO_C'): ?>
				<button type="button" id="syncDagsWithBhunakshaBtn" class="btn btn-info py-2" style="color: white; display:none;" onclick="syncDagsWithBhunaksha()">
					<i class='fa fa-sync'></i> Sync Dags With Bhunaksha
				</button>
			<?php endif; ?>
			<?php echo form_close(); ?>
			<input type="hidden" id="application_no_updated" value="">
			<div style="height: 60vh;overflow-y:auto;" class="border mb-2">
				<table class="table table-striped table-bordered table-hover table-sm text-center">
					<thead class="bg-warning">
						<tr>
							<th>Sl.No.</th>
							<th>Dag</th>
							<th>Land Class</th>
							<th>Occupiers</th>
							<th>Area(B-K-L)</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="tbody">
					</tbody>
				</table>
			</div>
			<input type="hidden" id="application_no">
			<div id="chitha_data"></div>
			<?php
			if (count($merge_village_requests)):
			?>
				<div id="merge_village_data">
					<?php if ($case_type == 'NC_TO_NC') : ?>
						<h4>Village list to be merged</h4>
					<?php endif; ?>
					<?php if ($case_type == 'NC_TO_C') : ?>
						<span style="color: #136a6f; font-weight: bold;">
							Village <?= $locations['village']['loc_name'] ?> will be merged with
						</span>
					<?php endif; ?>
					<?php if ($case_type == 'NC_TO_NC') : ?>
						<table class="table table-striped table-bordered">
							<thead>
								<tr style="background-color: #136a6f; color: #fff">
									<th>Sl No</th>
									<th>Village Name</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($merge_village_requests as $key => $merge_village_request):
								?>
									<tr>
										<td><?= ($key + 1) ?></td>
										<td><?= $merge_village_request['vill_loc']['village']['loc_name'] ?></td>
									</tr>
								<?php
								endforeach;
								?>
							</tbody>
						</table>
					<?php else: ?>
						<?php
						if (!empty($merge_village_requests)) {
							foreach ($merge_village_requests as $key => $merge_village_request):
						?>
								<div style="margin-bottom:10px; padding:10px; border:1px solid #136a6f; border-radius:5px; background:#f8f9fa;">
									<!-- <span style="font-weight:bold; color:#136a6f;">Village <?= ($key + 1) ?>:</span> -->
									<span><?= $merge_village_request['vill_loc']['village']['loc_name'] ?></span>
								</div>
						<?php
							endforeach;
						} else {
							echo "<div class='text-danger'>No village found.</div>";
						}
						?>
					<?php endif; ?>
				</div>
			<?php
			endif;
			?>
			<div id="final_submit"></div>
		</div>

	</div>
</div>

<!--	Modal for dag verify-->
<div class="modal" id="chitha_and_map" tabindex="-1" aria-labelledby="chitha_and_map" data-backdrop="static" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-xl" style="width: 100% !important;">
		<div class="modal-content">
			<div class="modal-header p-2">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" onclick="closeModal()">&times;</span>
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
							<th>Circle Name:</th>
							<td class="text-warning">
								<strong class="alert-warning">
									<input type="text" name="circle_name" value='<?= $this->utilityclass->getCircleName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code']) ?>' class="form-control input-sm" readonly>
								</strong>
							</td>
							<th>Mouza Name:</th>
							<td class="text-warning">
								<strong class="alert-warning">
									<input type="text" name="mouza_name" class="form-control input-sm" value='<?= $this->utilityclass->getMouzaName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code']) ?>' readonly>
								</strong>
							</td>
						</tr>
						<tr>
							<th>Lot Name:</th>
							<td class="text-warning">
								<strong class="alert-warning">
									<input type="text" name="lot_name" value='<?= $this->utilityclass->getLotLocationName($locations['dist']['dist_code'], $locations['subdiv']['subdiv_code'], $locations['circle']['cir_code'], $locations['mouza']['mouza_pargona_code'], $locations['lot']['lot_no']) ?>' class="form-control input-sm" readonly>
								</strong>
							</td>
							<th>Village Name:</th>
							<td class="text-warning">
								<strong class="alert-warning">
									<input type="text" name="village_name" id="location_village_name" value='' class="form-control input-sm" readonly>
								</strong>
							</td>
						</tr>
					</table>
				</div>

				<h5 class="reza-title">
					<i class="fa fa-file"></i> View Chitha
					<span id="view_chitha_button"></span>
				</h5>
				<div id="message"></div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="closeModal()" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i> Close
				</button>
				<span id="verifyAndSave"></span>
			</div>
		</div>
	</div>
</div>

<!--	Modal for village dag chitha verify-->
<div class="modal" id="modal_vill_dag_chitha" tabindex="-1" aria-labelledby="modal_vill_dag_chitha" data-backdrop="static" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-xl" style="width: 100% !important;">
		<div class="modal-content">
			<div class="modal-header p-2">
				<h5 class="modal-title"><i class="fa fa-file"></i> Village Draft Chitha</h5>
			</div>
			<div class="modal-body">
				<div id="view_village_chitha"></div>
				<div id="message"></div>
			</div>

			<div class="modal-footer">
				<button type="button" id="cithaCloseBtn" onclick="closeModal()" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i> Close
				</button>
				<button type="button" id="reGenerateChitha" onclick="reGenerateChitha()" class="btn btn-success btn-sm"><i class='fa fa-recycle'></i> Re-Generate Chitha
				</button>
				<span id="lm_verify_btn"></span>
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
							<?php foreach ($maps as $key => $map_single): ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td>
										<a href="<?= base_url() . 'index.php/nc_village/NcVillageLmController/viewUploadedMap?id=' . $map_single->id ?>" class="btn btn-info py-2" style="color: white" target="_blank">
											View Map
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
							<?php if (count($maps) == 0): ?>
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
<button id="loader2" class="btn btn-primary invisible">
	<span class="spinner-border spinner-border-sm"></span>
	Loading..
</button>
<style>
	#loader2 {
		position: fixed;
		z-index: 999999;
		/* High z-index so it is on top of the page */
		top: 50%;
		right: 50%;
		/* or: left: 50%; */
		margin-top: -..px;
		/* half of the elements height */
		margin-right: -..px;
		/* half of the elements width */
	}
</style>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>

<script>
	const SK_LABEL = "<?= SK_LABEL ?>";
	const CASE_TYPE = "<?= $case_type ?>";
	const merge_village_requests = <?= json_encode($merge_village_requests) ?>;
	var MERGE_WITH_C_VILLAGE = '';
	if (merge_village_requests && merge_village_requests.length > 0) {
		const mv = merge_village_requests[0];
		MERGE_WITH_C_VILLAGE = {
			dist_code: mv.request_dist_code || '',
			subdiv_code: mv.request_subdiv_code || '',
			cir_code: mv.request_cir_code || '',
			mouza_pargona_code: mv.request_mouza_pargona_code || '',
			lot_no: mv.request_lot_no || '',
			vill_townprt_code: mv.request_vill_townprt_code || ''
		};
	}
	var map_row = <?= json_encode($map_row) ?>;
	$(document).ready(function() {
		var vill_townprt_code = '<?php echo ($vill_townprt_code) ?>';
		$("select").attr("disabled", "disabled");
		getDags(vill_townprt_code);
	});

	/** get dags */
	function getDags(vill_townprt_code) {
		$('#loader2').removeClass('invisible');
		$('#vill_dags_chitha_button').html('');
		$('#total_dags').text('0');
		$('#total_verified').text('0');
		$('#final_submit').html('');
		$('#message').html('');
		$('#application_no').html('');
		var dist = $('#dist_code').val();
		var subdiv = $('#subdiv_code').val();
		var circle = $('#cir_code').val();
		var mouza = $('#mouza_pargona_code').val();
		var lot = $('#lot_no').val();
		var village = $('#vill_townprt_code').val();
		var vill_name = $('#vill_townprt_code option:selected').text();
		$('#location_village_name').val(vill_name);
		$.ajax({
			url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/getDags',
			method: "POST",
			async: true,
			dataType: 'json',
			data: {
				'dist_code': $('#dist_code').val(),
				'subdiv_code': $('#subdiv_code').val(),
				'cir_code': $('#cir_code').val(),
				'mouza_pargona_code': $('#mouza_pargona_code').val(),
				'lot_no': $('#lot_no').val(),
				'vill_townprt_code': vill_townprt_code,
				'application_no': $('#application_no_updated').val(),
				'case_type': CASE_TYPE,
				'merge_with_c_village': MERGE_WITH_C_VILLAGE,
				'min_dag_no': map_row['min_dag_no'],
				'max_dag_no': map_row['max_dag_no']
			},
			success: function(data) {
				$('#loader2').addClass('invisible');
				if (data.st == 0) {
					var row = '<tr>' +
						'<td colspan="7" class="text-center text-danger">' + data.errors + '</td>' +
						'</tr>'

					$('#tbody').html(row);
					swal({
						title: data.errors,
						icon: "warning",
					});
				} else if (data.data.length == 0) {
					var row = '<tr>' +
						'<td colspan="7" class="text-center">No Dags</td>' +
						'</tr>'

					$('#tbody').html(row);
					swal({
						title: "Bhunaksha data not found.",
						icon: "info",
					});
				} else {
					if (data.application_no) {
						$("#application_no_updated").val(data.application_no);
					}
					var button = '';
					var row = '';
					var i = 1;
					var total_verified = 0;
					data.data.forEach(dag => {
						if (data.status == null) {
							var viewButton = "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'><button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button> </a>";
							var editButton = "<button type='button' class='btn btn-sm btn-info' onclick='editDag(" + dag.dag_no + ',' + dag.block_code + ',' + dag.gp_code + ")'><i class='fa fa-edit'></i> Edit Dag</button"
							var button = viewButton + editButton;
						} else {
							if (dag.lm_verified != 'Y') {
								var viewButton = "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'><button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button> </a>";
								var editButton = "<button type='button' class='btn btn-sm btn-info' onclick='editDag(" + dag.dag_no + ',' + dag.block_code + ',' + dag.gp_code + ")'><i class='fa fa-edit'></i> Edit Dag</button"
								var button = viewButton + editButton;
							} else if (dag.lm_verified == 'Y') {
								total_verified++;
								var viewButton = "<span class='text-success'><b>Verified</b></span> " + "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'><button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button> </a>";
								var button = viewButton;
							}
							$('#application_no').html(dag.application_no);
						}
						occupiers = '';
						if (dag.occupiers) {
							dag.occupiers.forEach(occupier => {
								occupiers += occupier['encro_name'] + '</br>';
							});
						}

						row += "<tr>" +
							"<td>" + i++ + ')' + "</td>" +
							"<td>" + dag.dag_no + "</td>" +
							"<td>" + dag.full_land_type_name + "</td>" +
							"<td>" + occupiers + "</td>" +
							"<td>" + dag.dag_area_b + "-" + dag.dag_area_k + "-" + dag.dag_area_lc + "</td>" +
							"<td>" + button + "</td>" +
							"</tr>"
					});

					$('#tbody').html(row);
					$('#total_dags').text(data.data.length);
					$('#total_verified').text(total_verified);

					var chitha_area = '';
					if (data.chitha_data.chitha_total_area_skm < 2) {
						chitha_area = '<span>' +
							' The Chitha area is less than 2 (Square kilometre)' +
							'</span>';
					}
					var bhunaksa_area = '';
					if (data.chitha_data.bhunaksa_total_area_skm < 2) {
						<?php
						if (count($merge_village_requests)):
						?>
							bhunaksa_area = '<span>' +
								' The area is less than 2 (Square kilometre)' +
								'</span>';
						<?php
						else:
						?>
							bhunaksa_area = '<span>' +
								' The Bhunaksha area is less than 2 (Square kilometre)' +
								'</span>';
						<?php
						endif;
						?>
					}

					var chitha_data = '<table class="table table-striped table-bordered">' +
						'<thead>' +
						'<th colspan="2" style="background-color: #136a6f; color: #fff">' +
						'Village Details' +
						'</th>' +
						'</thead>' +
						'<tbody>' +
						'<tr>' +
						'<span style="display:none;" type="hidded" id="chitha_total_dag"> ' + data.chitha_data.chitha_total_dag + '</span>' +
						'<span style="display:none;" type="hidded" id="chitha_total_area"> ' + data.chitha_data.chitha_total_area_skm + '</span>' +
						'<td width="25%">Total Dags</td>' +
						'<td width="25%" style="color:red" id="bhunaksa_total_dag">' + data.chitha_data.bhunaksa_total_dag + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td width="25%">Village Area (sq km)</td>' +
						'<td width="25%" style="color:red" id="bhunaksa_total_area">' + data.chitha_data.bhunaksa_total_area_skm + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td class="text-danger font-weight-bold" colspan="2">' +
						bhunaksa_area +
						'</td>' +
						'</tr>' +
						'</tbody>' +
						'</table>';
					<?php
					if (count($merge_village_requests)):
					?>
					<?php
					else:
					?>
						// var chitha_data = '<table class="table table-striped table-bordered">' +
						// 	'<thead>' +
						// 	'<th colspan="2" style="background-color: #136a6f; color: #fff">' +
						// 	'Chitha Details' +
						// 	'</th>' +
						// 	'<th colspan="2" style="background-color: #136a6f; color: #fff">' +
						// 	'Bhunaksha Details' +
						// 	'</th>' +
						// 	'</thead>' +
						// 	'<tbody>' +
						// 	'<tr>' +
						// 	'<td width="25%">Total Dags</td>' +
						// 	'<td width="25%" style="color:red" id="chitha_total_dag">' + data.chitha_data.chitha_total_dag + '</td>' +
						// 	'<td width="25%">Total Dags</td>' +
						// 	'<td width="25%" style="color:red" id="bhunaksa_total_dag">' + data.chitha_data.bhunaksa_total_dag + '</td>' +
						// 	'</tr>' +
						// 	'<tr>' +
						// 	'<td width="25%">Chitha Area (sq km)</td>' +
						// 	'<td width="25%" style="color:red" id="chitha_total_area">' + data.chitha_data.chitha_total_area_skm + '</td>' +
						// 	'<td width="25%">Bhunakha Area (sq km)</td>' +
						// 	'<td width="25%" style="color:red" id="bhunaksa_total_area">' + data.chitha_data.bhunaksa_total_area_skm + '</td>' +
						// 	'</tr>' +
						// 	'<tr>' +
						// 	'<td class="text-danger font-weight-bold" colspan="2">' +
						// 	chitha_area+
						// 	'</td>' +
						// 	'<td class="text-danger font-weight-bold" colspan="2">' +
						// 	bhunaksa_area+
						// 	'</td>' +
						// 	'</tr>'+
						// 	'</tbody>' +
						// 	'</table>';	
					<?php
					endif;
					?>

					$('#chitha_data').html(chitha_data);
					$('#dags').val(data.chitha_data.bhunaksa_total_dag);
					$('#area').val(data.chitha_data.bhunaksa_total_area_skm);


					if (data.lm_verified != 'Y') {
						$('#syncDagsWithBhunakshaBtn').show();
						var final_submit = '<table width="100%"><tr>' +
							'<th class="font-weight-bold"> <b>Remark <span class="text-red">*</span></b> </th></tr>' +
							'<tr><td width="100%"><textarea style="width: 100%" class="form-control" id="remark" name="remark" placeholder="Enter Remark" required>Ground verification of map along with the record is done and also verified its edges with the adjacent cadastral village boundaries and found correct.</textarea></td>' +
							'</tr></table>' +
							'<div id="error"></div>' +
							'<div class="text-center"><button type="button" onclick="finalSubmit()" class="btn btn-primary mt-3 text-center"><i class="fa fa-check"></i> Save and Forward to ' + SK_LABEL + '</button></div>';

						$('#final_submit').html(final_submit);
					} else if (data.lm_verified == 'Y') {
						$('#syncDagsWithBhunakshaBtn').hide();
						$('#final_submit').html("<h3 class='text-success'>Draft Chitha and Map forwarded to " + SK_LABEL + ".</h3>");
					}

					// Village Dags Chitha Button
					$('#vill_dags_chitha_button').html('<button type="button" class="btn btn-primary" onclick="villDagsChithaButton()"><i class="fa fa-file"></i> Generate Chitha</button>');
				}
			}
		});
	}

	/** verify chitha and map modal **/
	function verifyChithaAndMap(patta_no, patta_type_code, dag_no, village, lm_verified = null) {
		$('#modal_vill_townprt_code').val(village);
		$('#modal_patta_no').val(patta_no);
		$('#modal_patta_type_code').val(patta_type_code);
		$('#modal_dag_no').val(dag_no);

		$('#message').html('');

		/** chitha view btn **/
		var base_url = "<?php echo base_url(); ?>";
		var view_chitha = '<a target="_blank" href="' + base_url + 'index.php/Chithacontrol/generateDagChitha?case_no=4&dag=' + dag_no + '&m=' + $('#mouza_pargona_code').val() + '&l=' + $('#lot_no').val() + '&v=' + village + '&p=' + patta_type_code + '&dist=' + $('#dist_code').val() + '&cir=' + $('#cir_code').val() + '&sub_div=' + $('#subdiv_code').val() + '">' +
			'<button type="button" class="btn btn-primary">Click Here</button>' +
			'</a>';

		// var view_map = '<a target="_blank" href="' + base_url + 'index.php/Chithacontrol/getMapBhunaksha?dag=' + dag_no + '&m=' + $('#mouza_pargona_code').val() + '&l=' + $('#lot_no').val() + '&v=' + village + '&p=' + patta_type_code + '&dist=' + $('#dist_code').val() + '&cir=' + $('#cir_code').val() + '&sub_div=' + $('#subdiv_code').val() + '">' +
		// 		'<button type="button" class="btn btn-primary">Click Here</button>' +
		// 		'</a>';

		$('#view_chitha_button').html(view_chitha);
		// $('#view_map_button').html(view_map);

		/** verify btn **/
		var save_and_verify_btn = '';
		if (lm_verified != 'Y') {
			save_and_verify_btn = "<button type='button' onclick='verifyAndSave(\"" + patta_no + "\",\"" + patta_type_code + "\",\"" + dag_no + "\",\"" + village + "\")' class='btn btn-primary'> <i class='fa fa-check'></i> Verify and Save</button>";
		} else if (lm_verified == 'Y') {
			$('#message').html('<h3 class="text-success">ALREADY VERIFIED BY LM.</h3>')
		}
		$('#verifyAndSave').html(save_and_verify_btn);

		$('#chitha_and_map').modal('show');
	}

	/** close modal **/
	function closeModal() {
		$('#chitha_and_map').modal('hide');
		$('#modal_vill_dag_chitha').modal('hide');
	}

	/***  Verify and Save **/
	function verifyAndSave(patta_no, patta_type_code, dag_no, village) {
		$('#error').html('');
		$.confirm({
			title: 'Confirm',
			content: 'Please Confirm for Verify Dag',
			type: 'orange',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Confirm',
					btnClass: 'btn-info',
					action: function() {
						$.ajax({
							url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/updateDag',
							method: "POST",
							async: true,
							dataType: 'json',
							data: {
								'dist_code': $('#dist_code').val(),
								'subdiv_code': $('#subdiv_code').val(),
								'cir_code': $('#cir_code').val(),
								'mouza_pargona_code': $('#mouza_pargona_code').val(),
								'lot_no': $('#lot_no').val(),
								'vill_townprt_code': village,
								'patta_no': patta_no,
								'patta_type_code': patta_type_code,
								'dag_no': dag_no
							},
							success: function(data) {
								if (data.st == '0') {
									alert('#NC0005 Unable to verify dag.');
									return;
								}
								if (data.data.length == 0) {
									var row = '<tr>' +
										'<td colspan="5" class="text-center">No Dags</td>' +
										'</tr>'

									$('#tbody').html(row);
								} else {
									var row = '';
									var button = '';
									var i = 1;
									var total_verified = 0;
									data.data.forEach(dag => {
										// if (dag.lm_verified != 'Y') {
										// 	// button = "<button type='button' id='" + dag.patta_no + "_" + dag.patta_type_code + "_" + dag.dag_no + "' class='btn btn-danger'" +
										// 	// 	" onclick='verifyChithaAndMap(\"" + dag.patta_no + "\",\"" + dag.patta_type_code + "\",\"" + dag.dag_no + "\",\"" + village + "\",\"" + dag.lm_verified + "\");'>" +
										// 	// 	"<i class='fa fa-close'></i> Verify</span></button>";
										// 	var viewButton = "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'> <button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button> </a>";
										// 	var editButton = "<button onclick='editDag()' class='btn btn-info btn-sm'><i class='fa fa-edit'></i> Edit Chitha</button></a>";
										// } else {
										// 	total_verified++;
										// 	// button = "<button type='button' id='" + dag.patta_no + "_" + dag.patta_type_code + "_" + dag.dag_no + "' class='btn btn-success'" +
										// 	// 	" onclick='verifyChithaAndMap(\"" + dag.patta_no + "\",\"" + dag.patta_type_code + "\",\"" + dag.dag_no + "\",\"" + village + "\",\"" + dag.lm_verified + "\");'>" +
										// 	// 	"<i class='fa fa-check'></i> Verified</span></button>";
										// 	var viewButton = "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'> <button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button> </a>";
										// 	var editButton = "<button onclick='editDisableAlert()' class='btn btn-info btn-sm'><i class='fa fa-edit'></i> Edit Chitha</button>";
										// }
										// row += "<tr>" +
										// 	"<td>" + i++ + ')' + "</td>" +
										// 	"<td>" + dag.dag_no + "</td>" +
										// 	"<td>" + dag.patta_no + "</td>" +
										// 	"<td>" + dag.dag_area_b + "-" + dag.dag_area_k + "-" + dag.dag_area_lc + "</td>" +
										// 	"<td>" + viewButton + "</td>" +
										// 	"</tr>"
									});

									$('#tbody').html(row);
									$('#application_no').html(data.application_no);
									$('#application_no_updated').val(data.application_no);
									$('#total_dags').text(data.data.length);
									$('#total_verified').text(total_verified);

									if (data.lm_verified != 'Y') {
										var final_submit = '<table width="100%"><tr>' +
											'<th class="font-weight-bold"> Remark <span class="text-red">*</span> </th></tr>' +
											'<tr><td width="100%"><textarea style="width: 100%" class="form-control" id="remark" name="remark" placeholder="Enter Remark" required></textarea></td>' +
											'</tr></table>' +
											'<div id="error"></div>' +
											'<div class="text-center"><button type="button" onclick="finalSubmit()" class="btn btn-primary mt-3 text-center"><i class="fa fa-check"></i> Save and Forward to CO</button></div>';

										$('#final_submit').html(final_submit);
									} else if (data.lm_verified == 'Y') {
										$('#final_submit').html("<h3 class='text-success'>Draft Chitha and Map forwarded to " + SK_LABEL + ".</h3>");
									}

									$.confirm({
										title: 'Success',
										content: 'Dag Verified Successfully.',
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
									$('#chitha_and_map').modal('hide');
								}
							}
						});
					}
				},
				Cancel: {
					text: 'Cancel',
					btnClass: 'btn-danger',
					action: function() {
						return;
					}
				},
			}
		});
	}

	/** final submit **/
	function finalSubmit() {
		$('#error').html('');
		var chitha_total_dag = $('#chitha_total_dag').text().trim();
		var chitha_total_area = $('#chitha_total_area').text().trim();
		var bhunaksa_total_dag = $('#bhunaksa_total_dag').text().trim();
		var bhunaksa_total_area = $('#bhunaksa_total_area').text().trim();

		// if(chitha_total_dag != bhunaksa_total_dag)
		// {
		// 	swal({
		// 		title: "The total number of dags in the Chitha does not match the total number of dags in the Bhunaksha.",
		// 		icon: "info",
		// 	});
		// 	return;
		// }
		// if(chitha_total_area < 2)
		// {
		// 	swal({
		// 		title: "The Chitha area is less than 2 (Square kilometre).",
		// 		icon: "info",
		// 	});
		// 	return;
		// }
		// if(bhunaksa_total_area < 2)
		// {
		// 	swal({
		// 		title: "The Bhunaksha area is less than 2 (Square kilometre).",
		// 		icon: "info",
		// 	});
		// 	return;
		// }

		$.confirm({
			title: 'Confirm',
			content: 'Please Confirm for Final Submission',
			type: 'orange',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Confirm',
					btnClass: 'btn-info',
					action: function() {
						$.ajax({
							url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/lmFinalSubmit',
							method: "POST",
							async: true,
							dataType: 'json',
							data: {
								'dist_code': $('#dist_code').val(),
								'subdiv_code': $('#subdiv_code').val(),
								'cir_code': $('#cir_code').val(),
								'mouza_pargona_code': $('#mouza_pargona_code').val(),
								'lot_no': $('#lot_no').val(),
								'vill_townprt_code': $('#vill_townprt_code').val(),
								'remark': $('#remark').val(),
								'map_id': '<?= $map_id ?>'
							},
							success: function(data) {
								if (data.st == '0') {
									$('#error').html('<span class="text-danger">' + data.errors + '</span>')
									return;
								}
								if (data.submitted == 'Y') {
									$('#final_submit').html("<h3 class='text-success'>Draft Chitha and Map forwarded to " + SK_LABEL + ".</h3>");
									$.confirm({
										title: 'Success',
										content: data.msg,
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
										title: 'Warning',
										content: data.msg,
										type: 'orange',
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
							}
						});
					}
				},
				Cancel: {
					text: 'Cancel',
					btnClass: 'btn-danger',
					action: function() {
						return;
					}
				},
			}
		});

	}

	function progress(dags) {
		if (dags == 'complete') {
			$('#village_dag_chitha_progress').css('width', '100%');
			$('#village_dag_chitha_progress').html('100%');
		} else {

			$('#village_dag_chitha_progress').animate({
				width: '90%'
			}, {
				duration: dags * 10,
				easing: 'linear',
				step: function(now, fx) {
					if (fx.prop == 'width') {
						$('#village_dag_chitha_progress').html((Math.round(now * 100) / 100 + '%'));
					}
				},
			});
		}

	}

	/** VIEW VILLAGE DAGS CHITHA Generate **/
	function villDagsChithaButton() {
		$('#reGenerateChitha').prop('disabled', true);
		$('#lm_verify_btn').html('');
		$('#modal_vill_dag_chitha').modal('show');
		$("#view_village_chitha").html('');
		$("#view_village_chitha").html('<h3 class="text-danger text-center">Please Wait.....</h3> <div class="progress"><div id="village_dag_chitha_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div></div>');
		$.ajax({
			url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/generateVillageDagsChitha',
			method: "POST",
			async: true,
			dataType: 'json',
			data: {
				'dist_code': $('#dist_code').val(),
				'subdiv_code': $('#subdiv_code').val(),
				'cir_code': $('#cir_code').val(),
				'mouza_pargona_code': $('#mouza_pargona_code').val(),
				'lot_no': $('#lot_no').val(),
				'vill_townprt_code': $('#vill_townprt_code').val()
			},
			beforeSend: function() {
				progress($('#total_dags').html());
			},
			success: function(data) {
				$.ajax({
					url: progress("complete"),
					success: function() {
						if (data.upload === true) {
							$("#view_village_chitha").html('');

							var base_url = "<?php echo base_url(); ?>";

							var randomInt = Math.floor(Math.random() * 100000); // Generate a random integer
							var pdfUrl = base_url + "nc_village_data/nc_village_chitha_pdf/" + data.file_name + "/" + data.file_name + ".pdf";
							var fullUrl = pdfUrl + "?rand=" + randomInt;

							$('#view_village_chitha').html("<iframe width='100%' height='500px;' src='" + fullUrl + "'></iframe>")

							var lm_verify_btn = '';
							if (data.lm_verified != 'Y') {
								lm_verify_btn = "<button type='button' onclick='lm_verified(\"" + $('#vill_townprt_code').val() + "\")' class='btn btn-primary btn-sm'><i class='fa fa-check'></i> Verify Draft Chitha</button>";
								$('#reGenerateChitha').prop('disabled', false);
							} else if (data.lm_verified == 'Y') {
								lm_verify_btn = '<h3 class="text-success">Draft Chitha and Map already verified by LM.</h3>';
								$('#reGenerateChitha').prop('disabled', true);
							}
							$('#lm_verify_btn').html(lm_verify_btn);
						}
						swal({
							title: "Please regenerate the Draft Chitha if the Bhunaksha data and Chitha data do not match.",
							icon: "info",
						});
					}
				})

			}
		});
	}

	/** LM Verified dags chitha **/
	function lm_verified() {
		$('#error').html('');
		var dist = $('#dist_code').val();
		var subdiv = $('#subdiv_code').val();
		var circle = $('#cir_code').val();
		var mouza = $('#mouza_pargona_code').val();
		var lot = $('#lot_no').val();
		var village = $('#vill_townprt_code').val();
		var chitha_total_dag = $('#chitha_total_dag').text().trim();
		var bhunaksa_total_dag = $('#bhunaksa_total_dag').text().trim();
		// if(chitha_total_dag != bhunaksa_total_dag)
		// {
		// 	swal({
		// 		title: "The total number of dags in the Chitha does not match the total number of dags in the Bhunaksha.",
		// 		icon: "info",
		// 	});
		// 	return;
		// }
		$.confirm({
			title: 'Confirm',
			content: 'Please Confirm for Verify Draft Chitha',
			type: 'orange',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Confirm',
					btnClass: 'btn-info',

					action: function() {
						$.ajax({
							url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/verifyDagsChithaAndSave',
							method: "POST",
							async: true,
							dataType: 'json',
							data: {
								'dist_code': $('#dist_code').val(),
								'subdiv_code': $('#subdiv_code').val(),
								'cir_code': $('#cir_code').val(),
								'mouza_pargona_code': $('#mouza_pargona_code').val(),
								'lot_no': $('#lot_no').val(),
								'vill_townprt_code': $('#vill_townprt_code').val(),
								'chitha_total_dag': $('#chitha_total_dag').text(),
								'chitha_total_area': $('#chitha_total_area').text(),
								'bhunaksa_total_dag': $('#bhunaksa_total_dag').text(),
								'bhunaksa_total_area': $('#bhunaksa_total_area').text(),
							},
							success: function(data) {
								if (data.error == 'Y') {
									alert(data.error.msg);
									return;
								}

								if (data.error == 'YY') {
									swal({
										title: "Please Re-generate the Draft Chitha.",
										icon: "info",
									});
									return;
								}

								var row = '';
								var button = '';
								var i = 1;
								var total_verified = 0;
								data.data.forEach(dag => {
									if (dag.lm_verified === null) {
										var viewButton = "<a target='_blank' href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "'><button class='btn btn-secondary btn-sm'> <i class='fa fa-eye'></i> View Chitha</button></a>";
										var editButton = "<button onclick='editDag()' class='btn btn-info btn-sm'><i class='fa fa-edit'></i> Edit Chitha</button></a>";
										var button = viewButton + editButton;
									} else if (dag.lm_verified == 'Y') {
										total_verified++;
										button = "<span class='text-success'><b>Verified</b></span>" +
											" <a href='" + base_url + "index.php/Chithacontrol/generateDagChitha?dist=" + dist + "&cir=" + circle + "&sub_div=" + subdiv + "&m=" + mouza + "&l=" + lot + "&v=" + village + "&dag=" + dag.dag_no + "&p=" + dag.patta_type_code + "' target='_blank'><button type='button' class='btn btn-secondary btn-sm'><i class='fa fa-eye'></i> View Chitha</button></a>";
									}
									occupiers = '';
									if (dag.occupiers) {
										dag.occupiers.forEach(occupier => {
											occupiers += occupier['encro_name'] + '</br>';
										});
									}
									row += "<tr>" +
										"<td>" + i++ + ')' + "</td>" +
										"<td>" + dag.dag_no + "</td>" +
										"<td>" + dag.full_land_type_name + "</td>" +
										"<td>" + occupiers + "</td>" +
										"<td>" + dag.dag_area_b + "-" + dag.dag_area_k + "-" + dag.dag_area_lc + "</td>" +
										"<td>" + button + "</td>" +
										"</tr>"
								});

								$('#tbody').html(row);
								$('#application_no').html(data.application_no);
								$('#application_no_updated').val(data.application_no);
								$('#total_dags').text(data.data.length);
								$('#total_verified').text(total_verified);

								var lm_verify_btn = '';
								if (data.lm_verified != 'Y') {
									lm_verify_btn = "<button type='button' onclick='lm_verified(\"" + $('#vill_townprt_code').val() + "\")' class='btn btn-primary'><i class='fa fa-check'></i> Verify All and Save</button>";
								} else if (data.lm_verified == 'Y') {
									lm_verify_btn = '<h3 class="text-success">CHITHA ALREADY VERIFIED BY LM.</h3>';
								}
								$('#lm_verify_btn').html(lm_verify_btn);

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
								$('#modal_vill_dag_chitha').modal('hide');
							}
						});
					}
				},
				Cancel: {
					text: 'Cancel',
					btnClass: 'btn-danger',
					action: function() {
						return;
					}
				},
			}
		});
	}

	/** LM Re Generate Draft chitha **/
	function reGenerateChitha() {
		var vill_townprt_code = '<?php echo ($vill_townprt_code) ?>';
		$('#error').html('');
		$.confirm({
			title: 'Confirm',
			content: 'Please Confirm for Re Generate Draft Chitha',
			type: 'orange',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Confirm',
					btnClass: 'btn-info',
					action: function() {
						$('#cithaCloseBtn').prop('disabled', true);
						$('#reGenerateChitha').prop('disabled', true);
						$('#lm_verify_btn').html('');
						$("#view_village_chitha").html('');
						$("#view_village_chitha").html('<h3 class="text-danger text-center">Please Wait.....</h3><div class="progress"><div id="village_dag_chitha_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div></div>');

						$.ajax({
							url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/regenerateChitha',
							method: "POST",
							async: true,
							dataType: 'json',
							data: {
								'dist_code': $('#dist_code').val(),
								'subdiv_code': $('#subdiv_code').val(),
								'cir_code': $('#cir_code').val(),
								'mouza_pargona_code': $('#mouza_pargona_code').val(),
								'lot_no': $('#lot_no').val(),
								'vill_townprt_code': $('#vill_townprt_code').val(),
								'case_type': CASE_TYPE,
								'merge_with_c_village': MERGE_WITH_C_VILLAGE,
								'min_dag_no': map_row['min_dag_no'],
								'max_dag_no': map_row['max_dag_no']
								// 'application_no': $('#application_no').val()
							},
							beforeSend: function() {
								progress($('#total_dags').html());
							},
							success: function(data) {
								if (data.st == 0) {
									swal({
										title: data.errors,
										icon: "warning",
									});
									return;
								}
								if (data.update === false) {
									alert(data.msg);
									return;
								}

								$.confirm({
									title: 'Success',
									content: 'Draft Chitha Regenerated Successfully.',
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
								// $('#modal_vill_dag_chitha').modal('hide');
								$('#cithaCloseBtn').prop('disabled', false);
								$('#reGenerateChitha').prop('disabled', false);
								// villDagsChithaButton();
								$("#view_village_chitha").html('');

								var base_url = "<?php echo base_url(); ?>";
								var randomInt = Math.floor(Math.random() * 100000); // Generate a random integer
								var pdfUrl = base_url + "nc_village_data/nc_village_chitha_pdf/" + data.file_name + "/" + data.file_name + ".pdf";
								var fullUrl = pdfUrl + "?rand=" + randomInt;
								$('#view_village_chitha').html("<iframe width='100%' height='500px;' src='" + fullUrl + "'></iframe>")

								var lm_verify_btn = '';
								if (data.lm_verified != 'Y') {
									lm_verify_btn = "<button type='button' onclick='lm_verified(\"" + $('#vill_townprt_code').val() + "\")' class='btn btn-primary btn-sm'><i class='fa fa-check'></i> Verify Draft Chitha</button>";
									$('#reGenerateChitha').prop('disabled', false);
								} else if (data.lm_verified == 'Y') {
									lm_verify_btn = '<h3 class="text-success">Draft Chitha and Map already verified by LM.</h3>';
									$('#reGenerateChitha').prop('disabled', true);
								}
								$('#lm_verify_btn').html(lm_verify_btn);
								getDags(vill_townprt_code);
							}
						});
					}
				},
				Cancel: {
					text: 'Cancel',
					btnClass: 'btn-danger',
					action: function() {
						return;
					}
				},
			}
		});
	}

	function editDisableAlert() {
		swal({
			title: "Dag Already Verified. To Edit Dag, Kindly Regenerate Chitha!",
			icon: "info",
		});
	}

	function editDag(dagNo, blockCode, gramPanchayat) {
		$('#error').html('');
		dagNumber = dagNo;
		blockCode = blockCode;
		gramPanchayat = gramPanchayat;
		$.confirm({
			title: 'Confirm',
			content: 'Are you sure you want to Edit Dag - ' + dagNumber + '?',
			type: 'red',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Yes, Edit',
					btnClass: 'btn-primary',
					action: function() {

						$.confirm({
							title: 'Confirm',
							content: 'After making changes, please refresh the page to see the updated details.',
							type: 'blue',
							typeAnimated: true,
							buttons: {
								Confirm: {
									text: 'OK',
									btnClass: 'btn-primary',
									action: function() {

										// Collect the form data
										var formData = {
											'dist_code': $('#dist_code').val(),
											'subdiv_code': $('#subdiv_code').val(),
											'cir_code': $('#cir_code').val(),
											'mouza_pargona_code': $('#mouza_pargona_code').val(),
											'lot_no': $('#lot_no').val(),
											'vill_townprt_code': $('#vill_townprt_code').val(),
											'block_code': blockCode,
											'gram_Panch_code': gramPanchayat,
											'dagNumber': dagNumber,
											'lm_edit': 'Y'
										};

										$.ajax({
											dataType: "json",
											url: "<?= base_url(); ?>index.php/SvamitvaCardController/submitLocation",
											data: formData,
											type: "POST",
											success: function(data) {
												if (data.st == 1) {
													swal("", data.msg, "success")
														.then((value) => {
															sessionStorage.setItem('dagNumberSession', dagNumber);
															window.open("<?= base_url(); ?>index.php/SvamitvaCardController/dagDetails", "_blank");
														});
												} else {
													swal("", 'Editing Not Allowed', "info");

												}
											},
											error: function(xhr, status, error) {
												// Handle errors if any
												console.error('Error:', error);
											}
										});
									}
								},

							}
						});
					}
				},
				Cancel: {
					text: 'No, Cancel',
					btnClass: 'btn-info',
					action: function() {
						return;
					}
				},
			}
		});
	}

	function viewMaps() {
		$('#modal_show_map_list').modal('show');
	}

	function syncDagsWithBhunaksha() {
		$('#vill_dags_chitha_button').html('');
		$('#total_dags').text('0');
		$('#total_verified').text('0');
		$('#final_submit').html('');
		$('#message').html('');
		$('#application_no').html('');
		var dist = $('#dist_code').val();
		var subdiv = $('#subdiv_code').val();
		var circle = $('#cir_code').val();
		var mouza = $('#mouza_pargona_code').val();
		var lot = $('#lot_no').val();
		var vill_townprt_code = '<?php echo ($vill_townprt_code) ?>';

		$.confirm({
			title: 'Are you sure you want to sync Dag with Bhunaksha?',
			content: 'You will have to generate chitha again!',
			type: 'red',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Yes, Sync',
					btnClass: 'btn-primary',
					action: function() {
						$('#loader2').removeClass('invisible');
						var formData = {
							'dist_code': $('#dist_code').val(),
							'subdiv_code': $('#subdiv_code').val(),
							'cir_code': $('#cir_code').val(),
							'mouza_pargona_code': $('#mouza_pargona_code').val(),
							'lot_no': $('#lot_no').val(),
							'vill_townprt_code': $('#vill_townprt_code').val(),
							'case_type': CASE_TYPE,
							'merge_with_c_village': MERGE_WITH_C_VILLAGE,
							'min_dag_no': map_row['min_dag_no'],
							'max_dag_no': map_row['max_dag_no']
						};

						$.ajax({
							dataType: "json",
							url: '<?= base_url(); ?>index.php/nc_village/NcVillageLmController/syncDagsWithBhunakshaAgain',
							data: formData,
							type: "POST",
							success: function(response) {
								if (response.success) {
									swal({
										title: response.message,
										icon: "success",
									});
									setTimeout(() => {
										location.reload(true);
									}, 2000);
								} else {
									swal({
										title: response.message,
										icon: "error",
									});
								}
							},
							error: function(xhr, status, error) {
								// Handle errors if any
								console.error('Error:', error);
							},
							complete: function() {
								$('#loader2').addClass('invisible');
							}
						});
					}
				},
				Cancel: {
					text: 'No, Cancel',
					btnClass: 'btn-info',
					action: function() {
						return;
					}
				},
			}
		});
	}
</script>