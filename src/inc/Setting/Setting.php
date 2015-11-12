<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Setting;

/**
 * Setting model.
 *
 * @package tfrommen\LinkedTaxonomies\Setting
 */
class Setting {

	/**
	 * @var string
	 */
	private $option_name;

	/**
	 * @var Sanitizer
	 */
	private $sanitizer;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Option    $option    Option model.
	 * @param Sanitizer $sanitizer Setting sanitizer object.
	 */
	public function __construct( Option $option, Sanitizer $sanitizer ) {

		$this->option_name = $option->get_name();

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

		register_setting(
			$this->option_name,
			$this->option_name,
			array( $this->sanitizer, 'sanitize' )
		);
	}

}
