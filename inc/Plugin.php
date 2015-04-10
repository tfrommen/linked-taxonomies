<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies;

use tf\LinkedTaxonomies\Controller;
use tf\LinkedTaxonomies\Model;

/**
 * Class Plugin
 *
 * @package tf\LinkedTaxonomies
 */
class Plugin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Init properties.
	 *
	 * @see init()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Initialize the controller.
	 *
	 * @see initialize()
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'wp_loaded', array( new Controller\Taxonomy(), 'initialize' ) );

		if ( is_admin() ) {
			$admin_controller = new Controller\Admin( $this->file );
			$admin_controller->initialize();
		}
	}

}
