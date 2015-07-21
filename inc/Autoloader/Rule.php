<?php # -*- coding: utf-8 -*-

namespace tf\Autoloader;

/**
 * Interface Rule
 *
 * @package tf\Autoloader
 */
interface Rule {

	/**
	 * Load a class or an interface.
	 *
	 * @param string $name Class or interface name.
	 *
	 * @return bool
	 */
	public function autoload( $name );

}
