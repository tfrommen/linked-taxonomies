<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Setting;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the option model.
 */
class OptionTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Setting\Option::get_name
	 *
	 * @return void
	 */
	public function test_get_name() {

		$testee = new Testee();

		$this->assertSame( 'linked_taxonomies', $testee->get_name() );
	}

	/**
	 * @covers       tfrommen\LinkedTaxonomies\Setting\Option::get
	 * @dataProvider provide_get_data
	 *
	 * @param array $expected
	 * @param array $default
	 * @param mixed $option
	 *
	 * @return void
	 */
	public function test_get( array $expected, array $default, $option ) {

		$testee = new Testee();

		WP_Mock::wpFunction(
			'get_option',
			array(
				'args'   => array(
					Mockery::type( 'string' ),
					$default,
				),
				'return' => $option,
			)
		);

		$this->assertSame( $expected, $testee->get() );
	}

	/**
	 * Data provider for test_get().
	 *
	 * @return array[]
	 */
	public function provide_get_data() {

		$value = array( 'value' );

		return array(
			'no_option' => array(
				'expected' => array(),
				'default'  => array(),
				'option'   => NULL,
			),
			'option'    => array(
				'expected' => $value,
				'default'  => array(),
				'option'   => $value,
			),
		);
	}

}
