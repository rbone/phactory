<?php

namespace Phactory;

/**
 * Convert blueprint arrays into actual objects. This class will convert all
 * blueprints into PHP stdClass objects, and won't persist any objects. You
 * must override it in order to implement the necessary behaviour.
 */
class Builder
{
    /**
     * Auto-incrementable key for objects
     * @var array
     */
    private $count = array();

    /**
     * Converts a blueprint array into a persisted object
     * @param array $blueprint
     * @return object
     */
    public function create($blueprint)
    {
        $name = $blueprint->name;
        $type = $blueprint->type;
        if (!isset($this->count[$name])) {
            $this->count[$name] = 0;
        }

        $count = ++$this->count[$name];

        $values = $blueprint->values();

        $strings = array_map(function ($value) use ($count) {
            return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
        }, $blueprint->strings());

        $relationships = array_map(function ($value) {
            return $value->create();
        }, $blueprint->relationships());

        $values = array_merge($values, $strings, $relationships);

        $dependencies = array_map(function ($value) use ($values) {
            return $value->meet($values);
        }, $blueprint->dependencies());

        $values = array_merge($values, $dependencies);

        $object = $this->toObject($name, $values);

        \Phactory::triggers()->beforeSave($name, $type, $object);

        $object = $this->saveObject($name, $object);

        \Phactory::triggers()->afterSave($name, $type, $object);

        return $object;
    }

    /**
     * Convert an array into an object
     * @param string $name factory name
     * @param array $values attributes values
     * @return object
     */
    protected function toObject($name, $values)
    {
        return (object) $values;
    }

    /**
     * Persist an object
     * @param string $name factory name
     * @param object $object object
     * @return object
     */
    protected function saveObject($name, $object)
    {
        return $object;
    }
}
