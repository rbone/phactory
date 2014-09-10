<?php

class UnsavedTest extends PHPUnit_Framework_TestCase
{

    public function setup()
    {
        Phactory::reset();
        Phactory::builder(new OreBuilder);
    }

    public function testBasicCreate()
    {
        $this->assertTrue(Phactory::ore()->saved);
        $this->assertFalse(Phactory::unsavedOre()->saved);
    }

    public function testRelationships()
    {
        $this->assertTrue(Phactory::ore()->mine->saved);
        $this->assertFalse(Phactory::unsavedOre()->mine->saved);
    }
}

class OreBuilder extends \Phactory\Builder
{
    public function toObject($name, $blueprint)
    {
        return (object) $blueprint;
    }

    public function saveObject($name, $object)
    {
        $object->saved = true;

        return $object;
    }
}

class MinePhactory
{
    public function blueprint()
    {
        return array(
            'owner' => 'Mr. Number #{sn}',
            'saved' => false,
        );
    }
}

class OrePhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'Ore #{sn}',
            'mine' => Phactory::hasOne('mine'),
            'saved' => false,
        );
    }
}

