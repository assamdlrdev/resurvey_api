<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<?php include APPPATH . '/views/user/message.php'; ?>
	<div class="card rounded-0">
		<div class="card-header rounded-0 text-center bg-info py-1">
			<h5>
				<i class="fa fa-map-marker" aria-hidden="true"></i> Select Location for Bulk Jama Pattdar Update
			</h5>
		</div>
		<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
		<form action="<?php echo $base ?>index.php/jamabandi/JamaPattadarController/jamaPattdarGetChithaPattadars" method="post" enctype="multipart/form-data">
			<div class="card-body">
				<div class="row border border-info p-3">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">District:</label>
							<select name="dist_code" class="form-control" id="d">
								<option selected value="">Select District</option>
								<?php foreach ($districts as $value) { ?>
									<option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
								<?php } ?>
							</select>
							<?php echo form_error('dist_code'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Sub-Div:</label>
							<select name="subdiv_code" class="form-control" id="sd">
								<option value="">Select Sub Division </option>
							</select>
							<?php echo form_error('subdiv_code'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Circle:</label>
							<select name="cir_code" class="form-control" id="c">
								<option value="">Select Circle </option>
							</select>
							<?php echo form_error('cir_code'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Mouza/Porgona:</label>
							<select name="mouza_pargona_code" class="form-control" id="m">
								<option value="">Select Mouza </option>
							</select>
							<?php echo form_error('mouza_pargona_code'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Lot:</label>
							<select name="lot_no" class="form-control" id="l">
								<option value="">Select Lot </option>
							</select>
							<?php echo form_error('lot_no'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="v">Village:</label>
							<select name="vill_townprt_code" class="form-control" id="v">
								<option value="">Select Village </option>
							</select>
							<?php echo form_error('vill_townprt_code'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
				<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
				<div class="text-right">
					<button type='submit' class="btn btn-primary">SUBMIT</button>
				</div>
			</div>
		</form>
	</div>
</div>
<br>
</div>
<!--<script src="--><? //= base_url('assets/js/location.js')
					?><!--"></script>-->
<script>
	$('#d').change(function() {
		var baseurl = $("#base").val();
		var id = $(this).val();
		$.ajax({
			url: baseurl + "index.php/common/AdminLocationController/getSubdivs",
			method: "POST",
			data: {
				id: id
			},
			async: true,
			dataType: 'json',
			success: function(data) {
				var html = '';
				var i;
				html += '<option value="">Select Subdivision</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
				}
				$('#sd').html(html);
			}
		});
		return false;
	});
	$('#sd').change(function() {
		var baseurl = $("#base").val();
		var dis = $('#d').val();
		var subdiv = $(this).val();
		$.ajax({
			url: baseurl + "index.php/common/AdminLocationController/getCircles",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv
			},
			async: true,
			dataType: 'json',
			success: function(data) {
				var html = '';
				var i;
				html += '<option value="">Select Circle</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
				}
				$('#c').html(html);
			}
		});
		return false;
	});
	$('#c').change(function() {
		var baseurl = $("#base").val();
		var dis = $('#d').val();
		var subdiv = $('#sd').val();
		var cir = $(this).val();
		$.ajax({
			url: baseurl + "index.php/common/AdminLocationController/getMouzas",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv,
				cir: cir
			},
			async: true,
			dataType: 'json',
			success: function(data) {
				var html = '';
				var i;
				html += '<option value="">Select Mouza</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
				}
				$('#m').html(html);
			}
		});
		return false;
	});
	$('#m').change(function() {
		var baseurl = $("#base").val();
		var dis = $('#d').val();
		var subdiv = $('#sd').val();
		var cir = $('#c').val();
		var mza = $(this).val();
		$.ajax({
			url: baseurl + "index.php/common/AdminLocationController/getLots",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv,
				cir: cir,
				mza: mza
			},
			async: true,
			dataType: 'json',
			success: function(data) {
				var html = '';
				var i;
				html += '<option value="">Select Lot</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
				}
				$('#l').html(html);
			}
		});
		return false;
	});
	$('#l').change(function() {
		var baseurl = $("#base").val();
		var dis = $('#d').val();
		var subdiv = $('#sd').val();
		var cir = $('#c').val();
		var mza = $('#m').val();
		var lot = $(this).val();
		$.ajax({
			url: baseurl + "index.php/common/AdminLocationController/getVillages",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv,
				cir: cir,
				mza: mza,
				lot: lot
			},
			async: true,
			dataType: 'json',
			success: function(data) {
				var html = '';
				var i;
				html += '<option value="">Select Village</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name + '</option>';
				}
				$('#v').html(html);
			}
		});
		return false;
	});

	function checkloc() {
		var baseurl = $("#base").val();
		$.ajax({
			dataType: "json",
			url: baseurl + "index.php/common/AdminLocationController/locationSubmit",
			data: $('form').serialize(),
			type: "POST",
			success: function(data) {
				console.log(data.location);
				if (data.st == 1) {
					swal("", data.msg, "success")
						.then((value) => {
							// window.location = baseurl + "index.php/jamabandiController/jamaPattdarGetChithaPattadars?d="+;
						});
				} else {
					swal("", data.msg, "info");

				}
			}
		});
	}
</script>
<script src="<?php echo base_url('assets/js/swal.min.js') ?>"></script>