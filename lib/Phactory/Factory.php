<?php

namespace Phactory;

class Factory
{
	private $factory;
	private $name;

	public function __construct($name, $factory)
	{
		$this->factory = $factory;
		$this->name = $name;
	}

	public function create($type, $override)
	{
		$base = $this->factory->blueprint();
		$variation = $this->get_variation($type);

		$blueprint = array_merge($base, $variation, $override);

		return new Blueprint($this->name, $type, $blueprint, $this->is_fixture($type));
	}

	private function get_variation($type)
	{
		if ($type == 'blueprint')
			return array();
		else if (method_exists($this->factory, "{$type}_fixture"))
			return call_user_func(array($this->factory, "{$type}_fixture"));
		else if (method_exists($this->factory, $type))
			return call_user_func(array($this->factory, $type));
		else
			throw new \BadMethodCallException("No such variation '$type' on ".get_class($this->factory));
	}

	private function is_fixture($type)
	{
		return method_exists($this->factory, "{$type}_fixture");
	}
}