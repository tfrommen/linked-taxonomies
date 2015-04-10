<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controller;

use tf\LinkedTaxonomies\Model;

/**
 * Class Taxonomy
 *
 * @package tf\LinkedTaxonomies\Controller
 */
class Taxonomy {

	/**
	 * Wire up all taxonomy-specific functions.
	 *
	 * @wp-hook wp_loaded
	 *
	 * @return void
	 */
	public function initialize() {

		$model = new Model\Taxonomy();
		add_action( 'created_term', array( $model, 'insert_term' ), 10, 3 );
		add_action( 'edit_terms', array( $model, 'save_term' ), 10, 2 );
		add_action( 'edited_term_taxonomy', array( $model, 'update_term' ), 10, 2 );
		add_action( 'delete_term', array( $model, 'delete_term' ), 10, 4 );
	}

}
