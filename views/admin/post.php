<div id="map"></div>

<div id="map-points">
	<h3>Inputs:</h3>
</div>

<script type="text/javascript">
	// @TODO make dynamic
	var geo_data = {
		api_key: '<?php echo $api_key; ?>',
		bounds: <?php echo json_encode( $bounds ); ?>,
		data: <?php echo json_encode( $data ); ?>,
		map_id: 'map'
	};
		
	jQuery( document ).ready( function(){
		new geoprecious_admin( geo_data );
	} );
</script>