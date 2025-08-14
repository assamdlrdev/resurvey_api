<style>
    #map1 {
        width: 100%;
        height: 430px;
    }

    .swal-wide {
        width: 95% !important;
    }
</style>
<!-- <script src="<?php echo base_url('assets/plugins/ol/ol.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/ol/ol.css') ?>"> -->
<div id="accordion">
    <?php if (count($revert_logs)): ?>
        <div class="card">
            <div class="card-header" id="headingRevertLog">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseRevertLog" aria-expanded="true" aria-controls="collapseRevertLog">
                Revert Logs
                </button>
            </h5>
            </div>

            <div id="collapseRevertLog" class="collapse show" aria-labelledby="headingRevertLog" data-parent="#accordion">
            <div class="card-body">
                <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Reverted By</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($revert_logs as $key => $revert_log):

                ?>
                    <tr>
                        <td><?= $key + 1; ?></td>
                        <td><?= date('d/m/Y', strtotime($revert_log['created_at'])); ?></td>
                        <td><?= $revert_log['reason'] ?></td>
                        <td><?= $revert_log['name'] ?></td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
            </div>
            </div>
        </div>
        
    <?php endif; ?>

    <?php
    if (count($final_upload_data)) {
    ?>
        <div class="card">
            <div class="card-header" id="headingFinalUploadLog">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFinalUploadLog" aria-expanded="true" aria-controls="collapseFinalUploadLog">
                        Final Data
                    </button>
                </h5>
            </div>

            <div id="collapseFinalUploadLog" class="collapse show" aria-labelledby="headingFinalUploadLog" data-parent="#accordion">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Document Name</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($final_upload_data as $key => $final_upload):

                            ?>
                                <tr>
                                    <td><?= $key + 1; ?></td>
                                    <td><?= $final_upload['document_name'] ?></td>
                                    <td>
                                        <a href="<?= base_url('') . FINAL_SURVEYED_FILE_UPLOAD_PATH . $final_upload['file_name'] ?>" download class="btn btn-sm btn-dark">Download File</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="card">
        <div class="card-header" id="headingSurvPrevProccess">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSurveyPrevProcess" aria-expanded="true" aria-controls="collapseSurveyPrevProcess">
                    Progress Logs
                    <?php if ($can_complete_survey): ?>
                        <?php if(COMPLETE_SURVEYOR_SURVEY_BTN_ENABLE): ?>
                            <button class="btn btn-danger float-right complete_survey" data-url="<?= base_url('index.php/surveyor-village/' . $surveyor_village->id . '/survey-complete') ?>">Complete Survey</button>
                        <?php else: ?>
                            <button class="btn btn-danger float-right" disabled>Complete Survey</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($can_complete_gis_qaqc && $surveyor_village->is_gis_qaqc_completed == 0): ?>
                        <?php if(COMPLETE_GIS_ASSISTANT_QAQC_BTN_ENABLE): ?>
                            <button class="btn btn-danger float-right complete_gis_qaqc" data-url="<?= base_url('index.php/gis/qa_qc/' . $surveyor_village->id . '/mark-complete') ?>">Complete QAQC</button>
                        <?php else: ?>
                            <button class="btn btn-danger float-right" disabled>Complete QAQC</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </button>
            </h5>
        </div>

        <div id="collapseSurveyPrevProcess" class="collapse show" aria-labelledby="headingSurvPrevProccess" data-parent="#accordion">
            <div class="card-body">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Date</th>
                            <th>Land Parcel Survey</th>
                            <th>Area Surveyed (in Sq. Mtr.)</th>
                            <th>File</th>
                            <?php if ($show_action): ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($daily_reports)):
                            foreach ($daily_reports as $key => $daily_report):

                        ?>
                                <tr>
                                    <td><?= $key + 1; ?></td>
                                    <td><?= date('d/m/Y', strtotime($daily_report['report_date'])) ?></td>
                                    <td><?= $daily_report['land_parcel_survey'] ?></td>
                                    <td><?= $daily_report['area_surveyed'] ?></td>
                                    <td>
                                        <?php if ($daily_report['file_ext'] == 'dxf' && $daily_report['bhunaksha_ref_id']): ?>
                                            <!-- <a href="javascript:void(0);" data-url="<?= base_url('index.php/survey/' . $daily_report['id'] . '/dly/fetch-map') ?>" class="show_map btn btn-sm btn-success">Show Map</a> -->
                                            <a href="<?= base_url('index.php/survey/' . $daily_report['id'] . '/dly/fetch-map') ?>" target="_blank" class="btn btn-sm btn-success">Show Map</a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('') . SURVEYED_FILE_UPLOAD_PATH . $daily_report['file_name'] ?>" download class="btn btn-sm btn-dark">Download File</a>
                                        <?php
                                            if($daily_report['is_gis_qaqc_completed'] == 1):
                                        ?>
                                                <a class="btn btn-warning btn-sm" data-toggle="collapse" href="#collapseReport_<?= $daily_report['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseReport_<?= $daily_report['id'] ?>">
                                                    View GIS QAQC
                                                </a>
                                        <?php
                                            endif;
                                        ?>
                                    </td>
                                    <?php if ($show_action): ?>
                                        <td>
                                            <?php
                                            if ($can_access_daily_progress_edit_btn && $surveyor_village->is_surveyor_completed_survey == 0 && (date('Y-m-d') == $daily_report['report_date'] || ENABLE_ALL_DLY_SURVEY_REPORT_EDIT)):
                                            ?>
                                                <button class="btn btn-primary btn-sm edit_dly_report" data-url="<?= base_url('index.php/surveyor-village/' . $daily_report['id'] . '/update-daily-progress'); ?>" data-land_parcel_area="<?= $daily_report['land_parcel_survey'] ?>" data-area_surveyed="<?= $daily_report['area_surveyed'] ?>">Edit</button>
                                            <?php
                                            elseif($show_gis_qa_qc_btn):
                                                if($daily_report['is_gis_qaqc_completed'] == 0):
                                            ?>
                                                    <button class="btn btn-primary btn-sm add_qa_qc" data-url="<?= base_url('index.php/gis/qa_qc/' . $daily_report['id'] . '/daily-upload-report/save'); ?>" >Submit QAQC</button>
                                            <?php
                                                endif;
                                            ?>
                                                
                                            <?php
                                            else:
                                            ?>
                                                -
                                            <?php
                                            endif;
                                            ?>

                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <?php
                                    if($daily_report['is_gis_qaqc_completed'] == 1 && $daily_report['gis_assistant_qaqc_report']):
                                ?>
                                        <tr class="collapse" id="collapseReport_<?= $daily_report['id'] ?>">
                                            <td class="text-center text-gray"><i class="fa fa-flip-vertical fa-mail-forward"></i></td>
                                            <td><?= date('d/m/Y', strtotime($daily_report['gis_assistant_qaqc_report']['report_date'])) ?></td>
                                            <td><?= $daily_report['gis_assistant_qaqc_report']['land_parcel_survey'] ?></td>
                                            <td><?= $daily_report['gis_assistant_qaqc_report']['area_surveyed'] ?></td>
                                            <td>
                                                <?php if ($daily_report['gis_assistant_qaqc_report']['bhunaksha_ref_id']): ?>
                                                    <a href="<?= base_url('index.php/survey/' . $daily_report['gis_assistant_qaqc_report']['id'] . '/gis_qaqc_dly/fetch-map') ?>" target="_blank" class="btn btn-sm btn-success">Show Map</a>
                                                <?php endif; ?>
                                                <a href="<?= base_url('') . SURVEYED_FILE_UPLOAD_PATH . $daily_report['gis_assistant_qaqc_report']['file_name'] ?>" download class="btn btn-sm btn-dark">Download File</a>
                                            </td>
                                            <?php if ($show_action): ?>
                                                <td>
                                                    <?php
                                                    if($show_gis_qa_qc_btn && $surveyor_village->is_gis_qaqc_completed == 0):
                                                    ?>
                                                        <button class="btn btn-primary btn-sm edit_qa_qc" data-url="<?= base_url('index.php/gis/qa_qc/' . $daily_report['gis_assistant_qaqc_report']['id'] . '/report-update'); ?>" data-land_parcel_area="<?= $daily_report['gis_assistant_qaqc_report']['land_parcel_survey'] ?>" data-area_surveyed="<?= $daily_report['gis_assistant_qaqc_report']['area_surveyed'] ?>">Edit</button>
                                                    <?php
                                                    endif;
                                                    ?>

                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                <?php
                                    endif;
                                ?>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td class="text-center" colspan="<?= $show_action ? '6' : '5'; ?>">No record found</td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!---// Add land detail modal --->
<!-- <div class="modal" id="landMapPreview" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="border: none">

      <div class="modal-header" style="color:#fff; background-color:#2196F3; font-weight: bold; border: none">
        Land Preview
        <button type="button" class="btn btn-sm" style="background-color: white; color: black" data-dismiss="modal">Close</button>
      </div>
      <div class="modal-body">
        <div id="map1"></div>
      </div>
    </div>
  </div>
</div> -->
<!---// Add land detail modal --->
<script type="text/javascript">
    $(document).ready(function() {
        let fetchingMap = false;
        $(document).on('click', '.show_map', function() {
            const actionUrl = $(this).data('url');
            if (!fetchingMap) {
                // console.log(actionUrl);
                fetchingMap = true;
                $.ajax({
                    url: actionUrl,
                    dataType: "JSON",
                    method: 'POST',
                    data: {},
                    contentType: 'application/x-www-form-urlencoded',
                    success: function(data) {
                        fetchingMap = false;

                        if (data.success) {
                            Swal.fire({
                                title: 'Map preview',
                                html: `<div id="map1"></div><div id="popup" class="ol-popup">
                                        <div id="popup-content"></div>
                                    </div>`,
                                customClass: 'swal-wide',
                            });
                            // $('#map1').html('');
                            // $('#landMapPreview').modal('show');

                            if (typeof ol === 'undefined' || !ol.format || !ol.layer || !ol.source) {
                                console.error('OpenLayers is not loaded correctly.');
                                return;
                            }
                            var format = new ol.format.GeoJSON();
                            var features = format.readFeatures(data.map_data);
                            if (features.length == 0) {
                                Swal.fire({
                                    title: "The village map was not found on the Bhunaksha portal.",
                                    icon: "info",
                                });
                            }
                            villVector = new ol.layer.Vector({ // Initialize within the callback
                                source: new ol.source.Vector({
                                    format: format
                                }),

                                style: function(feature, resolution) {
                                    var textValue = feature.get('Text'); // Get the text from the feature
                                    // var intValue = parseInt(textValue); // Convert to integer
                                    var style = new ol.style.Style({
                                        image: new ol.style.Circle({
                                            radius: 5,
                                            stroke: new ol.style.Stroke({
                                                color: 'rgba(0, 0, 0, 0.7)',
                                            }),
                                            fill: new ol.style.Fill({
                                                color: 'blue',
                                            }),
                                        }),
                                        fill: new ol.style.Fill({
                                            color: 'rgba(201, 199, 77, 0.3)'
                                        }),
                                        stroke: new ol.style.Stroke({
                                            color: '#17202A',
                                            width: 1
                                        }),
                                        text: new ol.style.Text({
                                            font: '12px Verdana',
                                            // text: textValue,
                                            fill: new ol.style.Fill({
                                                color: 'red'
                                            }),
                                            stroke: new ol.style.Stroke({
                                                color: 'white',
                                                width: 0.5
                                            })
                                        })
                                    });

                                    return [style];
                                }
                            });

                            villVector.getSource().addFeatures(features); // Add features to initialized layer

                            map = new ol.Map({ // Initialize map after villVector
                                layers: [villVector],
                                target: 'map1',
                                view: new ol.View({
                                    zoom: 4,
                                    minZoom: 0,
                                    maxZoom: 100
                                })
                            });

                            // map.getView().fit(villVector.getSource().getExtent(), map.getSize());

                            map.getView().fit(villVector.getSource().getExtent(), {
                                size: map.getSize(),
                                maxZoom: 18 // Adjust the fit to not zoom in too much
                            });

                            // Create a popup overlay
                            popup = new ol.Overlay({
                                element: document.getElementById('popup'),
                                positioning: 'bottom-center',
                                stopEvent: false,
                                offset: [0, -10]
                            });
                            map.addOverlay(popup);

                            // Add hover event to show popup
                            map.on('pointermove', function(evt) {
                                var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                                    // alert(feature.get('Text'));
                                    return feature;
                                });

                                if (feature) {
                                    var coordinates = feature.getGeometry().getCoordinates();
                                    // var textValue = feature.get('kide'); // Get the text from the feature
                                    var textValue = feature.get('Text'); // Get the text from the feature
                                    var content = textValue;
                                    $(popup.getElement()).popover('dispose');
                                    $(popup.getElement()).popover({
                                        placement: 'top',
                                        html: true,
                                        content: content
                                    });
                                    popup.setPosition(evt.coordinate);
                                    $(popup.getElement()).popover('show');
                                } else {
                                    $(popup.getElement()).popover('dispose');
                                }
                            });
                        } else {
                            Swal.fire({
                                title: data.message,
                                icon: 'warning',
                            });
                        }
                    },
                    error: (error) => {
                        fetchingMap = false;
                        Swal.fire({
                            title: "SOMETHING WENT WRONG !!!!",
                            icon: 'error',
                        });
                    },
                });
            }

        });
    });
</script>