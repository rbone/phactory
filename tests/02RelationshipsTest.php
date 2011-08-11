<?php

class RelationshipsTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		Phactory::reset();
	}

	public function testHasOneRelationship()
	{
		$comment = Phactory::comment();

		$this->assertEquals($comment, (object)array(
			'title' => 'OMGWTFBBQ!',
			'content' => 'Food goes in here.',
			'author' => (object)array(
				'first_name' => 'Fronzel',
				'last_name' => 'Neekburm',
				'email' => 'user0001@example.org',
			)
		));
	}

	public function testVariationRelationships()
	{
		$comment = Phactory::comment('admin');

		$this->assertTrue($comment->author->is_admin);
	}
}


class AuthorPhactory
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
			'author' => Phactory::has_one('author'),
		);
	}

	public function admin()
	{
		return array(
			'author' => Phactory::has_one('author', 'admin'),
		);
	}
}
