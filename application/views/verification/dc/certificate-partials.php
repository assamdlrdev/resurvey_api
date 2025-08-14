<!-- <div style="border: 1px solid gray;padding:24px;"> -->
<div style="padding:24px;">
    <h3 style="text-align: center;">Annexure-V</h3>
    <h5 style="text-align: center;font-size: 14px;"><u>Validation Certificate by District Commissioner for CLR-Chitha data entry</u></h5>
    <div style="margin-top: 24px;text-align:right;">
        <p style="font-size: 14px;">Date of Generation : <?php echo date('d-m-Y'); ?></p>
    </div>
    <div>
        <p style="font-size: 14px;">Name of the District Commissioner : <?php echo $dc_name; ?></p>
        <p style="font-size: 14px;">District : <?php echo $dist_name; ?></p>
    </div>
    <div style="margin-top: 32px;">
        <p>I have validated the following Chitha data entry work : </p>
    </div>
    <table border="1" cellpadding="3" style="border-collapse: collapse; width:100%;">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Name of Village</th>
                <th>Mouza</th>
                <th>Lot No</th>
                <th>Total No of Dags in the Village</th>
                <th>No of Dags Alloted</th>
                <th>No of Dags Verified</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($villages as $key => $village) : ?>
                <tr>
                    <td><?php echo $key + 1 ?></td>
                    <td><?php echo $village->loc_name ?></td>
                    <td><?php echo $village->mouza_name ?></td>
                    <td><?php echo $village->lot_name ?></td>
                    <td><?php echo $village->total_dag ?></td>
                    <td><?php echo $village->alloted_dag ?></td>
                    <td><?php echo $village->verified_dag ?></td>
                    <td>Validated</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 32px;">
        <p>
            Certified that I have tallied and verified 2% of the dags (minimum 2 dags) of the village
            duly validated and certified by the ADC(Rev.) in online chitha data entry module, with the
            manual Chitha and found correct.
        </p>
    </div>
    <div style="margin-top: 32px;text-align:right;">
        <!-- <p><b>Signature of District Commissioner</b></p> -->
    </div>
</div>