# O Básico

Phactory é uma biblioteca leve e extensível para criar dados de teste. Inspirada
na Machinist, a Phactory funciona criando-se blueprints a partir dos quais os
objetos são gerados.

Segue um exemplo de como é uma Phactory básica:

```php
<?php

class UserPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'Example User',
            'email' => 'user#{sn}@example.org',
        );
    }
}

```

Então se nós quisermos obter uma instância do objeto user, faríamos assim:

```php
<?php

$user = Phactory::user();

echo $user->name; // "Example User"
echo $user->email; // "user0001@example.org"

```

Muito simples, não?

Existem algumas convenções básicas que precisam ser seguidas. As phactories
devem ser chamadas de '{nome}Phactory', e todas devem implementar o método
blueprint.

## Variações

E se você precisar de um tipo de objeto ligeiramente diferente, digamos um
usuário administrador, por exemplo?

```php
<?php

class UserPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'Example User',
            'email' => 'user#{sn}@example.org',
        );
    }

    public function admin()
    {
        return array(
            'admin' => true,
        );
    }
}

```

Agora se chamarmos isso:

```php
<?php

$user = Phactory::user('admin');

echo $user->name; // "Example User"
echo $user->email; // "user0001@example.org"
echo $user->admin ? 'um admin' : 'não é um admin'; // 'um admin'

```

...veremos que um usuário admin foi criado. Não apenas isso, ele também herdou
todas as propriedades do blueprint base.

## Substituição

Isso é ótimo, mas as vezes você tem um teste que precisa de algo um pouquinho
mais especial. Para estes casos, você pode substituir as propriedades do blueprint
sem precisar modificar a sua Phactory:

```php
<?php

$user = Phactory::user(array(
    'name' => 'Fronzel Neekburm',
));

echo $user->name; // "Fronzel Neekburm"
echo $user->email; // "user0001@example.org"

```

Naturalmente, você pode combinar variações e substituições da seguinte forma:

```php
<?php

$user = Phactory::user('admin', array(
    'email' => 'admin@example.org',
));

echo $user->name; // "Example User"
echo $user->email; // "admin@example.org"
echo $user->admin ? 'um admin' : 'não é um admin'; // 'um admin'

```


