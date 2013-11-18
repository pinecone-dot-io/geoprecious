<div id="map"></div>

<script type="text/javascript">
	// @TODO make dynamic
	jQuery( document ).ready( function(){
		new geoprecious_admin( {
			api_key: '6cd994e708124b93976907fdd6e64e84',
			bounds: <?= json_encode( $bounds ); ?>,
			data: <?= json_encode( $data ); ?>,
			map_id: 'map'
		} );
	} );
</script>