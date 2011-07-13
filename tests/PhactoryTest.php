<?php

class PhactoryTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		Phactory::reset();
		Phactory::factory('user', 'UserPhactory');
		Phactory::factory('comment', 'CommentPhactory');
	}

	public function testBasicCreate()
	{
		$user = Phactory::user();

		$this->assertEquals($user, (object)array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user0001@example.org',
		));
	}

	public function testSerialNumberIncrements()
	{
		$one = Phactory::user();
		$two = Phactory::user();

		$this->assertNotEquals($one->email, $two->email);
	}

	public function testBlueprintsOverlay()
	{
		$admin = Phactory::user('admin');

		$this->assertEquals($admin, (object)array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user0001@example.org',
			'is_admin' => true,
		));
	}

	public function testOverrideAttributes()
	{
		$user = Phactory::user(array(
			'last_name' => 'Blarg#{sn}',
		));

		$this->assertEquals($user, (object)array(
			'first_name' => 'Fronzel',
			'last_name' => 'Blarg0001',
			'email' => 'user0001@example.org',
		));

		$admin = Phactory::user('admin', array(
			'first_name' => 'Admin',
		));

		$this->assertEquals($user, (object)array(
			'first_name' => 'Admin',
			'last_name' => 'Neekburm',
			'email' => 'user0001@example.org',
			'is_admin' => true,
		));
	}

	public function testRelationships()
	{
		$comment = Phactory::comment();

		$this->assertEquals($comment, (object)array(
			'title' => 'OMGWTFBBQ!',
			'content' => 'Food goes in here.',
			'user' => (object)array(
				'first_name' => 'Fronzel',
				'last_name' => 'Neekburm',
				'email' => 'user#{sn}@example.org',
			)
		));

		$comment = Phactory::comment(array(
			'user' => Phactory::user('admin'),
		));

		$this->assertTrue($comment->user->is_admin);
	}

	public function testCustomBuilder()
	{
		Phactory::builder(new Builder);

		$user = Phactory::user();

		$this->assertInstanceOf('TestObject', $user);
		$this->assertEquals($user->first_name, 'Fronzel');
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
			'is_admin' => true,
		);
	}
}

class CommentPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'OMGWTFBBQ!',
			'content' => 'Food goes in here.',
			'user' => Phactory::user(),
		);
	}
}

class Builder
{
	public function create($type, $blueprint)
	{
		$object = new TestObject();

		foreach ($blueprint as $key => $value)
			$object->$key = $value;

		return $object;
	}
}

class TestObject {}