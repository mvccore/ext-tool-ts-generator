<?php

namespace MvcCore\Ext\Tools\TsGenerators;

use \MvcCore\Ext\Tools\TsGenerator;

/**
 * @mixin \MvcCore\Ext\Tools\TsGenerator
 */
class MembersConfig extends \stdClass {

	public $Parse				= FALSE;

	public $InheritMembers		= FALSE;

	public $InstancePrivate		= FALSE;
	public $InstanceProtected	= FALSE;
	public $InstancePublic		= FALSE;
	public $InstanceMembers		= FALSE;
	
	public $StaticPrivate		= FALSE;
	public $StaticProtected		= FALSE;
	public $StaticPublic		= FALSE;
	public $StaticMembers		= FALSE;

	public $ReflectionFlags		= 0;

}