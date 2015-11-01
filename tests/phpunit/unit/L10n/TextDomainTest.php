<?php # -*- coding: utf-8 -*-

use tfrommen\LinkedTaxonomies\L10n\TextDomain as Testee;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the TextDomain class.
 */
class TextDomainTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\L10n\TextDomain::load
	 *
	 * @return void
	 */
	public function test_load() {

		$file = '/path/to/file.php';

		$text_domain = 'text-domain';

		$domain_path = '/domain';

		$plugin_data = compact(
			'text_domain',
			'domain_path'
		);

		WP_Mock::wpPassthruFunction(
			'plugin_basename',
			array(
				'times' => 1,
				'args'  => array(
					Mockery::type( 'string' ),
				),
			)
		);

		$testee = new Testee( $plugin_data, $file );

		$domain_path = dirname( $file ) . $domain_path;

		WP_Mock::wpFunction(
			'load_plugin_textdomain',
			array(
				'times' => 1,
				'args'  => array(
					$text_domain,
					FALSE,
					$domain_path,
				),
			)
		);

		$testee->load();

		$this->assertConditionsMet();
	}

}
