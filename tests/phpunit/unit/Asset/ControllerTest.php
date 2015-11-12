<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Asset;

use Mockery;
use tfrommen\LinkedTaxonomies\Asset\Controller as Testee;
use tfrommen\LinkedTaxonomies\Asset\Script;
use tfrommen\LinkedTaxonomies\Asset\Style;
use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the asset controller.
 */
class ControllerTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Asset\Controller::initialize
	 *
	 * @return void
	 */
	public function test_initialize() {

		/** @var Script $script */
		$script = Mockery::mock( 'tfrommen\LinkedTaxonomies\Asset\Script' );

		/** @var Style $style */
		$style = Mockery::mock( 'tfrommen\LinkedTaxonomies\Asset\Style' );

		$settings_page_slug = 'settings_page_slug';

		/** @var SettingsPage $settings_page */
		$settings_page = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage' )
			->shouldReceive( 'get_slug' )
			->andReturn( $settings_page_slug )
			->getMock();

		$testee = new Testee(
			$script,
			$style,
			$settings_page
		);

		$hook_suffix = "settings_page_$settings_page_slug";

		WP_Mock::expectActionAdded( "admin_head-$hook_suffix", array( $script, 'enqueue' ) );

		WP_Mock::expectActionAdded( "admin_head-$hook_suffix", array( $style, 'enqueue' ) );

		$testee->initialize();

		$this->assertConditionsMet();
	}

}
