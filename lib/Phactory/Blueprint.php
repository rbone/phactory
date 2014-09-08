<?php

namespace Phactory;

use Phactory\HasOneRelationship;
use Phactory\Dependency;

/**
 * Represents the blueprint of an object
 */
class Blueprint
{
    /**
     * Factory name
     * @var string
     */
    public $name;

    /**
     * Variation applied
     * @var string
     */
    public $type;

    /**
     * Blueprint data (may have a variation applied)
     * @var array
     */
    private $blueprint;

    /**
     * @var boolean
     */
    private $isFixture;

    /**
     * @var boolean
     */
    private $isScenario;

    /**
     * Constructor
     * @param string $name name of the factory, i.e., "user" in Phactory::user()
     * @param string $type variation, i.e. "admin" in Phactory::user('admin')
     * @param array $blueprint blueprint for the given variation
     * @param boolean $isFixture
     * @param boolean $isScenario
     */
    public function __construct($name, $type, $blueprint, $isFixture = false, $isScenario = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->blueprint = $blueprint;
        $this->isFixture = $isFixture;
        $this->isScenario = $isScenario;
    }

    /**
     * Return current blueprint values, including dependencies and relationships
     * @return array
     */
    public function values()
    {
        return array_filter($this->blueprint, function ($value) {
            return !is_string($value) && !$value instanceof HasOneRelationship && !$value instanceof Dependency;
        });
    }

    /**
     *  Return current blueprint values, only strings
     * @return array
     */
    public function strings()
    {
        return array_filter($this->blueprint, function ($value) {
            return is_string($value);
        });
    }

    /**
     * Return current blueprint values, only relationshops
     * @return HasOneRelationship[]
     */
    public function relationships()
    {
        return array_filter($this->blueprint, function ($value) {
            return $value instanceof HasOneRelationship;
        });
    }

    /**
     * Return current blueprint values, only dependencies
     * @return Dependency[]
     */
    public function dependencies()
    {
        return array_filter($this->blueprint, function ($value) {
            return $value instanceof Dependency;
        });
    }

    /**
     * Wether this is a fixture or not
     * @return boolean
     */
    public function isFixture()
    {
        return $this->isFixture;
    }

    /**
     * Wether this is a scenario or not
     * @return boolean
     */
    public function isScenario()
    {
        return $this->isScenario;
    }

    /**
     * Return the current scenario
     * @return object
     */
    public function getScenario()
    {
        if ($this->isScenario) {
            return $this->blueprint;
        }
    }

    /**
     * Wether this is a fixture or not
     * @return boolean
     * @deprecated Backwards compatibility
     */
    public function is_fixture()
    {
        return $this->isFixture;
    }
}
