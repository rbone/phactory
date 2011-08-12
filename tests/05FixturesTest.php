<?php

class FixturesTest extends PHPUnit_Framework_TestCase
{
	public function testFixtures()
	{
		$one = Phactory::category('tshirt');
		$two = Phactory::category('tshirt');

		$this->assertSame($one, $two);
	}
}

class CategoryPhactory
{
	public function blueprint()
	{
		return array(
			'name' => 'category#{sn}',
			'type' => 'category#{sn}',
		);
	}

	public function tshirt_fixture()
	{
		return array(
			'name' => "T-shirt's",
			'type' => 'tshirt',
		);
	}
}