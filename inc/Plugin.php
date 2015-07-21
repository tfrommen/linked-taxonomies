<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies;

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
	 * Constructor. Set up the properties.
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function initialize() {

		$taxonomy = new Models\Taxonomy();
		$taxonomy_controller = new Controllers\Taxonomy( $taxonomy );
		$taxonomy_controller->initialize();

		if ( is_admin() ) {
			$text_domain = new Models\TextDomain( $this->file );
			$text_domain->load();

			$settings_page = new Models\SettingsPage();
			$settings = new Models\Settings( $settings_page );
			$settings_page_view = new Views\SettingsPage( $settings_page );
			$settings_controller = new Controllers\Settings( $settings, $settings_page_view );
			$settings_controller->initialize();

			$script = new Models\Script( $this->file );
			$style = new Models\Style( $this->file );
			$assets_controller = new Controllers\Assets( $script, $style, $settings_page );
			$assets_controller->initialize();
		}
	}

}
