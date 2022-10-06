<?php

namespace MvcCore\Ext\Tools\TsGenerator;

use \MvcCore\Ext\Tools\TsGenerator;

/**
 * @mixin \MvcCore\Ext\Tools\TsGenerator
 */
trait PublicMethods {

	/**
	 * 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public static function CreateInstance () {
		return new static;
	}
	
	/**
	 * 
	 * @param \ReflectionClass $type 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetType (\ReflectionClass $type) {
		$this->type = $type;
		return $this;
	}
	
	/**
	 * 
	 * @return \ReflectionClass
	 */
	public function GetType () {
		return $this->type;
	}
	
	/**
	 * 
	 * @param  int $membersFlags 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetPropsFlags ($membersFlags) {
		$this->propsFlags = $membersFlags;
		return $this;
	}
	
	/**
	 * 
	 * @return int
	 */
	public function GetPropsFlags () {
		return $this->propsFlags;
	}

	/**
	 * 
	 * @param  int $writeFlags 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetWriteFlags ($writeFlags) {
		$this->writeFlags = $writeFlags;
		return $this;
	}

	/**
	 * 
	 * @return int
	 */
	public function GetWriteFlags () {
		return $this->writeFlags;
	}

	/**
	 * 
	 * @param  string|NULL $targetPath 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetTargetPath ($targetPath) {
		$this->targetPath = $targetPath;
		return $this;
	}

	/**
	 * 
	 * @return string|NULL
	 */
	public function GetTargetPath () {
		return $this->targetPath;
	}

	/**
	 * 
	 * @param  string|NULL $customName 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetCustomName ($customName) {
		$this->customName = $customName;
		return $this;
	}

	/**
	 * 
	 * @return string|NULL
	 */
	public function GetCustomName () {
		return $this->customName;
	}

	/**
	 * 
	 * @param  array|array<string, string> $typesAliases 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetTypesAliases ($typesAliases) {
		$this->typesAliases = $typesAliases;
		return $this;
	}

	/**
	 * 
	 * @return array|array<string, string>
	 */
	public function GetTypesAliases () {
		return $this->typesAliases;
	}
	
	/**
	 * 
	 * @param  \string[] $excludedPropsNames 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetExcludedPropsNames ($excludedPropsNames) {
		$this->excludedPropsNames = $excludedPropsNames;
		return $this;
	}

	/**
	 * 
	 * @return \string[]
	 */
	public function GetExcludedPropsNames () {
		return $this->excludedPropsNames;
	}
	
	/**
	 * 
	 * @param  \string[] $parsedProps 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetParsedProps ($parsedProps) {
		$this->parsedProps = $parsedProps;
		return $this;
	}

	/**
	 * 
	 * @return \string[]
	 */
	public function GetParsedProps () {
		return $this->parsedProps;
	}
	
	/**
	 * 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function Parse () {
		$this->parsedProps = $this->parseProps();
		return $this;
	}
	
	/**
	 * Check if target TS file has older modification time 
	 * than source PHP file(s) and if it has, parse PHP type
	 * and write TS result.
	 * @return bool
	 */
	public function Write () {
		if (!$this->getTargetIsNewest()) 
			return FALSE;
		return \MvcCore\Tool::AtomicWrite(
			$this->targetPath, $this->completeCode(), 'w'
		);
	}

}
