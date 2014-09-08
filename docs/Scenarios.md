# Scenarios

Scenarios are a way you can bundle different Phactory generated objects that
you use to test together in multiple places accross your tests.

Here's the basic usage of a scenario:

```php
<?php

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
}

```

If we then wanted to create an instance of a scenario, we would do this:

```php
<?php

$scenario = Phactory::orderScenario();

$scenario->order->getTotalAmmount(); // 4.00

$scenario->cancelledItem->cancelled = false;

$scenario->order->getTotalAmmount(); // 6.00

```

## Variations

As when I work with Phactory objects, scenarios also support variations:

```php
<?php

class OrderScenario
{
    // ... blueprint method... //

    public function cancelled()
    {
        $this->order->cancelled = true;
    }
}

```

Now we can call this:

```php
<?php

$scenario = Phactory::orderScenario('cancelled');

$scenario->order->getTotalAmmount(); // 0.00

```

