<?php

class ScenariosTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Phactory::reset();
        Phactory::builder(new ScenarioBuilder);
    }

    public function testScenario()
    {
        $scenario = Phactory::orderScenario();

        $order = $scenario->order;
        $cancelledItem = $scenario->cancelledItem;

        $this->assertEquals(4.00, $order->getTotalAmmount());

        $cancelledItem->cancelled = false;

        $this->assertEquals(6.00, $order->getTotalAmmount());
    }
    
    public function testScenarioType()
    {
        $scenario = Phactory::orderScenario('cancelled');

        $order = $scenario->order;

        $this->assertEquals(0.00, $order->getTotalAmmount());

        $order->cancelled = false;

        $this->assertEquals(4.00, $order->getTotalAmmount());
    }
    
    public function testScenarioOverrides()
    {
        $this->setExpectedException('Exception');

        $scenario = Phactory::orderScenario(array(
            'cancelledItem' => Phactory::item(array('ammount' => 10.00))
        ));
    }
}

class ScenarioBuilder extends \Phactory\Builder
{
    public function toObject($name, $blueprint)
    {
        $className = ($name == 'order') ? 'Order' : 'Item';

        $object = new $className;
        foreach ($blueprint as $attribute => $value) {
            $object->$attribute = $value;
        }

        return $object;
    }
}

class Order
{
    public $number;
    public $items = array();
    public $cancelled = false;

    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    public function getTotalAmmount()
    {
        $ammount = 0.0;

        if (!$this->cancelled) {

            foreach ($this->items as $item) {

                if ($item->cancelled) {
                    continue;
                }
                $ammount += $item->ammount;
            }
        }

        return $ammount;
    }
}

class Item
{
    public $cancelled = false;
    public $description;
    public $ammount;
}

class OrderPhactory
{
    public function blueprint()
    {
        return array(
            'number' => '#{sn}'
        );
    }
}

class ItemPhactory
{
    public function blueprint()
    {
        return array(
            'number' => '#{sn}'
        );
    }
}

class OrderScenario
{
    public $order;
    public $cancelledItem;

    public function blueprint()
    {
        // Add two objects to the scenario
        $this->order = Phactory::order();
        $this->cancelledItem = Phactory::item(array('ammount' => 2.00));

        $this->cancelledItem->cancelled = true;

        // Link objects
        $this->order->addItem($this->cancelledItem);
        $this->order->addItem(Phactory::item(array('ammount' => 1.00)));
        $this->order->addItem(Phactory::item(array('ammount' => 3.00)));
    }

    public function cancelled()
    {
        $this->order->cancelled = true;
    }
}