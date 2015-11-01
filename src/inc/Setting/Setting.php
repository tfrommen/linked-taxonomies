<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Setting;

/**
 * Setting model.
 *
 * @package tfrommen\LinkedTaxonomies\Setting
 */
class Setting {

	/**
	 * @var Sanitizer
	 */
	private $sanitizer;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Sanitizer $sanitizer Setting sanitizer object.
	 */
	public function __construct( Sanitizer $sanitizer ) {

		$this->sanitizer = $sanitizer;
	}

	/**
	 * Registers the setting.
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
			array( $this->sanitizer, 'sanitize' )
		);
	}

}
