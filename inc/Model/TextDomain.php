<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model;

/**
 * Class TextDomain
 *
 * @package tf\LinkedTaxonomies\Model
 */
class TextDomain {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * Constructor. Set up properties.
	 *
	 * @see tf\LinkedTaxonomies\Plugin::initialize()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->path = plugin_basename( $file );
		$this->path = dirname( $this->path ) . '/languages';
	}

	/**
	 * Load text domain.
	 *
	 * @see tf\LinkedTaxonomies\Controller\Admin::initialize()
	 *
	 * @return void
	 */
	public function load() {

		load_plugin_textdomain( 'linked-taxonomies', FALSE, $this->path );
	}

}
