# Fixtures 

Sometimes you want the same object returned every time, for those cases phactory
supports a special syntax for fixtures

Suppose you had the following phactory:

```php
<?php

class CategoryPhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'Category #{sn}',
            'description' => 'Description #{sn}',
            'key' => 'category#{sn}',
        );
    }

    public function tshirt()
    {
        return array(
            'title' => 'T-shirt Category',
            'key' => 'tshirt',
        );
    }
}

```

If we were to then try to get two tshirt categories this is what we'd see:

```php
<?php

$one = Phactory::category('tshirt');
$two = Phactory::category('tshirt');

echo $one === $two ? 'same object' : 'different objects';
// echo's 'different objects'

```

Every time you called `Phactory::category('tshirt')` you'd get a new instance. To make it return
the same instance every time, you'd need to change this:

```php
<?php

class CategoryPhactory
{
    ...
    public function tshirt_fixture()
    {
        return array(
            'title' => 'T-shirt Category',
            'key' => 'tshirt',
        );
    }
    ...
}

```

Now if we test the results of this again this is what happens:

```php
<?php

$one = Phactory::category('tshirt');
$two = Phactory::category('tshirt');

echo $one === $two ? 'same object' : 'different objects';
// echo's 'same object'

```

All that we've done is append _fixture to the tshirt method, but now it will always return the
same instance every time. Note that this only works on blueprint variations, if you were to define
`blueprint_fixture` method all that would happen is your Phactory wouldn't work.
