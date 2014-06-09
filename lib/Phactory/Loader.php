<?php

namespace Phactory;

/**
 * Default factory loader. Loads the factory class according to the object class
 * name. For example: a Phactory::user() call would load UserPhactory.
 */
class Loader
{
    /**
     * Loads the factory according to the object class name
     * @param string $name
     * @return \Phactory\Factory
     * @throws \Exception
     */
    public function load($name)
    {
        $factoryClass = ucfirst($name) . "Phactory";

        if (!class_exists($factoryClass)) {
            throw new \Exception("Unknown factory '$name'");
        }

        return new Factory($name, new $factoryClass);
    }
}
