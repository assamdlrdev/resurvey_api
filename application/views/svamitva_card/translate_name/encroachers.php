<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
	<div class="card rounded-0">
		<div class="card-header rounded-0 py-1 bg-info text-center">
			<h5>
				<i class="fa fa-map-marker" aria-hidden="true"></i> Encroachers List For Encroachers Name Transliteration
			</h5>
		</div>
		<div class="card-body">
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
							<option value="<?= $locations['village']['vill_townprt_code'] ?>"><?= $locations['village']['loc_name'] ?></option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-12 mt-3">
				<h5> Encroachers <span id="application_no" class="bg-gradient-danger text-white"></span> (Total Encroachers :
					<b id="total_dags"><?= sizeof($encroachers) ?></b> , Updated :
					<b class="text-success"><?= sizeof($encroachers) - sizeof($null_encroachers) ?></b>)
				</h5>
				<div style="height: 60vh;overflow-y:auto;" class="border mb-2">
					<table class="table table-striped table-hover table-sm">
						<thead class="bg-warning">
						<tr>
							<th>Sl.No.</th>
							<th>Dag No</th>
							<th>Encroacher English Name</th>
							<th>Encroacher Assamese Name</th>
							<th>Guardian English Name</th>
							<th>Guardian Assamese Name</th>
						</tr>
						</thead>
						<tbody id="tbody">
						<?php
						foreach ($encroachers as $key=>$e){ ?>
						<tr>
							<td><?= ++$key; ?></td>
							<td><?= $e['dag_no']; ?></td>
							<td><?= $e['encro_name']; ?></td>
							<td><?= $e['encro_name_as']; ?></td>
							<td><?= $e['encro_guardian']; ?></td>
							<td><?= $e['encro_guardian_as']; ?></td>
						</tr>
						<?php } if(sizeof($encroachers) == 0){?>
						<tr>
							<td colspan="100%" class="text-center">No Data Found</td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<style>
					#progress-bar-container {
						width: 100%;
						background-color: #f0f0f0;
						border-radius: 5px;
						overflow: hidden;
						margin: 10px 0;
					}

					#progress-bar {
						width: 0%;
						height: 20px;
						background-color: #4CAF50;
						text-align: center;
						line-height: 20px;
						color: black;
					}
				</style>
				<div class="my-4 text-center">
					<?php if(sizeof($null_encroachers) != 0) {?>
						<button type="button" id="submit_btn" onclick="submit()" class="btn btn-primary">
							<i class="fa fa-save" aria-hidden="true"></i> Transliteration
						</button>
						<div id="progress-bar-container">
							<div id="progress-bar"></div>
						</div>
						<span id="progress-bar-container-span" class="text-center font-weight-bold">0 OUT OF <?= sizeof($null_encroachers) ?></span>
					<?php } else {?>
						<div class="bg-gradient-blue text-white">ALREADY UPDATED ALL ENCROACHERS NAME.</div>
					<?php } ?>
					<div id="error_msg" class="text-center text-danger p-2"></div>
					<div id="success_msg" class="text-center text-success"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
	function submit() {
		$.confirm({
			title: 'Confirm',
			content: 'Are you sure want to transliteration Encroachers Name',
			type: 'orange',
			typeAnimated: true,
			buttons: {
				Confirm: {
					text: 'Confirm',
					btnClass: 'btn-info',
					action: function () {
						var encrochers = '<?= json_encode($null_encroachers) ?>';
						var dataArray = JSON.parse(encrochers);

						$('#submit_btn').prop('disabled', true)
						$('#success_msg').html('')
						$('#error_msg').html('')
						var progressBar = $('#progress-bar');
						var progressBarContainerSpan = $('#progress-bar-container-span');
						progressBar.css('width', '0/'+dataArray.length);

						function submitData(data, callback) {
							$.ajax({
								url: '<?= base_url(); ?>index.php/svamitva_card/TranslateController/nameTranslate',
								type: 'POST',
								async: true,
								dataType: 'json',
								data: { array: data },
								success: function (response) {
									if(response.error.error == 'Y')
									{
										$('#error_msg').append('<p>Dang No.: '+response.error.dag_no+' , Encroacher Name :'+response.error.encro_name+'</p>' )
										callback();
									}
									else if(response.error.error == 'YES')
									{
										$('#error_msg').html('<p><b>ERROR: '+response.error.msg+'</b></p>' )
										$('#submit_btn').prop('disabled', false)
									}
									else {
										callback();
									}
								},
								error: function (error) {
									$('#error_msg').append('<p>ERROR: Something went wrong.</p>' )
									$('#submit_btn').prop('disabled', false)
								}
							});
						}

						function processDataArray(index) {
							if (index < dataArray.length)
							{
								var data = dataArray[index];

								submitData(data, function () {
									var progressPercentage = ((index + 1) / dataArray.length) * 100;
									progressBar.css('width', progressPercentage + '%');
									// progressBar.html(index + 1+' OUT OF '+dataArray.length);
									progressBarContainerSpan.html(index + 1+' OUT OF '+dataArray.length);
									processDataArray(index + 1);

								});

							} else {
								$('#success_msg').html('<button class="btn btn-success">All data processed! Please Reload the Page</button>')
								// console.log("All data processed!");
							}

						}

						processDataArray(0);
					}
				},
				Cancel: {
					text: 'Cancel',
					btnClass: 'btn-danger',
					action: function () {
						return;
					}
				},
			}
		});
	}
</script>
