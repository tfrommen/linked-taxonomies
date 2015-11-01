<?php # -*- coding: utf-8 -*-

namespace tfrommen\LinkedTaxonomies\Asset;

use tfrommen\LinkedTaxonomies\SettingsPage\SettingsPage;

/**
 * Asset controller.
 *
 * @package tfrommen\LinkedTaxonomies\Asset
 */
class Controller {

	/**
	 * @var string
	 */
	private $hook_suffix;

	/**
	 * @var Script
	 */
	private $script;

	/**
	 * @var Style
	 */
	private $style;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Script       $script        Script model.
	 * @param Style        $style         Style model.
	 * @param SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Script $script, Style $style, SettingsPage $settings_page ) {

		$this->script = $script;

		$this->style = $style;

		$this->hook_suffix = 'settings_page_' . $settings_page->get_slug();
	}

	/**
	 * Wires up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( "admin_head-{$this->hook_suffix}", array( $this->script, 'enqueue' ) );

		add_action( "admin_head-{$this->hook_suffix}", array( $this->style, 'enqueue' ) );
	}

}
