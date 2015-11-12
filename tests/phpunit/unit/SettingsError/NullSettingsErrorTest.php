<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsError;

use tfrommen\LinkedTaxonomies\SettingsError\NullSettingsError as Testee;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the NULL representation of a settings error model.
 */
class NullSettingsErrorTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\SettingsError\NullSettingsError::add
	 *
	 * @return void
	 */
	public function test_add() {

		$testee = new Testee();

		$this->assertFalse( $testee->add() );
	}

}
