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
			'is_admin' => true,
		));
	}

	public function testOverrideAttributes()
	{
		Phactory::factory('user', 'UserFactory');
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
		Phactory::factory('user', 'UserPhactory');
		Phactory::factory('comment', 'CommentFactory');
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