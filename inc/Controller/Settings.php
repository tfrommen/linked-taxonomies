<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controller;

use tf\LinkedTaxonomies\Model;
use tf\LinkedTaxonomies\Model\SettingsError;

/**
 * Class Settings
 *
 * @package tf\LinkedTaxonomies\Controller
 */
class Settings {

	/**
	 * @var Model\SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\LinkedTaxonomies\Controller\Admin::initialize()
	 *
	 * @param Model\SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Model\SettingsPage $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Register the settings.
	 *
	 * @wp-hook admin_init
	 *
	 * @return void
	 */
	public function register_settings() {

		$option_name = Model\Option::get_name();
		register_setting(
			$option_name,
			$option_name,
			array( $this, 'sanitize_data' )
		);
	}

	/**
	 * Sanitize the settings data.
	 *
	 * @see register_settings()
	 *
	 * @param array $data Settings data.
	 *
	 * @return array
	 */
	public function sanitize_data( $data ) {

		if ( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsError\NoPermissionToEdit();
			$error->add();

			return Model\Option::get();
		}

		$sanitized_data = array();

		foreach ( $data as $source => $targets ) {
			if (
				! is_string( $source )
				|| $source === ''
			) {
				$error = new SettingsError\InvalidTaxonomy( $source );
				$error->add();
			} else {
				foreach ( $targets as $target => $link ) {
					if (
						! is_string( $target )
						|| $target === ''
					) {
						$error = new SettingsError\InvalidTaxonomy( $target );
						$error->add();
					} else {
						switch ( (int) $link ) {
							case 1:
								$sanitized_data = $this->add_link( $sanitized_data, $source, $target );
								break;

							case 2:
								$sanitized_data = $this->add_link( $sanitized_data, $source, $target, TRUE );
								break;
						}
					}
				}
			}
		}

		return $sanitized_data;
	}

	/**
	 * Add a link for the given source-target pair to the given array.
	 *
	 * @param array  $links         Optional. Links. Defaults to array().
	 * @param string $source        Source taxonomy name.
	 * @param string $target        Target taxonomy name.
	 * @param bool   $bidirectional Optional. Should the link be bidirectional? Defaults to FALSE.
	 *
	 * @return array
	 */
	private function add_link( array $links = array(), $source, $target, $bidirectional = FALSE ) {

		if ( isset( $links[ $source ] ) ) {
			$links[ $source ][ $target ] = $target;
		} else {
			$links[ $source ] = array(
				$target => $target,
			);
		}

		if ( $bidirectional ) {
			return $this->add_link( $links, $target, $source );
		}

		return $links;
	}
}
