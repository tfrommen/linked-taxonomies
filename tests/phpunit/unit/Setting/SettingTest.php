<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Setting;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\Setting\Sanitizer;
use tfrommen\LinkedTaxonomies\Setting\Setting as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the setting model.
 */
class SettingTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Setting\Setting::register
	 *
	 * @return void
	 */
	public function test_register() {

		$option_name = 'plugin_option';

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' )
			->shouldReceive( 'get_name' )
			->andReturn( $option_name )
		    ->getMock();

		/** @var Sanitizer $sanitizer */
		$sanitizer = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Sanitizer' );

		$testee = new Testee( $option, $sanitizer );

		WP_Mock::wpFunction(
			'register_setting',
			array(
				'args' => array(
					$option_name,
					$option_name,
					array( $sanitizer, 'sanitize' ),
				),
			)
		);

		$testee->register();

		$this->assertConditionsMet();
	}

}
