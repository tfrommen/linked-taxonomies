<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Setting;

/**
 * Option model.
 *
 * @package tfrommen\LinkedTaxonomies\Setting
 */
class Option {

	/**
	 * @var string
	 */
	private $name = 'linked_taxonomies';

	/**
	 * Returns the option name.
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}

	/**
	 * Returns the option value.
	 *
	 * @param string[][] $default Optional. Default option value. Defaults to array().
	 *
	 * @return string[][]
	 */
	public function get( array $default = array() ) {

		$value = get_option( $this->name, $default );
		if ( ! is_array( $value ) ) {
			$value = $default;
			if ( ! is_array( $value ) ) {
				return array();
			}
		}

		return $value;
	}

}
