<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Asset;

/**
 * Script model.
 *
 * @package tfrommen\LinkedTaxonomies\Asset
 */
class Script {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param string $file Main plugin file
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Enqueues the script file.
	 *
	 * @wp-hook admin_footer-{$hook_suffix}
	 *
	 * @return void
	 */
	public function enqueue() {

		$url = plugin_dir_url( $this->file );

		$file = 'assets/js/admin' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js';

		$path = plugin_dir_path( $this->file );

		$version = filemtime( $path . $file );

		wp_enqueue_script(
			'linked-taxonomies-admin',
			$url . $file,
			array( 'jquery' ),
			$version,
			TRUE
		);
	}

}
