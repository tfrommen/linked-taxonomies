<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model;

use tf\LinkedTaxonomies\Controller;
use tf\LinkedTaxonomies\View;

/**
 * Class SettingsPage
 *
 * @package tf\LinkedTaxonomies\Model
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
	 * @var string
	 */
	private $title;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\LinkedTaxonomies\Controller\Admin::initialize()
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

		$this->title = _x( 'Linked Taxonomies', 'Settings page title', 'linked-taxonomies' );
	}

	/**
	 * Return the page slug.
	 *
	 * @see tf\LinkedTaxonomies\View\AdminNotice::render()
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

	/**
	 * Return the page title.
	 *
	 * @see tf\LinkedTaxonomies\View\AdminNotice::render()
	 *
	 * @return string
	 */
	public function get_title() {

		return $this->title;
	}

	/**
	 * Add the settings page to the Pages menu.
	 *
	 * @wp-hook admin_menu
	 *
	 * @return void
	 */
	public function add() {

		$menu_title = _x( 'Taxonomies', 'Menu item title', 'linked-taxonomies' );
		add_options_page(
			$this->title,
			$menu_title,
			$this->capabilities[ 'list' ],
			$this->slug,
			array( new View\SettingsPage( $this ), 'render' )
		);
	}

	/**
	 * Check if the current user has the capability required to perform the given action.
	 *
	 * @see tf\LinkedTaxonomies\View\SettingsPage::render()
	 *
	 * @param string $action Action name.
	 *
	 * @return bool
	 */
	public function current_user_can( $action ) {

		return empty( $this->capabilities[ $action ] ) ? FALSE : current_user_can( $this->capabilities[ $action ] );
	}

}
