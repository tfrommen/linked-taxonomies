<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Taxonomy;

/**
 * Taxonomy controller.
 *
 * @package tfrommen\LinkedTaxonomies\Taxonomy
 */
class Controller {

	/**
	 * @var Taxonomy
	 */
	private $taxonomy;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Taxonomy $taxonomy Taxonomy model.
	 */
	public function __construct( Taxonomy $taxonomy ) {

		$this->taxonomy = $taxonomy;
	}

	/**
	 * Wires up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'created_term', array( $this->taxonomy, 'insert_term' ), 10, 3 );

		add_action( 'edit_terms', array( $this->taxonomy, 'save_term' ), 10, 2 );

		add_action( 'edited_term_taxonomy', array( $this->taxonomy, 'update_term' ), 10, 2 );

		add_action( 'delete_term', array( $this->taxonomy, 'delete_term' ), 10, 4 );
	}

}
