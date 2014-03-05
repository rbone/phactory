<?php

namespace Phactory;

use Phactory\HasOneRelationship;
use Phactory\Dependency;

class Blueprint
{
    public $name;
    public $type;
    private $blueprint;
    private $isFixture;

    public function __construct($name, $type, $blueprint, $isFixture = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->blueprint = $blueprint;
        $this->isFixture = $isFixture;
    }

    public function values()
    {
        return array_filter($this->blueprint, function ($value) {
            return !is_string($value) && !$value instanceof HasOneRelationship && !$value instanceof Dependency;
        });
    }

    public function strings()
    {
        return array_filter($this->blueprint, function ($value) {
            return is_string($value);
        });
    }

    public function relationships()
    {
        return array_filter($this->blueprint, function ($value) {
            return $value instanceof HasOneRelationship;
        });
    }

    public function dependencies()
    {
        return array_filter($this->blueprint, function ($value) {
            return $value instanceof Dependency;
        });
    }

    public function isFixture()
    {
        return $this->isFixture;
    }
}
