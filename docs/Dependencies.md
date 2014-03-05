# Dependencies

Sometimes one Phactory needs to use the exact same value or object as another Phactory, for this you have dependencies:

```php
<?php

class PublisherPhactory
{
    public function blueprint()
    {
        return array(
            'creator' => Phactory::has_one('user'),
            'book' => Phactory::has_one('book'),
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
            'author' => Phactory::has_one('user'),
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