<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsError;

use tfrommen\LinkedTaxonomies\SettingsError\SettingsError as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the settings error model.
 */
class SettingsErrorTest extends TestCase {

	/**
	 * @covers       tfrommen\LinkedTaxonomies\SettingsError\SettingsError::add
	 * @dataProvider provide_add_data
	 *
	 * @param bool   $expected
	 * @param string $slug
	 * @param string $code
	 * @param string $message
	 * @param string $type
	 *
	 * @return void
	 */
	public function test_add( $expected, $slug, $code, $message, $type = 'error' ) {

		WP_Mock::wpPassthruFunction( 'esc_html' );

		$testee = new Testee( $slug, $code, $message, $type );

		WP_Mock::wpPassthruFunction(
			'add_settings_error',
			array(
				'args' => array(
					$slug,
					$code,
					$message,
					$type,
				),
			)
		);

		$this->assertSame( $expected, $testee->add() );
	}

	/**
	 * Data provider for test_add().
	 *
	 * @return array[]
	 */
	public function provide_add_data() {

		return array(
			'empty_slug'    => array(
				'expected' => FALSE,
				'slug'     => '',
				'code'     => '0',
				'message'  => '0',
				'type'     => 'updated',
			),
			'empty_code'    => array(
				'expected' => FALSE,
				'slug'     => '0',
				'code'     => '',
				'message'  => '0',
				'type'     => 'updated',
			),
			'empty_message' => array(
				'expected' => FALSE,
				'slug'     => '0',
				'code'     => '0',
				'message'  => '',
				'type'     => 'updated',
			),
			'no_type'       => array(
				'expected' => TRUE,
				'slug'     => '0',
				'code'     => '0',
				'message'  => '0',
			),
			'default'       => array(
				'expected' => TRUE,
				'slug'     => '0',
				'code'     => '0',
				'message'  => '0',
				'type'     => 'updated',
			),
		);
	}

}
