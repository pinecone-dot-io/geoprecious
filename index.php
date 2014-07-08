<?php

namespace geoprecious;

define( 'GEOPRECIOUS_VERSION', '0.0.6' );

// no trailing slash
if( !defined('GEOPRECIOUS_PLUGIN_URL') )
	define( 'GEOPRECIOUS_PLUGIN_URL', plugins_url('', __FILE__) );
	
if( is_admin() )
	require __DIR__.'/admin.php';

// @TODO look into autoloading
//require __DIR__.'/api.php';
require __DIR__.'/functions.php';
require __DIR__.'/model.php';
require __DIR__.'/shortcode.php';

require __DIR__.'/lib/class-core.php';
require __DIR__.'/lib/class-collection.php';
require __DIR__.'/lib/class-point.php';

require __DIR__.'/lib/query-posts.php';
require __DIR__.'/lib/query-terms.php';
require __DIR__.'/lib/query-users.php';