<?php # -*- coding: utf-8 -*-

namespace tf\Autoloader;

foreach ( array( 'Autoloader', 'Rule', 'NamespaceRule' ) as $name ) {
	$fqn = __NAMESPACE__ . '\\' . $name;
	if ( ! class_exists( $fqn ) && ! interface_exists( $fqn ) ) {
		require_once __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';
	}
}
