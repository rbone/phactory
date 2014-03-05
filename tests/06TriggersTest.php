<?php

class TriggersTest extends PHPUnit_Framework_TestCase
{
    public function testTriggersOnBaseBlueprint()
    {
        Phactory::reset();
        Phactory::triggers(new FrameworkTriggers);

        $account = Phactory::account();

        $this->assertEquals($account->id, 10000);
        $this->assertTrue($account->beforeSave);
        $this->assertTrue($account->afterSave);
    }

    public function testTriggersWithVariation()
    {
        Phactory::reset();
        Phactory::triggers(new FrameworkTriggers);

        $account = Phactory::account('system');

        $this->assertEquals($account->id, 1);
        $this->assertTrue($account->beforeSave);
        $this->assertTrue($account->afterSave);
        $this->assertTrue($account->systemBeforeSave);
        $this->assertTrue($account->systemAfterSave);
    }
}

class AccountPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'User #{sn}',
        );
    }

    public function systemFixture()
    {
        return array(
            'name' => 'System',
        );
    }
}

class FrameworkTriggers
{
    private $sequences = 10000;

    public function accountBeforeSave($object)
    {
        $object->id = $this->sequences++;
        $object->beforeSave = true;
    }

    public function accountAfterSave($object)
    {
        $object->afterSave = true;
    }

    public function accountSystemBeforeSave($object)
    {
        $object->id = 1;
        $object->systemBeforeSave = true;
    }

    public function accountSystemAfterSave($object)
    {
        $object->systemAfterSave = true;
    }
}
