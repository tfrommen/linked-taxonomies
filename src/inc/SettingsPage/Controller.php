<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsPage;

/**
 * Settings page controller.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsPage
 */
class Controller {

	/**
	 * @var View
	 */
	private $view;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param View $view Settings page view.
	 */
	public function __construct( View $view ) {

		$this->view = $view;
	}

	/**
	 * Wires up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'admin_menu', array( $this->view, 'add' ), PHP_INT_MAX );
	}

}
