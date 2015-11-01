<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Update;

/**
 * Handles all update-related stuff.
 *
 * @package tfrommen\LinkedTaxonomies\Update
 */
class Updater {

	/**
	 * @var string
	 */
	private $option_name = 'linked_taxonomies_version';

	/**
	 * @var string
	 */
	private $version;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param string $version Optional. Current plugin version. Defaults to '0'.
	 */
	public function __construct( $version = '0' ) {

		$this->version = (string) $version;
	}

	/**
	 * Returns the plugin version option name.
	 *
	 * @return string
	 */
	public function get_option_name() {

		return $this->option_name;
	}

	/**
	 * Updates the plugin data.
	 *
	 * @return bool
	 */
	public function update() {

		$old_version = (string) get_option( $this->option_name );
		if ( $old_version === $this->version ) {
			return FALSE;
		}

		update_option( $this->option_name, $this->version );

		return TRUE;
	}

}
