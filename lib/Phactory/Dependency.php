<?php

namespace Phactory;

class Dependency
{
	private $class;
	private $property;

	public function __construct($dependency)
	{
		list($class, $property) = explode('.', $dependency);
		$this->class = $class;
		$this->property = $property;
	}

	public function met($blueprint)
	{
		$class = $this->class;
		$property = $this->property;
		return isset($blueprint[$class]) && isset($blueprint[$class]->$property);
	}

	public function meet($blueprint)
	{
		$class = $this->class;
		$property = $this->property;
		return $blueprint[$class]->$property;
	}
}