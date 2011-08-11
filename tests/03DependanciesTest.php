<?php

class DependanciesTest extends PHPUnit_Framework_TestCase
{
	public function testDependancy()
	{
		Phactory::reset();
		$entry = Phactory::entry();

		$this->assertSame($entry->designer, $entry->design->designer);
	}
}

class EntryPhactory
{
	public function blueprint()
	{
		return array(
			'title' => 'Food goes in here',
			'designer' => Phactory::uses('design.designer'),
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
			'designer' => Phactory::has_one('designer'),
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