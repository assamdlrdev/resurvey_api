<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div :class='modalHeaderBg' class="modal-header">
                <h5 class="modal-title text-center" id="viewDetailsModalLabel"><span x-text="modalTitle"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nature_of_reservation" class="col-sm-4 col-form-label text-right">Type Of Govt Land : <span class="text-danger">*</span> </label>
                            <div class="col-sm-5">
                                <select readonly class="form-control form-control-sm" x-model="nature_of_reservation" id="nature_of_reservation">
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
                                <select x-model="whether_encroached" readonly class="form-control form-control-sm" id="whether_encroached">
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
                                    <input type="number" readonly class="form-control form-control-sm" id="en_area_b" x-model="en_area_b">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="en_area_k" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Katha : <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" readonly class="form-control form-control-sm" id="en_area_k" x-model="en_area_k">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="en_area_lc" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Lessa : <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" readonly class="form-control form-control-sm" id="en_area_lc" x-model="en_area_lc">
                                </div>
                            </div>
                            <?php if ($is_barak) : ?>
                                <div class="form-group row">
                                    <label for="en_area_g" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Ganda : <span class="text-danger">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="number" readonly class="form-control form-control-sm" id="en_area_g" x-model="en_area_g">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="en_area_kr" class="col-sm-4 col-form-label text-right">(Encroach-Area)-Kranti : <span class="text-danger">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="number" readonly class="form-control form-control-sm" id="en_area_kr" x-model="en_area_kr">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </section>
                        <div class="form-group row">
                            <label for="longitude" class="col-sm-4 col-form-label text-right">Longitude : </label>
                            <div class="col-sm-5">
                                <input type="number" readonly class="form-control form-control-sm" id="longitude" x-model="longitude">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="latitude" class="col-sm-4 col-form-label text-right">Latitude : </label>
                            <div class="col-sm-5">
                                <input type="number" readonly class="form-control form-control-sm" id="latitude" x-model="latitude">
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
                                        <td class="">Name</td>
                                        <td class="no-wrap">Father's Name</td>
                                        <td class="">Gender</td>
                                        <td class="no-wrap">Encroached From</td>
                                        <td class="no-wrap">Encroached To</td>
                                        <td class="no-wrap">Landless Indigenous</td>
                                        <td class="">Landless</td>
                                        <td class="">caste</td>
                                        <td class="no-wrap">Erosion Affected</td>
                                        <td class="no-wrap">Landslide Prone</td>
                                        <td class="no-wrap">Type Of Land Use</td>
                                        <td class="">Type</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <template x-for="(enc,encroacher_index) in encroachers" :key="encroacher_index">
                                        <tr>
                                            <td x-text="enc.name">
                                            </td>
                                            <td x-text="enc.fathers_name">
                                            </td>
                                            <td>
                                                <template x-for="(gender,index) in genders" :key="index">
                                                    <span x-show="gender.id == enc.gender"><span x-text="gender.gen_name_eng"></span>(<span x-text="gender.gen_name_ass"></span>)</span>
                                                </template>
                                            </td>
                                            <td x-text="enc.encroachment_from">
                                            </td>
                                            <td x-text="enc.encroachment_to">
                                            </td>
                                            <td>
                                                <span x-text="enc.landless_indigenous == 'Y' ? 'Yes' : (enc.landless_indigenous == 'N' ? 'No' : '')"></span>
                                            </td>
                                            <td>
                                                <span x-text="enc.landless == 'Y' ? 'Yes' : (enc.landless == 'N' ? 'No' : '')"></span>
                                            </td>
                                            <td>
                                                <template x-for="(caste,index) in castes" :key="index">
                                                    <span x-show="caste.caste_id == enc.caste"><span x-text="caste.caset_name_eng"></span></span>
                                                </template>
                                            </td>
                                            <td>
                                                <span x-text="enc.erosion == 'Y' ? 'Yes' : (enc.erosion == 'N' ? 'No' : '')"></span>
                                            </td>
                                            <td>
                                                <span x-text="enc.landslide == 'Y' ? 'Yes' : (enc.landslide == 'N' ? 'No' : '')"></span>
                                            </td>
                                            <td>
                                                <template x-for="(land_used_type,index) in land_used_types" :key="index">
                                                    <span x-show="land_used_type.CODE == enc.type_of_land_use"><span x-text="land_used_type.NAME"></span></span>
                                                </template>
                                            </td>
                                            <td>
                                                <template x-for="(encroacher_type,index) in encroacher_types" :key="index">
                                                    <span x-show="encroacher_type.CODE == enc.type_of_encroacher"><span x-text="encroacher_type.NAME"></span></span>
                                                </template>
                                            </td>
                                            <td x-show="enc.show_delete">
                                                <button @click="removeEncroacher(encroacher_index)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>