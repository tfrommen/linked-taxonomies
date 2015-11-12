<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsPage;

use Mockery;
use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the settings page model.
 */
class SettingsPageTest extends TestCase {

	/**
	 * @covers       tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage::current_user_can
	 * @dataProvider provide_current_user_can_data
	 *
	 * @param bool   $expected
	 * @param string $action
	 * @param bool   $current_user_can
	 *
	 * @return void
	 */
	public function test_current_user_can( $expected, $action, $current_user_can ) {

		$testee = new Testee();

		WP_Mock::wpFunction(
			'current_user_can',
			array(
				'args'   => array(
					Mockery::type( 'string' ),
				),
				'return' => $current_user_can,
			)
		);

		$this->assertSame( $expected, $testee->current_user_can( $action ) );

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for test_get_capability().
	 *
	 * @return array[]
	 */
	public function provide_current_user_can_data() {

		$current_user_can = (bool) round( mt_rand( 0, 1 ) );

		return array(
			'list'           => array(
				'expected'         => $current_user_can,
				'action'           => 'list',
				'current_user_can' => $current_user_can,
			),
			'edit'           => array(
				'expected'         => $current_user_can,
				'action'           => 'edit',
				'current_user_can' => $current_user_can,
			),
			'invalid_action' => array(
				'expected'         => FALSE,
				'action'           => '',
				'current_user_can' => $current_user_can,
			),
		);
	}

	/**
	 * @covers       tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage::get_capability
	 * @dataProvider provide_get_capability_data
	 *
	 * @param string $expected
	 * @param string $action
	 *
	 * @return void
	 */
	public function test_get_capability( $expected, $action ) {

		$testee = new Testee();

		$this->assertSame( $expected, $testee->get_capability( $action ) );
	}

	/**
	 * Data provider for test_get_capability().
	 *
	 * @return array[]
	 */
	public function provide_get_capability_data() {

		return array(
			'list'           => array(
				'expected' => 'manage_categories',
				'action'   => 'list',
			),
			'edit'           => array(
				'expected' => 'manage_options',
				'action'   => 'edit',
			),
			'invalid_action' => array(
				'expected' => 'do_not_allow',
				'action'   => '',
			),
		);
	}

	/**
	 * @covers tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage::get_slug
	 *
	 * @return void
	 */
	public function test_get_slug() {

		$testee = new Testee();

		$this->assertSame( 'linked_taxonomies', $testee->get_slug() );
	}

}
