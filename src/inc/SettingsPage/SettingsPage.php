<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsPage;

/**
 * Settings page model.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsPage
 */
class SettingsPage {

	/**
	 * @var string[]
	 */
	private $capabilities;

	/**
	 * @var string
	 */
	private $slug = 'linked_taxonomies';

	/**
	 * Constructor. Sets up the properties.
	 */
	public function __construct() {

		/**
		 * Filters the capability required to list linked taxonomies.
		 *
		 * @param string $capability Capability required to list linked taxonomies.
		 */
		$this->capabilities[ 'list' ] = (string) apply_filters(
			'list_linked_taxonomies_capability',
			'manage_categories'
		);

		/**
		 * Filters the capability required to edit linked taxonomies.
		 *
		 * @param string $capability Capability required to edit linked taxonomies.
		 */
		$this->capabilities[ 'edit' ] = (string) apply_filters( 'edit_linked_taxonomies_capability', 'manage_options' );
	}

	/**
	 * Checks if the current user has the capability required to perform the given action.
	 *
	 * @param string $action Action name.
	 *
	 * @return bool
	 */
	public function current_user_can( $action ) {

		return empty( $this->capabilities[ $action ] ) ? FALSE : current_user_can( $this->capabilities[ $action ] );
	}

	/**
	 * Returns the capability for the given action.
	 *
	 * @param string $action Capability action.
	 *
	 * @return string
	 */
	public function get_capability( $action ) {

		return empty( $this->capabilities[ $action ] ) ? 'do_not_allow' : $this->capabilities[ $action ];
	}

	/**
	 * Returns the page slug.
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

}
