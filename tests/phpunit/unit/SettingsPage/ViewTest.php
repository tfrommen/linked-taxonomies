<?php # -*- coding: utf-8 -*-

namespace tfrommen\Tests\LinkedTaxonomies\SettingsPage;

use Mockery;
use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage;
use tfrommen\LinkedTaxonomies\SettingsPage\View as Testee;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test case for the settings page view.
 */
class ViewTest extends TestCase {

	/**
	 * @covers tfrommen\LinkedTaxonomies\SettingsPage\View::add
	 *
	 * @return void
	 */
	public function test_add() {

		$capability = 'capability';

		$settings_page_slug = 'settings_page_slug';

		/** @var SettingsPage $settings_page */
		$settings_page = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage' )
			->shouldReceive( 'current_user_can' )
			->with( 'edit' )
			->andReturn( TRUE )
			->shouldReceive( 'get_capability' )
			->with( 'list' )
			->andReturn( $capability )
			->shouldReceive( 'get_slug' )
			->andReturn( $settings_page_slug )
			->getMock();

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' );

		WP_Mock::wpPassthruFunction(
			'esc_html_x',
			array(
				'args' => array(
					Mockery::type( 'string' ),
					Mockery::type( 'string' ),
					'linked-taxonomies',
				),
			)
		);

		$testee = new Testee( $settings_page, $option );

		WP_Mock::wpFunction(
			'add_options_page',
			array(
				'args' => array(
					Mockery::type( 'string' ),
					Mockery::type( 'string' ),
					$capability,
					$settings_page_slug,
					array( $testee, 'render' ),
				),
			)
		);

		$testee->add();

		$this->assertConditionsMet();
	}

	/**
	 * @covers       tfrommen\LinkedTaxonomies\SettingsPage\View::render
	 * @dataProvider provide_render_data
	 *
	 * @param string      $output
	 * @param bool        $current_user_can_edit
	 * @param object[]    $linked_taxonomies
	 * @param string [][] $all_taxonomies
	 *
	 * @return void
	 */
	public function test_render( $output, $current_user_can_edit, array $linked_taxonomies, array $all_taxonomies ) {

		/** @var SettingsPage $settings_page */
		$settings_page = Mockery::mock( 'tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage' )
			->shouldReceive( 'current_user_can' )
			->with( 'edit' )
			->andReturn( $current_user_can_edit )
			->getMock();

		$option_name = 'option_name';

		/** @var Option $option */
		$option = Mockery::mock( 'tfrommen\LinkedTaxonomies\Setting\Option' )
			->shouldReceive( 'get' )
			->andReturn( $linked_taxonomies )
			->shouldReceive( 'get_name' )
			->andReturn( $option_name )
			->getMock();

		WP_Mock::wpPassthruFunction(
			'esc_html_x',
			array(
				'args' => array(
					Mockery::type( 'string' ),
					Mockery::type( 'string' ),
					'linked-taxonomies',
				),
			)
		);

		$testee = new Testee( $settings_page, $option );

		WP_Mock::wpFunction(
			'get_taxonomies',
			array(
				'args'   => array(
					Mockery::type( 'array' ),
					'objects',
				),
				'return' => $all_taxonomies,
			)
		);

		WP_Mock::wpFunction(
			'esc_html_e',
			array(
				'args'   => array(
					Mockery::type( 'string' ),
					'linked-taxonomies',
				),
				'return' => function ( $param ) {

					echo $param;
				},
			)
		);

		WP_Mock::wpPassthruFunction( 'admin_url' );

		WP_Mock::wpPassthruFunction(
			'settings_fields',
			array(
				'args' => array(
					$option_name,
				),
			)
		);

		WP_Mock::wpFunction(
			'disabled',
			array(
				'args'   => array(
					$current_user_can_edit,
					FALSE,
					FALSE,
				),
				'return' => '',
			)
		);

		WP_Mock::wpFunction(
			'checked',
			array(
				'args'   => array(
					Mockery::type( 'int' ),
					Mockery::type( 'int' ),
					FALSE,
				),
				'return' => '',
			)
		);

		WP_Mock::wpFunction( 'submit_button' );

		$this->expectOutputString( $output );

		$testee->render();

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for test_render().
	 *
	 * @return array[]
	 */
	public function provide_render_data() {

		$taxonomy1 = (object) array(
			'label' => 'Taxonomy 1',
			'name'  => 'taxonomy1',
		);

		$taxonomy2 = (object) array(
			'label' => 'Taxonomy 2',
			'name'  => 'taxonomy2',
		);

		$taxonomy3 = (object) array(
			'label' => 'Taxonomy 3',
			'name'  => 'taxonomy3',
		);

		$all_taxonomies = compact(
			'taxonomy1',
			'taxonomy2',
			'taxonomy3'
		);

		$linked_taxonomies = array(
			$taxonomy1->name => array(
				$taxonomy2->name,
				$taxonomy3->name,
			),
			$taxonomy2->name => array(
				$taxonomy1->name,
			),
		);

		$option_name = 'option_name';

		$output_start = '<div class="wrap"><h2>Linked Taxonomies</h2>';

		$output_end = '</div>';

		$output_table_start = '<table class="form-table"><tbody>';

		$output_table_end = '</tbody></table>';

		$output_table_invalid_taxonomies = $output_table_start . $output_table_end;

		$output_no_taxonomies = $output_start . '<p>No linkable taxonomies found.</p>' . $output_end;

		$output_invalid_taxonomies = $output_start . $output_table_invalid_taxonomies . $output_end;

		$table_head = <<<HTML
<thead>
<tr>
	<th>Taxonomy</th>
	<th>No Link</th>
	<th>Unidirectional Link</th>
	<th>Bidirectional Link</th>
</tr>
</thead>
HTML;

		$output_table = <<<HTML
$output_table_start
<tr>
	<th scope="row">
		{$taxonomy1->label}
		<p class="description">
			{$taxonomy1->name}
		</p>
	</th>
	<td>
		<table>
			$table_head
			<tbody>
			<tr>
				<td>
					{$taxonomy2->label}
					<p class="description">
						{$taxonomy2->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy2->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy2->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy2->name}]" value="2"></td>
			</tr>
			<tr>
				<td>
					{$taxonomy3->label}
					<p class="description">
						{$taxonomy3->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy3->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy3->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy1->name}][{$taxonomy3->name}]" value="2"></td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
<tr>
	<th scope="row">
		{$taxonomy2->label}
		<p class="description">
			{$taxonomy2->name}
		</p>
	</th>
	<td>
		<table>
			$table_head
			<tbody>
			<tr>
				<td>
					{$taxonomy1->label}
					<p class="description">
						{$taxonomy1->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy1->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy1->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy1->name}]" value="2"></td>
			</tr>
			<tr>
				<td>
					{$taxonomy3->label}
					<p class="description">
						{$taxonomy3->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy3->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy3->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy2->name}][{$taxonomy3->name}]" value="2"></td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
<tr>
	<th scope="row">
		{$taxonomy3->label}
		<p class="description">
			{$taxonomy3->name}
		</p>
	</th>
	<td>
		<table>
			$table_head
			<tbody>
			<tr>
				<td>
					{$taxonomy1->label}
					<p class="description">
						{$taxonomy1->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy1->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy1->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy1->name}]" value="2"></td>
			</tr>
			<tr>
				<td>
					{$taxonomy2->label}
					<p class="description">
						{$taxonomy2->name}
					</p>
				</td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy2->name}]" value="0"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy2->name}]" value="1"></td>
				<td><input type="radio" name="{$option_name}[{$taxonomy3->name}][{$taxonomy2->name}]" value="2"></td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
$output_table_end
HTML;

		$output_user_cannot_edit = $output_start . $output_table . $output_end;

		$output_form = '<form action="options.php" method="post">' . $output_table . '</form>';

		$output_user_can_edit = $output_start . $output_form . $output_end;

		return array(
			'no_taxonomies'      => array(
				'output'                => $output_no_taxonomies,
				'current_user_can_edit' => FALSE,
				'linked_taxonomies'     => $linked_taxonomies,
				'all_taxonomies'        => array(),
				'option_name'           => $option_name,
			),
			'invalid_taxonomies' => array(
				'output'                => $output_invalid_taxonomies,
				'current_user_can_edit' => FALSE,
				'linked_taxonomies'     => $linked_taxonomies,
				'all_taxonomies'        => array( NULL ),
				'option_name'           => $option_name,
			),
			'user_cannot_edit'   => array(
				'output'                => $output_user_cannot_edit,
				'current_user_can_edit' => FALSE,
				'linked_taxonomies'     => $linked_taxonomies,
				'all_taxonomies'        => $all_taxonomies,
				'option_name'           => $option_name,
			),
			'user_can_edit'      => array(
				'output'                => $output_user_can_edit,
				'current_user_can_edit' => TRUE,
				'linked_taxonomies'     => $linked_taxonomies,
				'all_taxonomies'        => $all_taxonomies,
				'option_name'           => $option_name,
			),
		);
	}

}
