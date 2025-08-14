<div class="modal fade" id="addDetailModal" tabindex="-1" aria-labelledby="addDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div :class='modalHeaderBg' class="modal-header">
                <h5 class="modal-title " id="addDetailModalLabel">
                    <span x-text="modalTitle"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center text-danger">
                    <u><strong class="">NOTE : </strong>Fields marked with(*) are mandatory.</u>
                </p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nature_of_reservation" class="col-sm-4 col-form-label text-right">Type Of Govt Land : <span class="text-danger">*</span> </label>
                            <div class="col-sm-5">
                                <select class="form-control form-control-sm" x-model="nature_of_reservation" id="nature_of_reservation">
                                    <option value="">---Select Type Of Govt Land---</option>
                                    <?php foreach (json_decode(LB_NATURE_OF_RESERVATION) as $nor) : ?>
                                        <option value="<?= $nor->CODE ?>"><?= $nor->NAME ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="whether_encroached" class="col-sm-4 col-form-label text-right">Whether Encroached : <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <select x-model="whether_encroached" class="form-control form-control-sm" id="whether_encroached">
                                    <option value="">---Select Whether Encroached---</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                    <option value="I">Institution</option>
                                </select>
                            </div>
                        </div>
                        <section x-show="whether_encroached == 'Y'">
                        <div class="form-group row">
                            <label for="en_area_b" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Bigha : <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control form-control-sm" id="en_area_b" x-model="en_area_b">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="en_area_k" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Katha : <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control form-control-sm" id="en_area_k" x-model="en_area_k">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="en_area_lc" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Lessa : <span class="text-danger">*</span></label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control form-control-sm" id="en_area_lc" x-model="en_area_lc">
                            </div>
                        </div>
                        <?php if ($is_barak) : ?>
                            <div class="form-group row">
                                <label for="en_area_g" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Ganda : <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control form-control-sm" id="en_area_g" x-model="en_area_g">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="en_area_kr" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Kranti : <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control form-control-sm" id="en_area_kr" x-model="en_area_kr">
                                </div>
                            </div>
                        <?php endif; ?>
                        </section>
                        <div class="form-group row">
                            <label for="longitude" class="col-sm-4 col-form-label text-right">Longitude : </label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control form-control-sm" id="longitude" x-model="longitude">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="latitude" class="col-sm-4 col-form-label text-right">Latitude : </label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control form-control-sm" id="latitude" x-model="latitude">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <td colspan="13" class="text-center">Encroacher Details</td>
                                    </tr>
                                    <tr class="bg-info text-white" style="position: sticky; top:0; z-index:10;">
                                        <td class="min-width-td">Name</td>
                                        <td class="no-wrap">Father's Name</td>
                                        <td class="min-width-td">Gender</td>
                                        <td class="no-wrap">Encroached From</td>
                                        <td class="no-wrap">Encroached To</td>
                                        <td class="no-wrap">Landless Indigenous</td>
                                        <td class="min-width-td">Landless</td>
                                        <td class="min-width-td">caste</td>
                                        <td class="no-wrap">Erosion Affected</td>
                                        <td class="no-wrap">Landslide Prone</td>
                                        <td class="no-wrap">Type Of Land Use</td>
                                        <td class="min-width-td">Type</td>
                                        <td >Action</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <template x-for="(enc,encroacher_index) in encroachers" :key="encroacher_index">
                                        <tr>
                                            <td>
                                                <input type="text" x-model="enc.name" placeholder="Name" class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="text" x-model="enc.fathers_name" placeholder="Father's Name" class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <select x-model="enc.gender" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <template x-for="(gender,index) in genders" :key="index">
                                                        <option :selected="gender.id == enc.gender" :value="gender.id"><span x-text="gender.gen_name_eng"></span>(<span x-text="gender.gen_name_ass"></span>)</option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" x-model="enc.encroachment_from" class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="date" x-model="enc.encroachment_to" class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <select x-model="enc.landless_indigenous" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <option value="Y">Yes</option>
                                                    <option value="N">No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.landless" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <option value="Y">Yes</option>
                                                    <option value="N">No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.caste" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <template x-for="(caste,index) in castes" :key="index">
                                                        <option :selected="caste.caste_id == enc.caste" :value="caste.caste_id"><span x-text="caste.caset_name_eng"></span></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.erosion" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <option value="Y">Yes</option>
                                                    <option value="N">No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.landslide" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <option value="Y">Yes</option>
                                                    <option value="N">No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.type_of_land_use" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <template x-for="(land_used_type,index) in land_used_types" :key="index">
                                                        <option :selected="land_used_type.CODE == enc.type_of_land_use" :value="land_used_type.CODE"><span x-text="land_used_type.NAME"></span></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td>
                                                <select x-model="enc.type_of_encroacher" id="" class="form-control form-control-sm">
                                                    <option value="">--Select--</option>
                                                    <template x-for="(encroacher_type,index) in encroacher_types" :key="index">
                                                        <option :selected="encroacher_type.CODE == enc.type_of_encroacher" :value="encroacher_type.CODE"><span x-text="encroacher_type.NAME"></span></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td>
                                                <button @click="removeEncroacher(encroacher_index)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <th colspan="13" class="text-center">
                                            <button @click="addEncroacher" :disabled="whether_encroached != 'Y'" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Add more controls"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add Encroacher's Details&nbsp;</button>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" :disabled="whether_encroached == 'Y' && encroachers.length == 0"  @click="submitForm" class="btn btn-success btn-sm">SUBMIT</button>
            </div>
        </div>
    </div>
</div>