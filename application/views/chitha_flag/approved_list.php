<script src="<?php echo base_url(); ?>assets/plugins/block-ui/blockUI.js"></script>
<link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>

<script>
    document.onreadystatechange = function(e) {
        $.blockUI({
            message: $('#displayBox'),
            css: {
                border: 'none',
                backgroundColor: 'transparent'
            }
        });
    };
    window.onload = function() {
        $.unblockUI();
    }
</script>

<div class="card card-info card-form">
    <div class="card-header text-center">
        <h3 class="card-title">
            Chitha Flag Mapping - (Approved-List) : Circle - <?php echo $this->utilityclass->getCircleName($dist_code, $subdiv_code, $circle_code); ?>,
        </h3>
    </div>

    <div class="card-body">
        <div class="table table-responsive">
            <table class="table table-hover table-sm table-bordered text-center" id="example" >
                <thead>
                    <tr>
                        <th>Sl no.</th>
                        <th>Village-Name</th>
                        <th>Approve</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;
                    foreach ($pending_list as $pending) : ?>
                        <tr>
                            <td><?= $count++; ?> </td>
                            <td>
                                <span class="text-primary font-weight-bold" id="lb_view_village_name">
                                    <?= $this->utilityclass->getVillageName(
                                        $pending->dist_code,
                                        $pending->subdiv_code,
                                        $pending->cir_code,
                                        $pending->mouza_pargona_code,
                                        $pending->lot_no,
                                        $pending->vill_townprt_code
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <button type="button" class="btn btn-success btn-sm text-white" onclick="PendingVillageFlagDetailsApproved('<?= $pending->dist_code; ?>', '<?= $pending->subdiv_code; ?>', 
                                            '<?= $pending->cir_code; ?>', '<?= $pending->mouza_pargona_code; ?>', '<?= $pending->lot_no; ?>', '<?= $pending->vill_townprt_code; ?>')">
                                    <i class="fa fa-eye"></i>
                                    View all dags
                                </button>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="dagList"></div>
        <div class="text-center">
            <!-- <a href="<?= base_url() . 'index.php/chithaFlag/FlagIndexLM'; ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i>BACK</a> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    function PendingVillageFlagDetailsApproved(dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_code) {
        $.blockUI({
            message: $('#displayBox'),
            css: {
                border: 'none',
                backgroundColor: 'transparent'
            }
        });
        $.ajax({
            url: '<?= base_url() ?>' + "index.php/ChithaFlag/viewApprovedDagFlagVillageWise",
            type: "POST",
            data: {
                dist_code: dist_code,
                subdiv_code: subdiv_code,
                cir_code: cir_code,
                mouza_pargona_code: mouza_pargona_code,
                lot_no: lot_no,
                vill_code: vill_code
            },
            error: function() {
                $.unblockUI();
                Swal.fire({
                    title: "Failed",
                    text: "Error",
                    icon: "warning",
                    timer: 50000
                });
            },

            success: function(data) {
                $.unblockUI();
                $("#dagList").html(data);
                $('#myLargeModalLabelDagList').modal('show');
            }
        });
    }


    $(document).ready(function() {
        $('#example').DataTable({
            "bLengthChange": false,
            "showNEntries": false,
            "bSort": false,
            "bnew": false,
            "pageLength": 20
        });

    });
</script>