<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Setting;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Controller as Testee;
use tfrommen\LinkedTaxonomies\Setting\Setting;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the setting controller.
 */
class ControllerTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Setting\Controller::initialize
	 *
	 * @return void
	 */
	public function test_initialize() {

		/** @var Setting $setting */
		$setting = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Setting' );

		$testee = new Testee( $setting );

		WP_Mock::expectActionAdded( 'admin_init', array( $setting, 'register' ) );

		$testee->initialize();

		$this->assertConditionsMet();
	}

}
