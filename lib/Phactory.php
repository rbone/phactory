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

		$type = 'blueprint';
		$override = array();

		if (count($arguments) == 2)
		{
			$type = $arguments[0];
			$override = $arguments[1];
		}
		else if (count($arguments) == 1)
		{
			if (is_string($arguments[0]))
				$type = $arguments[0];
			else if (is_array($arguments[0]))
				$override = $arguments[0];
		}

		if ($type != 'blueprint')
			$blueprint = array_merge($factory->blueprint(), $factory->$type(), $override);
		else
			$blueprint = array_merge($factory->blueprint(), $override);


		$count = ++self::$count;
		$blueprint = array_map(function ($value) use ($count) {
			if (is_string($value))
				return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
			else
				return $value;
		}, $blueprint);

		return (object)$blueprint;
	}
}