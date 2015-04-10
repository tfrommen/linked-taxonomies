<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controller;

use tf\LinkedTaxonomies\Model;

/**
 * Class Admin
 *
 * @package tf\LinkedTaxonomies\Controller
 */
class Admin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Init properties.
	 *
	 * @see tf\LinkedTaxonomies\Plugin::initialize()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Wire backend-specific functions up.
	 *
	 * @see tf\LinkedTaxonomies\Plugin::initialize()
	 *
	 * @return void
	 */
	public function initialize() {

		$text_domain = new Model\TextDomain( $this->file );
		$text_domain->load();

		$settings_page = new Model\SettingsPage();
		add_action( 'admin_menu', array( $settings_page, 'add' ), PHP_INT_MAX );

		$hook_suffix = 'settings_page_' . $settings_page->get_slug();
		add_action( 'admin_head-' . $hook_suffix, array( new Model\Script( $this->file ), 'enqueue' ) );
		add_action( 'admin_head-' . $hook_suffix, array( new Model\Style( $this->file ), 'enqueue' ) );

		add_action( 'admin_init', array( new Settings( $settings_page ), 'register_settings' ) );
	}

}
