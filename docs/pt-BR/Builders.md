# Builders

Direto da caixa, a Phactory é bastante limitada. Os objetos que ela cria são
simples objetos do tipo stdClass que funcionam bem nas demonstrações, mas não
são o que você quer quando a sua aplicação estiver usando um ORM sofisticado.
Felizmente, a Phactory oferece uma forma fácil de sobrescrever o comportamento
padrão.

Primeiramente, crie a sua própria classe builder da seguinte forma:

```php
<?php

class CustomBuilder extends \Phactory\DefaultBuilder
{
    protected function toObject($name, $blueprint)
    {
        $object = new CustomObject($name);

        foreach ($blueprint as $key => $value) {
            $object->$key = $value;
        }
        
        return $object;
    }
}

```

Tendo feito isso, diga à Phactory para usá-la:

```php
<?php

Phactory::builder(new CustomBuilder());

```

Agora todos os blueprints serão criados usando a classe CustomBuilder.

