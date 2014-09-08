# Cenários

Cenários são uma forma de agrupar diferentes objetos gerados pela Phactory que você
costuma testar em conjunto em vários lugares ao longo de seus testes.

Essa é a definição básica de um cenário:

```php
<?php

class OrderScenario
{
    public $order;
    public $cancelledItem;

    public function blueprint()
    {
        // Adiciona dois objetos ao cenário
        $this->order = Phactory::order();
        $this->cancelledItem = Phactory::item(array('ammount' => 2.00));

        $this->cancelledItem->cancelled = true;

        // Vincula os objetos
        $this->order->addItem($this->cancelledItem);
        $this->order->addItem(Phactory::item(array('ammount' => 1.00)));
        $this->order->addItem(Phactory::item(array('ammount' => 3.00)));
    }
}

```

Se nós quiséssemos criar uma nova instância de um cenário, faríamos assim:

```php
<?php

$scenario = Phactory::orderScenario();

$scenario->order->getTotalAmmount(); // 4.00

$scenario->cancelledItem->cancelled = false;

$scenario->order->getTotalAmmount(); // 6.00

```

## Variações

Assim como quando você trabalha com objetos de Phactory, os cenários também suportam variações:

```php
<?php

class OrderScenario
{
    // ... método blueprint... //

    public function cancelled()
    {
        $this->order->cancelled = true;
    }
}

```

Agora podemos chamar assim:

```php
<?php

$scenario = Phactory::orderScenario('cancelled');

$scenario->order->getTotalAmmount(); // 0.00

```

