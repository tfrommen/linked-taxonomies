<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

/** @var \wpdb $wpdb */
global $wpdb;

$uninstaller = new Uninstall\Uninstaller(
	new Update\Updater(),
	$wpdb
);
$uninstaller->uninstall();
