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
        $variation = $this->getVariation($type);

        $blueprint = array_merge($base, $variation, $override);

        return new Blueprint($this->name, $type, $blueprint, $this->isFixture($type));
    }

    private function getVariation($type)
    {
        if ($type == 'blueprint') {
            return array();
        } elseif (method_exists($this->factory, "{$type}Fixture")) {
            return call_user_func(array($this->factory, "{$type}Fixture"));
        } elseif (method_exists($this->factory, $type)) {
            return call_user_func(array($this->factory, $type));
        } else {
            throw new \BadMethodCallException("No such variation '$type' on " . get_class($this->factory));
        }
    }

    private function isFixture($type)
    {
        return method_exists($this->factory, "{$type}Fixture");
    }
}
