<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card col-md-12" id="loc_save">
        <div class="card-body">

            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 " align="center">
                    <div style="border: 1px solid gray; padding: 2px; width: 180px; height: 200px">
                        <img src="<?php echo base_url('assets/image/qr.png') ?>" alt="" style="width: 100%; height: 100%">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 " align="center">
                    <div>
                        <img src="<?php echo base_url('assets/image/reza.jpg') ?>" alt="" style="width: 60px; height: 90px">
                    </div>
                    <h5>Government of Assam</h5>
                    <h3 style="font-weight: bold">SVAMITVA CARD</h3>
                    <h5>Revenue & Disaster Management Department</h5>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 " align="center">
                    <div style="border: 1px solid gray; padding: 2px; width: 180px; height: 200px">
                        <img src="<?php echo base_url('assets/image/man.png') ?>" alt="" style="width: 100%; height: 100%">
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 35px">

                <table class="table  table-bordered table-sm" style="border: 1px solid gray">
                    <tbody>
                        <tr>
                            <td>
                                District
                                :
                                <?php echo $districtName->district ?>
                            </td>
                            <td>
                                Sub Division
                                :
                                <?php echo $subdivName->subdiv ?>

                            </td>
                            <td>
                                Circle
                                :
                                <?php echo $circleName->circle ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Mouza
                                :
                                <?php echo $mouzaName->mouza ?>

                            </td>
                            <td>
                                Lot
                                :
                                <?php echo $lotName->lot_no ?>

                            </td>
                            <td>
                                Village
                                :
                                <?php echo $villageName->village ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Block
                                :
                                <?php echo $blockGPName->block_name ?>

                            </td>
                            <td>
                                Gram Panchayat
                                :
                                <?php echo $blockGPName->panch_name ?>

                            </td>
                            <td>
                                Police Station
                                :
                                <?php echo $dag->police_station ?>

                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 p-0">
                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr align="center">
                                <td colspan="2" align="center" style="text-align: center">Patta No.</td>
                                <td colspan="2" align="center" style="text-align: center">Dag No.</td>
                                <td colspan="2" style="text-align: center">Land Class</td>
                                <td>Land Revenue</td>
                                <td colspan="2">Local Rate</td>
                            </tr>

                            <tr align="center">
                                <td>Old </td>
                                <td>New</td>
                                <td>Old </td>
                                <td>New</td>
                                <td rowspan="2" colspan="2" align="center">
                                    <?php echo $dag->full_land_type_name ?>
                                </td>
                                <td><?php echo $dag->dag_revenue ?></td>
                                <td colspan="2"><?php echo $dag->dag_local_tax ?></td>
                            </tr>
                            <tr align="center">
                                <td><?php echo $dag->old_patta_no ?></td>
                                <td><?php echo $dag->patta_no ?></td>
                                <td><?php echo $dag->old_dag_no ?></td>
                                <td><?php echo $dag->dag_no ?></td>

                                <td colspan="3">Total &nbsp; <b><?php echo ($dag->dag_revenue +  $dag->dag_local_tax) ?> </b></td>
                            </tr>
                            <tr align="center">
                                <td colspan="4" align="center" style="text-align: center">Area of Property</td>
                                <td colspan="2" align="center" style="text-align: center">Zonal Value</td>
                                <td colspan="2" style="text-align: center">Revenue paid up to (year)</td>
                                <td colspan="2" style="text-align: center">Ulpin Number</td>
                            </tr>

                            <tr align="center">
                                <td colspan="2">in B - K - L
                                    <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) { ?>
                                        - G
                                    <?php } ?>
                                </td>
                                <td colspan="2">In Are</td>
                                <td rowspan="2" colspan="2">
                                    <?php echo $dag->zonal_value ?>
                                </td>
                                <td rowspan="2" colspan="2">
                                    <?php echo $dag->revenue_paid_upto ?>
                                </td>
                                <td rowspan="2" colspan="2">ul</td>
                            </tr>
                            <tr align="center">
                                <td colspan="2">
                                    <?php echo $dag->dag_area_b ?> -
                                    <?php echo $dag->dag_area_k ?> -
                                    <?php echo $dag->dag_area_lc ?>
                                    <?php if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                                        echo ('-' . $dag->dag_area_g) ?>

                                    <?php } ?>
                                </td>
                                <td colspan="2">
                                    <?php echo $dag->dag_area_are ?>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row" style="margin-top: 15px">

                <?php foreach ($occupiers as $occupier) : ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 border border-dark p-0 my-2">
                        <table class="table  table-bordered table-sm m-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <b>Pattadar Name</b>
                                        :
                                        <?php echo $occupier['occupier']->encro_name ?>
                                    </td>
                                    <td>
                                        Guardian Name
                                        :
                                        <?php echo $occupier['occupier']->encro_guardian ?>

                                    </td>
                                    <td>
                                        Marital status
                                        :
                                        <?php foreach (MARITAL_STATUS_LIST as $key => $MARITAL_STATUS) : ?>
                                            <?php if (trim($occupier['occupier']->marital_status) == $key) : ?>
                                                <?= $MARITAL_STATUS ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        Gender
                                        :
                                        <?php foreach ($master_genders as $master_gender) : ?>
                                            <?php if (trim($occupier['occupier']->gender) == $master_gender->short_name) : ?>
                                                <?= $master_gender->gen_name_ass ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                    </td>
                                </tr>
                                <tr>

                                    <td>
                                        Caste Category
                                        :
                                        <?php foreach ($master_casts as $caste) : ?>
                                            <?php if (trim($occupier['occupier']->category) == $caste->caste_id) : ?>
                                                <?= $caste->caste_name_ass ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        Mobile No.
                                        :
                                        <?php echo $occupier['occupier']->mobile ?>

                                    </td>
                                    <td>
                                        Current Occupation
                                        :
                                        <?php foreach ($master_occupations as $master_occupation) : ?>
                                            <?php if (trim($occupier['occupier']->current_occupation) == $master_occupation->id) : ?>
                                                <?= $master_occupation->name_eng ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td colspan="2">
                                        Address
                                        :
                                        <?php echo $occupier['occupier']->encro_add ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="py-4"><b>Family Details : <?php echo (count($occupier['families']) == 0 ? 'N/A' : '') ?></b></td>
                                </tr>
                                <?php foreach ($occupier['families'] as $key_member => $family_member) : ?>
                                    <tr>
                                        <td rowspan="2"><?= $key_member + 1 ?></td>
                                        <td>
                                            <strong>Name</strong> :
                                            <?php echo $family_member->occupier_fmember_name ?>
                                        </td>
                                        <td>
                                            Relation :
                                            <?php if (trim($family_member->occupier_fmember_relation) == 'f') : ?>
                                                পিতৃ
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'm') : ?>
                                                মাতৃ
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'h') : ?>
                                                পতি
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'w') : ?>
                                                পত্নী
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'a') : ?>
                                                অধ্যক্ষ মাতা
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == '') : ?>
                                                অভিভাৱক
                                            <?php elseif (trim($family_member->occupier_fmember_relation) == 'n') : ?>
                                                নাই
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            Gender :
                                            <?php foreach ($master_genders as $master_gender) : ?>
                                                <?php if (trim($family_member->occupier_fmember_gender) == $master_gender->short_name) : ?>
                                                    <?= $master_gender->gen_name_ass ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Occupation :
                                            <?php foreach ($master_occupations as $master_occupation) : ?>
                                                <?php if (trim($family_member->occupier_fmember_occupation) == $master_occupation->id) : ?>
                                                    <?= $master_occupation->name_eng ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 p-0">
                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr>
                                <td width="50%">Property Sketch with drone image</td>
                                <td colspan="2">Property Description</td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="<?php echo base_url('assets/image/drone.jpeg') ?>" alt="" style="width: 100%; height: 250px">
                                </td>
                                <td>
                                    <table style="width: 100%!important;">
                                        <tbody>
                                            <tr>
                                                <td>North</td>
                                                <td><?php echo $dag->dag_n_desc ?></td>
                                            </tr>
                                            <tr>
                                                <td>South</td>
                                                <td>
                                                    <?php echo $dag->dag_s_desc ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>East</td>
                                                <td><?php echo $dag->dag_e_desc ?></td>
                                            </tr>
                                            <tr>
                                                <td>West</td>
                                                <td><?php echo $dag->dag_w_desc ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table style="width: 100%!important;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 50%">
                                                    <b>Remarks </b>: &nbsp;
                                                    In Informatics, dummy data is benign information that does not contain any useful
                                                    data, but serves to reserve space where real data is nominally present.
                                                </td>
                                                <td style="width: 50%">
                                                    <b>Digital Signature</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>