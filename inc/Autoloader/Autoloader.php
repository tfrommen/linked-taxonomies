<?php # -*- coding: utf-8 -*-

namespace tf\Autoloader;

/**
 * Class Autoloader
 *
 * @package tf\Autoloader
 */
class Autoloader {

	/**
	 * @var Rule[]
	 */
	private $rules = array();

	/**
	 * Register to spl autoload stack.
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Add autoloader rule.
	 *
	 * @param Rule $rule Autoloader rule object
	 */
	public function add_rule( Rule $rule ) {

		$this->rules[ ] = $rule;
	}

	/**
	 * Load class or interface.
	 *
	 * @param string $name Class or interface name
	 *
	 * @return bool
	 */
	public function autoload( $name ) {

		foreach ( $this->rules as $rule ) {
			if ( $rule->autoload( $name ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}

}
