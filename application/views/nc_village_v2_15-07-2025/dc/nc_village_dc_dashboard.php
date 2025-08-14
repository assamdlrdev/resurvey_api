<div class="col-lg-8">
	<div class="card casedisplay">
		<div class="card-header bg-info">
			<div class="card-title">
				NC VILLAGE
			</div>
		</div>
		<div class="card-body">
			<table class="table table-striped table-hover table-bordered">
				<tr class="">
					<td>Approval and Digital Signature in Chitha and Map (First Proceeding)</td>
					<td>
						<?php
						echo "<span class=\"badge badge-info\">$dsign_pending_count</span>";
						?>
					</td>
					<td><a class="btn btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageDcController/showVillages'; ?>" style="float:right">Go</a></td>
				</tr>
				<tr class="">
					<td>Proposal for approval and notification (Second Proceeding)</td>
					<td>
					<?php
						echo "<span class=\"badge badge-warning\">$dc_proposal_pending</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageDcController/showProposalVillages'; ?>" style="float:right">Go</a></td>
				</tr>
				<!-- <tr class="">
					<td>Forwarded to JDS for Name Change on Map</td>
					<td><?php
						echo "<span class=\"badge badge-danger\">forwarded_name_change</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village/NcVillageDcController/jdsVillages'; ?>" style="float:right">View</a></td>
				</tr> -->
				<tr class="">
					<td>Received from JDS after Name Change on Map</td>
					<td><?php
						echo "<span class=\"badge badge-primary\">$reverted_name_change</span>";
						?>
					</td>
					<td><a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageDcController/showVillages/3'; ?>" style="float:right">View</a></td>
				</tr>
				<tr class="">
					<td>Reverted Cases</td>
					<td><?php
						echo "<span class=\"badge badge-danger\">$reverted</span>";
						?>
					</td>
					<td>
						<a class="btn  btn-sm btn-info" href="<?php echo base_url() . 'index.php/nc_village_v2/NcVillageDcController/revertedVillages'; ?>" style="float:right">View</a>
					</td>
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