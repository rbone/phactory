<?php

use \Phactory\HasOneRelationship;
use \Phactory\Dependency;
use \Phactory\Builder;
use \Phactory\Loader;
use \Phactory\Fixtures;
use \Phactory\Triggers;

/**
 * Base Phactory class.
 * @see https://github.com/alanwillms/phactory/tree/master/docs
 */
class Phactory
{
    /**
     * Factory loader
     * @var object
     */
    private static $loader;

    /**
     * Object builder
     * @var object
     */
    private static $builder;

    /**
     * Fixtures collection
     * @var object
     */
    private static $fixtures;

    /**
     * Triggers caller
     * @var object
     */
    private static $triggers;

    /**
     * Reset all Phactory settings to default
     */
    public static function reset()
    {
        self::$loader = null;
        self::$builder = null;
        self::$fixtures = null;
        self::$triggers = null;
    }

    /**
     * Define an attribute that requires another factory generated object
     * @param string $name factory name
     * @param string $type (OPTIONAL) variation or fixture name
     * @param array $override (OPTIONAL) attribute values to be overriden
     * @return \Phactory\HasOneRelationship
     */
    public static function hasOne($name, $arguments = array())
    {
        $arguments = func_get_args();
        array_shift($arguments);

        list($type, $override) = self::resolveArgs($arguments);

        return new HasOneRelationship($name, $type, $override);
    }

    /**
     * Define an attribute dependency on another factory generated object which
     * must be the same for both objects. I.e., comment.author must be the same
     * as comment.topic.author if you are creating the topic first comment.
     * @param string $dependancy dependency path, i.e., "topic.author"
     * @return \Phactory\Dependency
     */
    public static function uses($dependancy)
    {
        return new Dependency($dependancy);
    }

    /**
     * Undefined static methods calls will try to load factory objects.
     * For example, the method "Phactory::user()" is not defined, so the class
     * will try to load an user factory and return an user object.
     * @param string $name method name, which will map to a factory name
     * @param array $arguments
     * @return type
     */
    public static function __callStatic($name, $arguments = array())
    {
        list($type, $override) = self::resolveArgs($arguments);

        $persisted = true;

        if (strlen($name) > 8 && substr($name, 0, 7) == 'unsaved') {
            $persisted = false;
            $name = substr($name, 7);
        }

        return self::createBlueprint($name, $type, $override, $persisted);
    }

    /**
     * Loads, prepare, persist and return a factory generated object
     * @param string $name factory name
     * @param string $type variation or fixture name
     * @param array $override overriden attributes values
     * @param boolean $persisted wether it will save the object or not
     * @return object|array
     */
    public static function createBlueprint($name, $type, $override = array(), $persisted = true)
    {
        if (self::fixtures()->hasFixture($name, $type)) {
            return self::fixtures()->getFixture($name, $type);
        }

        $blueprint = self::getBlueprint($name, $type, $override, $persisted);

        $object = self::builder()->create($blueprint);

        if ($blueprint->isFixture()) {
            self::fixtures()->setFixture($name, $type, $object);
        }

        return $object;
    }

    /**
     * Returns an object blueprint
     * @param string $name factory name
     * @param string $type variation or fixture name
     * @param array $override overriden attributes values
     * @param boolean $persisted wether it will save the object or not
     * @return \Phactory\Blueprint
     */
    public static function getBlueprint($name, $type, $override = array(), $persisted = true)
    {
        $factory = self::loader()->load($name);

        return $factory->create($type, $override, $persisted);
    }

    /**
     * Get factory loader. If it is not defined, it will be set.
     * @param object|null $loader
     * @return \Phactory\Loader
     */
    public static function loader($loader = null)
    {
        if (is_object($loader)) {
            self::$loader = $loader;
        }

        return isset(self::$loader) ? self::$loader : self::$loader = new Loader;
    }

    /**
     * Get object builder. If it is not defined, it will be set.
     * @param object|null $builder
     * @return \Phactory\Builder
     */
    public static function builder($builder = null)
    {
        if (is_object($builder)) {
            self::$builder = $builder;
        }

        return isset(self::$builder) ? self::$builder : self::$builder = new Builder;
    }

    /**
     * Get fixtures manager. If it is not defined, it will be set.
     * @return \Phactory\Fixtures
     */
    public static function fixtures()
    {
        return isset(self::$fixtures) ? self::$fixtures : self::$fixtures = new Fixtures;
    }

    /**
     * Get triggers events caller. If it is not defined, it will be set.
     * @param object|null $triggers
     * @return \Phactory\Triggers
     */
    public static function triggers($triggers = null)
    {
        if (is_object($triggers)) {
            self::$triggers = new Triggers($triggers);
        }

        return isset(self::$triggers) ? self::$triggers : self::$triggers = new Triggers;
    }

    /**
     * Resolve factory arguments. For example:
     * <code>
     * Phactory::user(); // will return array('blueprint', array())
     * Phactory::user('admin'); // will return array('admin', array())
     * Phactory::user(array('name' => 'Karl')); // will return array('blueprint', array('name' => 'Karl'))
     * Phactory::user('admin', array('name' => 'Karl')); // will return array('admin', array('name' => 'Karl'))
     * </code>
     * @param array $args arguments
     * @return array
     */
    private static function resolveArgs($args)
    {
        $type = 'blueprint';
        $override = array();

        if (count($args) == 2) {
            $type = $args[0] ? : 'blueprint';
            $override = $args[1] ? : array();
        } elseif (count($args) == 1) {
            if (is_string($args[0])) {
                $type = $args[0];
            } elseif (is_array($args[0])) {
                $override = $args[0];
            }
        }

        return array($type, $override);
    }

    /**
     * Define an attribute that requires another factory generated object
     * @param string $name factory name
     * @param string $type (OPTIONAL) variation or fixture name
     * @param array $override (OPTIONAL) attribute values to be overriden
     * @return \Phactory\HasOneRelationship
     * @deprecated Backwards compatibility
     */
    public static function has_one()
    {
        return call_user_func_array('Phactory::hasOne', func_get_args());
    }

    /**
     * Loads, prepare, persist and return a factory generated object
     * @param string $name factory name
     * @param string $type variation or fixture name
     * @param array $override overriden attributes values
     * @return object|array
     * @deprecated Backwards compatibility
     */
    public static function create_blueprint($name, $type, $override = array())
    {
        return call_user_func_array('Phactory::createBlueprint', func_get_args());
    }

    /**
     * Returns an object blueprint
     * @param string $name factory name
     * @param string $type variation or fixture name
     * @param array $override overriden attributes values
     * @return \Phactory\Blueprint
     * @deprecated Backwards compatibility
     */
    public static function get_blueprint($name, $type, $override = array())
    {
        return call_user_func_array('Phactory::getBlueprint', func_get_args());
    }

    /**
     * Resolve factory arguments. For example:
     * <code>
     * Phactory::user(); // will return array('blueprint', array())
     * Phactory::user('admin'); // will return array('admin', array())
     * Phactory::user(array('name' => 'Karl')); // will return array('blueprint', array('name' => 'Karl'))
     * Phactory::user('admin', array('name' => 'Karl')); // will return array('admin', array('name' => 'Karl'))
     * </code>
     * @param array $args arguments
     * @return array
     * @deprecated Backwards compatibility
     */
    private static function resolve_args($args)
    {
        return call_user_func_array('Phactory::resolveArgs', func_get_args());
    }
}
