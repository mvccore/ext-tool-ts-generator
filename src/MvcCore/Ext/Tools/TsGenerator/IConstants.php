<?php

namespace MvcCore\Ext\Tools\TsGenerator;

interface IConstants {


	const PROPS_INHERIT				= 2;


	const PROPS_INSTANCE_PRIVATE	= 4;

	const PROPS_INSTANCE_PROTECTED	= 8;

	const PROPS_INSTANCE_PUBLIC		= 16;

	const PROPS_INSTANCE_ALL		= 30; // 2|4|8|16


	const PROPS_STATIC_PRIVATE		= 32;

	const PROPS_STATIC_PROTECTED	= 64;

	const PROPS_STATIC_PUBLIC		= 128;

	const PROPS_STATIC_ALL			= 226; // 2|32|64|128


	const PROPS_ALL					= 254; // 2|4|8|16|32|64|128

	const PROPS_INHERIT_PROTECTED	= 10; // 2|8;
	


	const WRITE_CLASS				= 1;
	
	const WRITE_INTERFACE			= 2;

	const WRITE_ENUM				= 4;

	const WRITE_DECLARE				= 8;

	const WRITE_EXPORT				= 16;

	const WRITE_DEFAULT				= 18; // 2|16


}
