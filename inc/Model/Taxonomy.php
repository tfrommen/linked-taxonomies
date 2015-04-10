<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model;

/**
 * Class Taxonomy
 *
 * @package tf\LinkedTaxonomies\Model
 */
class Taxonomy {

	/**
	 * @var array
	 */
	private $linked_taxonomies;

	/**
	 * @var bool
	 */
	private $processing = FALSE;

	/**
	 * @var object[]
	 */
	private $terms = array();

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\LinkedTaxonomies\Controller\Taxonomy::initialize()
	 */
	public function __construct() {

		$this->linked_taxonomies = Option::get();
	}

	/**
	 * Insert a term into all linked taxonomies.
	 *
	 * @wp-hook created_term
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Unused. Term taxonomy ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return void
	 */
	public function insert_term( $term_id, $tt_id, $taxonomy ) {

		if ( ! isset( $this->linked_taxonomies[ $taxonomy ] ) ) {
			return;
		}

		$term = get_term( $term_id, $taxonomy );
		if ( ! $term ) {
			return;
		}

		foreach ( $this->linked_taxonomies[ $taxonomy ] as $linked_taxonomy ) {
			if ( ! get_term_by( 'name', $term->name, $linked_taxonomy ) ) {
				$args = array(
					'slug'        => $term->slug,
					'description' => $term->description,
				);

				if ( $term->parent ) {
					$parent = get_term( $term->parent, $taxonomy );
					$linked_parent = get_term_by( 'name', $parent->name, $linked_taxonomy );
					if ( $linked_parent ) {
						$args[ 'parent' ] = $linked_parent->term_id;
					}
				}

				wp_insert_term( $term->name, $linked_taxonomy, $args );
			}
		}
	}

	/**
	 * Save to-be-updated term for later access.
	 *
	 * @wp-hook edit_terms
	 *
	 * @param int    $term_id  Term ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return void
	 */
	public function save_term( $term_id, $taxonomy ) {

		unset( $this->terms[ $term_id ] );

		if (
			$this->processing
			|| ! isset( $this->linked_taxonomies[ $taxonomy ] )
		) {
			return;
		}

		$this->terms[ $term_id ] = get_term( $term_id, $taxonomy );
	}

	/**
	 * Update a term in all linked taxonomies.
	 *
	 * @wp-hook edited_term_taxonomy
	 *
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return void
	 */
	public function update_term( $tt_id, $taxonomy ) {

		// Prior to WordPress 4.2, $taxonomy in some cases held the taxonomy object instead of the taxonomy name
		if ( isset( $taxonomy->name ) ) {
			$taxonomy = $taxonomy->name;
		}

		if (
			$this->processing
			|| ! isset( $this->linked_taxonomies[ $taxonomy ] )
		) {
			return;
		}

		$updated_term = get_term_by( 'term_taxonomy_id', $tt_id, $taxonomy );
		if (
			! $updated_term
			|| empty( $this->terms[ $updated_term->term_id ] )
		) {
			return;
		}

		$saved_term = $this->terms[ $updated_term->term_id ];

		$this->processing = TRUE;

		foreach ( $this->linked_taxonomies[ $taxonomy ] as $linked_taxonomy ) {
			$linked_term = get_term_by( 'name', $saved_term->name, $linked_taxonomy );
			if ( $linked_term ) {
				unset(
					$updated_term->term_id,
					$updated_term->term_taxonomy_id,
					$updated_term->taxonomy
				);

				if (
					$saved_term->parent !== $updated_term->parent
					&& (int) $updated_term->parent > 0
				) {
					$parent_term = get_term( $updated_term->parent, $taxonomy );
					if ( $parent_term ) {
						$linked_parent = get_term_by( 'name', $parent_term->name, $linked_taxonomy );
						if ( $linked_parent ) {
							$updated_term->parent = $linked_parent->term_id;
						}
					}
				}

				wp_update_term( $linked_term->term_id, $linked_taxonomy, (array) $updated_term );
			}
		}

		$this->processing = FALSE;
	}

	/**
	 * Delete a term from all linked taxonomies.
	 *
	 * @wp-hook delete_term
	 *
	 * @param string $term         Unused. Term name.
	 * @param int    $tt_id        Unused. Term taxonomy ID.
	 * @param string $taxonomy     Taxonomy name.
	 * @param object $deleted_term Term object.
	 *
	 * @return void
	 */
	public function delete_term( $term, $tt_id, $taxonomy, $deleted_term ) {

		if ( ! isset( $this->linked_taxonomies[ $taxonomy ] ) ) {
			return;
		}

		foreach ( $this->linked_taxonomies[ $taxonomy ] as $linked_taxonomy ) {
			$linked_term = get_term_by( 'name', $deleted_term->name, $linked_taxonomy );
			if ( $linked_term ) {
				wp_delete_term( $linked_term->term_id, $linked_taxonomy );
			}
		}
	}

}
