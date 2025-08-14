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
</head>

<body>
    <?php echo form_open('Chithacontrol/fruit'); ?>

    <div class="container-fluid mt-3 mb-2 font-weight-bold">
        <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
        <?php echo $daghd ?><?php echo $landhd ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Edit of Fruit details (Col 30)</h3>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table" border=0>

                            <tr>
                                <td>Fruit Plant Id:</td>
                                <td><input type="text" value="<?php echo $fruitId; ?>" class="form-control form-control-sm" id="frplantid" name="frplantid" readonly></td>
                            </tr>
                            <tr>
                                <td>Fruit Plant name:<font color=red>*</font>
                                </td>
                                <td>
                                    <select class="form-control" name="frname" id="frname">
                                        <option value="">Select</option>
                                        <?php foreach ($fruitnm as $row) { ?>
                                            <option value="<?php echo $row->fruit_code; ?>" <?php if ($row->fruit_code == $fruitD->fruit_plants_name) { ?> selected <?php } ?>>
                                                <?php echo $row->fruit_name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>No of Plants:<font color=red>*</font>
                                </td>
                                <td><input type="text" value="<?php echo $fruitD->no_of_plants; ?>" class="form-control" id="fplantno" name="fplantno"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <br>
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <?php if ($fruitId > 1) { ?>
                        <input type='button' class="btn btn-primary" id="modten" name="modten" value="Edit Fruit Details" onclick="fruitList();"></input>
                    <?php } ?>

                    <input type='button' class="btn btn-primary" id="frsubmit" name="frsubmit" value="Submit" onclick="fruitDetailsUpdate();"></input>
                    <input type='button' class="btn btn-primary" id="frnext" name="frnext" value="Next" onclick="rmkentry();"></input>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
<script src="<?= base_url('assets/js/location.js') ?>"></script>