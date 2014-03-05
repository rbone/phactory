<?php

namespace Phactory;

class Builder
{
    private $count = array();

    public function create($blueprint)
    {
        $name = $blueprint->name;
        $type = $blueprint->type;
        if (!isset($this->count[$name])) {
            $this->count[$name] = 0;
        }

        $count = ++$this->count[$name];

        $self = $this;

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

    protected function toObject($name, $values)
    {
        return (object) $values;
    }

    protected function saveObject($name, $object)
    {
        return $object;
    }
}
