<div id="map"></div>

<script type="text/javascript">
	// @TODO make dynamic
	jQuery( document ).ready( function(){
		new geoprecious_admin( {
			api_key: '<?= $api_key; ?>',
			bounds: <?= json_encode( $bounds ); ?>,
			data: <?= json_encode( $data ); ?>,
			map_id: 'map'
		} );
	} );
</script>