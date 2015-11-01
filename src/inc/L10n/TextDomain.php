<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\L10n;

/**
 * Text domain model.
 *
 * @package tfrommen\LinkedTaxonomies\L10n
 */
class TextDomain {

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param string[] $plugin_data Plugin data.
	 * @param string   $file        Main plugin file.
	 */
	public function __construct( array $plugin_data, $file ) {

		$this->domain = $plugin_data[ 'text_domain' ];

		$this->path = plugin_basename( $file );
		$this->path = dirname( $this->path ) . $plugin_data[ 'domain_path' ];
	}

	/**
	 * Loads the text domain.
	 *
	 * @return bool
	 */
	public function load() {

		return load_plugin_textdomain( $this->domain, FALSE, $this->path );
	}

}
