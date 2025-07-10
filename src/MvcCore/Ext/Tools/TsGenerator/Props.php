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
	 * @var \string[]
	 */
	protected static $excludedPropsNamesModelDefault = [
		self::INTERFACE_MODEL => [[
			// \MvcCore\IModel:
			'connectionArguments',
			'sysConfigProperties',
			'parserTypes',
			'defaultConnectionName',
			'defaultConnectionClass',
			'connections',
			'configs',
			'protectedProperties',
		],[
			// \MvcCore\IModel:
			'initialValues',
			'resource',
		]],
		self::INTERFACE_EXTENDED_MODEL => [[
			// \MvcCore\IModel:
			'connectionArguments',
			'sysConfigProperties',
			'parserTypes',
			'defaultConnectionName',
			'defaultConnectionClass',
			'connections',
			'configs',
			'protectedProperties',
			// \MvcCore\Ext\Models\Db\IModel:
			'metaDataCacheClass',
			'metaDataCacheKeyBase',
			'metaDataCacheTags',
			'defaultPropsFlags',
		],[
			// \MvcCore\IModel:
			'initialValues',
			'resource',
			// \MvcCore\Ext\Models\Db\IModel:
			'editResource',
		]],
	];

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected static $formFields2HtmlTypesDefault = [
		// selects:
		'MvcCore\\Ext\\Forms\\Fields\\LocalizationSelect'	=> 'HTMLSelectElement',
		'MvcCore\\Ext\\Forms\\Fields\\CountrySelect'		=> 'HTMLSelectElement',
		'MvcCore\\Ext\\Forms\\Fields\\Select'				=> 'HTMLSelectElement',
		// dates & times:
		'MvcCore\\Ext\\Forms\\Fields\\Month'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Week'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Time'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\DateTime'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Date'					=> 'HTMLInputElement',
		// special number types:
		'MvcCore\\Ext\\Forms\\Fields\\Range'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Number'				=> 'HTMLInputElement',
		// special text types:
		'MvcCore\\Ext\\Forms\\Fields\\Search'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Url'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Tel'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Email'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Password'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Color'				=> 'HTMLInputElement',
		// buttons:
		'MvcCore\\Ext\\Forms\\Fields\\ResetInput'			=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\ResetButton'			=> 'HTMLButtonElement',
		'MvcCore\\Ext\\Forms\\Fields\\SubmitInput'			=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\SubmitButton'			=> 'HTMLButtonElement',
		'MvcCore\\Ext\\Forms\\Fields\\Image'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\ButtonInput'			=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Button'				=> 'HTMLButtonElement',
		// field groups:
		'MvcCore\\Ext\\Forms\\Fields\\RadioGroup'			=> 'RadioNodeList',
		'MvcCore\\Ext\\Forms\\Fields\\CheckboxGroup'		=> 'HTMLInputElement[]',
		'MvcCore\\Ext\\Forms\\FieldsGroup'					=> 'HTMLInputElement[]',
		// base types:
		'MvcCore\\Ext\\Forms\\Fields\\File'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\DataList'				=> 'HTMLDataListElement',
		'MvcCore\\Ext\\Forms\\Fields\\Textarea'				=> 'HTMLTextAreaElement',
		'MvcCore\\Ext\\Forms\\Fields\\Checkbox'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Text'					=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Fields\\Hidden'				=> 'HTMLInputElement',
		'MvcCore\\Ext\\Forms\\Field'						=> 'HTMLInputElement',
	];

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected static $formFieldset2HtmlTypesDefault = [
		'MvcCore\\Ext\\Forms\\Fieldset'	=> 'HTMLFieldSetElement',
		
	];

	/**
	 * Parsing mode.
	 * @var int
	 */
	protected $mode = self::MODE_TYPE;

	/**
	 * PHP source object reflection type (source object could 
	 * be any PHP class instance or MvcCore Model type).
	 * @var \ReflectionClass|NULL
	 */
	protected $type = NULL;

	/**
	 * Form instance, which has elements to render into TS.
	 * @var \MvcCore\Ext\IForm|NULL
	 */
	protected $form = NULL;
	
	/**
	 * Source object properties flags.
	 * @var int
	 */
	protected $propsFlags = self::PROPS_INHERIT_PROTECTED;
	
	/**
	 * Source form elements flags.
	 * @var int
	 */
	protected $formFlags = self::FORM_FIELDS_ALL;

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
	 * TypeScript output flags.
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
	protected $typesAliases = [];

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected $formFields2HtmlTypes = [];

	/**
	 * 
	 * @var array|array<string, string>
	 */
	protected $formFieldset2HtmlTypes = [];

	/**
	 * 
	 * @var array|array<string, string>|NULL
	 */
	protected $typeTraits = NULL;

}
