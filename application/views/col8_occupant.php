<!DOCTYPE html>

<html lang="en">

<head>
	<title>Dharitee Data Entry</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->load->view('header'); ?>

	<style>
		.row {
			margin-left: -5px;
			margin-right: -5px;

		}
	</style>

	<script src="<?= base_url('assets/js/common.js') ?>"></script>
	<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
		<script src="<?= base_url('assets/js/bengali.js') ?>"></script>
	<?php } else { ?>
		<script src="<?= base_url('assets/js/assamese.js') ?>"></script>
	<?php } ?>
</head>

<body>
	<!--div class="container bg-light p-0 border border-dark"-->
	<div class="container-fluid mt-3 mb-2 font-weight-bold">
		<?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
		<?php echo $daghd ?><?php echo $landhd ?>
		<div class="col-12 px-0 pb-3">
			<div class="bg-info text-white text-center py-2">
				<h3>Enter Occupant Details(Column 8)</h3>
			</div>
		</div>
		<form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
			<div class="row">

				<div class="col-sm-6">

					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-4 col-form-label">Occupant ID:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="occupant_id" id="occupant_id" value="<?php echo $occupid ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword3" class="col-sm-4 col-form-label">Col8Order Serial no:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="col8order_cron_no" id="col8order_cron_no" value="<?php echo $col8crno ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-4 col-form-label">Occupant's Name:</label>
						<div class="col-sm-8">
							<select name="occupant_name" id="occp_nm" class="form-control" onchange="getocuupant()" ;>
								<option selected value="">Select</option>
								<?php foreach ($occupnm as $row) {
									$xx = $row->pdar_name . '/' . $row->pdar_father . '/' . $row->pdar_relation . '/' . $row->dag_por_b . '/' . $row->dag_por_k . '/' . $row->dag_por_lc;

								?>
									<option value="<?php echo $xx; ?>">
										<?php echo $row->pdar_name; ?></option>
								<?php } ?>
							</select>

						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword3" class="col-sm-4 col-form-label">Guardian:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="occupant_fmh_name" id="guardian" readonly>
						</div>
					</div>

					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-4 col-form-label">Relationship:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="relation" id="relation" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword3" class="col-sm-4 col-form-label">Address 1:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="occupant_add1" id="adress1" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
						</div>
					</div>
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-4 col-form-label">Address 2:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="address2" name="occupant_add2" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
						</div>
					</div>
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-4 col-form-label">Address 3:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="address3" name="occupant_add3" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
						</div>
					</div>

				</div>
				<?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
					<div class="col-sm-6">
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Bigha:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="bigha" name="land_area_b" value="0">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Katha:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="katha" name="land_area_k" value="0">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Chetak:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="lessa" name="land_area_lc" value="0">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Ganda:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="ganda" name="land_area_g" value="0">
							</div>
						</div>
					<?php } else { ?>
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Bigha:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="bigha" name="land_area_b" value="0">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Katha:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="katha" name="land_area_k" value="0">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword3" class="col-sm-4 col-form-label">Land Area Lessa:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="lessa" name="land_area_lc" value="0">
								</div>
							</div>
							<input type="hidden" class="form-control" id="ganda" name="land_area_g" value="0">
						<?php } ?>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Old Patta No:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="opattano" name="old_patta_no">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">New Patta No:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="npattano" name="new_patta_no">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">Old Dag No:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="odagno" name="old_dag_no">
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-4 col-form-label">New Dag No:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="ndagno" name="new_dag_no">
							</div>
						</div>
						</div>
					</div>
					<div class="col-12 text-center pb-3">
						<input type="hidden" name="base" id="base" value='<?php echo $base ?>' /><input type="hidden" name="dag_no" id="dag_no" value='<?php echo $dag_no ?>' /><input type="hidden" name="occupant_fmh_flag" id="pdarrel" /><input type="hidden" name="occupantnm" id="occupantnm" />
						<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
						<input type="button" class="btn btn-primary" id="osubmit" name="osubmit" value="Submit" onclick="occupent();"></input>
						<input type="button" class="btn btn-primary" id="onext" name="onext" value="Tenant"></input>
					</div>
		</form>
	</div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>