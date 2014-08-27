# The Basics

Phactory is a lightweight, extensible library for creating test data. Inspired by Machinist, 
Phactory works by defining blueprints from which objects can be created.

Here's what a basic Phactory looks like:

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

If we then wanted to create an instance of a user object we would do this:

```php
<?php

$user = Phactory::user();

echo $user->name; // "Example User"
echo $user->email; // "user0001@example.org"

```

Pretty simple no?

There are a few basic conventions to adhere to. Phactories should be named '{name}Phactory', and 
all Phactories must implement the blueprint method.

## Variations

What if you need a slightly different type of object, say an admin user for example?

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

Now if we call this:

```php
<?php

$user = Phactory::user('admin');

echo $user->name; // "Example User"
echo $user->email; // "user0001@example.org"
echo $user->admin ? 'an admin' : 'not an admin'; // 'an admin'

```

we see an admin user has been created. Not only that but it has inherited all of the properties
from the base blueprint.

## Overrides

So all of this is great, but sometimes you've got a test that needs something a little bit
special, for these cases you can override the blueprint properties without needing to modify
our Phactory:

```php
<?php

$user = Phactory::user(array(
    'name' => 'Fronzel Neekburm',
));

echo $user->name; // "Fronzel Neekburm"
echo $user->email; // "user0001@example.org"

```

Naturally you can also combine variations and overrides like so:

```php
<?php

$user = Phactory::user('admin', array(
    'email' => 'admin@example.org',
));

echo $user->name; // "Example User"
echo $user->email; // "admin@example.org"
echo $user->admin ? 'an admin' : 'not an admin'; // 'an admin'

```


