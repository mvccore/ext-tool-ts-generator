<?php

namespace MvcCore\Ext\Tools\TsGenerator;

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
	 * @param  \ReflectionClass|NULL $type 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetType (\ReflectionClass $type) {
		$this->type = $type;
		return $this;
	}
	
	/**
	 * 
	 * @return \ReflectionClass|NULL
	 */
	public function GetType () {
		$this->mode = static::MODE_TYPE;
		return $this->type;
	}
	
	/**
	 * 
	 * @param  string|NULL $classFullName
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetModel ($classFullName) {
		$interfaces = class_implements($classFullName);
		if (in_array(static::INTERFACE_EXTENDED_MODEL, $interfaces, TRUE)) {
			$this->mode = static::MODE_MODEL_EXTENDED;
		} else if (in_array(static::INTERFACE_MODEL, $interfaces, TRUE)) {
			$this->mode = static::MODE_MODEL;
		} else {
			$i1 = static::INTERFACE_MODEL;
			$i2 = static::INTERFACE_EXTENDED_MODEL;
			throw new \InvalidArgumentException(
				"Class `{$classFullName}` doesn't implement interface `{$i1}` or `{$i2}`. \n"
				."Use method `\$tsGenerator->SetType(\\ReflectionClass('{$classFullName}'));` instead."
			);
		}
		$this->type = new \ReflectionClass($classFullName);
		return $this;
	}
	
	/**
	 * 
	 * @return string|NULL
	 */
	public function GetModel () {
		return $this->type !== NULL
			? $this->type->getName()
			: NULL;
	}
	
	/**
	 * 
	 * @param  \MvcCore\Ext\IForm|NULL $form
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetForm ($form) {
		$interfaces = class_implements($form);
		if (in_array(static::INTERFACE_FORM, $interfaces, TRUE)) {
			$this->mode = static::MODE_FORM;
			$this->form = $form;
			$this->type = new \ReflectionClass($form);
		} else {
			$i1 = static::INTERFACE_FORM;
			$classFullName = get_class($form);
			throw new \InvalidArgumentException(
				"Class `{$classFullName}` doesn't implement interface `{$i1}`. \n"
				."Use method `\$tsGenerator->SetType(\\ReflectionClass('{$classFullName}'));` instead."
			);
		}
		return $this;
	}
	
	/**
	 * 
	 * @return \MvcCore\Ext\IForm|NULL
	 */
	public function GetForm () {
		return $this->form;
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
	 * @param  array|array<string, string> $typesAliases 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function AddTypesAliases ($typesAliases) {
		$this->typesAliases = array_unique(array_merge([], $this->typesAliases, $typesAliases));
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
	 * @param  array|array<string, string> $formFields2HtmlTypes 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function SetFormFields2HtmlTypes ($formFields2HtmlTypes) {
		$this->formFields2HtmlTypes = $formFields2HtmlTypes;
		return $this;
	}

	/**
	 * 
	 * @param  array|array<string, string> $formFields2HtmlTypes 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function AddFormFields2HtmlTypes ($formFields2HtmlTypes) {
		$this->formFields2HtmlTypes = array_unique(array_merge([], $this->formFields2HtmlTypes, $formFields2HtmlTypes));
		return $this;
	}

	/**
	 * 
	 * @return array|array<string, string>
	 */
	public function GetFormFields2HtmlTypes () {
		return $this->formFields2HtmlTypes;
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
	 * @param  \string[] $excludedPropsNames 
	 * @return \MvcCore\Ext\Tools\TsGenerator
	 */
	public function AddExcludedPropsNames ($excludedPropsNames) {
		$this->excludedPropsNames = array_unique(array_merge([], $this->excludedPropsNames ?: [], $excludedPropsNames));
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
		if (!$this->getTargetIsNewest()) 
			return $this;
		$cfg =  $this->getCfg();
		if (!$cfg->Parse) 
			return $this;
		$exclPropsDefault = static::$excludedPropsNamesModelDefault;
		if ($this->mode === static::MODE_TYPE) {
			$this->parsedProps = $this->parseType($cfg);

		} else if ($this->mode === static::MODE_MODEL) {
			$excludedPropsNames = $exclPropsDefault[static::INTERFACE_MODEL];
			if ($cfg->StaticProtected)
				$this->AddExcludedPropsNames($excludedPropsNames[0]);
			if ($cfg->InstanceProtected)
				$this->AddExcludedPropsNames($excludedPropsNames[1]);
			$this->parsedProps = $this->parseType($cfg);

		} elseif ($this->mode === static::MODE_MODEL_EXTENDED) {
			$excludedPropsNames = $exclPropsDefault[static::INTERFACE_EXTENDED_MODEL];
			if ($cfg->StaticProtected)
				$this->AddExcludedPropsNames($excludedPropsNames[0]);
			if ($cfg->InstanceProtected)
				$this->AddExcludedPropsNames($excludedPropsNames[1]);
			$this->parsedProps = $this->parseType($cfg);

		} else if ($this->mode === static::MODE_FORM) {
			$this->parsedProps = $this->parseForm($cfg);

		}
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
