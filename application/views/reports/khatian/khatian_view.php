<style>
    th,
    td {
        border-color: black !important;
    }
    .no-wrap{
        white-space: nowrap;
    }
</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size: 14px;">
    <div class="row">

        <div class="col-lg-12" style="text-align: center;">
            <div style="text-align: center;font-size: 1.2em;font-weight: bold;">
            Draft Khatina Under the Assam (Temporarily Settled Areas Tenancy Act 1971)
            </div>
        </div>
    </div>

    <p class="red uni_text">Khatian No: 1 </p>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr class="bg-warning">
                    <td rowspan="3" style="text-align: center;width:10% !important">Name/Fathers Name and Residence of Tenant</td>
                    <td colspan="10" style="text-align: center;">Land in Possession of Tenants</td>
                    <td colspan="5" style="text-align: center;">Particulars of Pattadars</td>
                </tr>
                <tr>

                    <td rowspan="2">Old Dag</td>
                    <td rowspan="2">New Dag</td>
                    <td rowspan="2" class="text-center">Area</td>
                    <td rowspan="2">Class of Land</td>
                    <td rowspan="2">Revenue Payable</td>
                    <td rowspan="2">Length of Possession</td>
                    <td colspan="2" class="text-center">Rent</td>
                    <td rowspan="2">Status of Tenants</td>
                    <td rowspan="2">Special Conditions</td>
                    <td rowspan="2">Name/Fathers Name</td>
                    <td rowspan="2">Patta No and Nature</td>
                    <td rowspan="2">Remarks</td>
                </tr>
                <tr>
                    <td>cash/kind</td>
                    <td>cash/kind</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                    <td>12</td>
                    <td>13</td>
                    <td>14</td>
                </tr>
                <?php foreach ($tenants as $tenant) : ?>
                    <tr>
                        <td><?php echo ($tenant->tenants_father); ?></td>
                        <td><?php echo ($tenant->dag_det->old_dag_no); ?></td>
                        <td><?php echo ($tenant->dag_det->dag_no); ?></td>
                        <?php if (in_array($this->session->userdata('dist_code'), BARAK_VALLEY)) : ?>
                            <td class="no-wrap"><?php echo ($tenant->bigha . 'B - ' . $tenant->katha . 'K - ' . $tenant->lessa . 'LC - ' . $tenant->ganda . 'G'); ?></td>
                        <?php else : ?>
                            <td class="no-wrap"><?php echo ($tenant->bigha . 'B - ' . $tenant->katha . 'K - ' . $tenant->lessa . 'LC'); ?></td>
                        <?php endif; ?>
                        <td>
                            <?php echo ($this->utilityclass->getLandClassCode($tenant->dag_det->land_class_code)); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->dag_det->dag_revenue); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->duration); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->paid_cash_kind); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->payable_cash_kind); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->tenant_status); ?>
                        </td>
                        <td>
                            <?php echo ($tenant->special_conditions); ?>
                        </td>
                        <td>
                            <?php foreach ($tenant->dag_det->pattadars as $pattadar) : ?>
                                <?php echo "<p>" . $pattadar->pdar_name . "<br>(" . $pattadar->pdar_father . ")</p>----"; ?>
                            <?php endforeach; ?>
                        </td>
                        <td>
                        <?php echo ($tenant->dag_det->patta_no."/".$this->utilityclass->getPattaType($tenant->dag_det->patta_type_code));?>
                        </td>
                        <td>
                        <?php echo ($tenant->remarks);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>