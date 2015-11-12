<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsPage;

use tfrommen\LinkedTaxonomies\Setting\Option;

/**
 * Settings page view.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsPage
 */
class View {

	/**
	 * @var object[]
	 */
	private $all_taxonomies;

	/**
	 * @var bool
	 */
	private $current_user_can_edit;

	/**
	 * @var string[][]
	 */
	private $linked_taxonomies;

	/**
	 * @var Option
	 */
	private $option;

	/**
	 * @var SettingsPage
	 */
	private $settings_page;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param SettingsPage $settings_page Settings page model.
	 * @param Option       $option        Option model.
	 */
	public function __construct( SettingsPage $settings_page, Option $option ) {

		$this->settings_page = $settings_page;

		$this->current_user_can_edit = $settings_page->current_user_can( 'edit' );

		$this->option = $option;

		$this->title = esc_html_x( 'Linked Taxonomies', 'Settings page title', 'linked-taxonomies' );
	}

	/**
	 * Adds the settings page to the Settings menu.
	 *
	 * @wp-hook admin_menu
	 *
	 * @return void
	 */
	public function add() {

		$menu_title = esc_html_x( 'Taxonomies', 'Menu item title', 'linked-taxonomies' );

		$capability = $this->settings_page->get_capability( 'list' );

		$menu_slug = $this->settings_page->get_slug();

		add_options_page(
			$this->title,
			$menu_title,
			$capability,
			$menu_slug,
			array( $this, 'render' )
		);
	}

	/**
	 * Renders the HTML.
	 *
	 * @return void
	 */
	public function render() {

		/**
		 * Filters the args for getting all taxonomies.
		 *
		 * @see get_taxonomies()
		 *
		 * @param array $args Taxonomies args.
		 */
		$args = apply_filters( 'linked_taxonomies_get_taxonomies_args', array() );
		$this->all_taxonomies = get_taxonomies( $args, 'objects' );
		/**
		 * Filters the taxonomies that are available for linking.
		 *
		 * @param object[] $taxonomies Taxonomy objects.
		 */
		$this->all_taxonomies = (array) apply_filters( 'linkable_taxonomies', $this->all_taxonomies );

		$this->linked_taxonomies = $this->option->get();
		?>
		<div class="wrap">
			<h2>
				<?php echo $this->title; ?>
			</h2>
			<?php if ( ! $this->all_taxonomies ) : ?>
				<p>
					<?php esc_html_e( 'No linkable taxonomies found.', 'linked-taxonomies' ); ?>
				</p>
			<?php elseif ( $this->current_user_can_edit ) : ?>
				<?php $this->render_form(); ?>
			<?php else : ?>
				<?php $this->render_table(); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Renders the settings form.
	 *
	 * @return void
	 */
	private function render_form() {

		$option_name = $this->option->get_name();
		?>
		<form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
			<?php settings_fields( $option_name ); ?>
			<?php $this->render_table(); ?>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Renders the settings table.
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
	 * Renders the form row for the given taxonomy.
	 *
	 * @param object $taxonomy Taxonomy object.
	 *
	 * @return void
	 */
	private function render_row( $taxonomy ) {

		if ( ! isset( $taxonomy->label ) || ! isset( $taxonomy->name ) ) {
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
							<?php esc_html_e( 'Taxonomy', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'No Link', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Unidirectional Link', 'linked-taxonomies' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Bidirectional Link', 'linked-taxonomies' ); ?>
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
	 * Renders the settings for the given taxonomy.
	 *
	 * @param string $taxonomy_name   Current taxonomy name.
	 * @param object $target_taxonomy Target taxonomy object.
	 *
	 * @return void
	 */
	private function render_taxonomy_settings( $taxonomy_name, $target_taxonomy ) {

		if ( ! isset( $target_taxonomy->name ) || $taxonomy_name === $target_taxonomy->name ) {
			return;
		}

		$link = 0;

		if ( isset( $this->linked_taxonomies[ $taxonomy_name ][ $target_taxonomy->name ] ) ) {
			$link++;

			if ( isset( $this->linked_taxonomies[ $target_taxonomy->name ][ $taxonomy_name ] ) ) {
				$link++;
			};
		};

		$option_name = $this->option->get_name();
		$name = "{$option_name}[$taxonomy_name][{$target_taxonomy->name}]";

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
					'<td><input type="radio" name="%s" value="%d"%s%s></td>',
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
