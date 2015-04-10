<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model;

/**
 * Class Style
 *
 * @package tf\LinkedTaxonomies\Model
 */
class Style {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Enqueue the script file.
	 *
	 * @wp-hook admin_print_scripts-{$hook_suffix}
	 *
	 * @return void
	 */
	public function enqueue() {

		$url = plugin_dir_url( $this->file );
		$file = 'assets/css/admin.css';
		$path = plugin_dir_path( $this->file );
		$version = filemtime( $path . $file );
		wp_enqueue_style(
			'linked-taxonomies-admin',
			$url . $file,
			array(),
			$version
		);
	}

}
