<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Linked Taxonomies
 * Plugin URI:  https://github.com/tfrommen/linked-taxonomies
 * Description: This plugin links two (or more) taxonomies and synchronizes their terms.
 * Author:      Thorsten Frommen
 * Author URI:  http://tfrommen.de
 * Version:     1.2.0
 * Text Domain: linked-taxonomies
 * Domain Path: /src/languages
 * License:     GPLv3
 */

defined( 'ABSPATH' ) or die();

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/src/' . basename( __FILE__ );
