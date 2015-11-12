<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Taxonomy;

use Mockery;
use tfrommen\LinkedTaxonomies\Taxonomy\Controller as Testee;
use tfrommen\LinkedTaxonomies\Taxonomy\Taxonomy;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the taxonomy controller.
 */
class TaxonomyControllerTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Taxonomy\Controller::initialize
	 *
	 * @return void
	 */
	public function test_initialize() {

		/** @var Taxonomy $taxonomy */
		$taxonomy = Mockery::mock( 'tfrommen\LinkedTaxonomies\Taxonomy\Taxonomy' );

		$testee = new Testee( $taxonomy );

		WP_Mock::expectActionAdded( 'created_term', array( $taxonomy, 'insert_term' ), 10, 3 );

		WP_Mock::expectActionAdded( 'edit_terms', array( $taxonomy, 'save_term' ), 10, 2 );

		WP_Mock::expectActionAdded( 'edited_term_taxonomy', array( $taxonomy, 'update_term' ), 10, 2 );

		WP_Mock::expectActionAdded( 'delete_term', array( $taxonomy, 'delete_term' ), 10, 4 );

		$testee->initialize();

		$this->assertConditionsMet();
	}

}
