<div class="wrap">
	<h2>GeoPrecious</h2>
	<form action="" method="post">
		<fieldset>
			<legend>Post Types</legend>
			<label>All<input type="checkbox" name=""/></label>
			<?php foreach( $post_types as $post_type ): ?>
			<? //dbug($post_type); ?>
			<label><?php echo $post_type->labels->name; ?><input type="checkbox" name="<?php echo $post_type->name; ?>"/></label>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset>
			<legend>Taxonomies</legend>
		</fieldset>
		
		<fieldset>
			<legend>Users</legend>
		</fieldset>
		
		<input type="submit"/>
	</form>
</div>