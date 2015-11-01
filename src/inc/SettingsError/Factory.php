<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsError;

/**
 * Factory for settings error models.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsError
 */
class Factory {

	/**
	 * Returns a new instance of a settings error model of the given type.
	 *
	 * @param string $type Type of the settings error model.
	 *
	 * @return SettingsErrorInterface
	 */
	public function create( $type ) {

		switch ( $type ) {
			case 'invalid-taxonomy':
				$message = _x( "Taxonomy invalid!", 'Settings error message', 'linked-taxonomies' );

				return new SettingsError(
					'Invalid Taxonomy',
					'invalid-taxonomy',
					$message
				);

			case 'no-permission-to-edit':
				$message = _x(
					"You don't have permission to edit linked taxonomies.",
					'Settings error message',
					'linked-taxonomies'
				);

				return new SettingsError(
					'No Permission to Edit',
					'no-permission-to-edit',
					$message
				);

			default:
				return new NullSettingsError();
		}
	}

}
