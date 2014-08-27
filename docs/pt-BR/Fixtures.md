# Fixtures 

Às vezes você quer o mesmo objeto sendo retornado todas as vezes. Para estes
casos, a Phactory suporta uma sintaxe especial para fixtures.

Suponha que você tenha a seguinte phactory:

```php
<?php

class CategoriaPhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'Categoria #{sn}',
            'description' => 'Description #{sn}',
            'key' => 'categoria#{sn}',
        );
    }

    public function camiseta()
    {
        return array(
            'title' => 'Categoria Camiseta',
            'key' => 'camiseta',
        );
    }
}

```

Então se nós tentássemos obter duas categorias de camisetas, veríamos isso:

```php
<?php

$one = Phactory::categoria('camiseta');
$two = Phactory::categoria('camiseta');

echo $one === $two ? 'mesmo objeto' : 'objetos diferentes';
// printa 'objetos diferentes'

```

Toda vez que você chamar `Phactory::categoria('camiseta')` você obterá uma nova
instância. Para fazer com que ela retorne a mesma instância todas as vezes,
você terá que alterá-la da seguinte forma:

```php
<?php

class CategoriaPhactory
{
    //...
    public function camisetaFixture()
    {
        return array(
            'title' => 'Categoria Camiseta',
            'key' => 'camiseta',
        );
    }
    //...
}

```

Agora se testarmos os resultados novamente, acontecerá isso:

```php
<?php

$one = Phactory::categoria('camiseta');
$two = Phactory::categoria('camiseta');

echo $one === $two ? 'mesmo objeto' : 'objetos diferentes';
// printa 'mesmo objeto'

```

Tudo o que fizemos foi adicionar o sufixo Fixture ao método camiseta, mas agora
ele sempre retornará a mesma instância. Perceba que isso só funciona com variações
do blueprint, se você fosse definir um método `blueprintFixture` a sua Phactory
não funcionaria.
