<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Linked Taxonomies
 * Plugin URI:  https://github.com/tfrommen/linked-taxonomies
 * Description: This plugin links two (or more) taxonomies and synchronizes their terms.
 * Author:      Thorsten Frommen
 * Author URI:  http://tfrommen.de
 * Version:     1.2.0
 * Text Domain: linked-taxonomies
 * Domain Path: /languages
 * License:     GPLv3
 */

namespace tfrommen\LinkedTaxonomies;

if ( ! function_exists( 'add_action' ) ) {
	return;
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\initialize' );

/**
 * Initializes the plugin.
 *
 * @wp-hook plugins_loaded
 *
 * @return void
 */
function initialize() {

	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		require_once __DIR__ . '/vendor/autoload.php';
	}

	$plugin = new Plugin( __FILE__ );
	$plugin->initialize();
}
