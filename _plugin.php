<?php
/*
Plugin Name: GeoPrecious
Plugin URI: 
Description: 
Author: 
Version: 0.0.5
Author URI:
*/

register_activation_hook( __FILE__, create_function("", '$ver = "5.3"; if( version_compare(phpversion(), $ver, "<") ) die( "This plugin requires PHP version $ver or greater be installed." );') );

register_activation_hook( __FILE__, 'activation' );

require __DIR__.'/index.php';