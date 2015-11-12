<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\Setting;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\Setting\Sanitizer as Testee;
use tfrommen\LinkedTaxonomies\SettingsError\Factory as SettingsErrorFactory;
use tfrommen\LinkedTaxonomies\SettingsError\SettingsErrorInterface;
use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the sanitizer model.
 */
class SanitizerTest extends TestCase {

	/**
	 * @covers       tfrommen\LinkedTaxonomies\Setting\Sanitizer::sanitize
	 * @dataProvider provide_sanitize_data
	 *
	 * @param string[][] $expected
	 * @param string[][] $data
	 * @param bool       $current_user_can_edit
	 * @param string     $settings_error_type
	 * @param string[][] $option_value
	 *
	 * @return void
	 */
	public function test_sanitize(
		array $expected,
		array $data,
		$current_user_can_edit,
		$settings_error_type,
		array $option_value
	) {

		/** @var SettingsPage $settings_page */
		$settings_page = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage' )
			->shouldReceive( 'current_user_can' )
			->with( 'edit' )
			->andReturn( $current_user_can_edit )
			->getMock();

		/** @var SettingsErrorInterface $settings_error */
		$settings_error = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsError\SettingsErrorInterface' )
			->shouldReceive( 'add' )
			->getMock();

		/** @var SettingsErrorFactory $settings_error_factory */
		$settings_error_factory = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsError\Factory' )
			->shouldReceive( 'create' )
			->with( $settings_error_type )
			->andReturn( $settings_error )
			->getMock();

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' )
			->shouldReceive( 'get' )
			->andReturn( $option_value )
			->getMock();

		$testee = new Testee( $settings_page, $settings_error_factory, $option );

		$this->assertSame( $expected, $testee->sanitize( $data ) );

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for test_sanitize().
	 *
	 * @return array[]
	 */
	public function provide_sanitize_data() {

		$option_value = array( 'option_value' );

		$no_permission_to_edit_settings_error_type = 'no-permission-to-edit';

		$invalid_taxonomy_settings_error_type = 'invalid-taxonomy';

		$invalid_source_data = array( 'invalid_source' );

		$invalid_target_data = array(
			'taxonomy' => array( 'invalid_target' ),
		);

		$invalid_input_data = array(
			'source' => array(
				'target' => 0,
			),
		);

		$unidirectional_input_data = array(
			'source' => array(
				'target' => 1,
			),
		);

		$unidirectional_data = array(
			'source' => array(
				'target' => 'target',
			),
		);

		$bidirectional_input_data = array(
			'source' => array(
				'target' => 2,
			),
		);

		$bidirectional_data = array(
			'source' => array(
				'target' => 'target',
			),
			'target' => array(
				'source' => 'source',
			),
		);

		return array(
			'current_user_cannot_edit' => array(
				'expected'              => $option_value,
				'data'                  => array(),
				'current_user_can_edit' => FALSE,
				'settings_error_type'   => $no_permission_to_edit_settings_error_type,
				'option_value'          => $option_value,
			),
			'empty_data'               => array(
				'expected'              => array(),
				'data'                  => array(),
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
			'invalid_source'           => array(
				'expected'              => array(),
				'data'                  => $invalid_source_data,
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
			'invalid_target'           => array(
				'expected'              => array(),
				'data'                  => $invalid_target_data,
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
			'invalid_link'             => array(
				'expected'              => array(),
				'data'                  => $invalid_input_data,
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
			'unidirectional_link'      => array(
				'expected'              => $unidirectional_data,
				'data'                  => $unidirectional_input_data,
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
			'bidirectional_link'       => array(
				'expected'              => $bidirectional_data,
				'data'                  => $bidirectional_input_data,
				'current_user_can_edit' => TRUE,
				'settings_error_type'   => $invalid_taxonomy_settings_error_type,
				'option_value'          => $option_value,
			),
		);
	}

}
