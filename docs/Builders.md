# Builders

Out of the box, Phactory is quite limited. The objects it creates are simple stdClass objects 
which work well for demo's, but aren't what you want when your app is using a sophisticated
ORM. Fortunately, Phactory provides an easy way to override the default behaviour.

First create your own builder class like so:

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

    protected function saveObject($name, $object)
    {
        $object->save();

        return $object;
    }
}

```

Then with that in place, tell Phactory to use it:

```php
<?php

Phactory::builder(new CustomBuilder());

```

All blueprints will now be created using the CustomBuilder class.

## Non-persisted objects

Sometimes you don't need to persist the object in the database to perform your
tests. If you want to get an object skipping the `saveObject` step, you can simply call:

```php
$user = Phactory::unsavedUser();
```
