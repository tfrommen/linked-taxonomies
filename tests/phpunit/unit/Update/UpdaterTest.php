<?php # -*- coding: utf-8 -*-

use tfrommen\LinkedTaxonomies\Update\Updater as Testee;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the Updater class.
 */
class UpdaterTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Update\Updater::get_option_name
	 *
	 * @return void
	 */
	public function test_get_option_name() {

		$testee = new Testee();

		$this->assertSame( 'linked_taxonomies_version', $testee->get_option_name() );
	}

	/**
	 * @covers       tfrommen\LinkedTaxonomies\Update\Updater::update
	 * @dataProvider provide_update_data
	 *
	 * @param bool   $expected
	 * @param string $version
	 * @param string $old_version
	 *
	 * @return void
	 */
	public function test_update( $expected, $version, $old_version ) {

		$testee = new Testee( $version );

		WP_Mock::wpFunction(
			'get_option',
			array(
				'times'  => 1,
				'args'   => array(
					Mockery::type( 'string' ),
				),
				'return' => $old_version,
			)
		);

		if ( $old_version !== $version ) {
			WP_Mock::wpFunction(
				'update_option',
				array(
					'times' => 1,
					'args'  => array(
						Mockery::type( 'string' ),
						$version,
					),
				)
			);
		}

		$this->assertSame( $expected, $testee->update() );

		$this->assertConditionsMet();
	}

	/**
	 * Provider for the test_update() method.
	 *
	 * @return array[]
	 */
	public function provide_update_data() {

		$version = '9.9.9';

		return array(
			'no_version'      => array(
				'expected'    => TRUE,
				'version'     => $version,
				'old_version' => '',
			),
			'old_version'     => array(
				'expected'    => TRUE,
				'version'     => $version,
				'old_version' => '0',
			),
			'current_version' => array(
				'expected'    => FALSE,
				'version'     => $version,
				'old_version' => $version,
			),
		);
	}

}
