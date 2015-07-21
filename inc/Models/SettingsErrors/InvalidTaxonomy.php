<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Models\SettingsErrors;

/**
 * Class InvalidTaxonomy
 *
 * @package tf\LinkedTaxonomies\Models\SettingsErrors
 */
class InvalidTaxonomy extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $taxonomy Taxonomy name.
	 */
	public function __construct( $taxonomy ) {

		$this->set_slug( 'Invalid Taxonomy' );

		$this->set_code( 'invalid-taxonomy' );

		$message = _x( "Taxonomy '%s' invalid!", 'Settings error message, %s=taxonomy name', 'linked-taxonomies' );
		$message = sprintf( $message, $taxonomy );
		$this->set_message( $message );
	}

}
