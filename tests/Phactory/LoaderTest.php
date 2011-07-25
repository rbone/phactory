<?php

namespace Phactory
{

class LoaderTest extends \PHPUnit_Framework_TestCase
{
	public function testManuallyLoad()
	{
		$loader = new Loader();
		$loader->factory('blarg', 'StrangeBlargFactory');

		$this->assertInstanceOf('StrangeBlargFactory', $loader->load('blarg'));
	}

	public function testAutoLoad()
	{
		$loader = new Loader();

		$this->assertInstanceOf('FoodPhactory', $loader->load('food'));
	}
}

}

namespace
{

class StrangeBlargFactory {}

class FoodPhactory {}

}