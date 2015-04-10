<?php # -*- coding: utf-8 -*-

namespace tf\Autoloader;

/**
 * Class NamespaceRule
 *
 * @package tf\Autoloader
 */
class NamespaceRule implements Rule {

	/**
	 * @var string
	 */
	private $dir;

	/**
	 * @var string
	 */
	private $namespace;

	/**
	 * Set up directory and namespace.
	 *
	 * @param string $dir       Directory
	 * @param string $namespace Namespace (Optional, must be given as absolute namespace)
	 */
	public function __construct( $dir, $namespace = '\\' ) {

		$dir = preg_replace( '~[\\|/]+~', DIRECTORY_SEPARATOR, $dir );
		$this->dir = rtrim( $dir, DIRECTORY_SEPARATOR );
		$this->namespace = $namespace;
	}

	/**
	 * Load class or interface.
	 *
	 * @param string $name Class or interface name
	 *
	 * @return bool
	 */
	public function autoload( $name ) {

		$namespace = trim( $this->namespace, '\\' );
		$name = ltrim( $name, '\\' );

		if ( strpos( $name, $namespace ) !== 0 ) {
			return FALSE;
		}

		$namepart = str_replace( $namespace, '', $name );
		$namepart = ltrim( $namepart, '\\' );

		$file = $this->dir . DIRECTORY_SEPARATOR . $namepart . '.php';
		$file = str_replace( '\\', DIRECTORY_SEPARATOR, $file );

		if ( ! is_readable( $file ) ) {
			return FALSE;
		}

		require $file;

		return TRUE;
	}

}
