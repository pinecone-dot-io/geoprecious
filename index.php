<?php

namespace geoprecious;

define( 'GEOPRECIOUS_VERSION', '0.0.4' );

// no trailing slash
if( !defined('GEOPRECIOUS_PLUGIN_DIR') )
	define( 'GEOPRECIOUS_PLUGIN_DIR', __DIR__ );
	
if( !defined('GEOPRECIOUS_PLUGIN_URL') )
	define( 'GEOPRECIOUS_PLUGIN_URL', plugins_url('', __FILE__) );
	
if( is_admin() )
	require GEOPRECIOUS_PLUGIN_DIR.'/admin.php';

// @TODO look into loading these on demand
require GEOPRECIOUS_PLUGIN_DIR.'/api.php';
require GEOPRECIOUS_PLUGIN_DIR.'/functions.php';
require GEOPRECIOUS_PLUGIN_DIR.'/model.php';
require GEOPRECIOUS_PLUGIN_DIR.'/shortcode.php';
require GEOPRECIOUS_PLUGIN_DIR.'/sql.php';


