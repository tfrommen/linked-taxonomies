<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Asset;

use Mockery;
use tfrommen\LinkedTaxonomies\Asset\Style as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the style model.
 */
class StyleTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\Asset\Style::enqueue
	 *
	 * @return void
	 */
	public function test_enqueue() {

		$file = '/path/to/file.php';

		$testee = new Testee( $file );

		WP_Mock::wpFunction(
			'plugin_dir_url',
			array(
				'args'   => array(
					$file,
				),
				'return' => '',
			)
		);

		WP_Mock::wpPassthruFunction(
			'plugin_dir_path',
			array(
				'args' => array(
					$file,
				),
			)
		);

		WP_Mock::wpFunction(
			'wp_enqueue_style',
			array(
				'args' => array(
					'linked-taxonomies-admin',
					Mockery::type( 'string' ),
					array(),
					Mockery::any(),
				),
			)
		);

		// Error suppression due to filemtime()
		@$testee->enqueue();

		$this->assertConditionsMet();
	}

}
