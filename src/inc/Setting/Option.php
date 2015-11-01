<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Setting;

/**
 * Static option model.
 *
 * @package tfrommen\LinkedTaxonomies\Setting
 */
class Option {

	/**
	 * @var string
	 */
	private static $name = 'linked_taxonomies';

	/**
	 * Returns the option name.
	 *
	 * @return string
	 */
	public static function get_name() {

		return self::$name;
	}

	/**
	 * Returns the option value.
	 *
	 * @param string[][] $default Optional. Default option value. Defaults to array().
	 *
	 * @return string[][]
	 */
	public static function get( array $default = array() ) {

		$value = get_option( self::$name, $default );
		if ( ! is_array( $value ) ) {
			return $default;
		}

		return $value;
	}

	/**
	 * Update the option to the given value.
	 *
	 * @param string[][] $value New option value.
	 *
	 * @return bool
	 */
	public static function update( array $value ) {

		$updated = update_option( self::$name, $value );

		return $updated;
	}

}
