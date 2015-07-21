<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Models;

/**
 * Class Script
 *
 * @package tf\LinkedTaxonomies\Models
 */
class Script {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $file Main plugin file
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Enqueue the script file.
	 *
	 * @wp-hook admin_footer-{$hook_suffix}
	 *
	 * @return void
	 */
	public function enqueue() {

		$url = plugin_dir_url( $this->file );
		$infix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$file = 'assets/js/admin' . $infix . '.js';
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
