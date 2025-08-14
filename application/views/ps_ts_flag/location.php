<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
	function alpineData() {
		return {
			'dist_code': '',
			'subdiv_code': '',
			'cir_code': '',
			'mouza_pargona_code': '',
			'lot_no': '',
			'vill_townprt_code': '',
			'flag': '',
			'base_url': "<?= base_url(); ?>",
			'is_loading': false,
			'filter_status': 'pending',
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
			},
			getLots(){
				var self = this;
				this.is_loading = true;
				$.ajax({
					url: "<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/lotdetails",
					method: "POST",
					data: {
						mza: self.mouza_pargona_code,
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
						self.is_loading = false;
					}
				});
			},
			getVillages(){
				var self = this;
				this.is_loading = true;
				$.ajax({
					url: "<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/villagedetails",
					method: "POST",
					data: {
						mza: self.mouza_pargona_code,
						lot: self.lot_no
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var i;
						html += '<option value="">Select Village</option>';
						for (i = 0; i < data.length; i++) {
							if(data[i].count > 0)
							{
								var option = '<option disabled style="color: red; font-weight: bold" value=' + data[i].vill_townprt_code + '>' + data[i].loc_name +'</option>';
							}else {
								var option = '<option value=' + data[i].vill_townprt_code + '>' + data[i].loc_name +'</option>';
							}
							html += option;
						}
						$('#v').html(html);
						self.is_loading = false;
					}
				});
			},
			submitFlag() {
				var self = this;
				this.is_loading = true;
				$.confirm({
					title: 'Confirm',
					content: 'Please confirm to change the village flag',
					type: 'orange',
					typeAnimated: true,
					buttons: {
						Confirm: {
							text: 'Confirm',
							btnClass: 'btn-success',
							action: function() {
								$.ajax({
									url: '<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/submitFlag',
									method: "POST",
									async: true,
									dataType: 'json',
									data: {
										'mouza_pargona_code': self.mouza_pargona_code,
										'lot_no': self.lot_no,
										'vill_townprt_code': self.vill_townprt_code,
										'flag': self.flag
									},
									success: function(data) {
										if (data.response === 1) {
											$.confirm({
												title: 'Success',
												content: 'Village Flag Successfully Updated.',
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
										}
										else if (data.response === 0) {
											if(data.dag == '0') {
												var msg = 'No dag found.';
											}else {
												var msg ='Error: Flag Update Fail.';
											}
											$.confirm({
												title: 'Error',
												content: msg,
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
										else {
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
										self.getVillages()
										self.is_loading = false;
									},
									error: () => {
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
		}
	}
</script>
<div class="container" x-data="alpineData()">
	<div id="displayBox" style="display: none;"><img src="<?=base_url();?>/assets/process.gif">
	</div>
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card" id="loc_save">
				<div class="card-body">
					<div class="col-12 px-0 pb-3">
						<div class="bg-info text-white text-center py-1">
							<h5>Select Village for PS TS Flag</h5>
						</div>
					</div>
					<div class="form-group">
						<label for="sel1">District:</label>
						<select x-model="dist_code" name="dist_code" class="form-control form-control-sm" id="d">
							<option value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Sub-Div:</label>
						<select x-model="subdiv_code" name="subdiv_code" class="form-control form-control-sm" id="sd">
							<option value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Circle:</label>
						<select x-model="cir_code"  name="cir_code" class="form-control form-control-sm" id="c">
							<option value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Mouza/Porgona:</label>
						<select x-model="mouza_pargona_code" @change="getLots" name="mouza_pargona_code" class="form-control form-control-sm" id="m">
							<option value="">Select Mouza </option>
							<?php foreach ($mouzas as $mouza) : ?>
								<option value="<?= $mouza['mouza_pargona_code'] ?>"><?= $mouza['loc_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Lot:</label>
						<select x-model="lot_no" @change="getVillages" name="lot_no" class="form-control form-control-sm" id="l">
							<option value="">Select Lot </option>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Village:</label>
						<select x-model="vill_townprt_code" name="vill_townprt_code" class="form-control form-control-sm" id="v">
							<option value="">Select Village </option>
						</select>
					</div>
					<div class="form-group">
						<label for="sel1">Flag:</label>
						<select x-model="flag" name="flag" class="form-control form-control-sm" id="f">
							<option value="">Select Flag</option>
							<option value="<?php echo PS_FLAG ?>">Permanent Settlement (PS FLAG)</option>
							<option value="<?php echo TS_FLAG ?>">Temporary Settlement (TS FLAG)</option>
						</select>
					</div>
					<div class="text-center">
						<span x-show="is_loading" style="color: red">Please wait...!</span><br>
						<input x-on:click="submitFlag" x-show="!is_loading" type='button' class="btn btn-primary" id="loc_save_btn" name="submit" value="Submit">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
