<?php

namespace Phactory;

class Blueprint
{
	public $name;
	private $blueprint;

	public function __construct($name, $blueprint)
	{
		$this->name = $name;
		$this->blueprint = $blueprint;
	}

	public function values()
	{
		return $this->blueprint;
	}
}