<div class="row" style='margin-top:20px'>               
    <div class="col-lg-12 col-lg-offset-3">
        <div class="card casedisplay">                        
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <tr class="bg-info" style="background: #17a2b8 !important;">
                        <td colspan="3">Chitha Flag Mapping</td>
                    </tr>
                    <tr>
                        <td>Chitha Flag Mapping-Lists</td>
                        <td>
                            <!-- <span class="badge badge-warning"><?=$pending_count?></span>                                         -->
                        </td>
                        <td><a href="<?php echo base_url() . 'index.php/ChithaFlag/locationDetails' ?>" class="text-danger" style="float:right">ADD/UPDATE MAPPING</a></td>
                    </tr>
                    <tr>
                        <td>Approved-Mapping-Lists</td>
                        <td>
                            <span class="badge badge-success"><?=$approve_count?></span>                                        
                        </td>
                        <td><a href="<?php echo base_url() . 'index.php/ChithaFlag/ChithaFlagApprovedListLM' ?>" class="green" style="float:right">view</a></td>
                    </tr>
                    
                </table>
            </div>
        </div>
    </div>               
</div>