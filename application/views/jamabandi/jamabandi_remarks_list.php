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
    <script src="<?= base_url('assets/js/common.js') ?>"></script>
    <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
        <script src="<?= base_url('assets/js/bengali.js') ?>"></script>
    <?php } else { ?>
        <script src="<?= base_url('assets/js/assamese.js') ?>"></script>
    <?php } ?>
</head>

<body>

    <div class="container-fluid">
        <?php include 'message.php'; ?>
        <div class="card col-md-12" id="loc_save">
            <div class="card-body">


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4 class="mb-4" style="line-height: 0.2; color: #007bff; margin-top: 20px">
                            Jamabandi Remarks Details
                        </h4>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>

                <br>
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 20px">
                        <h5>Location & Patta Details</h5>
                    </div>

                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td style="width: 40%;">District</td>
                                <td><?php echo $locationDetails['dist_name']['loc_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Sub Division</td>
                                <td><?php echo $locationDetails['subdiv_name']['loc_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Circle</td>
                                <td><?php echo $locationDetails['cir_name']['loc_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Mouza</td>
                                <td><?php echo $locationDetails['mouza_name']['loc_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Lot</td>
                                <td><?php echo $locationDetails['lot']['loc_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Village</td>
                                <td><?php echo $locationDetails['village']['loc_name']; ?></td>
                            </tr>

                            <?php foreach ($pattaTypeName as $pattaType) : ?>
                                <tr>
                                    <td style="width: 40%;">Patta Type</td>
                                    <td><?php echo $pattaType->patta_type; ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td>Patta Number</td>
                                <td><?php echo $pattaNo ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; border: 1px solid #007bff"></div>
                <div class="row">
                    <input type="hidden" name="base" id="base" value='<?php echo $base ?>' />

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 20px; margin-top: 20px">
                        <h5>Remarks List</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right" style="padding-bottom: 20px; margin-top: 20px">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Remarks
                        </button>
                    </div>
                    <!-- add new remarks modal  -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Remarks </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?php echo $base ?>index.php/add-jamabandi-remarks" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="dist_code" value="<?php echo $dist_code ?>">
                                        <input type="hidden" name="subdiv_code" value="<?php echo $subdiv_code ?>">
                                        <input type="hidden" name="cir_code" value="<?php echo $circle_code  ?>">
                                        <input type="hidden" name="mouza_pargona_code" value="<?php echo $mouza_code ?>">
                                        <input type="hidden" name="lot_no" value="<?php echo $lot_no  ?>">
                                        <input type="hidden" name="vill_townprt_code" value="<?php echo $vill_code ?>">
                                        <input type="hidden" name="patta_type" value="<?php echo $pattatypeCode ?>">
                                        <input type="hidden" name="patta_no" value="<?php echo $pattaNo ?>">
                                        <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />

                                        <div class="row">

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Serial No. :</label>
                                                    <input type="text" value="<?php echo $line_no; ?>" class="form-control" name="sl_no" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="sel1">Remarks :</label>
                                                    <textarea type="text" class="form-control" name="remarks" rows="7" required charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                                </textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($remarks as $remark) : ?>
                                <tr>
                                    <td style="width: 15px"><?php echo $i; ?></td>
                                    <td><?php echo $remark->remark; ?></td>
                                    <td style="width: 220px">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRemarks<?php echo $remark->rmk_line_no ?>">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRemarks<?php echo $remark->rmk_line_no ?>">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editRemarks<?php echo $remark->rmk_line_no ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Remark </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form id="form_validation" action="<?php echo $base ?>index.php/edit-jamabandi-remarks" method="post" enctype="multipart/form-data">

                                                <input type="hidden" name="dist_code" value="<?php echo $dist_code ?>">
                                                <input type="hidden" name="subdiv_code" value="<?php echo $subdiv_code ?>">
                                                <input type="hidden" name="cir_code" value="<?php echo $circle_code  ?>">
                                                <input type="hidden" name="mouza_pargona_code" value="<?php echo $mouza_code ?>">
                                                <input type="hidden" name="lot_no" value="<?php echo $lot_no  ?>">
                                                <input type="hidden" name="vill_townprt_code" value="<?php echo $vill_code ?>">
                                                <input type="hidden" name="patta_type" value="<?php echo $pattatypeCode ?>">
                                                <input type="hidden" name="patta_no" value="<?php echo $pattaNo ?>">

                                                <input type="hidden" name="slNo" id="uId" value="<?php echo $remark->rmk_line_no ?>">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <label>Remark<span style="color:red;">*</span></label>
                                                                    <textarea type="text" id="fName" class="form-control" rows="7" name="remark" charset="utf-8" onKeyPress="javascript:convertThis(event)" onKeyDown="toggleKBMode(event)">
                                                                <?php echo $remark->remark; ?>
                                                            </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                                    <button type="submit" class="btn btn-primary waves-effect">SUBMIT</button>
                                                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade" id="deleteRemarks<?php echo $remark->rmk_line_no ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Delete Remark </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form id="form_validation" action="<?php echo $base ?>index.php/delete-jamabandi-remarks" method="post" enctype="multipart/form-data">

                                                <input type="hidden" name="dist_code" value="<?php echo $dist_code ?>">
                                                <input type="hidden" name="subdiv_code" value="<?php echo $subdiv_code ?>">
                                                <input type="hidden" name="cir_code" value="<?php echo $circle_code  ?>">
                                                <input type="hidden" name="mouza_pargona_code" value="<?php echo $mouza_code ?>">
                                                <input type="hidden" name="lot_no" value="<?php echo $lot_no  ?>">
                                                <input type="hidden" name="vill_townprt_code" value="<?php echo $vill_code ?>">
                                                <input type="hidden" name="patta_type" value="<?php echo $pattatypeCode ?>">
                                                <input type="hidden" name="patta_no" value="<?php echo $pattaNo ?>">

                                                <input type="hidden" name="slNo" id="uId" value="<?php echo $remark->rmk_line_no ?>">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
                                                            <h3>Are You Sure !</h3>
                                                            <br>
                                                            <h5>Do you really want to delete these records ?</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" id="csrf" name="<?php echo ($this->security->get_csrf_token_name()); ?>" value="<?php echo ($this->security->get_csrf_hash()); ?>" />
                                                    <button type="submit" class="btn btn-danger  waves-effect">YES</button>
                                                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">NO</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <br>
            </div>
        </div>
        <br>
    </div>


</body>

</html>


<script src="<?= base_url('assets/js/location.js') ?>"></script>