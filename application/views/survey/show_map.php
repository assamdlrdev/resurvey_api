<script src="<?php echo base_url('assets/plugins/ol/ol.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/ol/proj4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/plugins/ol/32646.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/ol/ol.css') ?>">

<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>" type="text/javascript"></script>
<script>
    var villVector; // Define villVector at a higher scope

    $(document).ready(function() {
        let json_data = (<?= $map_data ?>);
        $('#loader2').removeClass('invisible');
        if (typeof ol === 'undefined' || !ol.format || !ol.layer || !ol.source) {
            console.error('OpenLayers is not loaded correctly.');
            return;
        }
        // let json_data = JSON.parse(data);
        var format = new ol.format.GeoJSON();
        var features = format.readFeatures(json_data);
        if (features.length == 0) {
            swal({
                title: "The village map was not found on the Bhunaksha portal.",
                icon: "info",
            });
        }
        villVector = new ol.layer.Vector({ // Initialize within the callback
            source: new ol.source.Vector({
                format: format
            }),

            style: function(feature, resolution) {
                // var textValue = feature.get('Text'); // Get the text from the feature
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
            layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM() // Using OpenStreetMap as the base layer
                        }),
                        villVector
                    ],
            target: 'map1',
            view: new ol.View({
                projection: ol.proj.get('EPSG:32646'),
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
        let fileType = json_data.file_type;
        // Add hover event to show popup
        map.on('pointermove', function(evt) {
            var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                return feature;
            });

            if (feature) {
                var coordinates = feature.getGeometry().getCoordinates();
                var textValue = ''; // Get the text from the feature
                //if(fileType == 'dxf'){
                    //  textValue = feature.get('Text'); // Get the text from the feature
                //}else if(fileType == 'shp'){
                    let properties = feature.getProperties();
                    $.each(properties, function(propIndex, propVal){
                        if(propIndex.toLowerCase()!="geometry" && propIndex.toLowerCase()!="linetype" && propIndex.toLowerCase()!="subclasses" && propIndex.toLowerCase()!="entityhandle" && propIndex.toLowerCase()!="paperspace"){  
                            textValue += `${propIndex}: ${propVal}<br/>`
                        }
                    });
                    // textValue = feature.get('Text'); // Get the text from the feature
                // }

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
        $('#loader2').addClass('invisible');
        // $.post("<?= base_url('index.php/survey/' . $daily_report->id . '/'.  $type . '/fetch-map') ?>", {}, function(data) {
        // });
    });
</script>
<button id="loader2" class="btn btn-primary invisible">
    <span class="spinner-border spinner-border-sm"></span>
    Loading..
</button>
<style>
    #loader2 {
        position: fixed;
        z-index: 999999;
        /* High z-index so it is on top of the page */
        top: 50%;
        right: 50%;
        /* or: left: 50%; */
        /* margin-top: -..px; */
        /* half of the elements height */
        /* margin-right: -..px; */
        /* half of the elements width */
    }
</style>
<style>
    #map1 {
        width: 100%;
        /* or a specific pixel width like 600px */
        height: 450px;
        /* or any height you prefer */
    }
</style>
<div class="col-lg-12 col-md-12">
    <div class="text-center p-2 mb-2" style="font-size:18px; font-weight: bold; background-color: #4298c9">
        Map Preview
    </div>
    <div id="popup"></div>

    <div class="border border-primary m-2" style="width: 100%">
        <div id="map1"></div>
    </div>
</div>