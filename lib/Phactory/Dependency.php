<?php

namespace Phactory;

class Dependency
{
	private $dependency;
	private $property;

	public function __construct($dependency)
	{
		$this->dependency = $dependency;
	}

	public function meet($blueprint)
	{
		$parts = explode('.', $this->dependency);

		return $this->get($parts, $blueprint);
	}

	private function get($parts, $subject)
	{
		$part = array_shift($parts);

		if (method_exists($subject, $part))
			$value = call_user_func(array($subject, $part));
		elseif (is_array($subject) && isset($subject[$part]))
			$value = $subject[$part];
		elseif (is_object($subject) && isset($subject->$part))
			$value = $subject->$part;
		else
			throw new \Exception("");

		if (count($parts) == 0)
			return $value;
		else
			return $this->get($parts, $value);
	}
}