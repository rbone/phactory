# Dependências

Às vezes uma Phactory precisa usar exatamente o mesmo valor ou objeto de outra
Phactory, para isso você tem as dependências:

```php
<?php

class PublisherPhactory
{
    public function blueprint()
    {
        return array(
            'creator' => Phactory::hasOne('user'),
            'book' => Phactory::hasOne('book'),
        );
    }

    public function selfpublished()
    {
        return array(
            'creator' => Phactory::uses('book.author'),
        );
    }
}

class BookPhactory
{
    public function blueprint()
    {
        return array(
            'author' => Phactory::hasOne('user'),
        );
    }
}

class UserPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'person#{sn}',
        );
    }
}

$publisher = Phactory::publisher('selfpublished');

var_dump($publisher->creator === $publisher->book->author); // true
```