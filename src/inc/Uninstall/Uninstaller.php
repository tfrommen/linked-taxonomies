<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Uninstall;

use tfrommen\LinkedTaxonomies\Setting\Option;
use tfrommen\LinkedTaxonomies\Update\Updater;

/**
 * Handles all uninstall-related stuff.
 *
 * @package tfrommen\LinkedTaxonomies\Uninstall
 */
class Uninstaller {

	/**
	 * @var string
	 */
	private $version_option_name;

	/**
	 * @var \wpdb
	 */
	private $wpdb;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Updater $updater Updater.
	 * @param \wpdb   $wpdb    Database object.
	 */
	public function __construct( Updater $updater, \wpdb $wpdb ) {

		$this->version_option_name = $updater->get_option_name();

		$this->wpdb = $wpdb;
	}

	/**
	 * Uninstalls all plugin data.
	 *
	 * @return void
	 */
	public function uninstall() {

		$option_name = Option::get_name();

		if ( is_multisite() ) {
			foreach ( $this->wpdb->get_col( "SELECT blog_id FROM {$this->wpdb->blogs}" ) as $blog_id ) {
				switch_to_blog( $blog_id );

				delete_option( $this->version_option_name );

				delete_option( $option_name );
			}

			restore_current_blog();
		} else {
			delete_option( $this->version_option_name );

			delete_option( $option_name );
		}
	}

}
