# Relationships

Objects don't live in vacuum, inevitably one object will depend on the existence of another, that's where relationships come in. Implementing a relationship is easy:

```php
<?php

class BookPhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'Food goes in here',
            'author' => Phactory::has_one('author'),
        );
    }
}

class AuthorPhactory
{
    public function blueprint()
    {
        return array(
            'name' => 'Fronzel Neekburm',
        );
    }
}

```

As you can see implementing a relationship is just as easy as writing `Phactory::has_one('type')`. 
All of the usual features of Phactory are available to relationships as well, variations and overrides
may be used by passing in a second or third argument.
