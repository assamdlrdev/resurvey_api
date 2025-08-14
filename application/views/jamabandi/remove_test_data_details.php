<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <style>
        .card {
            margin: 0 auto;
            /* Added */
            float: none;
            /* Added */
            margin-bottom: 10px;
            /* Added */
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php include 'message.php'; ?>
        <div class="card col-md-12" id="loc_save">
            <div class="card-body">

                <form action="<?php echo $base ?>index.php/get-patta-no-for-jamabandi" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px">
                                Remove Testing Database Table
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                    <br>
                    <div class="row">
                        <table class="table  table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%">Database Switch Code</td>
                                    <td> <?php echo $dbSCode ?></td>
                                </tr>
                                <tr>
                                    <td>Database Code</td>
                                    <td> <?php echo $dbCode ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Deleted Database Table</td>

                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?php $i = 1;
                                        foreach ($deleteDB as $db) : ?>

                                            <?php echo  $i . '. ' . $db  . '<br>'; ?>

                                        <?php $i++;
                                        endforeach; ?>
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <br>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <div class="text-right">
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">
                            <i class="fa fa-trash" aria-hidden="true"></i> Deleted
                        </button>

                    </div>
                </form>

                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Deleted Database Table Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo $base ?>index.php/delete-removable-test-data" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="dist_code" value="<?php echo $dist_code ?>">
                                    <input type="hidden" name="subdiv_code" value="<?php echo $subdiv_code ?>">
                                    <input type="hidden" name="cir_code" value="<?php echo $circle_code  ?>">
                                    <input type="hidden" name="mouza_pargona_code" value="<?php echo $mouza_code ?>">
                                    <input type="hidden" name="lot_no" value="<?php echo $lot_no  ?>">
                                    <input type="hidden" name="vill_townprt_code" value="<?php echo $vill_code ?>">
                                    <input type="hidden" name="patta_type" value="<?php echo $pattatypeCode ?>">
                                    <input type="hidden" name="patta_no" value="<?php echo $pattaNo ?>">

                                    <div class="row">
                                        <div class="col-md-12" align="center">
                                            <br>
                                            <h2>Are You Sure !</h2>
                                            <br>

                                        </div>



                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>
    </div>

</body>

</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>