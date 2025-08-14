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
					<td>Verify Draft Map (First Proceeding)</td>
					<td>
						<?php
						echo "<span class=\"badge badge-info\">$maps_count</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageCoController/showVillagesDraftMap'; ?>" style="float:right">Go</a></td>
				</tr>
				<tr class="">
					<td>Certification of Draft Chitha and Draft Map (Second Proceeding)</td>
					<td>
						<?php
						echo "<span class=\"badge badge-success\">$fowarded_sk_count</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageCoController/showVillages'; ?>" style="float:right">Go</a></td>
				</tr>
				<tr class="">
					<td>Proposal for approval and notification (Third Proceeding)</td>
					<td>
					<?php
						echo "<span class=\"badge badge-warning\">$co_proposal_pending</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageCoController/showProposalVillages'; ?>" style="float:right">Go</a></td>
				</tr>
				<tr class="">
					<td>Reverted Back from DC</td>
					<td>
						<?php
						echo "<span class=\"badge badge-danger\">$reverted</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageCoController/revertedVillages'; ?>" style="float:right">View</a></td>
				</tr>
				<tr class="table-danger">
					<td>Forwarded from JDS for Chitha Correction</td>
					<td>
						<?php
						echo "<span class=\"badge badge-danger\">$forwarded_jds_count</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageCoController/jdsForwardedVillages'; ?>" style="float:right">View</a></td>
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
