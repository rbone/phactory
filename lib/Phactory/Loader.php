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
		$class = ucfirst($name)."Phactory";

		if (!class_exists($class))
			throw new \Exception("Unknown factory '$name'");

		$factory = new Factory($name, new $class);

		return $factory;
	}
}
