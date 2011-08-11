<?php

use \Phactory\Blueprint;
use \Phactory\HasOneRelationship;
use \Phactory\BelongsToRelationship;
use \Phactory\Dependancy;
use \Phactory\DefaultBuilder;
use \Phactory\Loader;

class Phactory
{
	private static $loader;
	private static $builder;

	public static function reset()
	{
		self::$loader = new Loader;
		self::$builder = new DefaultBuilder;
	}

	public static function factory($name, $class)
	{
		self::loader()->factory($name, $class);
	}

	public static function has_one($name, $arguments = array())
	{
		$arguments = func_get_args();
		array_shift($arguments);

		list($type, $override) = self::resolve_args($arguments);

		return new HasOneRelationship($name, $type, $override);
	}

	public function uses($dependancy)
	{
		return new Dependancy($dependancy);
	}

	public static function __callStatic($name, $arguments = array())
	{
		list($type, $override) = self::resolve_args($arguments);

		$blueprint = self::get_blueprint($name, $type, $override);

		return self::builder()->create($blueprint);
	}

	public static function get_blueprint($name, $type, $override=array())
	{
		$factory = self::loader()->load($name);

		$blueprint = $factory->blueprint();

		if (!is_array($blueprint))
			throw new Exception("Phactory $name:$type did not return an array as needed");

		if ($type != 'blueprint')
			$blueprint = array_merge($blueprint, $factory->$type(), $override);
		else
			$blueprint = array_merge($blueprint, $override);

		return new Blueprint($name, $blueprint);
	}

	private static function loader()
	{
		return isset(self::$loader) ? self::$loader : self::$loader = new Loader;
	}

	public static function builder($builder=null)
	{
		if (is_object($builder))
			self::$builder = $builder;

		return isset(self::$builder) ? self::$builder : self::$builder = new DefaultBuilder;
	}

	private static function resolve_args($args)
	{
		$type = 'blueprint';
		$override = array();

		if (count($args) == 2)
		{
			$type = $args[0] ?: 'blueprint';
			$override = $args[1] ?: array();
		}
		else if (count($args) == 1)
		{
			if (is_string($args[0]))
				$type = $args[0];
			else if (is_array($args[0]))
				$override = $args[0];
		}

		return array($type, $override);
	}
}
