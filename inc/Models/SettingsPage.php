<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Models;

use tf\LinkedTaxonomies\Views;

/**
 * Class SettingsPage
 *
 * @package tf\LinkedTaxonomies\Models
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
	 * Constructor. Set up the properties.
	 */
	public function __construct() {

		/**
		 * Filter the capability required to list the linked taxonomies.
		 *
		 * @param string $capability Capability required to list the linked taxonomies.
		 */
		$this->capabilities[ 'list' ] = apply_filters( 'list_linked_taxonomies_capability', 'manage_categories' );

		/**
		 * Filter the capability required to edit the linked taxonomies.
		 *
		 * @param string $capability Capability required to edit the linked taxonomies.
		 */
		$this->capabilities[ 'edit' ] = apply_filters( 'edit_linked_taxonomies_capability', 'manage_options' );
	}

	/**
	 * Return the capability for the given action.
	 *
	 * @param string $action Capability action.
	 *
	 * @return string
	 */
	public function get_capability( $action ) {

		return empty( $this->capabilities[ $action ] ) ? 'do_not_allow' : $this->capabilities[ $action ];
	}

	/**
	 * Return the page slug.
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

	/**
	 * Check if the current user has the capability required to perform the given action.
	 *
	 * @param string $action Action name.
	 *
	 * @return bool
	 */
	public function current_user_can( $action ) {

		return empty( $this->capabilities[ $action ] ) ? FALSE : current_user_can( $this->capabilities[ $action ] );
	}

}
