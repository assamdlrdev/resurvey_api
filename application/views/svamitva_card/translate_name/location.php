<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
	<div class="card rounded-0">
		<div class="card-header rounded-0 py-1 bg-info text-center">
			<h5>
				<i class="fa fa-map-marker" aria-hidden="true"></i> Location Details For Encroachers Name Translate
			</h5>
		</div>
		<div class="card-body">
			<form action="<?= base_url() ?>index.php/svamitva_card/TranslateController/viewEncroachers" method="post">
				<div class="row border border-info p-3">
					<input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">District:</label>
							<select name="dist_code" class="form-control form-control-sm" id="d">
								<option selected value="">Select District</option>

								<?php foreach ($districts as $value) { ?>
									<option value="<?= $value['dist_code'] ?>"><?= $value['loc_name'] ?></option>
								<?php } ?>
							</select>
							<span class="text-danger"><?php echo form_error('dist_code'); ?></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Sub-Div:</label>
							<select name="subdiv_code" class="form-control form-control-sm" id="sd">
								<option value="">Select Sub Division</option>
							</select>
							<span class="text-danger"><?php echo form_error('subdiv_code'); ?></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Circle:</label>
							<select name="cir_code" class="form-control form-control-sm" id="c">
								<option value="">Select Circle</option>
							</select>
							<span class="text-danger"><?php echo form_error('cir_code'); ?></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Mouza/Porgona:</label>
							<select name="mouza_pargona_code" class="form-control form-control-sm" id="m">
								<option value="">Select Mouza </option>
							</select>
							<span class="text-danger"><?php echo form_error('mouza_pargona_code'); ?></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Lot:</label>
							<select name="lot_no" class="form-control form-control-sm" id="l">
								<option value="">Select Lot </option>
							</select>
							<span class="text-danger"><?php echo form_error('lot_no'); ?></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="sel1">Village:</label>
							<select name="vill_townprt_code" class="form-control form-control-sm" id="v">
								<option value="">Select Village </option>
							</select>
							<span class="text-danger"><?php echo form_error('vill_townprt_code'); ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right" style="margin-top: 20px">
						<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
						<button type='submit' class="btn btn-info" name="submit" value="Submit">
							<i class="fa fa-check-square-o" aria-hidden="true"></i> Submit
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>

<script>
	$('#d').change(function() {
		var baseurl = $("#base").val();
		var id = $(this).val();
		$.ajax({
			url: baseurl + "index.php/svamitva_card/TranslateController/getSubdivs",
			method: "POST",
			data: {
				id: id
			},
			async: true,
			dataType: 'json',
			beforeSend: function() {
				$('#sd').prop('selectedIndex', 0);
				$.blockUI({
					message: $('#displayBox'),
					css: {
						border: 'none',
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data) {
				$.unblockUI();
				var html = '';
				var i;
				html += '<option value="">Select Subdivision</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].subdiv_code + '>' + data[i].loc_name + '</option>';
				}
				$('#sd').html(html);
			},
			error: function(jqXHR, exception) {
				$.unblockUI();
				$('#sd').prop('selectedIndex', 0);
				alert('Could not Complete your Request ..!, Please Try Again later..!');
			}
		});
		return false;
	});

	$('#sd').change(function() {
		var baseurl = $("#base").val();
		var dis = $('#d').val();
		var subdiv = $(this).val();
		$.ajax({
			url: baseurl + "index.php/svamitva_card/TranslateController/getCircles",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv
			},
			async: true,
			dataType: 'json',
			beforeSend: function() {
				$('#c').prop('selectedIndex', 0);
				$.blockUI({
					message: $('#displayBox'),
					css: {
						border: 'none',
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data) {
				$.unblockUI();
				var html = '';
				var i;
				html += '<option value="">Select Circle</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].cir_code + '>' + data[i].loc_name + '</option>';
				}
				$('#c').html(html);
			},
			error: function(jqXHR, exception) {
				$.unblockUI();
				$('#c').prop('selectedIndex', 0);
				alert('Could not Complete your Request ..!, Please Try Again later..!');
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
			url: baseurl + "index.php/svamitva_card/TranslateController/getMouzas",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv,
				cir: cir
			},
			async: true,
			dataType: 'json',
			beforeSend: function() {
				$('#m').prop('selectedIndex', 0);
				$.blockUI({
					message: $('#displayBox'),
					css: {
						border: 'none',
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data) {
				$.unblockUI();
				var html = '';
				var i;
				html += '<option value="">Select Mouza</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].mouza_pargona_code + '>' + data[i].loc_name + '</option>';
				}
				$('#m').html(html);
			},
			error: function(jqXHR, exception) {
				$.unblockUI();
				$('#m').prop('selectedIndex', 0);
				alert('Could not Complete your Request ..!, Please Try Again later..!');
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
			url: baseurl + "index.php/svamitva_card/TranslateController/getLots",
			method: "POST",
			data: {
				dis: dis,
				subdiv: subdiv,
				cir: cir,
				mza: mza
			},
			async: true,
			dataType: 'json',
			beforeSend: function() {
				$('#l').prop('selectedIndex', 0);
				$.blockUI({
					message: $('#displayBox'),
					css: {
						border: 'none',
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data) {
				$.unblockUI();
				var html = '';
				var i;
				html += '<option value="">Select Lot</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].lot_no + '>' + data[i].loc_name + '</option>';
				}
				$('#l').html(html);
			},
			error: function(jqXHR, exception) {
				$.unblockUI();
				$('#l').prop('selectedIndex', 0);
				alert('Could not Complete your Request ..!, Please Try Again later..!');
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
			url: baseurl + "index.php/svamitva_card/TranslateController/getVillages",
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
			beforeSend: function() {
				$('#v').prop('selectedIndex', 0);
				$.blockUI({
					message: $('#displayBox'),
					css: {
						border: 'none',
						backgroundColor: 'transparent'
					}
				});
			},
			success: function(data) {
				$.unblockUI();
				var html = '';
				var i;
				html += '<option value="">Select Village</option>';
				for (i = 0; i < data.length; i++) {
					html += '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name + '</option>';
				}
				$('#v').html(html);
			},
			error: function(jqXHR, exception) {
				$.unblockUI();
				$('#v').prop('selectedIndex', 0);
				alert('Could not Complete your Request ..!, Please Try Again later..!');
			}
		});
		return false;
	});
</script>