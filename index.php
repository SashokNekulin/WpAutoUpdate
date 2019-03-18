<?php

require_once 'vendor/autoload.php';
/**
 * Plugin 
 */

$plugin = new SashokNekulin\WpAutoUpdate\PluginUpdate( __FILE__ );
$plugin->set_username( 'SashokNekulin' );
$plugin->set_repository( 'tf-tag' );
/*
	$plugin->authorize( 'CODE_REPOS' ); // Your auth code goes here for private repos
*/
$plugin->initialize();



/**
 * Template
 */

$theme = new SashokNekulin\WpAutoUpdate\ThemeUpdate( __FILE__ );
$theme->set_username( 'SashokNekulin' );
$theme->set_repository( 'tf-tag' );
/*
	$theme->authorize( 'CODE_REPOS' ); // Your auth code goes here for private repos
*/
$theme->initialize();


?>