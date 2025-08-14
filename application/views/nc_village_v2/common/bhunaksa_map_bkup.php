<script src="<?php echo base_url('assets/plugins/ol/ol.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/ol/ol.css') ?>">

<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?=base_url('assets/plugins/jquery/jquery.min.js')?>" type="text/javascript"></script>
<script>
    var villVector; // Define villVector at a higher scope

    $(document).ready(function() {
		$('#loader2').removeClass('invisible');
        $.post("<?= base_url()?>index.php/nc_village_v2/NcVillageCommonController/viewBhunaksaMapPost", {
            location: "<?= $location ?>"
        }, function(data) {
			if (typeof ol === 'undefined' || !ol.format || !ol.layer || !ol.source) {
				console.error('OpenLayers is not loaded correctly.');
				return;
			}
            var format = new ol.format.GeoJSON();
            var features = format.readFeatures(data);

            // Get min and max dag numbers from PHP only if case_type is NC_TO_C
            <?php if (isset($case_type) && $case_type === 'NC_TO_C' && isset($map_row->min_dag_no) && isset($map_row->max_dag_no)): ?>
                var minDag = <?= (int)$map_row->min_dag_no ?>;
                var maxDag = <?= (int)$map_row->max_dag_no ?>;
                var highlightDags = true;
            <?php else: ?>
                var minDag = null;
                var maxDag = null;
                var highlightDags = false;
            <?php endif; ?>

            if(features.length == 0)
            {
                swal({
                    title: "The village map was not found on the Bhunaksha portal.",
                    icon: "info",
                });
            }
            villVector = new ol.layer.Vector({  // Initialize within the callback
                source: new ol.source.Vector({
                    format: format
                }),

                style: function (feature, resolution) {
					var textValue = feature.get('kide'); // Get the text from the feature
					var intValue = parseInt(textValue); // Convert to integer
                    var style = new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'rgba(201, 199, 77)'
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#17202A',
                            width: 1
                        }),
                        text: new ol.style.Text({
                            font: '12px Verdana',
                            text: intValue.toString(),
                            fill: new ol.style.Fill({color: 'black'}),
                            stroke: new ol.style.Stroke({color: 'white', width: 0.5})
                        })

                    });
                    return [style];
                }
            });

            villVector.getSource().addFeatures(features);  // Add features to initialized layer

            map = new ol.Map({  // Initialize map after villVector
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
				maxZoom: 18  // Adjust the fit to not zoom in too much
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
					return feature;
				});

				if (feature) {
					var coordinates = feature.getGeometry().getCoordinates();
					var textValue = feature.get('kide'); // Get the text from the feature
					var intValue = parseInt(textValue); // Convert to integer
					var areaValue = parseFloat(feature.get('Area')).toFixed(4); // Format to 4 decimal places
					var content = "Dag No: " + intValue + "<br>Area (sq Meter): " + areaValue;
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
        });
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
		margin-top: -..px;
		/* half of the elements height */
		margin-right: -..px;
		/* half of the elements width */
	}
</style>
<style>
    #map1 {
        width: 100%;  /* or a specific pixel width like 600px */
        height: 800px;  /* or any height you prefer */
    }
</style>
<div id="popup"></div>
<?php if (isset($case_type) && $case_type === 'NC_TO_C' && isset($map_row->min_dag_no) && isset($map_row->max_dag_no)): ?>
    <div class="alert alert-info mt-3 mb-3">
        <strong>Note:</strong>
        <?php if (isset($merge_with_c_village->vill_loc->village->loc_name)): ?>
            This map displays the cadastral village <span style="color:red;"><?= $merge_with_c_village->vill_loc->village->loc_name ?></span> to which the NC village will be merged. 
        <?php else: ?>
            This map displays the cadastral village <span style="color:red;">N/A</span> to which the NC village will be merged. 
        <?php endif; ?>
        The map highlights the merged dags of the NC village <span style="color:red;"><?= isset($data['vill_name']) ? $data['vill_name'] : 'N/A' ?></span>. 
        The dag numbers of the NC village range from <span style="color:red;"><?= isset($map_row->min_dag_no) ? $map_row->min_dag_no : 'N/A' ?></span> to <span style="color:red;"><?= isset($map_row->max_dag_no) ? $map_row->max_dag_no : 'N/A' ?></span>.
    </div>
<?php endif; ?>
<table class="table table-striped table-bordered">
    <thead>
    <th class="text-center" colspan="6" style="background-color: #136a6f; color: #fff">
        Map Details
    </th>
    </thead>
    <tbody>
    <tr>
        <td width="20%">Village Name</td>
        <td width="20%" style="color:red"><?=$data['vill_name']?></td>
        <td width="15%">Total Dags</td>
        <td width="15%" style="color:red"><?=$data['dags']?></td>
        <td width="15%">Area (sq km)</td>
        <td width="15%" style="color:red"><?= $data['area']?></td>
    </tr>
    </tbody>
</table>
<div class="border border-primary m-2" style="width: 100%">
	<div id="map1"></div>
</div>
