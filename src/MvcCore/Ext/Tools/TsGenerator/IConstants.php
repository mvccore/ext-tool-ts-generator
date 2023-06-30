<?php

namespace MvcCore\Ext\Tools\TsGenerator;

interface IConstants {

	/**
	 * Parsing mode.
	 * @var int
	 */
	const
		MODE_TYPE					= 1,
		MODE_MODEL					= 2,
		MODE_MODEL_EXTENDED			= 3,
		MODE_FORM					= 4;
	
	/**
	 * Source object properties flags.
	 * @var int
	 */
	const
		PROPS_INHERIT				= 2,
		PROPS_INSTANCE_PRIVATE		= 4,
		PROPS_INSTANCE_PROTECTED	= 8,
		PROPS_INSTANCE_PUBLIC		= 16,
		PROPS_INSTANCE_ALL			= 30, // 2|4|8|16
		PROPS_STATIC_PRIVATE		= 32,
		PROPS_STATIC_PROTECTED		= 64,
		PROPS_STATIC_PUBLIC			= 128,
		PROPS_STATIC_ALL			= 226, // 2|32|64|128
		PROPS_ALL					= 254, // 2|4|8|16|32|64|128
		PROPS_INHERIT_PROTECTED		= 10; // 2|8;
	
	/**
	 * TypeScript output flags.
	 * @var int
	 */
	const
		WRITE_CLASS					= 1,
		WRITE_INTERFACE				= 2,
		WRITE_ENUM					= 4,
		WRITE_DECLARE				= 8,
		WRITE_EXPORT				= 16,
		WRITE_DEFAULT				= 18; // 2|16

	/**
	 * Possible PHP Interface objects possible to parse.
	 * @var string
	 */
	const
		INTERFACE_MODEL				= 'MvcCore\\IModel',
		INTERFACE_EXTENDED_MODEL	= 'MvcCore\\Ext\\Models\\Db\\IModel',
		INTERFACE_FORM				= 'MvcCore\\Ext\\IForm';


}
