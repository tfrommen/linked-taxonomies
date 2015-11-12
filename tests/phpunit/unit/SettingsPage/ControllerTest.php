<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsPage;

use Mockery;
use tfrommen\LinkedTaxonomies\SettingsPage\Controller as Testee;
use tfrommen\LinkedTaxonomies\SettingsPage\View;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the settings page controller.
 */
class ControllerTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\SettingsPage\Controller::initialize
	 *
	 * @return void
	 */
	public function test_initialize() {

		/** @var View $view */
		$view = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsPage\View' );

		$testee = new Testee( $view );

		WP_Mock::expectActionAdded( 'admin_menu', array( $view, 'add' ), PHP_INT_MAX );

		$testee->initialize();

		$this->assertConditionsMet();
	}

}
