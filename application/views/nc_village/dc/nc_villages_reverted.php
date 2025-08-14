<script src="<?php echo base_url('assets/plugins/alpinejs/alpinejs3.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.css') ?>">
<script src="<?= base_url('assets/plugins/jquery-confirm-v3.3.4/jquery-confirm.min.js') ?>"></script>
<script>
	function alpineData() {
		return {
			'villages': [],
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
				var self = this;
				this.getVillages();
			},

			getVillages() {
				var self = this;
				this.selected_villages = [];
				this.is_loading = true;
				$.ajax({
					url: '<?= base_url(); ?>index.php/nc_village/NcVillageDcController/getVillagesH',
					method: "POST",
					async: true,
					dataType: 'json',
					success: function(data) {
						self.villages = data;
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
<div class="col-lg-12 mt-3" x-data="alpineData()">
	<div class="text-center p-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">NC VILLAGE</div>
	<div class="card">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mb-1">
				<div>
					<h5> REVERTED VILLAGES BY DLR
						<small class="text-dark" x-text="'Total Villages : ' + villages.length"></small>
					</h5>
				</div>
			</div>
			<table class="table table-hover table-sm">
				<thead class="bg-warning">
				<tr>
					<th>Village Name</th>
					<th>CO Verified at</th>
					<th>DC Note</th>
					<th>Reverted Note</th>
					<th>Action</th>
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
					<tr>
						<td x-text="village.loc_name"></td>
						<td x-text="village.co_verified_at"></td>
						<td x-text="village.dc_note"></td>
						<td>
							<span x-show="village.adlr_note" x-text="'ADLR Note: '+village.adlr_note"></span><br>
							<span x-show="village.dlr_note" x-text="'DLR Note: '+village.dlr_note"></span>
						</td>
						<td>
							<a class="btn btn-sm btn-info text-white" x-bind:href=base_url+"index.php/nc_village/NcVillageDcController/showDags?application_no="+village.application_no>Proceed <i class=" fa fa-chevron-right"></i></a>
						</td>
					</tr>
				</template>
				<tr x-show="villages.length == 0">
					<td colspan="4" class="text-center">
						<span>No Villages Found</span>
					</td>
				</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>
