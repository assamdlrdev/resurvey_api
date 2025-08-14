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
			'vill_townprt_code_ts': '',
			'vill_townprt_code_ps': '',
			'dags_ts': [],
			'dags_ps': [],
			'base_url': "<?= base_url(); ?>",
			'is_loading': false,
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
					url: "<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/getBothVillages",
					method: "POST",
					data: {
						mza: self.mouza_pargona_code,
						lot: self.lot_no
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						var ts = data.ts;
						var html = '';
						var i;
						html += '<option value="">Select Village</option>';
						for (i = 0; i < ts.length; i++) {
							if(ts[i].flag > 0)
							{
								var option = '<option value=' + ts[i].vill_townprt_code + '>' + ts[i].loc_name +'</option>';
								html += option;
							}
						}
						$('#ts_v').html(html);

						var ps = data.ps;
						var html_ps = '';
						var ii;
						html_ps += '<option value="">Select Village</option>';
						for (ii = 0; ii < ps.length; ii++) {
							if(ps[ii].flag > 0)
							{
								var option_ps = '<option value=' + ps[ii].vill_townprt_code + '>' + ps[ii].loc_name +'</option>';
								html_ps += option_ps;
							}
						}
						$('#ps_v').html(html_ps);
						self.is_loading = false;
					}
				});
			},
			getTsDags()
			{
				var self = this;
				this.is_loading = true;
				$.ajax({
					url: "<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/getTsDags",
					method: "POST",
					data: {
						mouza_pargona_code: self.mouza_pargona_code,
						lot_no: self.lot_no,
						vill_townprt_code: self.vill_townprt_code_ts,
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						self.dags_ts = data;
						self.is_loading = false;
					}
				});
			},
			getPsDags()
			{
				var self = this;
				this.is_loading = true;
				$.ajax({
					url: "<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/getPsDags",
					method: "POST",
					data: {
						mouza_pargona_code: self.mouza_pargona_code,
						lot_no: self.lot_no,
						vill_townprt_code: self.vill_townprt_code_ps,
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						self.dags_ps = data;
						self.is_loading = false;
					}
				});
			},
			submitMerge() {
				var self = this;
				if (!this.mouza_pargona_code) {
					alert('Please select mouza name before proceeding');
					return;
				}
				if (!this.lot_no) {
					alert('Please select lot name before proceeding');
					return;
				}
				if (!this.vill_townprt_code_ts) {
					alert('Please select temporary settlement village before proceeding');
					return;
				}
				if (!this.vill_townprt_code_ps) {
					alert('Please select permanent settlement village before proceeding');
					return;
				}
				this.is_loading = true;
				$.confirm({
					title: 'Confirm',
					content: 'Please confirm to merge village to Permanent Settlement',
					type: 'orange',
					typeAnimated: true,
					buttons: {
						Confirm: {
							text: 'Confirm',
							btnClass: 'btn-success',
							action: function() {
								$.ajax({
									url: '<?= base_url(); ?>index.php/ps_ts_flag/PsTsController/submitMerge',
									method: "POST",
									async: true,
									dataType: 'json',
									data: {
										'mouza_pargona_code': self.mouza_pargona_code,
										'lot_no': self.lot_no,
										'vill_townprt_code_ts': self.vill_townprt_code_ts,
										'vill_townprt_code_ps': self.vill_townprt_code_ps
									},
									success: function(data) {
										if (data.error == false) {
											$.confirm({
												title: 'Success',
												content: 'Village Merge Successfully.',
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
										else if (data.error == true) {
											$.confirm({
												title: 'Error',
												content: data.msg,
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
										self.getPsDags()
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
<div class="col-lg-12 col-md-12" x-data="alpineData()">
	<div class="row justify-content-center mx-1 bg-white">
		<div class="text-center py-2" style="width: 100%; font-size:18px; font-weight: bold; background-color: #4298c9; color: yellow">Merge Settlement Villages</div>
		<div class="col-lg-12 py-2">
			<div class="row">
				<div class="col-lg-2">
					<label for="sel1">District:</label>
					<select x-model="dist_code" name="dist_code" class="form-control form-control-sm" id="d">
						<option value="<?= $locations['dist']['dist_code'] ?>"><?= $locations['dist']['loc_name'] ?></option>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="sel1">Sub-Div:</label>
					<select x-model="subdiv_code" name="subdiv_code" class="form-control form-control-sm" id="sd">
						<option value="<?= $locations['subdiv']['subdiv_code'] ?>"><?= $locations['subdiv']['loc_name'] ?></option>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="">Circle</label>
					<select x-model="cir_code"  name="cir_code" class="form-control form-control-sm" id="c">
						<option value="<?= $locations['circle']['cir_code'] ?>"><?= $locations['circle']['loc_name'] ?></option>
					</select>
				</div>
				<div class="col-lg-3">
					<label for="">Mouza</label>
					<select x-model="mouza_pargona_code" @change="getLots" name="mouza_pargona_code" class="form-control form-control-sm" id="m">
						<option value="">Select Mouza </option>
						<?php foreach ($mouzas as $mouza) : ?>
							<option value="<?= $mouza['mouza_pargona_code'] ?>"><?= $mouza['loc_name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-3">
					<label for="">Lot</label>
					<select x-model="lot_no" @change="getVillages" name="lot_no" class="form-control form-control-sm" id="l">
						<option value="">Select Lot </option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row px-2">
		<div class="col">
			<div class="card rounded-0">
				<div class="card-header rounded-0 text-center bg-info py-1" style="font-size:18px; ">
					Temporary Settlement
				</div>
				<div class="form-group row p-2">
					<label class="col-sm-4 col-form-label">Select Village:</label>
					<div class="col-sm-8">
						<select x-model="vill_townprt_code_ts" @change="getTsDags" class="form-control form-control-sm" id="ts_v">
							<option value="">Select Village </option>
						</select>
					</div>
				</div>

				<div class="card-body pt-0" id="report" style="max-height: 500px;overflow-y: auto;">
					<div class="table-responsive">
						<table class="table table-sm table-bordered p-0 table-striped">
							<thead>
							<tr class="bg-warning">
								<th>#</th>
								<th>Dag No</th>
								<th>Area (B-K-L)</th>
								<th>Occupiers & Families</th>
							</tr>
							</thead>
							<tbody>
							<span x-show="is_loading" style="color: red">Please wait...!</span><br>
							<template x-for="(dag,index) in dags_ts">
								<tr class="">
									<td class="px-1 py-0" x-text="index + 1"></td>
									<td class="px-1 py-0" x-text="dag.dag_no"></td>
									<td>
										B-<span x-text="dag.dag_area_b"></span>,
										K-<span x-text="dag.dag_area_k"></span>,
										L-<span x-text="dag.dag_area_lc"></span>
									</td>
									<td>
										<span x-show="dag.encroachers.length == 0">No Occupiers</span>
										<template x-for="(encroacher,index2) in dag.encroachers">
											<div class="row mb-1">
												<div class="col" style="background-color:#bfdbfe;">
													<small>
														<span x-text="index2 + 1"></span>.
														<span x-text="encroacher.encro_name"></span>
														(
														B-<span x-text="encroacher.encro_land_b"></span>,
														K-<span x-text="encroacher.encro_land_k"></span>,
														L-<span x-text="encroacher.encro_land_lc"></span>
														)
													</small>
												</div>
												<div class="col bg-indigo-light">
													<span x-show="encroacher.families.length == 0">No Family Details</span>
													<template x-for="(family_member,index3) in encroacher.families">
														<small>
															<span x-text="index3 + 1"></span>.
															<span x-text="family_member.occupier_fmember_name"></span>
														</small>
													</template>
												</div>
											</div>
										</template>
									</td>
								</tr>
							</template>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card rounded-0">
				<div class="card-header rounded-0 text-center bg-info py-1" style="font-size:18px; ">
					Permanent Settlement
				</div>
				<div class="form-group row p-2">
					<label class="col-sm-4 col-form-label">Select Village:</label>
					<div class="col-sm-8">
						<select x-model="vill_townprt_code_ps" @change="getPsDags" class="form-control form-control-sm" id="ps_v">
							<option value="">Select Village </option>
						</select>
					</div>
				</div>
				<div class="card-body pt-0" id="report" style="max-height: 500px;overflow-y: auto;">
					<div class="table-responsive">
						<table class="table table-sm table-bordered p-0 table-striped">
							<thead>
							<tr class="bg-warning">
								<th>#</th>
								<th>Dag No</th>
								<th>Area (B-K-L)</th>
								<th>Occupiers & Families</th>
							</tr>
							</thead>
							<tbody>
							<span x-show="is_loading" style="color: red">Please wait...!</span><br>
							<template x-for="(dag,index) in dags_ps">

								<tr class="">
									<td class="px-1 py-0" x-text="index + 1"></td>
									<td class="px-1 py-0" x-text="dag.dag_no"></td>
									<td>
										B-<span x-text="dag.dag_area_b"></span>,
										K-<span x-text="dag.dag_area_k"></span>,
										L-<span x-text="dag.dag_area_lc"></span>
									</td>
									<td>
										<span x-show="dag.encroachers.length == 0">No Occupiers</span>
										<template x-for="(encroacher,index2) in dag.encroachers">
											<div class="row mb-1">
												<div class="col" style="background-color:#bfdbfe;">
													<small>
														<span x-text="index2 + 1"></span>.
														<span x-text="encroacher.encro_name"></span>
														(
														B-<span x-text="encroacher.encro_land_b"></span>,
														K-<span x-text="encroacher.encro_land_k"></span>,
														L-<span x-text="encroacher.encro_land_lc"></span>
														)
													</small>
												</div>
												<div class="col bg-indigo-light">
													<span x-show="encroacher.families.length == 0">No Family Details</span>
													<template x-for="(family_member,index3) in encroacher.families">
														<small>
															<span x-text="index3 + 1"></span>.
															<span x-text="family_member.occupier_fmember_name"></span>
														</small>
													</template>
												</div>
											</div>
										</template>
									</td>
								</tr>
							</template>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center mb-4">
		<span x-show="is_loading" style="color: red; font-size: 25px">Please wait...!</span><br>
		<button class="btn btn-primary" x-on:click="submitMerge" x-show="!is_loading"><i class="fa fa-check"></i> Merge Temporary Settlement to Permanent Settlement</button>
	</div>

</div>

