# Gatilhos

Os gatilhos servem para todos os efeito colaterais irritantes dos quais a sua
aplicação dependem que uma Phactory básica não consegue criar. Por exemplo,
imagine que você tenha essa UserPhactory:

```php
<?php

class UserPhactory
{
    public function blueprint()
    {
        return array(
            'fullname' => 'user#{sn}',
            'email' => 'user#{sn}@example.org',
        );
    }

    public function systemFixture()
    {
        return array(
            'fullname' => 'System User',
            'email' => 'no-reply@example.org'
        );
    }
}

```

Ela nos permite criar ou um usuário básico ou um usuário de sistema. No entanto,
a minha aplicação espera que o usuário de sistema tenha um userid de 1, mas é o
meu ORM quem determina qual objeto terá qual id e a Phactory não tem como influenciar
sobre seus blueprints, então não posso apenas definir 1 no userid do blueprint
sistema.

Existe um jeito de contornar isso, é aqui que entram os gatilhos. Os gatilhos
são observers básicos na Phactory que nos permitem saber quando um objeto está
para ser salvo, ou já foi salvo, e nos permite fazer algo. Eis como um
gatilho parece:

```php
<?php

class MyFrameworksTrigger
{
    private $sequences_incremented = false;

    public function userBeforeSave($user)
    {
        if (!$this->sequences_incremented)
        {
            // seta a sequence inicial do userid em 100000
            
            $this->sequences_incremented = true;
        }
    }

    public function userSystemAfterSave($user)
    {
        Db()->execute(
            'UPDATE user SET userid = 1 WHERE userid = ?',
            $user->userid
        );
        $user->userid = 1;
    }
}

```

E é registrada via:

```php
<?php

Phactory::trigger(new MyFrameworksTrigger);

```

Agora toda vez que eu criar um user, o gatilho verificará se a sequence foi alterada
para começar em 100000 ao invés de 1, e sempre que eu criar um usuário de sistema
(que seria uma fixture), o userid será reescrito como 1.

Isso funciona bem, mas não encorajamos que você use gatilhos se você não precisar,
porque eles são um grande hack para garantir que a Phactory continue
funcionando até mesmo ao lidar com as partes mais intrincadas do seu sistema
que possuem requerimentos incomuns.
