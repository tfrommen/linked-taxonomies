<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\SettingsError;

/**
 * Settings error model.
 *
 * @package tfrommen\LinkedTaxonomies\SettingsError
 */
class SettingsError  implements SettingsErrorInterface {

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
	 * Constructor. Sets up the properties.
	 *
	 * @param string $slug    Error slug.
	 * @param string $code    Error code.
	 * @param string $message Error message.
	 * @param string $type    Optional. Error type. Defaults to 'error'.
	 */
	public function __construct( $slug, $code, $message, $type = 'error' ) {

		$this->slug = $slug;

		$this->code = $code;

		$this->message = esc_html( $message );

		if ( $type === 'updated' ) {
			$this->type = 'updated';
		}
	}

	/**
	 * Adds the settings error.
	 *
	 * @return bool
	 */
	public function add() {

		if ( empty( $this->code ) ) {
			return FALSE;
		}

		if ( empty( $this->message ) ) {
			return FALSE;
		}

		if ( empty( $this->slug ) ) {
			return FALSE;
		}

		add_settings_error( $this->slug, $this->code, $this->message, $this->type );

		return TRUE;
	}

}
