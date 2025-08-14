<div class="col-lg-8">
	<div class="card">
		<div class="card-header bg-info">
			<div class="card-title">
				NC VILLAGE
			</div>
		</div>
		<div class="card-body">
			<table class="table table-striped table-hover table-bordered">
				<tr class="">
					<td>Certification of Draft Chitha and Draft Map</td>
					<td>
						<?php
						echo "<span class=\"badge badge-success\">$fowarded_sk_count</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageSkController/showVillages'; ?>" style="float:right">Go</a></td>
				</tr>
				<tr class="">
					<td>Reverted Back from CO</td>
					<td>
						<?php
						echo "<span class=\"badge badge-danger\">$reverted</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageSkController/revertedVillages'; ?>" style="float:right">View</a></td>
				</tr>
				<tr class="">
					<td>Processed Cases</td>
					<td>
						<span class="badge badge-warning"><?= $processed_case_count; ?></span>
					</td>
					<td>
						<a class="btn btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageCommonController/processed_cases' ?>" style="float:right">view</a>
					</td>
				</tr>
				<tr class="">
					<td>Notifications</td>
					<td><?php
						echo "<span class=\"badge badge-success\">$notifications_count</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-success" href="<?php echo base_url() . 'index.php/nc-village-notifications'; ?>" style="float:right">View</a></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script>
	$(function() {

	});
</script>
