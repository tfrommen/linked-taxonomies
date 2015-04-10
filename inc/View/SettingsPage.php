<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\View;

use tf\LinkedTaxonomies\Controller;
use tf\LinkedTaxonomies\Model;

/**
 * Class SettingsPage
 *
 * @package tf\LinkedTaxonomies\View
 */
class SettingsPage {

	/**
	 * @var object[]
	 */
	private $all_taxonomies;

	/**
	 * @var bool
	 */
	private $current_user_can_edit;

	/**
	 * @var string[]
	 */
	private $linked_taxonomies;

	/**
	 * @var Model\SettingsPage
	 */
	private $model;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model\SettingsPage $model Settings page model.
	 */
	public function __construct( Model\SettingsPage $model ) {

		$this->model = $model;
		$this->current_user_can_edit = $this->model->current_user_can( 'edit' );
	}

	/**
	 * Render the HTML.
	 *
	 * @see tf\LinkedTaxonomies\Model\SettingsPage::add()
	 *
	 * @return void
	 */
	public function render() {

		/**
		 * Customize the args for getting all taxonomies.
		 *
		 * @see get_taxonomies()
		 *
		 * @param array $args Taxonomies args.
		 */
		$args = apply_filters( 'linked_taxonomies_get_taxonomies_args', array() );
		$this->all_taxonomies = get_taxonomies( $args, 'objects' );
		/**
		 * Customize the taxonomies that are available for linking.
		 *
		 * @param array $taxonomies Taxonomies available for linking.
		 */
		$this->all_taxonomies = (array) apply_filters( 'linkable_taxonomies', $this->all_taxonomies );

		$option_name = Model\Option::get_name();
		$this->linked_taxonomies = get_option( $option_name, array() );

		$title = $this->model->get_title();
		?>
		<div class="wrap">
			<h2>
				<?php esc_html_e( $title ); ?>
			</h2>
			<?php
			if ( $this->current_user_can_edit ) {
				$this->render_form();
			} else {
				$this->render_table();
			}
			?>
		</div>
	<?php
	}

	/**
	 * Render the settings form.
	 *
	 * @see render()
	 *
	 * @return void
	 */
	private function render_form() {

		$option_name = Model\Option::get_name();
		?>
		<form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
			<?php settings_fields( $option_name ); ?>
			<?php $this->render_table(); ?>
			<?php submit_button(); ?>
		</form>
	<?php
	}

	/**
	 * Render the settings table.
	 *
	 * @see render()
	 * @see render_form()
	 *
	 * @return void
	 */
	private function render_table() {

		?>
		<table class="form-table">
			<tbody>
			<?php foreach ( $this->all_taxonomies as $taxonomy ) : ?>
				<?php $this->render_row( $taxonomy ); ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php
	}

	/**
	 * Render the form row for the given taxonomy.
	 *
	 * @param \stdClass $taxonomy Taxonomy object.
	 *
	 * @return void
	 */
	private function render_row( \stdClass $taxonomy ) {

		if ( ! isset( $taxonomy->label, $taxonomy->name ) ) {
			return;
		}
		?>
		<tr>
			<th scope="row">
				<?php echo $taxonomy->label; ?>
				<p class="description">
					<?php echo $taxonomy->name; ?>
				</p>
			</th>
			<td>
				<table>
					<thead>
					<tr>
						<th>
							<?php _e( 'Taxonomy', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php _e( 'No Link', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php _e( 'Unidirectional Link', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php _e( 'Bidirectional Link', 'linked-taxonomies' ); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( $this->all_taxonomies as $target_taxonomy ) : ?>
						<?php $this->render_taxonomy_settings( $taxonomy->name, $target_taxonomy ); ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
	<?php
	}

	/**
	 * Render the settings for the given taxonomy.
	 *
	 * @param string    $taxonomy_name   Current taxonomy name.
	 * @param \stdClass $target_taxonomy Target taxonomy object.
	 *
	 * @return void
	 */
	private function render_taxonomy_settings( $taxonomy_name, \stdClass $target_taxonomy ) {

		if (
			! isset( $target_taxonomy->name )
			|| $taxonomy_name === $target_taxonomy->name
		) {
			return;
		}

		$link = 0;
		if ( isset( $this->linked_taxonomies[ $taxonomy_name ][ $target_taxonomy->name ] ) ) {
			$link++;
			if ( isset( $this->linked_taxonomies[ $target_taxonomy->name ][ $taxonomy_name ] ) ) {
				$link++;
			};
		};
		$name = Model\Option::get_name() . '[' . $taxonomy_name . '][' . $target_taxonomy->name . ']';
		$disabled = disabled( $this->current_user_can_edit, FALSE, FALSE );
		?>
		<tr>
			<td>
				<?php echo $target_taxonomy->label; ?>
				<p class="description">
					<?php echo $target_taxonomy->name; ?>
				</p>
			</td>
			<?php
			for ( $i = 0; $i < 3; $i++ ) {
				$checked = checked( $link, $i, FALSE );
				printf(
					'<td><input type="radio" name="%s" value="%d" %s %s></td>',
					$name,
					$i,
					$checked,
					$disabled
				);
			}
			?>
		</tr>
	<?php
	}

}
