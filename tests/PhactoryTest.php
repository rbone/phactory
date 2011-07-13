<?php

class PhactoryTest
{
	public function testBasicCreate()
	{
		Phactory::factory('user', 'UserPhactory');
		$user = Phactory::user();

		$this->assertEquals($user, (object)array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user0001@example.org',
		));
	}

	public function testSerialNumberIncrements()
	{
		Phactory::factory('user', 'UserFactory');
		$one = Phactory::user();
		$two = Phactory::user();

		$this->assertNotEquals($one->email, $two->email);
	}

	public function testBlueprintsOverlay()
	{
		Phactory::factory('user', 'UserFactory');
		$admin = Phactory::user('admin');

		$this->assertEquals($admin, (object)array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user0001@example.org',
			'isadmin' => true,
		));
	}
}

class UserPhactory
{
	public function blueprint()
	{
		return array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user#{sn}@example.org',
		);
	}

	public function admin()
	{
		return array(
			'isadmin' => true,
		);
	}
}