<?php

class TriggersTest extends PHPUnit_Framework_TestCase
{
	public function testTriggersOnBaseBlueprint()
	{
		Phactory::reset();
		Phactory::triggers(new FrameworkTriggers);

		$account = Phactory::account();

		$this->assertEquals($account->id, 10000);
		$this->assertTrue($account->beforesave);
		$this->assertTrue($account->aftersave);
	}

	public function testTriggersWithVariation()
	{
		Phactory::reset();
		Phactory::triggers(new FrameworkTriggers);

		$account = Phactory::account('system');

		$this->assertEquals($account->id, 1);
		$this->assertTrue($account->beforesave);
		$this->assertTrue($account->aftersave);
		$this->assertTrue($account->system_beforesave);
		$this->assertTrue($account->system_aftersave);
	}
}

class AccountPhactory
{
	public function blueprint()
	{
		return array(
			'name' => 'User #{sn}',
		);
	}

	public function system_fixture()
	{
		return array(
			'name' => 'System',
		);
	}
}

class FrameworkTriggers
{
	private $sequences = 10000;

	public function account_beforesave($object)
	{
		$object->id = $this->sequences++;
		$object->beforesave = true;
	}

	public function account_aftersave($object)
	{
		$object->aftersave = true;
	}

	public function account_system_beforesave($object)
	{
		$object->id = 1;
		$object->system_beforesave = true;
	}

	public function account_system_aftersave($object)
	{
		$object->system_aftersave = true;
	}
}