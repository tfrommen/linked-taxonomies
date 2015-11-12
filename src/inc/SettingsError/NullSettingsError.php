<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsError;

/**
 * NULL representation of a settings error model.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsError
 */
class NullSettingsError implements SettingsErrorInterface {

	/**
	 * Adds the settings error.
	 *
	 * @return bool
	 */
	public function add() {

		return FALSE;
	}

}
