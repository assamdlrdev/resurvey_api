<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js')?>"></script>
    <style>
        .card {
            margin: 0 auto; /* Added */
            float: none; /* Added */
            margin-bottom: 10px; /* Added */
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'message.php'; ?>
    <div class="card col-md-12" id="loc_save">
        <div class="card-body">

            <form action="<?php echo $base?>index.php/get-removable-database" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px" >
                            Remove Testing Data
                        </h4>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Database Switch Code (Eg: lsp4)</label>
                            <input type="text" class="form-control" name="dbSwitchCode" required >
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sel1">Database Code (Eg: 02)</label>
                            <input type="text" class="form-control" name="dbCode" required >
                        </div>
                    </div>
                </div>
                <br>
                <input type="hidden" name="base" id="base" value='<?php echo $base ?>'/>
				<input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                <div class="text-right">
                    <button type='submit' class="btn btn-primary" >SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    <br>
</div>

</body>
</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>