<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Taxonomy;

use tfrommen\LinkedTaxonomies\Setting\Option;

/**
 * Taxonomy model.
 *
 * @package tfrommen\LinkedTaxonomies\Taxonomy
 */
class Taxonomy {

	/**
	 * @var string[][]
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
	 * Constructor. Sets up the properties.
	 */
	public function __construct() {

		$this->linked_taxonomies = Option::get();
	}

	/**
	 * Inserts a term into all linked taxonomies.
	 *
	 * @wp-hook created_term
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Unused. Term taxonomy ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return void
	 */
	public function insert_term(
		/** @noinspection PhpUnusedParameterInspection */
		$term_id,
		$tt_id,
		$taxonomy
	) {

		if ( ! isset( $this->linked_taxonomies[ $taxonomy ] ) ) {
			return;
		}

		$term = get_term( $term_id, $taxonomy );
		if ( ! $term ) {
			return;
		}

		foreach ( $this->linked_taxonomies[ $taxonomy ] as $linked_taxonomy ) {
			if ( ! get_term_by( 'slug', $term->slug, $linked_taxonomy ) ) {
				$args = array(
					'slug'        => $term->slug,
					'description' => $term->description,
				);

				if ( $term->parent ) {
					$parent = get_term( $term->parent, $taxonomy );
					$linked_parent = get_term_by( 'slug', $parent->slug, $linked_taxonomy );
					if ( $linked_parent ) {
						$args[ 'parent' ] = $linked_parent->term_id;
					}
				}

				wp_insert_term( $term->name, $linked_taxonomy, $args );
			}
		}
	}

	/**
	 * Saves to-be-updated term for later access.
	 *
	 * @wp-hook edit_terms
	 *
	 * @param int    $term_id  Term ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return void
	 */
	public function save_term( $term_id, $taxonomy ) {

		$term = get_term( $term_id, $taxonomy );
		if ( ! $term ) {
			return;
		}

		unset( $this->terms[ $term->term_taxonomy_id ] );

		if ( $this->processing ) {
			return;
		}

		if ( ! isset( $this->linked_taxonomies[ $taxonomy ] ) ) {
			return;
		}

		$this->terms[ $term->term_taxonomy_id ] = $term;
	}

	/**
	 * Updates a term in all linked taxonomies.
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

		if ( $this->processing ) {
			return;
		}

		if ( ! isset( $this->linked_taxonomies[ $taxonomy ] ) ) {
			return;
		}

		$updated_term = get_term_by( 'term_taxonomy_id', $tt_id, $taxonomy );
		if ( ! $updated_term ) {
			return;
		}

		if ( empty( $this->terms[ $tt_id ] ) ) {
			return;
		}

		unset( $updated_term->taxonomy );
		unset( $updated_term->term_id );
		unset( $updated_term->term_taxonomy_id );

		$saved_term = $this->terms[ $tt_id ];

		$this->processing = TRUE;

		foreach ( $this->linked_taxonomies[ $taxonomy ] as $linked_taxonomy ) {
			$linked_term = get_term_by( 'slug', $saved_term->slug, $linked_taxonomy );
			if ( $linked_term ) {
				$to_be_saved_term = $updated_term;

				if ( $saved_term->parent !== $to_be_saved_term->parent ) {
					if ( (int) $to_be_saved_term->parent > 0 ) {
						$parent_term = get_term( $to_be_saved_term->parent, $taxonomy );
						unset( $to_be_saved_term->parent );
						if ( $parent_term ) {
							$linked_parent = get_term_by( 'slug', $parent_term->slug, $linked_taxonomy );
							if ( $linked_parent ) {
								$to_be_saved_term->parent = $linked_parent->term_id;
							}
						}
					} else {
						$to_be_saved_term->parent = 0;
					}
				} else {
					unset( $to_be_saved_term->parent );
				}
				wp_update_term( $linked_term->term_id, $linked_taxonomy, (array) $to_be_saved_term );
			}
		}

		$this->processing = FALSE;
	}

	/**
	 * Deletes a term from all linked taxonomies.
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
	public function delete_term(
		/** @noinspection PhpUnusedParameterInspection */
		$term,
		$tt_id,
		$taxonomy,
		$deleted_term
	) {

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
