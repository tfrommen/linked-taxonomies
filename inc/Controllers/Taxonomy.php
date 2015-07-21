<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controllers;

use tf\LinkedTaxonomies\Models\Taxonomy as Model;

/**
 * Class Taxonomy
 *
 * @package tf\LinkedTaxonomies\Controllers
 */
class Taxonomy {

	/**
	 * @var Model
	 */
	private $model;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model $model Model.
	 */
	public function __construct( Model $model ) {

		$this->model = $model;
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'created_term', array( $this->model, 'insert_term' ), 10, 3 );
		add_action( 'edit_terms', array( $this->model, 'save_term' ), 10, 2 );
		add_action( 'edited_term_taxonomy', array( $this->model, 'update_term' ), 10, 2 );
		add_action( 'delete_term', array( $this->model, 'delete_term' ), 10, 4 );
	}

}
