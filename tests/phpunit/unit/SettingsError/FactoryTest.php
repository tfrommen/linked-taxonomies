<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsError;

use Mockery;
use tfrommen\LinkedTaxonomies\SettingsError\Factory as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the settings error factory.
 */
class TaxonomyControllerTest extends TestCase {

	/**
	 * @covers       tfrommen\LinkedTaxonomies\SettingsError\Factory::create
	 * @dataProvider provide_create_data
	 *
	 * @param string $expected
	 * @param string $type
	 *
	 * @return void
	 */
	public function test_create( $expected, $type ) {

		$testee = new Testee();

		WP_Mock::wpPassthruFunction(
			'_x',
			array(
				'args' => array(
					Mockery::type( 'string' ),
					Mockery::type( 'string' ),
					'linked-taxonomies',
				),
			)
		);

		WP_Mock::wpPassthruFunction( 'esc_html' );

		$this->assertSame( $expected, get_class( $testee->create( $type ) ) );

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for test_create().
	 *
	 * @return array[]
	 */
	public function provide_create_data() {

		$namespace = 'tfrommen\LinkedTaxonomies\SettingsError';

		return array(
			'invalid_type'          => array(
				'expected' => $namespace . '\NullSettingsError',
				'type'     => '',
			),
			'invalid-taxonomy'      => array(
				'expected' => $namespace . '\SettingsError',
				'type'     => 'invalid-taxonomy',
			),
			'no-permission-to-edit' => array(
				'expected' => $namespace . '\SettingsError',
				'type'     => 'no-permission-to-edit',
			),
		);
	}

}
