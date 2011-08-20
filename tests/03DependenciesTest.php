<?php

class DependenciesTest extends PHPUnit_Framework_TestCase
{
	public function testDependency()
	{
		Phactory::reset();
		$entry = Phactory::entry();

		$this->assertSame($entry->designer, $entry->design->designer);
	}

	public function testMultiLevelDependencyWithMethodCalls()
	{
		Phactory::reset();
		Phactory::builder(new TestBuilder);
		$entry = Phactory::entry();

		$this->assertSame($entry->designer, $entry->design->attachment->creator());
	}
}

class EntryPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'Food goes in here',
			'designer' => Phactory::uses('design.attachment.creator'),
			'design' => Phactory::has_one('design'),
		);
	}
}

class DesignPhactory
{
	public function blueprint()
	{
		return array(
			'type' => 'jpg',
			'path' => '/some/place/elsewhere.jpg',
			'attachment' => Phactory::has_one('attachment'),
			'designer' => Phactory::uses('attachment.creator'),
		);
	}
}

class AttachmentPhactory
{
	public function blueprint()
	{
		return array(
			'type' => 'jpg',
			'content' => '@^&#$^#@&*$',
			'creator' => Phactory::has_one('designer'),
		);
	}
}

class DesignerPhactory
{
	public function blueprint()
	{
		return array(
			'first_name' => 'Fronzel',
			'last_name' => 'Neekburm',
			'email' => 'user#{sn}@example.org',
		);
	}
}

class TestBuilder extends \Phactory\Builder
{
	protected function to_object($name, $values)
	{
		if ($name == 'attachment')
			return new Attachment($values);
		else
			return (object)$values;
	}
}

class Attachment
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function type() { return $this->data['type']; }
	public function content() { return $this->data['content']; }
	public function creator() { return $this->data['creator']; }

}