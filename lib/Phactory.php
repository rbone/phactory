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

	public static function has_a($name, $arguments = array())
	{
		$arguments = func_get_args();
		array_shift($arguments);

		list($type, $override) = self::resolve_args($arguments);

		return new HasOneRelationship($name, $type, $override);
	}

	public function belongs_to($name, $arguments = array())
	{
		$arguments = func_get_args();
		array_shift($arguments);

		list($type, $override) = self::resolve_args($arguments);

		return new BelongsToRelationship($name, $type, $override);
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

	public static function get_blueprint($name, $type, $override)
	{
		$factory = self::loader()->load($name);

		if ($type != 'blueprint')
			$blueprint = array_merge($factory->blueprint(), $factory->$type(), $override);
		else
			$blueprint = array_merge($factory->blueprint(), $override);

		return new Blueprint($name, $blueprint);
	}

	private static function loader()
	{
		return isset(self::$loader) ? self::$loader : self::$loader = new Loader;
	}

	private static function builder()
	{
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