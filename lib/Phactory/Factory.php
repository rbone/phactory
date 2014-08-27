<?php

namespace Phactory;

/**
 * Prepare blueprints for a given object type
 */
class Factory
{
    /**
     * Factory object
     * @var object
     */
    private $factory;

    /**
     * Factory name
     * @var string
     */
    private $name;

    /**
     * Constructor
     * @param string $name factory name
     * @param object $factory factory object
     */
    public function __construct($name, $factory)
    {
        $this->factory = $factory;
        $this->name = $name;
    }

    /**
     * Creates a new blueprint
     * @param string $type variation or fixture
     * @param array $override attributes values overrides
     * @return \Phactory\Blueprint
     */
    public function create($type, $override)
    {
        $base = $this->factory->blueprint();
        $variation = $this->getVariation($type);

        $blueprint = array_merge($base, $variation, $override);

        return new Blueprint($this->name, $type, $blueprint, $this->isFixture($type));
    }

    /**
     * Applies a variation to the basic blueprint or calls the fixture
     * @param string $type variation or fixture
     * @return array
     * @throws \BadMethodCallException
     */
    private function getVariation($type)
    {
        if ($type == 'blueprint') {
            return array();
        } elseif (method_exists($this->factory, "{$type}Fixture")) {
            return call_user_func(array($this->factory, "{$type}Fixture"));
        } elseif (method_exists($this->factory, "{$type}_fixture")) { // @deprecated Backwards compatibility
            return call_user_func(array($this->factory, "{$type}_fixture"));
        } elseif (method_exists($this->factory, $type)) {
            return call_user_func(array($this->factory, $type));
        } else {
            throw new \BadMethodCallException("No such variation '$type' on " . get_class($this->factory));
        }
    }

    /**
     * Whether the variation is a fixture or not
     * @param string $type variation name
     * @return boolean
     */
    private function isFixture($type)
    {
        if (method_exists($this->factory, "{$type}_fixture")) { // @deprecated Backwards compatibility
            return true;
        }
        return method_exists($this->factory, "{$type}Fixture");
    }
}
