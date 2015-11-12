<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Taxonomy;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\Taxonomy\Taxonomy as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the taxonomy model.
 */
class TaxonomyTest extends TestCase {

	// TODO: Add missing tests.

	/**
	 * @covers       tfrommen\LinkedTaxonomies\Taxonomy\Taxonomy::delete_term
	 * @dataProvider provide_delete_term_data
	 *
	 * @param bool       $expected
	 * @param string     $taxonomy
	 * @param object     $deleted_term
	 * @param string[][] $linked_taxonomies
	 * @param object[]   $linked_terms
	 *
	 * @return void
	 */
	public function test_delete_term(
		$expected,
		$taxonomy,
		$deleted_term,
		array $linked_taxonomies,
		array $linked_terms
	) {

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' )
			->shouldReceive( 'get' )
			->andReturn( $linked_taxonomies )
			->getMock();

		$testee = new Testee( $option );

		WP_Mock::wpFunction(
			'get_term_by',
			array(
				'args'         => array(
					'slug',
					Mockery::type( 'string' ),
					Mockery::type( 'string' ),
				),
				'returnValues' => $linked_terms,
			)
		);

		WP_Mock::wpFunction(
			'wp_delete_term',
			array(
				'args' => array(
					Mockery::type( 'int' ),
					Mockery::type( 'string' ),
				),
			)
		);

		$this->assertSame( $expected, $testee->delete_term( 'unused', 0, $taxonomy, $deleted_term ) );

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for test_delete_term().
	 *
	 * @return array[]
	 */
	public function provide_delete_term_data() {

		$taxonomy = 'taxonomy';

		$deleted_term = (object) array(
			'slug' => 'slug',
		);

		// TODO: Add more test data sets.

		return array(
			'unlinked_taxonomy' => array(
				'expected'          => FALSE,
				'taxonomy'          => $taxonomy,
				'deleted_term'      => $deleted_term,
				'linked_taxonomies' => array(),
				'linked_terms'      => array(),
			),

		);
	}

}
