<?php

namespace MvcCore\Ext\Tools\TsGenerator;

use \MvcCore\Ext\Tools\TsGenerator;
use \MvcCore\Ext\Tools\TsGenerator\IConstants;

/**
 * @mixin \MvcCore\Ext\Tools\TsGenerator
 */
trait Props {

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected static $typesAliasesDefault = [
		'bool'		=> 'boolean',
		'int'		=> 'number',
		'float'		=> 'number',
		'array'		=> 'any[]',
		'object'	=> 'any',
		'resource'	=> 'any',
		'callable'	=> 'Function',
		'NULL'		=> 'null',
	];

	/**
	 * 
	 * @var \ReflectionClass
	 */
	protected $type = NULL;
	
	/**
	 * 
	 * @var int
	 */
	protected $propsFlags = self::PROPS_INHERIT_PROTECTED;

	/**
	 * 
	 * @var bool|NULL
	 */
	protected $targetIsNewest = NULL;

	/**
	 * 
	 * @var \string[]
	 */
	protected $parsedProps = [];

	/**
	 * 
	 * @var int
	 */
	protected $writeFlags = self::WRITE_DEFAULT;

	/**
	 * 
	 * @var string|NULL
	 */
	protected $targetPath = NULL;

	/**
	 * 
	 * @var string|NULL
	 */
	protected $customName = NULL;
	
	/**
	 * 
	 * @var \string[]
	 */
	protected $excludedPropsNames = [];

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected $typesAliases = NULL;

}
