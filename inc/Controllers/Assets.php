<?php # -*- coding: utf-8 -*-

namespace tf\LinkedTaxonomies\Controllers;

use tf\LinkedTaxonomies\Models\Script;
use tf\LinkedTaxonomies\Models\SettingsPage;
use tf\LinkedTaxonomies\Models\Style;

/**
 * Class Assets
 *
 * @package tf\LinkedTaxonomies\Controllers
 */
class Assets {

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
	 * Constructor. Set up the properties.
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
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'admin_head-' . $this->hook_suffix, array( $this->script, 'enqueue' ) );

		add_action( 'admin_head-' . $this->hook_suffix, array( $this->style, 'enqueue' ) );
	}

}
