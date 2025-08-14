<div class="A4" style="">
	<div style="text-align: center; width: 100%;">
		<p style="text-align: center;">
			<b>
				GOVERNMENT OF ASSAM<br>
				REVENUE & D.M. DEPARTMENT<br>
				(SURVEY & SETTLEMENT BRANCH)<br>
				DISPUR::GUWAHATI
			</b>
		</p>
	</div>

	<div style="padding-top: 10px;">
		<p style="text-align: center;">
			<b><u>ORDERS BY THE GOVERNOR<br>
					NOTIFICATION</u></b>
		</p>
	</div>

	<div style="padding-top: 10px; padding-right: 20px;">
		<p style="text-align: right;"><b>Dated Dispur the <span class="approved_date"><?= date('d') ?>/<?= date('m') ?>, <?= date('Y') ?></span> </b></p>
	</div>

	<div style="padding-top: 15px;">
		<p align="justify" style="">
			<b>eCF No.<?= $ecf_no; ?>,</b>On completion of the survey by the Director of Surveys, Assam, under Section 18 of the Assam Land & Revenue Regulation, 1886, Governor of Assam is pleased to notify the following Non-Cadastral villages as Cadastral villages as noted below:
		</p>
	</div>

	<table style="border: 1px solid black;" cellpadding="3" style="border-collapse: collapse; width:100%;">
		<tbody>
			<tr >
				<td style="border: 1px solid black;">
					Sl No.
				</td>
				<td style="border: 1px solid black;">
					District
				</td>
				<td style="border: 1px solid black;">
					Revenue Circle
				</td>
				<td style="border: 1px solid black;">
					Mouza
				</td>
				<td style="border: 1px solid black;">
					Lot No.
				</td>
				<?php if($nc_village[0]->merge_village_names == ''): ?>
					<td style="border: 1px solid black;">
						Existing Name of Non-Cadastral Village
					</td>
					<td style="border: 1px solid black;">Cadastral village to be known as</td>
				<?php else: ?>
					<?php if($nc_village[0]->is_end_village_cadastral_village): ?>
						<td style="border: 1px solid black;">
							Merged Non-cadastral Villages
						</td>
						<td style="border: 1px solid black;">Existing Cadastral village with which the non-cadastral village is merged</td>
					<?php else: ?>
						<td style="border: 1px solid black;">
							Merged Non-cadastral Villages
						</td>
						<td style="border: 1px solid black;">
							Proposed Name of Non -cadastral village
						</td>
						<td style="border: 1px solid black;">
							Name of cadastral village
						</td>
					<?php endif; ?>
				<?php endif; ?>
				<td style="border: 1px solid black;">Area as per Bhunaksha(km²)</td>
			</tr>
			<?php foreach ($nc_village as $k => $v): ?>
				<tr>
					<td style="border: 1px solid black;"><?= ++$k ?>
					</td>
					<td style="border: 1px solid black;"><?= $locations['dist']['loc_name'] ?>
					</td>
					<td style="border: 1px solid black;"><?= $v->circle_name->loc_name ?>
					</td>
					<td style="border: 1px solid black;"><?= $v->mouza_name->loc_name ?>
					</td>
					<td style="border: 1px solid black;"><?= $v->lot_name->loc_name ?>
					</td>
					<?php if($nc_village[0]->merge_village_names == ''): ?>
						<td style="border: 1px solid black;"><?= $v->old_vill_name ?>
						</td>
						<td style="border: 1px solid black;"><?= $v->new_vill_name ?></td>
					<?php else: ?>
						<?php if($nc_village[0]->is_end_village_cadastral_village): ?>
							<td style="border: 1px solid black;"><?= $v->old_vill_name ?>
							</td>
							<td style="border: 1px solid black;"><?= $v->new_vill_name ?></td>
						<?php else: ?>
							<td style="border: 1px solid black;"><?= $v->merge_village_names ?>
							</td>
							<td style="border: 1px solid black;"><?= $v->new_vill_name ?>
							</td>
							<td style="border: 1px solid black;"><?= $v->old_vill_name ?>
							</td>
						<?php endif; ?>
					<?php endif; ?>
					
					<td style="border: 1px solid black;"><?= $v->bhunaksa_total_area_skm ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if($nc_village[0]->merge_village_names != ''): ?>
		<div style="padding-top: 20px;">
			<p align="justify" style="">
				<?php if($nc_village[0]->is_end_village_cadastral_village): ?>
					The Non-Cadastral Village is merged with adjacent cadastral village, as the area of Non-Cadastral Village is less than 2 sqkm. 
				<?php else: ?>
					The Non-Cadastral villages are merged together, as the individual area is less than 2 sqkm.
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>

	<div style="padding-top: 20px;">
		<p align="justify" style="">The District Commissioner on receipt of online application for settlement of land from the possessors of the land of the aforementioned villages will undertake the settlement process as per the digitalized process flow along with the issuance of Digital Land and Property Card.
		</p>
	</div>

	<div style="padding-top: 20px;page-break-inside: avoid;">
		<p align="justify" style="">The settlement of land and issuance of Digital Land and Property Card will be as per the provisions of Land Policy, 2019 (as amended), and Notification No. 213444/I/393981/2024 dated Dispur, the 13/05/2024 and Office Memorandums/Notifications issued from time to time.
		</p>
		<br>
		<p style="text-align: center;">Principal Secretary to the Govt. of Assam<br>
			<b><u>Revenue & D.M. Department</u></b>
		</p>
	</div>

	<b style="">Memo No.<?= $proposal_id; ?></b>
	<p style="text-align: right;"><b>Dated: <span class="draft_notification_date"><?= date('d/m/Y'); ?></span></b></p>

	<b style=""><i><u>Copy to for favour of kind information:</u></i></b>

	<p style="">
		1. The Principal Secretary to the Hon’ble Chief Minister, Assam, Dispur
		<br>
		2. The Director of Land Records & Surveys etc., Assam, Rupnagar, Guwahati- 32
		<br>
		3. The Director of Printing and Stationery, Assam, Bamunimaidam -21 for publication in the Assam Gazette
		<br>
		4. Administrative Officer, Assam Board of Revenue for kind information of the Board.
		<br>
		5. The District Commissioners (Concerned District)
		<br>
		6. S.O to the Chief Secretary, O/o the Chief Secretary, Assam, Dispur for kind appraisal of Chief Secretary
		<br>
		7. All Secretary/Special Secretary/Joint Secretary/Deputy Secretary/Under Secretary of Revenue & D.M. Department, Dispur
		<br>
		8. The Co-District Commissioners (Concerned Co-District)
		<br>
		9. The Circle Officer (Concerned Revenue Circle)
		<br>
		10. The P.S. to the Hon’ble Minister, Revenue & D.M. Department, Assam, Dispur for kind appraisal of Hon’ble Minister

	</p>

	<div style="page-break-inside: avoid;">
		<p style="text-align: center; padding-left: 200px; padding-top: 30px;"><u><b>e-signed/-</b></u><br>
			Joint Secretary to the Govt. of Assam<br>
			<b><u>Revenue & D.M. Department</u>
		</p>
	</div>
</div>
