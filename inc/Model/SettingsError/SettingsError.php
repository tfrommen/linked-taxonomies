<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Model\SettingsError;

/**
 * Class SettingsError
 *
 * @package tf\LinkedTaxonomies\Model\SettingsError
 */
abstract class SettingsError {

	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $type = 'error';

	/**
	 * Set the error code.
	 *
	 * @param string $code Error code.
	 *
	 * @return void
	 */
	protected function set_code( $code ) {

		$this->code = $code;
	}

	/**
	 * Set the error message.
	 *
	 * @param string $message Error message.
	 *
	 * @return void
	 */
	protected function set_message( $message ) {

		$this->message = $message;
	}

	/**
	 * Set the error slug.
	 *
	 * @param string $slug Error slug.
	 *
	 * @return void
	 */
	protected function set_slug( $slug ) {

		$this->slug = $slug;
	}

	/**
	 * Set the error type. Valid types are 'error' and 'updated'.
	 *
	 * @param string $type Error type.
	 *
	 * @return bool
	 */
	protected function set_type( $type ) {

		$valid_types = array(
			'error',
			'updated',
		);
		if ( ! in_array( $type, $valid_types ) ) {
			return FALSE;
		}

		$this->type = $type;

		return TRUE;
	}

	/**
	 * Add settings error.
	 *
	 * @return bool
	 */
	public function add() {

		if (
			empty( $this->slug )
			|| empty( $this->code )
			|| empty( $this->message )
		) {
			return FALSE;
		}

		add_settings_error( $this->slug, $this->code, $this->message, $this->type );

		return TRUE;
	}

}
