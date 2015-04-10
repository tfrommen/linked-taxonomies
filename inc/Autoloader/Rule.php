<?php # -*- coding: utf-8 -*-

namespace tf\Autoloader;

/**
 * Interface Rule
 *
 * @package tf\Autoloader
 */
interface Rule {

	/**
	 * Load class or interface.
	 *
	 * @param string $name Class or interface name
	 *
	 * @return bool
	 */
	public function autoload( $name );

}
