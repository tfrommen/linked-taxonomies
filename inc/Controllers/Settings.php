<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controllers;

use tf\LinkedTaxonomies\Models\Settings as Model;
use tf\LinkedTaxonomies\Views\SettingsPage as View;

/**
 * Class Settings
 *
 * @package tf\LinkedTaxonomies\Controllers
 */
class Settings {

	/**
	 * @var Model
	 */
	private $model;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model $model Settings model.
	 * @param View  $view  Settings page view.
	 */
	public function __construct( Model $model, View $view ) {

		$this->model = $model;

		$this->view = $view;
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'admin_menu', array( $this->view, 'add' ), PHP_INT_MAX );

		add_action( 'admin_init', array( $this->model, 'register' ) );
	}

}
