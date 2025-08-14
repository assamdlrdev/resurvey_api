<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
	function alpineData() {
		return {
			'dist_code': [],
			'subdiv_code': [],
			'subdiv_codes': [],
			'cir_code': '',
			'mouza_pargona_code': '',
			'lot_no': '',
			'circles': [],
			'mouzas': [],
			'lots': [],
			'villages': [],
			'base_url': "<?= base_url(); ?>",
			'is_loading': false,
			'filter_status': 'I',
			'columns': [],
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
				var self = this;
				this.$watch('dist_code', function() {
					self.subdiv_codes = [];
					self.circles = [];
					self.mouzas = [];
					self.lots = [];
					self.villages = [];
					self.getSubdiv();
					self.getVillages();
				});
				this.$watch('subdiv_code', function() {
					self.circles = [];
					self.mouzas = [];
					self.lots = [];
					self.villages = [];
					self.getCircles();
					self.getVillages();
				});
				this.$watch('cir_code', function() {
					self.mouzas = [];
					self.lots = [];
					self.villages = [];
					self.getMouzas();
					self.getVillages();
				});
				this.$watch('mouza_pargona_code', function() {
					self.lots = [];
					self.villages = [];
					self.getLots();
					self.getVillages();
				});
				this.$watch('lot_no', function() {
					self.getVillages();
				});
				this.$watch('filter_status', function() {
					self.getVillages();
				});
				var columns = '<?= json_encode($columns) ?>';
				this.columns = JSON.parse(columns);
			},
			getSubdiv() {
				var self = this;
				$.ajax({
					url: '<?= base_url(); ?>index.php/common/LocationController/subdivisiondetails',
					method: "POST",
					async: true,
					dataType: 'json',
					data: {
						'dis': self.dist_code,
					},
					success: function(data) {
						self.subdiv_codes = data;
					}
				});
			},
			getCircles() {
				var self = this;
				$.ajax({
					url: '<?= base_url(); ?>index.php/common/LocationController/circledetails',
					method: "POST",
					async: true,
					dataType: 'json',
					data: {
						'dis': self.dist_code,
						'subdiv': self.subdiv_code,
					},
					success: function(data) {
						self.circles = data;
					}
				});
			},
			getMouzas() {
				var self = this;
				$.ajax({
					url: '<?= base_url(); ?>index.php/common/LocationController/mouzadetails',
					method: "POST",
					async: true,
					dataType: 'json',
					data: {
						'dis': self.dist_code,
						'subdiv': self.subdiv_code,
						'cir': self.cir_code,
					},
					success: function(data) {
						self.mouzas = data;
					}
				});
			},
			getLots() {
				var self = this;
				$.ajax({
					url: '<?= base_url(); ?>index.php/common/LocationController/lotdetails',
					method: "POST",
					async: true,
					dataType: 'json',
					data: {
						'dis': self.dist_code,
						'subdiv': self.subdiv_code,
						'cir': self.cir_code,
						'mza': self.mouza_pargona_code
					},
					success: function(data) {
						self.lots = data;
					}
				});
			},

			getVillages() {
				var self = this;
				this.is_loading = true;
				$.ajax({
					url: '<?= base_url(); ?>index.php/nc_village/NcVillageReportController/getVillages',
					method: "POST",
					async: true,
					dataType: 'json',
					data: {
						'dist_code': self.dist_code,
						'subdiv_code': self.subdiv_code,
						'cir_code': self.cir_code,
						'mouza_pargona_code': self.mouza_pargona_code,
						'lot_no': self.lot_no,
						'filter': self.filter_status
					},
					success: function(data) {
						if (data) {
							self.villages = data;
						} else {
							self.villages = [];
						}
						self.is_loading = false;
					},
					error: () => {
						self.is_loading = false;
					}
				});
			},
		}
	}
</script>
<div class="col-lg-12 col-md-12" x-data="alpineData()">
	<div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE PROGRESS REPORT</div>
	<div x-data="alpineData()" class="row justify-content-center">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-2">
					<label for="">District</label>
					<select x-model="dist_code" id="district" class="form-control form-control-sm">
						<option value="">Select District</option>
						<?php foreach ($locations as $dis) : ?>
							<option value="<?= $dis['dist_code'] ?>"><?= $dis['loc_name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="">Sub-Division</label>
					<select x-model="subdiv_code" id="subdiv" class="form-control form-control-sm">
						<option value="">Select Sub-division</option>
						<template x-for="(sdc,index_subdiv_code) in subdiv_codes" :key="index_subdiv_code">
							<option x-bind:value="sdc.subdiv_code" x-text="sdc.loc_name"></option>
						</template>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="">Circle</label>
					<select x-model="cir_code" id="circle" class="form-control form-control-sm">
						<option value="">Select Circle</option>
						<template x-for="(circle,index_circle) in circles" :key="index_circle">
							<option x-bind:value="circle.cir_code" x-text="circle.loc_name"></option>
						</template>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="">Mouza</label>
					<select x-model="mouza_pargona_code" id="mouza" class="form-control form-control-sm">
						<option value="">Select Mouza</option>
						<template x-for="(mouza,index_mouza) in mouzas" :key="index_mouza">
							<option x-bind:value="mouza.mouza_pargona_code" x-text="mouza.loc_name"></option>
						</template>
					</select>
				</div>
				<div class="col-lg-2">
					<label for="">Lot</label>
					<select x-model="lot_no" id="lot" class="form-control form-control-sm">
						<option value="">Select Lot</option>
						<template x-for="(lot,index_lot) in lots" :key="index_lot">
							<option x-bind:value="lot.lot_no" x-text="lot.loc_name"></option>
						</template>
					</select>
				</div>
				<!-- <div class="col-lg-2">
					<label for="">Filter</label>
					<select x-model="filter_status" id="filter_status" class="form-control form-control-sm">
						<option value="I">Verified</option>
					</select>
				</div> -->
			</div>
		</div>
		<div class="col-lg-12 mt-3">
			<div class="card">
				<div class="card-body table-responsive">

					<table class="table table-hover table-sm table-bordered table-striped">
						<thead class="bg-warning">
							<tr class="no-wrap">
								<th>Sl</th>
								<th>Village</th>
								<th>Lot</th>
								<th>Mouza</th>
								<th>Circle</th>
								<th>LM </th>
								<th>SK </th>
								<th>CO </th>
								<th>DC </th>
								<template x-for="(col,index_col) in columns">
									<th x-text="col"></th>
								</template>
							</tr>
						</thead>
						<tbody>
							<tr x-show="is_loading">
								<td colspan="6" class="text-center">
									<div class="d-flex justify-content-center">
										<div class="spinner-border" role="status">
											<span class="sr-only">Loading...</span>
										</div>
									</div>
								</td>
							</tr>
							<template x-for="(village,index) in villages">
								<tr class="no-wrap">
									<td x-text="index+1"></td>
									<td x-text="village.loc_name"></td>
									<td x-text="village.lot_name"></td>
									<td x-text="village.mouza_name"></td>
									<td x-text="village.circle_name"></td>
									<td x-text="village.lm_name"></td>
									<td x-text="village.sk_name"></td>
									<td x-text="village.co_name"></td>
									<td x-text="village.dc_name"></td>
									<template x-for="(col,index_col) in columns">
										<th x-text="village[col]"></th>
									</template>
								</tr>
							</template>
							<tr x-show="villages.length == 0">
								<td colspan="6" class="text-center">
									<span>No villages Found</span>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>