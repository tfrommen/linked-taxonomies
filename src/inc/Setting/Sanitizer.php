<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Setting;

use tfrommen\LinkedTaxonomies\SettingsError\Factory as SettingsErrorFactory;
use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage;

/**
 * Setting sanitizer.
 *
 * @package tfrommen\LinkedTaxonomies\Setting
 */
class Sanitizer {

	/**
	 * @var SettingsErrorFactory
	 */
	private $settings_error_factory;

	/**
	 * @var SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param SettingsPage         $settings_page          Settings page object.
	 * @param SettingsErrorFactory $settings_error_factory Settings error factory object.
	 */
	public function __construct( SettingsPage $settings_page, SettingsErrorFactory $settings_error_factory ) {

		$this->settings_page = $settings_page;

		$this->settings_error_factory = $settings_error_factory;
	}

	/**
	 * Sanitizes the setting data.
	 *
	 * @param string[][] $data Settings data.
	 *
	 * @return string[][]
	 */
	public function sanitize( $data ) {

		if ( ! $this->settings_page->get_capability( 'edit' ) ) {
			$error = $this->settings_error_factory->create( 'no-permission-to-edit' );
			$error->add();

			$data = Option::get();

			return $data;
		}

		$sanitized_data = array();

		foreach ( $data as $source => $targets ) {
			if ( ! is_string( $source ) || $source === '' ) {
				$error = $this->settings_error_factory->create( 'invalid-taxonomy' );
				$error->add();
			} else {
				foreach ( $targets as $target => $link ) {
					if ( ! is_string( $target ) || $target === '' ) {
						$error = $this->settings_error_factory->create( 'invalid-taxonomy' );
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
	 * Adds a link for the given source-target pair to the given array.
	 *
	 * @param string[][] $links         Optional. Links. Defaults to array().
	 * @param string     $source        Source taxonomy name.
	 * @param string     $target        Target taxonomy name.
	 * @param bool       $bidirectional Optional. Should the link be bidirectional? Defaults to FALSE.
	 *
	 * @return string[][]
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
			$links = $this->add_link( $links, $target, $source );
		}

		return $links;
	}

}
