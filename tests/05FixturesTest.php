<?php

class FixturesTest extends PHPUnit_Framework_TestCase
{
	public function testFixtures()
	{
		$one = Phactory::category('tshirt');
		$two = Phactory::category('tshirt');

		$this->assertSame($one, $two);
	}

	public function testFixturesWorkWithRelationships()
	{
		$one = Phactory::project();
		$two = Phactory::project();

		$this->assertSame($one->category, $two->category);
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

class ProjectPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'project #{sn}',
			'category' => Phactory::has_one('category', 'tshirt'),
		);
	}
}