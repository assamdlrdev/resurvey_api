<html>
<link rel="stylesheet" href="<?php echo base_url('fonts/css/font-awesome.css'); ?>">
<style>
	.pagebreak {
		clear: both;
		page-break-after: always;
	}

	b,
	strong {
		font-weight: bolder;
	}
</style>
<style>
	@media print {
		body {
			font-size: 10pt
		}
	}

	@media print {
		body {
			line-height: 1.2
		}
	}

	.no-page-break {
		page-break-inside: avoid;
	}
</style>

<body>
	<div class="A4" style="font-size: 14pt;">
		<table>
			<tr>
				<td>To,</td>
			</tr>
			<tr>
				<td width="20%"></td>
				<td width="75%">The Director of Land Records & Surveys, Assam,<br>
					Rajah Bhawan, Rupnagar, <br>
					Guwahati-32
				</td>
			</tr>
			<tr>
				<td width="20%" style="padding-top: 20px"> Sub :</td>
				<td width="75%" style="padding-top: 20px">Proposal for approval and notification of surveyed villages as
					cadastral
					village.
				</td>
			</tr>

		</table>
		<table style="padding-top: 45px">
			<tr>
				<td style="padding-top: 20px;">Sir,</td>
			</tr>
			<tr>
				<td style="text-align: justify;" width="100%">With reference to the subject cited above,
					I have the honour to inform you that vide Govt. Notification No. ECF.206672/2022/26 dated 31-05-2022,
					detailed survey of NC villages annexed at <b>Annexure-I</b> has been completed. The Chitha and
					the Map are certified after proper checking and verification by respective Land Record Assistant,
					Land Record Supervisor and Circle Officer.
				</td>
			</tr>
			<tr>
				<td style="text-align: justify; padding-top: 20px;" width="100%">
					The details of the proposed villages are given in <b>Annexure-I</b>.
				</td>
			</tr>
			<tr>
				<td style="text-align: justify;  padding-top: 20px;" width="100%">
					You are therefore requested to take necessary steps for notifying the proposed villages as cadastral
					and publication of the notification in the Assam Gazette as per rules.
				</td>
			</tr>
			<tr>
				<td width="100%" style="padding-top: 30px;"><b>Annexure-I</b></td>
			</tr>
		</table>

		<table style="width:100%; border-collapse:collapse" id="proposalTable">
			<tr>
				<td style="border: 1px solid black;">
					Sl No.
				</td>
				<td style="border: 1px solid black;">
					Circle
				</td>
				<td style="border: 1px solid black;">
					Mouza
				</td>
				<td style="border: 1px solid black;">
					Lot No
				</td>
				<?php
				if (count($nc_villages[0]->merge_village_requests)):
				?>
					<td style="border: 1px solid black;" class="merge_vill_col">
						Merged Existing NC Villages Name
					</td>
				<?php
				else:
				?>
					<td style="border: 1px solid black;" class="non_merge_vill_col">
						Existing Village Name
					</td>
				<?php
				endif;
				?>
				<td style="border: 1px solid black;">
					Total Area (B-K-L)
				</td>
				<td style="border: 1px solid black;">
					Total Dags
				</td>
				<td style="border: 1px solid black;">
					Total Occupiers
				</td>
				<td style="border: 1px solid black;">
					<?php if ($nc_villages[0]->case_type == 'NC_TO_C'): ?>
						Existing Cadastral village with which the non-cadastral village is merged
					<?php
					else:
					?>
						Proposed Cadastral Village Name
					<?php endif; ?>
				</td>
			</tr>
			<?php foreach ($nc_villages as $k => $v): ?>
				<tr x-show="selected_cases.includes('<?= addslashes($v->application_no) ?>')">
					<td style="border: 1px solid black;">
						<center class="modal_sl"><?= ++$k ?></center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->circle_name->loc_name ?></center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->mouza_name->loc_name ?></center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->lot_name->loc_name ?></center>
					</td>
					<?php
					if (count($v->merge_village_requests)):
					?>
						<?php if ($v->case_type == 'NC_TO_C'): ?>
							<td style="border: 1px solid black;" class="merge_vill_col">
								<center><?= $v->old_vill_name ?></center>
							</td>
						<?php
						else:
						?>
							<td style="border: 1px solid black;" class="merge_vill_col">
								<center><?= $v->merge_village_names ?></center>
							</td>
						<?php endif; ?>
					<?php
					else:
					?>
						<td style="border: 1px solid black;" class="non_merge_vill_col">
							<center><?= $v->old_vill_name ?></center>
						</td>
					<?php
					endif;
					?>
					<td style="border: 1px solid black;">
						<center><?= $v->total_b_k_l[0] ?>B - <?= $v->total_b_k_l[1] ?>K - <?= $v->total_b_k_l[2] ?>L</center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->total_dag_area->total_dag ?></center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->occupiers ?></center>
					</td>
					<td style="border: 1px solid black;">
						<center><?= $v->new_vill_name ?></center>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>

		<?php
		if (count($nc_villages[0]->merge_village_requests)):
		?>
			<table style="padding-top: 45px" class="merge_vill_note_table">
				<tr class="merge_vill_note_tr">
					<td style="text-align: justify;" width="100%">
						<?php if ($nc_villages[0]->case_type == 'NC_TO_C'): ?>
							The non-cadastral villages are merged with the existing cadastral village as the individual area of the non-cadastral village is less than 2 Square kilometer.
						<?php
						else:
						?>
							The non-cadastral villages are merged together as the individual area is less than 2 Square kilometer.
						<?php endif; ?>
					</td>
				</tr>
			</table>
		<?php
		endif;
		?>

		<table style="width:100%;" class="no-page-break">
			<tr>
				<td widtd="100%" style="padding-top: 80px"></td>
				<td widtd="100%" style="padding-top: 80px;text-align: right;">Yours faithfully</td>
			</tr>
			<tr>
				<td widtd="100%" style="padding-top: 80px"></td>
				<td widtd="100%" style="padding-top: 80px;text-align: right;">District Commissioner,<br>
					<?= $dist_name->locname_eng ?> District.</center>
				</td>
			</tr>
		</table>
	</div>
</body>

</html>