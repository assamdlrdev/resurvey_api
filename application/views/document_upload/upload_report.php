<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js')?>"></script>
    <link href='<?php echo base_url('assets/dataTable/datatables.min.css') ?>' rel='stylesheet' type='text/css'>
    <style>
        .card {
            margin: 0 auto; /* Added */
            float: none; /* Added */
            margin-bottom: 10px; /* Added */
           /* margin-top: 50px; */
        }

    
    .tenant{
        background-color : #007bff78 !important;
        border-bottom-width: 0px;
    }
    
   #example_wrapper{
    width: 100%;
   }
</style>


</head>
<body>

<div class="container">
    <?php include 'message.php'; ?>
    <div class="card col-md-12" id="loc_save">
        <div class="card-body">
            <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
            

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px" >
                            Dag Wise Document Upload Report
                        </h4>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                <br>
                <div class="row">

                    <table class="table table-striped table-bordered" id="example"> 
                        <thead>
                        <tr>
                        <th class="tenant">District</th>
                        <th class="tenant">Circle</th>
                        <th class="tenant">Village</th>
                        <th class="tenant">Dag No</th>
                        <th class="tenant">Uploaded list</th>
                        </tr>
                        </thead>
                    <tbody>
                        <?php  foreach($all_data as $d):?>
                            <tr>
                            <td><?php echo $d['districtname'];?></td>
                            <td><?php echo $d['cirname'];?></td>
                            <td><?php echo $d['villname'];?></td>
                            <td><?php echo $d['dag_no'];?></td>

                            <td class="text-bold">
                            <?php foreach ($d['doc_flag'] as $key => $value) {?>

                             <?php echo (($value->doc_flag==1)?'Chitha Copy,':(($value->doc_flag==2)?'Jamabandi Copy,':(($value->doc_flag==3)?'khatian,':(($value->doc_flag==4)?'Touzi,':(($value->doc_flag==5)?'Additional':'null')))));
                                }?>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    
                    </tbody>
                    </table>

                </div>

                <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
                
     
        </div>
    </div>
</div>

</body>
</html>


<script src="<?php echo base_url('assets/dataTable/datatables.min.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable({
    
    "pageLength": 20
  });
  
});
</script> 


<script src="<?= base_url('assets/js/location_document.js') ?>"></script>