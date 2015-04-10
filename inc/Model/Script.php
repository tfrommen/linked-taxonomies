<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model;

/**
 * Class Script
 *
 * @package tf\LinkedTaxonomies\Model
 */
class Script {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Set up properties.
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
		$file = 'assets/js/admin.js';
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
