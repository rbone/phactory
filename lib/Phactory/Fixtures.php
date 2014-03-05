<?php

namespace Phactory;

class Fixtures
{
	private $fixtures = array();

	public function has_fixture($name, $type)
	{
		return isset($this->fixtures["{$name}-{$type}"]) && $type != 'blueprint';
	}

	public function get_fixture($name, $type)
	{
		return $this->fixtures["{$name}-{$type}"];
	}

	public function set_fixture($name, $type, $object)
	{
		$this->fixtures["{$name}-{$type}"] = $object;
	}

	public function is_fixture()
	{
		return preg_match('\w*Fixture', $type);
	}
}