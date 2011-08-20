# Phactory

A simple PHP library for creating data for tests.

## Usage

Define factories like so:

```php
<?php

class UserPhactory
{
	public function blueprint()
	{
		return array(
			'name' => 'User #{sn}',
			'activated' => true,
		);
	}

	public function admin()
	{
		return array(
			'isadmin' => true,
		);
	}
}

```

Then use them:

```php
<?php

$user = Phactory::user();

echo $user->name == 'User 0001' ? 'true' : 'false'; // 'true'
echo $user->activated ? 'true' : 'false'; // 'true'

$admin = Phactory::user('admin');

echo $user->name == 'User 0002' ? 'true' : 'false'; // 'true'
echo $user->activated ? 'true' : 'false'; // 'true'
echo $user->isadmin ? 'true' : 'false'; // 'true'

```

## What it doesn't do

Phactory doesn't know about your database or ORM, this is by design. Rather than trying
to support every ORM out there Phactory is designer to be easily extended to support
whatever it needs to. e.g. using our above UserPhactory

```php
<?php

class User {}

class MyCustomBuilder extends \Phactory\Builder
{
	protected function to_object($name, $values)
	{
		$object = new $name;

		foreach ($values as $key => $value)
			$object->$key = $value;

		return $object;
	}
}

$user = Phactory::user();

echo get_class($user); // 'User'

```

## Status

v1.1. Solid test coverage is in place, just type `phpunit` within your phactory directory.

## Installing

```
git clone git://github.com/rbone/phactory.git

<?php

require 'phactory/bootstrap.php';

```