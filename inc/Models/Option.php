<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Models;

/**
 * Class Option
 *
 * @package tf\LinkedTaxonomies\Models
 */
class Option {

	/**
	 * @var string
	 */
	private static $name = 'linked_taxonomies';

	/**
	 * Return the option name.
	 *
	 * @return string
	 */
	public static function get_name() {

		return self::$name;
	}

	/**
	 * Return the option value.
	 *
	 * @param array $default Optional. Default option value. Defaults to array().
	 *
	 * @return array
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
	 * @param array $value New option value.
	 *
	 * @return bool
	 */
	public static function update( array $value ) {

		return update_option( self::$name, $value );
	}

}
