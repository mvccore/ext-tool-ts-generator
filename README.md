# MvcCore - Extension - Tool - TypeScript Generator

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.2.1-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-tool-ts-generator/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

## Installation
```shell
composer require mvccore/ext-tool-ts-generator
```

## Features
Extension to easilly generate TypeScript model classes, interfaces or enums from PHP equivalents.

## Usage
```php
<?php

include_once('vendor/autoload.php');

use \MvcCore\Ext\Tools\TsGenerator;

TsGenerator::CreateInstance()
	->SetType(
		new \ReflectionClass(\PhpObjects\BaseClass::class)
	)
	->SetPropsFlags(
		TsGenerator::PROPS_INHERIT_PROTECTED
	)
	->SetTargetPath(
		__DIR__ . '/Ts/Custom/ClassName.d.ts'
	)
	->SetWriteFlags(
		TsGenerator::WRITE_INTERFACE
		| TsGenerator::WRITE_DECLARE
		// | TsGenerator::WRITE_EXPORT
	)
	->SetExcludedPropsNames(['db'])
	//->SetCustomName('Custom.ClassName')
	->Parse()
	->Write();

```