<?php

namespace Phactory;

/**
 * Fixtures collection
 */
class Fixtures
{
    /**
     * Loaded fixtures
     * @var array
     */
    private $fixtures = array();

    /**
     * Verify if a fixture is defined
     * @param string $name factory name
     * @param string $type fixture name
     * @return boolean
     */
    public function hasFixture($name, $type)
    {
        return isset($this->fixtures["{$name}-{$type}"]) && $type != 'blueprint';
    }

    /**
     * Get a fixture
     * @param string $name factory name
     * @param string $type fixture name
     * @return array|object
     */
    public function getFixture($name, $type)
    {
        return $this->fixtures["{$name}-{$type}"];
    }

    /**
     * Set a fixture
     * @param string $name factory name
     * @param string $type fixture name
     * @param array|object $object blueprint
     */
    public function setFixture($name, $type, $object)
    {
        $this->fixtures["{$name}-{$type}"] = $object;
    }
}
