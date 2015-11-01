<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies;

/**
 * Main controller.
 *
 * @package tfrommen\LinkedTaxonomies
 */
class Plugin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $plugin_data;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;

		$headers = array(
			'version'     => 'Version',
			'text_domain' => 'Text Domain',
			'domain_path' => 'Domain Path',
		);
		$this->plugin_data = get_file_data( $file, $headers );
	}

	/**
	 * Initializes the plugin.
	 *
	 * @return void
	 */
	public function initialize() {

		$updater = new Update\Updater( $this->plugin_data[ 'version' ] );
		$updater->update();

		$taxonomy_controller = new Taxonomy\Controller(
			new Taxonomy\Taxonomy()
		);
		$taxonomy_controller->initialize();

		if ( is_admin() ) {
			$text_domain = new L10n\TextDomain( $this->plugin_data, $this->file );
			$text_domain->load();

			$settings_page = new SettingsPage\SettingsPage();
			$settings_page_controller = new SettingsPage\Controller(
				new SettingsPage\View( $settings_page )
			);
			$settings_page_controller->initialize();

			$setting_controller = new Setting\Controller(
				new Setting\Setting(
					new Setting\Sanitizer(
						$settings_page,
						new SettingsError\Factory()
					)
				)
			);
			$setting_controller->initialize();

			$assets_controller = new Asset\Controller(
				new Asset\Script( $this->file ),
				new Asset\Style( $this->file ),
				$settings_page
			);
			$assets_controller->initialize();
		}
	}

}
