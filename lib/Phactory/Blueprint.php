<?php

namespace Phactory;

class Blueprint
{
	public $name;
	public $type;
	private $blueprint;
	private $is_fixture;

	public function __construct($name, $type, $blueprint, $is_fixture=false)
	{
		$this->name = $name;
		$this->type = $type;
		$this->blueprint = $blueprint;
		$this->is_fixture = $is_fixture;
	}

	public function values()
	{
		return $this->blueprint;
	}

	public function is_fixture()
	{
		return $this->is_fixture;
	}
}