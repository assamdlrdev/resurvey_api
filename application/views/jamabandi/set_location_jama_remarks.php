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
                <div id="displayBox" style="display: none;"><img src="<?= base_url(); ?>/assets/process.gif"></div>
                <form action="<?php echo $base ?>index.php/get-patta-no-jamabandi-remarks" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px">
                                Select Location For Jamabandi Remarks Add/Edit
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                    <br>
                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">District:</label>
                                <select name="dist_code" class="form-control" id="d">
                                    <option selected value="">Select District</option>
                                    <?php foreach ($districts as $value) { ?>
                                        <option value="<?= $value['dist_code'] ?>" <?php if ($locations and $current_url == current_url()) {
                                                                                        if ($locations['dist']['code'] == $value['dist_code']) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }
                                                                                    ?>><?= $value['loc_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Sub-Div:</label>
                                <select name="subdiv_code" class="form-control" id="sd">
                                    <option value="">Select Sub Division </option>
                                    <?php if ($locations and $current_url == current_url()) {
                                        foreach ($locations['subdivs']['all'] as $value) { ?>
                                            <option value="<?= $value['subdiv_code'] ?>" <?php if ($locations['subdivs']['code'] == $value['subdiv_code']) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>><?= $value['loc_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Circle:</label>
                                <select name="cir_code" class="form-control" id="c">
                                    <option value="">Select Circle </option>
                                    <?php if ($locations and $current_url == current_url()) {
                                        foreach ($locations['cir']['all'] as $value) { ?>
                                            <option value="<?= $value['cir_code'] ?>" <?php if ($locations['cir']['code'] == $value['cir_code']) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>><?= $value['loc_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Mouza/Porgona:</label>
                                <select name="mouza_pargona_code" class="form-control" id="m">
                                    <option value="">Select Mouza </option>
                                    <?php if ($locations and $current_url == current_url()) {
                                        foreach ($locations['mza']['all'] as $value) { ?>
                                            <option value="<?= $value['mouza_pargona_code'] ?>" <?php if ($locations['mza']['code'] == $value['mouza_pargona_code']) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                ?>><?= $value['loc_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Lot:</label>
                                <select name="lot_no" class="form-control" id="l">
                                    <option value="">Select Lot </option>
                                    <?php if ($locations and $current_url == current_url()) {
                                        foreach ($locations['lot']['all'] as $value) { ?>
                                            <option value="<?= $value['lot_no'] ?>" <?php if ($locations['lot']['code'] == $value['lot_no']) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>><?= $value['loc_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="sel1">Village:</label>
                                <select name="vill_townprt_code" class="form-control" id="v">
                                    <option value="">Select Village </option>
                                    <?php if ($locations and $current_url == current_url()) {
                                        foreach ($locations['vill']['all'] as $value) { ?>
                                            <option value="<?= $value['vill_townprt_code'] ?>" <?php if ($locations['vill']['code'] == $value['vill_townprt_code']) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                ?>><?= $value['loc_name'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>


                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />
                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                    <div class="text-right">
                        <button type='submit' class="btn btn-primary">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
        <br>
    </div>

</body>

</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>