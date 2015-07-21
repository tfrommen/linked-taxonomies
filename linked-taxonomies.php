<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Linked Taxonomies
 * Plugin URI:  https://github.com/tfrommen/linked-taxonomies
 * Description: Link two (or more) taxonomies and synchronize their terms.
 * Author:      Thorsten Frommen
 * Author URI:  http://ipm-frommen.de/wordpress
 * Version:     1.1
 * Text Domain: linked-taxonomies
 * Domain Path: /languages
 * License:     GPLv3
 */

namespace tf\LinkedTaxonomies;

use tf\Autoloader;

if ( ! function_exists( 'add_action' ) ) {
	return;
}

require_once __DIR__ . '/inc/Autoloader/bootstrap.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\initialize' );

/**
 * Initialize the plugin.
 *
 * @wp-hook plugins_loaded
 *
 * @return void
 */
function initialize() {

	$autoloader = new Autoloader\Autoloader();
	$autoloader->add_rule( new Autoloader\NamespaceRule( __DIR__ . '/inc', __NAMESPACE__ ) );

	$plugin = new Plugin( __FILE__ );
	$plugin->initialize();
}
