<?php

namespace MvcCore\Ext\Tools;

/**
 * Extension to easilly generate TypeScript model 
 * classes, interfaces or enums from PHP equivalents.
 */
class TsGenerator implements \MvcCore\Ext\Tools\TsGenerator\IConstants {

	use \MvcCore\Ext\Tools\TsGenerator\Props,
		\MvcCore\Ext\Tools\TsGenerator\LocalMethods,
		\MvcCore\Ext\Tools\TsGenerator\PublicMethods;
	
	/**
	 * MvcCore Extension - Tool - TsGenerator - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.1.0';

}