<!DOCTYPE html>

<html lang="en">

<head>
    <title>Dharitee Data Entry</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view('header'); ?>

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

        * {
            box-sizing: border-box;
        }

        .row {
            margin-left: -5px;
            margin-right: -5px;
        }

        .column {
            float: left;
            width: 50%;
            padding: 5px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            /* border: 1px solid #ddd;*/
        }

        th,
        td {
            text-align: left;
            padding: 5px 16px !important;
        }

        tr {
            border-top: hidden;
        }
    </style>
    <script src="<?= base_url('assets/js/common.js') ?>"></script>
    <?php if ($this->session->userdata('dist_code') == '21') { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>
</head>

<body>
    <?php echo form_open('Chithacontrol/tenantedit'); ?>
    <!--<div class="card-title font-weight-bold">Chitha Data Entry</div>-->

    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Edit of Crop Details(Column 14-17,20-23,26-29)</h3>
                    </div>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Crop Id</th>
                                    <th>Year</th>
                                    <th>Crop Name</th>
                                    <th>Crop Category</th>
                                    <th>Crop Season</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cropList) {
                                    foreach ($cropList as $row) {
                                        $tid = $row->crop_sl_no;
                                        $dagno = $row->dag_no;
                                        $dcode = $row->dist_code;
                                        $scode = $row->subdiv_code;
                                        $ccode = $row->cir_code;
                                        $mcode = $row->mouza_pargona_code;
                                        $lotno = $row->lot_no;
                                        $vcode = $row->vill_townprt_code;
                                        $nm = $tid . '-' . $dagno . '-' . $dcode . '-' . $scode . '-' . $ccode . '-' . $mcode . '-' . $lotno . '-' . $vcode
                                ?>

                                        <tr>
                                            <td><?php echo $row->crop_sl_no; ?></td>
                                            <td style="font-size:14px"><?php echo $row->yearno; ?></td>
                                            <td><?php echo $row->crop_name; ?></td>
                                            <td><?php echo $row->crop_categ_desc; ?></td>
                                            <td><?php echo $row->crop_season; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('index.php/Chithacontrol/cropModify/' . $nm); ?>" class="btn btn-info ">Edit</a>
                                            </td>
                                        </tr>

                                <?php }
                                } ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <br>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <a href="<?php echo base_url('index.php/Chithacontrol/crop/'); ?>" class="btn btn-primary">Add New Crop</a>
                    <input type='button' class="btn btn-primary" id="cnext" name="cnext" value="Next" onclick="ncrpentry();"></input>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>