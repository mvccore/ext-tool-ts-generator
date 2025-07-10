<?php

namespace MvcCore\Ext\Tools\TsGenerator;

use \MvcCore\Ext\Tools\TsGenerators\MembersConfig;

/**
 * @mixin \MvcCore\Ext\Tools\TsGenerator
 */
trait LocalMethods {

	/**
	 * There is not possible to create instance outside of this class.
	 * @return void
	 */
	private function __construct () {
	}
	
	/**
	 * 
	 * @return array
	 */
	protected function getTypeTraits () {
		if ($this->type === NULL) 
			return NULL;
		if ($this->typeTraits !== NULL)
			return $this->typeTraits;
		/** @var $currentType \ReflectionClass */
		$typeTraits = [];
		$currentType = $this->type;
		while (TRUE) {
			$typesAndFiles = [$currentType->getName() => $currentType->getFileName()];
			$this->getTypeTraitsRecursive($currentType, $typesAndFiles);
			$typeTraits = array_merge([], $typeTraits, $typesAndFiles);
			$parentType = $currentType->getParentClass();
			if ($parentType === FALSE) break;
			$currentType = $parentType;
		}
		return $this->typeTraits = $typeTraits;
	}
	
	/**
	 * 
	 * @param  \ReflectionClass $type 
	 * @param  array            $typesAndFiles 
	 * @return void
	 */
	protected function getTypeTraitsRecursive (\ReflectionClass $type, array & $typesAndFiles) {
		$traits = array_keys($type->getTraits());
		if (count($traits) === 0) return;
		foreach ($traits as $trait) {
			if (!isset($typesAndFiles[$trait])) {
				$traitType = new \ReflectionClass($trait);
				$typesAndFiles[$trait] = $traitType->getFileName();
				$this->getTypeTraitsRecursive($traitType, $typesAndFiles);
			}
		}
	}

	/**
	 * 
	 * @return bool
	 */
	protected function getTargetIsNewest () {
		if ($this->targetIsNewest !== NULL)
			return $this->targetIsNewest;
		$sourceMtime = 0;
		$localMtime = filemtime($this->type->getFileName());
		if ($localMtime > $sourceMtime) $sourceMtime = $localMtime;
		$this->getTypeTraits();
		foreach ($this->typeTraits as $srcFullPath) {
			$localMtime = filemtime($srcFullPath);
			if ($localMtime > $sourceMtime) $sourceMtime = $localMtime;
		}
		$targetMtime = PHP_INT_MAX;
		if (!file_exists($this->targetPath)) 
			return $this->targetIsNewest = TRUE;
		$targetMtime = filemtime($this->targetPath);
		return $this->targetIsNewest = ($targetMtime <= $sourceMtime);
	}

	/**
	 * 
	 * @return \MvcCore\Ext\Tools\TsGenerators\MembersConfig
	 */
	protected function getCfg () {
		$cfg = new \MvcCore\Ext\Tools\TsGenerators\MembersConfig;
		
		$cfg->InheritMembers = ($this->propsFlags & static::PROPS_INHERIT) != 0;
		
		$cfg->InstancePrivate = ($this->propsFlags & static::PROPS_INSTANCE_PRIVATE) != 0;
		$cfg->InstanceProtected = ($this->propsFlags & static::PROPS_INSTANCE_PROTECTED) != 0;
		$cfg->InstancePublic = ($this->propsFlags & static::PROPS_INSTANCE_PUBLIC) != 0;
		$cfg->InstanceMembers = $cfg->InstancePrivate || $cfg->InstanceProtected || $cfg->InstancePublic;
		
		$cfg->StaticPrivate = ($this->propsFlags & static::PROPS_STATIC_PRIVATE) != 0;
		$cfg->StaticProtected = ($this->propsFlags & static::PROPS_STATIC_PROTECTED) != 0;
		$cfg->StaticPublic = ($this->propsFlags & static::PROPS_STATIC_PUBLIC) != 0;
		$cfg->StaticMembers = $cfg->StaticPrivate || $cfg->StaticProtected || $cfg->StaticPublic;

		$cfg->Parse = $cfg->InheritMembers || $cfg->InstanceMembers || $cfg->StaticMembers;
		if (!$cfg->Parse) return $cfg;

		$cfg->ReflectionFlags = 0;
		if (
			($this->propsFlags & static::PROPS_INSTANCE_PRIVATE) != 0 ||
			($this->propsFlags & static::PROPS_STATIC_PRIVATE) != 0
		) $cfg->ReflectionFlags |= \ReflectionProperty::IS_PRIVATE;
		if (
			($this->propsFlags & static::PROPS_INSTANCE_PROTECTED) != 0 ||
			($this->propsFlags & static::PROPS_STATIC_PROTECTED) != 0
		) $cfg->ReflectionFlags |= \ReflectionProperty::IS_PROTECTED;
		if (
			($this->propsFlags & static::PROPS_INSTANCE_PUBLIC) != 0 ||
			($this->propsFlags & static::PROPS_STATIC_PUBLIC) != 0
		) $cfg->ReflectionFlags |= \ReflectionProperty::IS_PUBLIC;
		
		return $cfg;
	}

	/**
	 * 
	 * @param  MembersConfig $cfg
	 * @return array|\string[]
	 */
	protected function parseType (MembersConfig $cfg) {
		$result = [];
		if ($this->typesAliases === NULL) {
			$typesAliases = static::$typesAliasesDefault;
		} else {
			$typesAliases = array_merge([], static::$typesAliasesDefault, $this->typesAliases);
		}
		/** @var \ReflectionProperty[] $props */
		$props = $this->type->getProperties($cfg->ReflectionFlags);
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		$phpWithUnionTypes = PHP_VERSION_ID >= 80000;
		foreach ($props as $prop) {
			$isStatic = $prop->isStatic();
			if (
				($isStatic && !$cfg->StaticMembers) ||
				(!$isStatic && !$cfg->InstanceMembers) ||
				array_search($prop->name, $this->excludedPropsNames, TRUE) !== FALSE
			) continue;
			$accessMod = '';
			if (($this->writeFlags & static::WRITE_CLASS) != 0) {
				if ($prop->isPrivate()) 
					$accessMod = 'private ';
				if ($prop->isProtected()) 
					$accessMod = 'protected ';
				if ($prop->isPublic()) 
					$accessMod = 'public ';
			}
			$staticMod = $isStatic ? 'static ' : '';
			list ($allowNull, $types) = $this->parseTypeProp(
				$prop, $phpWithTypes, $phpWithUnionTypes
			);
			foreach ($types as $index => $type)
				if (isset($typesAliases[$type]))
					$types[$index] = $typesAliases[$type];
			if (count($types) === 0) 
				$types = ['any'];
			$result[] = implode('', [
				$accessMod,
				$staticMod,
				$prop->name,
				($allowNull ? '?: ' : ': '),
				implode(' | ', $types),
				';',
			]);
		}
		return $result;
	}

	/**
	 * 
	 * @param  \ReflectionProperty $prop 
	 * @param  bool                $phpWithTypes 
	 * @param  bool                $phpWithUnionTypes 
	 * @return array|[bool, \string[]]
	 */
	protected function parseTypeProp (\ReflectionProperty $prop, $phpWithTypes, $phpWithUnionTypes) {
		$types = [];
		$allowNull = FALSE;
		if ($phpWithTypes && $prop->hasType()) {
			/** @var $reflType \ReflectionUnionType|\ReflectionNamedType */
			$refType = $prop->getType();
			if ($refType !== NULL) {
				if ($phpWithUnionTypes && $refType instanceof \ReflectionUnionType) {
					$refTypes = $refType->getTypes();
					/** @var \ReflectionNamedType $refTypesItem */
					$strIndex = NULL;
					foreach ($refTypes as $index => $refTypesItem) {
						$typeName = $refTypesItem->getName();
						if ($strIndex === NULL && $typeName === 'string')
							$strIndex = $index;
						if ($typeName !== 'null')
							$types[] = $typeName;
					}
					if ($strIndex !== NULL) {
						unset($types[$strIndex]);
						$types = array_values($types);
						$types[] = 'string';
					}
				} else {
					$types = [$refType->getName()];
				}
				$allowNull = $refType->allowsNull();
			}
		} else {
			preg_match('/@var\s+([^\s]+)/', $prop->getDocComment(), $matches);
			if ($matches) {
				$rawTypes = '|'.$matches[1].'|';
				$nullPos = mb_stripos($rawTypes,'|null|');
				$qmPos = mb_strpos($rawTypes, '?');
				$qmMatched = $qmPos !== FALSE;
				$nullMatched = $nullPos !== FALSE;
				$allowNull = $qmMatched || $nullMatched;
				if ($qmMatched) 
					$rawTypes = str_replace('?', '', $rawTypes);
				if ($nullMatched)
					$rawTypes = (
						mb_substr($rawTypes, 0, $nullPos) . 
						mb_substr($rawTypes, $nullPos + 5)
					);
				$rawTypes = mb_substr($rawTypes, 1, mb_strlen($rawTypes) - 2);
				$types = explode('|', $rawTypes);
			}
		}
		foreach ($types as $key => $type)
			$types[$key] = trim($type, '\\');
		return [
			$allowNull,	// boolean
			$types		// \string[]
		];
	}
	
	/**
	 * 
	 * @param  MembersConfig $cfg
	 * @return array|\string[]
	 */
	protected function parseForm (MembersConfig $cfg) {
		$result = [];

		$accessMod = '';
		if (($this->writeFlags & static::WRITE_CLASS) != 0)
			$accessMod = 'public '; // all form fields are accessible as public by default
		
		if ($this->typesAliases === NULL) {
			$formFields2HtmlTypes = static::$formFields2HtmlTypesDefault;
		} else {
			$formFields2HtmlTypes = array_merge([], static::$formFields2HtmlTypesDefault, $this->formFields2HtmlTypes);
		}

		$fields = $this->form->GetFields();
		$submits = $this->form->GetSubmitFields();
		$fieldsets = $this->form->GetFieldsets();
		
		$renderFields = ($this->formFlags & static::FORM_FIELDS) != 0;
		$renderSubmits = ($this->formFlags & static::FORM_SUBMITS) != 0;
		$renderFieldsets = ($this->formFlags & static::FORM_FIELDSETS) != 0;

		if ($renderFields && !$renderSubmits) {
			foreach (array_keys($submits) as $submitName)
				unset($fields[$submitName]);
		}

		if ($renderFields) {
			foreach ($fields as $fieldName => $field) {
				$type = 'HTMLElement';
				$fieldFullClassName = get_class($field);
				if (isset($formFields2HtmlTypes[$fieldFullClassName])) {
					$type = $formFields2HtmlTypes[$fieldFullClassName];
				} else {
					foreach ($formFields2HtmlTypes as $fieldClass2Detect => $htmlType) {
						if (is_subclass_of($field, $fieldClass2Detect)) {
							$type = $htmlType;
							break;
						}
					}
				}
			
				$result[] = implode('', [
					$accessMod,
					$fieldName,
					': ',
					$type,
					';',
				]);
			}
		}

		if ($renderFieldsets) {
			if ($this->typesAliases === NULL) {
				$formFieldset2HtmlTypes = static::$formFieldset2HtmlTypesDefault;
			} else {
				$formFieldset2HtmlTypes = array_merge([], static::$formFieldset2HtmlTypesDefault, $this->formFieldset2HtmlTypes);
			}
			$fieldsets = $this->form->GetFieldsets();
			foreach ($fieldsets as $fieldsetName => $fieldset) {
				$type = 'HTMLFieldSetElement';
				$fieldsetFullClassName = get_class($fieldset);
				if (isset($formFieldset2HtmlTypes[$fieldsetFullClassName])) {
					$type = $formFieldset2HtmlTypes[$fieldsetFullClassName];
				} else {
					foreach ($formFieldset2HtmlTypes as $fieldsetClass2Detect => $htmlType) {
						if (is_subclass_of($fieldset, $fieldsetClass2Detect)) {
							$type = $htmlType;
							break;
						}
					}
				}

				$result[] = implode('', [
					$accessMod,
					$fieldsetName,
					': ',
					$type,
					';',
				]);
			}
		}

		return $result;
	}

	/**
	 * 
	 * @return string
	 */
	protected function completeCode () {
		$typeStr = 'class ';
		if (($this->writeFlags & static::WRITE_CLASS) != 0) {
			$typeStr = 'class ';	
		} else if (($this->writeFlags & static::WRITE_INTERFACE) != 0) {
			$typeStr = 'interface ';	
		} else if (($this->writeFlags & static::WRITE_ENUM) != 0) {
			$typeStr = 'enum ';	
		}

		$jsFullName = $this->customName !== NULL
			? $this->customName
			: str_replace(['\\', '_'], '.', $this->type->getName());
		$jsFullNameParts = explode('.', $jsFullName);
		$jsFullNamePartsCnt = count($jsFullNameParts);
		$jsNameSpace = '';
		$jsObjName = $jsFullNameParts[$jsFullNamePartsCnt - 1];
		if ($jsFullNamePartsCnt > 1) {
			unset($jsFullNameParts[$jsFullNamePartsCnt - 1]);
			$jsNameSpace = implode('.', $jsFullNameParts);
		}
		$exportStr = ($this->writeFlags & static::WRITE_EXPORT) != 0
			? 'export '
			: '';
		$declareStr = ($this->writeFlags & static::WRITE_DECLARE) != 0 && !$exportStr
			? 'declare '
			: '';
		$code = [];
		$indent = '';
		if ($jsNameSpace) {
			$code[] = $indent . $declareStr . 'namespace ' . $jsNameSpace . ' {';
			$declareStr = '';
			$indent .= "\t";
		}
		$code[] = $indent . $exportStr . $declareStr . $typeStr . $jsObjName . ' {';
		$indent .= "\t";
		foreach ($this->parsedProps as $parsedProp) {
			$code[] = $indent . $parsedProp;
		}
		$indent = $jsNameSpace ? "\t" : '';
		$code[] = $indent . '}';
		if ($jsNameSpace) {
			$indent = '';
			$code[] = $indent . '}';
		}
		return implode("\n", $code);
	}
}