<?php

use \Phactory\Blueprint;
use \Phactory\HasOneRelationship;
use \Phactory\BelongsToRelationship;
use \Phactory\Dependancy;
use \Phactory\Builder;
use \Phactory\Loader;
use \Phactory\Fixtures;
use \Phactory\Triggers;

class Phactory
{
	private static $loader;
	private static $builder;
	private static $fixtures;
	private static $triggers;

	public static function reset()
	{
		self::$loader = null;
		self::$builder = null;
		self::$fixtures = null;
		self::$triggers = null;
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

		if (self::fixtures()->has_fixture($name, $type))
			return self::fixtures()->get_fixture($name, $type);

		$blueprint = self::get_blueprint($name, $type, $override);

		$object = self::builder()->create($blueprint);

		if ($blueprint->is_fixture())
			self::fixtures()->set_fixture($name, $type, $object);

		return $object;
	}

	public static function get_blueprint($name, $type, $override=array())
	{
		$factory = self::loader()->load($name);

		return $factory->create($type, $override);
	}

	private static function loader()
	{
		return isset(self::$loader) ? self::$loader : self::$loader = new Loader;
	}

	public static function builder($builder=null)
	{
		if (is_object($builder))
			self::$builder = $builder;

		return isset(self::$builder) ? self::$builder : self::$builder = new Builder;
	}

	public static function fixtures()
	{
		return isset(self::$fixtures) ? self::$fixtures : self::$fixtures = new Fixtures;
	}

	public function triggers($triggers=null)
	{
		if (is_object($triggers))
			self::$triggers = new Triggers($triggers);

		return isset(self::$triggers) ? self::$triggers : self::$triggers = new Triggers;
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
