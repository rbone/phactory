<?php

namespace Phactory;

class Loader
{
	private $factories;

	public function factory($name, $class)
	{
		$this->factories[$name] = $class;
	}

	public function load($name)
	{
		if (isset($this->factories[$name]))
			return new $this->factories[$name];

		$class = "{$name}Phactory";
		if (class_exists($class))
			return new $class;

		throw new Exception("Unknown factory '$name'");
	}
}