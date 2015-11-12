<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Uninstall;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\Uninstall\Uninstaller as Testee;
use tfrommen\LinkedTaxonomies\Update\Updater;
use WP_Mock;
use WP_Mock\Tools\TestCase;
use wpdb;

/**
 * Test case for the uninstaller.
 */
class UninstallrTest extends TestCase {

	/**
	 * @covers       tfrommen\LinkedTaxonomies\Uninstall\Uninstaller::uninstall
	 * @dataProvider provide_uninstall_data
	 *
	 * @param bool  $is_multisite
	 * @param int[] $blog_ids
	 *
	 * @return void
	 */
	public function test_uninstall( $is_multisite, $blog_ids ) {

		$version_option_name = 'plugin_version';

		/** @var Updater $updater */
		$updater = Mockery::mock( 'tfrommen\LinkedTaxonomies\Update\Updater' )
			->shouldReceive( 'get_option_name' )
			->andReturn( $version_option_name )
			->getMock();

		/** @var wpdb $wpdb */
		$wpdb = Mockery::mock( 'wpdb' );

		$option_name = 'plugin_option';

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' )
		                  ->shouldReceive( 'get_name' )
		                  ->andReturn( $option_name )
		                  ->getMock();

		WP_Mock::wpFunction(
			'is_multisite',
			array(
				'return' => $is_multisite,
			)
		);

		$return_get_col = array();

		if ( $is_multisite ) {
			$return_get_col[] = $blog_ids;

			$wpdb->blogs = 'blogs';

			WP_Mock::wpFunction( 'switch_to_blog' );

			WP_Mock::wpFunction( 'restore_current_blog' );
		}

		$wpdb->shouldReceive( 'get_col' )
			->andReturnValues( $return_get_col );

		WP_Mock::wpFunction(
			'delete_option',
			array(
				'args' => array(
					$version_option_name,
				),
			)
		);

		WP_Mock::wpFunction(
			'delete_option',
			array(
				'args' => array(
					$option_name,
				),
			)
		);

		$testee = new Testee( $updater, $wpdb, $option );

		$testee->uninstall();

		$this->assertConditionsMet();
	}

	/**
	 * Provider for the test_uninstall() method.
	 *
	 * @return array[]
	 */
	public function provide_uninstall_data() {

		$blog_ids = array(
			4,
			8,
			15,
			16,
			23,
			42,
		);

		return array(
			'single'                  => array(
				'is_multisite' => FALSE,
				'blog_ids'     => $blog_ids,
			),
			'multisite_without_blogs' => array(
				'is_multisite' => TRUE,
				'blog_ids'     => array(),
				'term_ids'     => array(),
			),
			'multisite'               => array(
				'is_multisite' => TRUE,
				'blog_ids'     => $blog_ids,
			),
		);
	}

}
