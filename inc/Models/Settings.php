<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Models;

/**
 * Class Settings
 *
 * @package tf\LinkedTaxonomies\Models
 */
class Settings {

	/**
	 * @var SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param SettingsPage $settings_page Settings page model.
	 */
	public function __construct( SettingsPage $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Register the settings.
	 *
	 * @wp-hook admin_init
	 *
	 * @return void
	 */
	public function register() {

		$option_name = Option::get_name();
		register_setting(
			$option_name,
			$option_name,
			array( $this, 'sanitize' )
		);
	}

	/**
	 * Sanitize the settings data.
	 *
	 * @param array $data Settings data.
	 *
	 * @return array
	 */
	public function sanitize( $data ) {

		if ( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsErrors\NoPermissionToEdit();
			$error->add();

			return Option::get();
		}

		$sanitized_data = array();

		foreach ( $data as $source => $targets ) {
			if (
				! is_string( $source )
				|| $source === ''
			) {
				$error = new SettingsErrors\InvalidTaxonomy( $source );
				$error->add();
			} else {
				foreach ( $targets as $target => $link ) {
					if (
						! is_string( $target )
						|| $target === ''
					) {
						$error = new SettingsErrors\InvalidTaxonomy( $target );
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
