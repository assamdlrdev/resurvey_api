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
    <?php $auto = array('autocomplete' => 'off', 'name' => 'pmod', 'id' => 'pmod');
    echo form_open('Chithacontrol/pattadardetmod', $auto); ?>
    <div class="container">
        <?php $is_govt = in_array($patta_type_code, GovtPattaCode) ?>
        <form class='form-horizontal mt-3' id="f1" method="post" action="" enctype="multipart/form-data">
            <div class="row bg-light p-0 border border-dark">
                <?php if ($locationname["dist_name"] != NULL) echo $locationname['dist_name']['loc_name'] . '/' . $locationname['subdiv_name']['loc_name'] . '/' . $locationname['cir_name']['loc_name'] . '/' . $locationname['mouza_name']['loc_name'] . '/' . $locationname['lot']['loc_name'] . '/' . $locationname['village']['loc_name']; ?>
                <?php echo $daghd ?><?php echo $landhd ?>
                <div class="col-12 px-0 pb-3">
                    <div class="bg-info text-white text-center py-2">
                        <h3>Add/Modify Pattadar (Column 7)</h3>

                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <tr>
                            <td>Add a Pattadars for dag no&nbsp;<?php echo $dag_no ?></td>
                        </tr>
                        <table class="table" border=0 bgcolor="#BFFFE6">
                            <tr>
                                <td>
                                    <div id='r4'><?php echo $pstr; ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="r3"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table" border=0>

                            <tr>
                                <td>Insert Pattadars from patta no&nbsp;<?php echo $patta_no ?> and any other dag</td>
                                <td>

                                    <select name="pdag" id="pdag" class="form-control" onchange="gpattadar();">
                                        <option selected value="">Select</option>
                                        <?php foreach ($pdag as $row) { ?>
                                            <option value="<?php echo ($row->dag_no); ?>" <?php if ($row->dag_no == $pdag_no1) { ?> selected <?php } ?>>
                                                <?php echo $row->dag_no; ?></option>
                                        <?php } ?>
                                    </select>


                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <div id="r1">
                                        <table class="table" border=0 bgcolor="#BFFFE6">
                                            <?php if ($opt != 1) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td>Id</td>
                                                    <td>Name</td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($query) {
                                                foreach ($query as $row) {
                                                    $pid = $row->pdar_id;
                                                    $pname = $row->pdar_name;
                                                    $patta = $row->patta_no;
                                                    $ptype = $row->patta_type_code;
                                                    $dno = $row->dag_no;
                                                    $vl = $pid . ',' . $pname . ',' . $patta . ',' . $ptype . ',' . $dno;
                                            ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="chk[]" id="chk[]" value="<?php echo $vl ?>"></td>
                                                        <td><?php echo $row->pdar_id ?></td>
                                                        <td><?php echo $row->pdar_name ?></td>
                                                    <tr>
                                                <?php }
                                            } ?>
                                        </table>
                                    </div>
                                    <div id="r2"></div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php if ($opt != 1) { ?>
                                        <input type="button" class="btn btn-primary" id="dpdar" name="dpdar" value="Insert Selected Pattadars" onclick="patins();"></input>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-12 text-center pb-3">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' /><input type="hidden" name="edag" id="edag" value='<?php echo $dag_no ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <input type="button" class="btn btn-primary" id="submitp" name="submitp" value="Add Pattadar" onclick="getpdarentry();"></input>
                    <?php if ($is_govt) : ?>
                        <input type="button" class="btn btn-primary" onclick="rmkentry()" value="Next"></input>
                    <?php else : ?>
                        <input type="button" class="btn btn-primary" id="orddet" name="orddet" value="Next"></input>
                    <?php endif; ?>
                    <input type="button" class="btn btn-primary" id="onextt" name="onextt" value="Exit" onclick="ordexit();"></input>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<script src="<?= base_url('assets/js/location.js') ?>"></script>