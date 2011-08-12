<?php

class BuildersTest extends PHPUnit_Framework_TestCase
{
	public function testCustomBuilder()
	{
		Phactory::reset();
		Phactory::builder(new CustomBuilder());

		$cake = Phactory::cake();

		$this->assertInstanceOf('CustomObject', $cake);
	}
}

class CakePhactory
{
	public function blueprint()
	{
		return array(
			'type' => 'chocolate',
			'frosting' => true,
		);
	}
}

class CustomBuilder extends \Phactory\Builder
{
	public function to_object($name, $blueprint)
	{
		$object = new CustomObject();
		foreach ($blueprint as $attribute => $value)
			$object->$attribute = $value;
		
		return $object;
	}
}

class CustomObject {}