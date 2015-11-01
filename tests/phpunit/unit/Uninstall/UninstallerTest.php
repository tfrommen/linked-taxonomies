<?php # -*- coding: utf-8 -*-

use tfrommen\LinkedTaxonomies\Uninstall\Uninstaller as Testee;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the Uninstaller class.
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

		/** @var tfrommen\LinkedTaxonomies\Update\Updater $updater */
		$updater = Mockery::mock( 'tfrommen\LinkedTaxonomies\Update\Updater' )
			->shouldReceive( 'get_option_name' )
			->once()
			->andReturn( $version_option_name )
			->getMock();

		/** @var wpdb $wpdb */
		$wpdb = Mockery::mock( 'wpdb' );

		WP_Mock::wpFunction(
			'is_multisite',
			array(
				'times'  => 1,
				'return' => $is_multisite,
			)
		);

		$times_delete = 1;

		$return_get_col = array();

		if ( $is_multisite ) {
			$return_get_col[] = $blog_ids;

			$wpdb->blogs = 'blogs';

			$times_delete = count( $blog_ids );

			WP_Mock::wpFunction(
				'switch_to_blog',
				array(
					'times' => $times_delete,
				)
			);

			WP_Mock::wpFunction(
				'restore_current_blog',
				array(
					'times' => 1,
				)
			);
		}

		$wpdb->shouldReceive( 'get_col' )
			->times( count( $return_get_col ) )
			->andReturnValues( $return_get_col );

		WP_Mock::wpFunction(
			'delete_option',
			array(
				'times' => $times_delete,
				'args'  => array(
					$version_option_name,
				),
			)
		);

		WP_Mock::wpFunction(
			'delete_option',
			array(
				'times' => $times_delete,
				'args'  => array(
					Mockery::type( 'string' ),
				),
			)
		);

		$testee = new Testee(
			$updater,
			$wpdb
		);

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
