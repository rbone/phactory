<?php

class PhactoryTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		Phactory::reset();
		Phactory::factory('user', 'UserPhactory');
		Phactory::factory('comment', 'CommentPhactory');
		Phactory::factory('invoice', 'InvoicePhactory');
		Phactory::factory('contest', 'ContestPhactory');
		Phactory::factory('brief', 'BriefPhactory');
		Phactory::factory('contestentry', 'ContestEntryPhactory');
		Phactory::factory('design', 'DesignPhactory');
		Phactory::factory('attachment', 'AttachmentPhactory');
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

		$this->assertEquals($admin, (object)array(
			'first_name' => 'Admin',
			'last_name' => 'Neekburm',
			'email' => 'user0002@example.org',
			'is_admin' => true,
		));
	}

	public function testHasARelationship()
	{
		$comment = Phactory::comment();

		$this->assertEquals($comment, (object)array(
			'title' => 'OMGWTFBBQ!',
			'content' => 'Food goes in here.',
			'user' => (object)array(
				'first_name' => 'Fronzel',
				'last_name' => 'Neekburm',
				'email' => 'user0001@example.org',
			)
		));

		$comment = Phactory::comment('admin');

		$this->assertTrue($comment->user->is_admin);
	}

	public function testVariationRelationships()
	{
		$comment = Phactory::comment('admin');

		$this->assertTrue($comment->user->is_admin);
	}

	public function testBelongsToRelationship()
	{
		$brief = Phactory::brief();

		$this->assertEquals($brief->contest, (object)array(
			'title' => 'May contest 0001',
			'user' => (object)array(
				'first_name' => 'Fronzel',
				'last_name' => 'Neekburm',
				'email' => 'user0001@example.org',
			),
		));
	}

	public function testRelationshipWithSharedBlueprint()
	{
		$entry = Phactory::contestentry();

		$this->assertSame($entry->user, $entry->design->user);
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
			'user' => Phactory::has_a('user'),
		);
	}

	public function admin()
	{
		return array(
			'user' => Phactory::has_a('user', 'admin'),
		);
	}
}

class InvoicePhactory
{
	public function blueprint()
	{
		$user = Phactory::user();
		return array(
			'amount' => 100,
			'client' => $user,
			'designer' => $user,
		);
	}
}

class ContestPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'May contest #{sn}',
			'user' => Phactory::has_a('user'),
		);
	}
}

class BriefPhactory
{
	public function blueprint()
	{
		return array(
			'description' => 'Food goes in here',
			'requirements' => 'In mah belly',
			'contest' => Phactory::belongs_to('contest'),
		);
	}
}


class ContestEntryPhactory
{
	public function blueprint()
	{
		return array(
			'contest' => Phactory::belongs_to('contest'),
			'design' => Phactory::has_a('design'),
			'user' => Phactory::uses('design.user'),
			'title' => 'Entry #{sn} title',
		);
	}
}

class DesignPhactory
{
	public function blueprint()
	{
		return array(
			'attachment' => Phactory::has_a('attachment'),
			'user' => Phactory::uses('attachment.user'),
		);
	}
}

class AttachmentPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'Attachment #{sn}',
			'filename' => 'example.png',
			'user' => Phactory::has_a('user'),
			'timecreated' => 1234567890,
			'mimetype' => 'image/png',
			'attachmentkey' => 'tmp',
			'filesize' => 1024,
			'filehash' => 'myfakehash',
		);
	}
}