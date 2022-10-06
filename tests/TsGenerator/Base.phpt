<?php

include_once(__DIR__ . '/../bootstrap.php');
include_once(__DIR__ . '/../../src/MvcCore/Ext/Tools/TsGenerator.php');

include_once(__DIR__ . '/PhpObjects/BaseClass/Props/InstanceProps.php');
include_once(__DIR__ . '/PhpObjects/BaseClass/Props/StaticProps.php');
include_once(__DIR__ . '/PhpObjects/BaseClass/Props.php');
include_once(__DIR__ . '/PhpObjects/BaseClass.php');
include_once(__DIR__ . '/PhpObjects/BaseClasses/ExtendedClass.php');

use \Tester\Assert,
	\MvcCore\Ext\Tools\TsGenerator;

/**
 * @see run.cmd ./TsGenerator/*
 */
class TsGeneratorTest extends \Tester\TestCase {

	/** before each test method */
	public function setUp () {
		
	}

	/** after each test method */
	public function tearDown () {
		
	}

	public function test1 () {
		TsGenerator::CreateInstance()
			->SetType(new \ReflectionClass(\PhpObjects\BaseClass::class))
			->SetPropsFlags(TsGenerator::PROPS_ALL)
			->SetTargetPath(__DIR__ . '/TsObjects/BaseClass.ts')
			->SetWriteFlags(
				TsGenerator::WRITE_CLASS |
				TsGenerator::WRITE_EXPORT
			)
			->SetExcludedPropsNames(['privateProp'])
			->SetCustomName('Custom.ClassName')
			->Parse()
			->Write();
	}


}

run(function () {
	(new TsGeneratorTest)->run();
});