<?php

namespace Phactory;

class Fixtures
{
    private $fixtures = array();

    public function hasFixture($name, $type)
    {
        return isset($this->fixtures["{$name}-{$type}"]) && $type != 'blueprint';
    }

    public function getFixture($name, $type)
    {
        return $this->fixtures["{$name}-{$type}"];
    }

    public function setFixture($name, $type, $object)
    {
        $this->fixtures["{$name}-{$type}"] = $object;
    }

    public function isFixture()
    {
        return preg_match('\w*Fixture', $type);
    }
}
