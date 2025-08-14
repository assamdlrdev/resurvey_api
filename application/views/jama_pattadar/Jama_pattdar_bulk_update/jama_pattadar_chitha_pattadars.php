<div class="container-fluid form-top login">
	<div class="row">
		<div class="col-lg-12 ">
			<div class="col-lg-12">
				<div class="well well-sm">
					<h2 style="text-align: center;">Jama Pattdar Update Village Wise</h2>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="panel panel-info">

					<div class="panel-body">
						<form method='post' id="update_pattadar">
							<div class="row">
								<table class="table table-striped table-bordered text-bold">
									<thead>
										<th colspan="6" style="background-color: #136a6f; color: #fff">Location
											Details
										</th>
									</thead>
									<tbody>
										<tr>
											<td>District</td>
											<td class="text-red">
												<?php echo $locationname['dist_name']['loc_name']; ?>
											</td>
											<td>Subdivision</td>
											<td class="text-red">
												<?php echo $locationname['subdiv_name']['loc_name']; ?>
											</td>
											<td>Circle</td>
											<td class="text-red">
												<?php echo $locationname['cir_name']['loc_name']; ?>
											</td>
										</tr>
										<tr>
											<td>Mouza</td>
											<td class="text-red">
												<?php echo $locationname['mouza_name']['loc_name']; ?>
											</td>
											<td>Lot No</td>
											<td class="text-red">
												<?php echo $locationname['lot']['loc_name']; ?>
											</td>
											<td>Village / Town</td>
											<td class="text-red">
												<?php echo $locationname['village']['loc_name']; ?>
											</td>
										</tr>
									</tbody>
								</table>

								<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">&nbsp;</div>

								<table class="table table-striped table-bordered text-bold">
									<thead>
										<th style="background-color: #136a6f; color: #fff" colspan="6">Patta Details
										</th>
									</thead>
									<tbody>
										<tr>
											<td>Sl No.</td>
											<td>Patta No:</td>
											<td>Patta Type:</td>
											<td>No of Pattadars:</td>
										</tr>
										<?php foreach ($chitha_pattadars as $key => $cp) { ?>
											<tr>
												<td><span class="text-danger"><?= ++$key ?></span></td>
												<td>
													<span class="text-danger">
														<?= $cp->patta_no; ?>
													</span>
												</td>
												<td>
													<span class="text-danger">
														<?= $cp->patta_type_code; ?>
													</span>
												</td>

												<td>
													<span class="text-danger">
														<?= $cp->pattadars; ?>
													</span>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>

								<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">&nbsp;</div>

								<div class="col-lg-12 pb-4">
									<center>
										<input type="hidden" name="dist_code" id="dist_code" value="<?= $dist_code ?>">
										<input type="hidden" name="subdiv_code" id="subdiv_code" value="<?= $subdiv_code ?>">
										<input type="hidden" name="cir_code" value="<?= $cir_code ?>" id="cir_code">
										<input type="hidden" name="mouza_pargona_code" value="<?= $mouza_pargona_code ?>" id="mouza_pargona_code">
										<input type="hidden" name="lot_no" value="<?= $lot_no ?>" id="lot_no">
										<input type="hidden" name="vill_townprt_code" value="<?= $vill_townprt_code ?>" id="vill_townprt_code">
										<div id="error_u_message"></div>
										<div id="submit_btn">
											<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
											<button type="submit" class="btn btn-sm btn-primary" id="submit_button"><i class='fa fa-check'></i>&nbsp;
												Update Pattadar Village Wise
											</button>
										</div>
									</center>
								</div>

								<hr style="border-bottom: 2px solid #000;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<script>
	$('#update_pattadar').submit(function(e) {
		e.preventDefault();
		if (!confirm(" Are you sure want to update?")) {
			return;
		}
		$.ajax({
			url: window.base_url + "index.php/update-jama-pattdar-from-chitha-pattadars",
			type: 'POST',
			data: $('#update_pattadar').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('#submit_button').hide();
				$('#error_u_message').html('Please Wait.....');
			},
			success: function(data) {
				if (data.error === false) {
					$('#error_u_message').html('');
					$('#error_u_message')
						.html('<div class="green bold p-2 center">' + data.msg +
							'<br><br>' +
							'<a href="' + window.base_url + 'index.php/set-location-for-jama-pattadar-bulk-update"> <button type="button" class="btn btn-primary">' +
							'<i class="fa fa-view"></i> Go to Previous Page</button></a>' +
							'</div>');
					// window.location.href = window.base_url + "index.php/set-location-for-jama-pattadar-bulk-update";
					return;
				}

				if (data.error === true) {
					$('#submit_button').show();
					$('#error_u_message').html('');
					$('#error_u_message')
						.html('<div class="bg-gradient-danger p-2 rounded">' + data.msg +
							'<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">&nbsp;</div></div>');
					return;
				}
			},
			error: function(jqXHR, exception) {
				$('#submit_button').show();
				$('#error_u_message').html('');
				alert('Error [#CHP101]: Could not Complete your Request (AJAX ERROR)..!');
			}
		});
	});
</script>