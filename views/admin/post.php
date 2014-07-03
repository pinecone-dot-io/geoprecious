<div id="map"></div>

<script type="text/javascript">
	// @TODO make dynamic
	jQuery( document ).ready( function(){
		new geoprecious_admin( {
			api_key: '<?php echo $api_key; ?>',
			bounds: <?php echo json_encode( $bounds ); ?>,
			data: <?php echo json_encode( $data ); ?>,
			map_id: 'map'
		} );
	} );
</script>