<?php

class Phactory
{
	private static
		$factories = array(),
		$count = 0;

	public static function reset()
	{
		self::$factories = array();
		self::$count = 0;
	}

	public static function factory($name, $class)
	{
		self::$factories[$name] = $class;
	}

	public static function __callStatic($name, $arguments)
	{
		if (!isset(self::$factories[$name]))
			throw new BadMethodCallException("No such method $name");

		$class = self::$factories[$name];
		$factory = new $class;

		$blueprint = $factory->blueprint();
		$count = ++self::$count;
		$blueprint = array_map(function ($value) use ($count) {
			return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
		}, $blueprint);

		return (object)$blueprint;
	}
}