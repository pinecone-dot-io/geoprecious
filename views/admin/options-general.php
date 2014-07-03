<div class="wrap geoprecious">
	<h2>GeoPrecious</h2>
	<form action="" method="post">
		<input type="hidden" name="_wpnonce" value="<?php echo $wpnonce; ?>"/>
		<fieldset>
			<legend>API Key <a href="https://www.mapbox.com">https://www.mapbox.com</a></legend>
			<label><input type="text" name="api_key" value="<?php echo $api_key; ?>"/></label>
		</fieldset>
		
		<fieldset>
			<legend>Post Types</legend>
			<label>All<input type="checkbox" name="post_type[all]"/></label>
			<?php foreach( $post_types as $post_type ): ?>
			<? //dbug($post_type); ?>
			<label><?php echo $post_type->labels->name; ?><input type="checkbox" name="<?php echo $post_type->name; ?>"/></label>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset>
			<legend>Taxonomies</legend>
			<label>All<input type="checkbox" name="taxonomy[all]"/></label>
		</fieldset>
		
		<fieldset>
			<legend>Users</legend>
			<label>All<input type="checkbox" name="user[all]"/></label>
		</fieldset>
		
		<input type="submit"/>
	</form>
</div>