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

Phactory doesn't know about your database, it doesn't know about your domain model.
This is by design, there's simply too many different ORM's out there to provide support for.
However, you can easily implement support for your own ORM of choice, see the wiki
documentation on builders for more information.

Why do it this way? Simple because I wanted Phactory to return real objects, not database rows.

## Status

v1.0. Solid test coverage is in place, just type `phpunit` within your phactory directory.

## Installing

```
git clone git://github.com/rbone/phactory.git

<?php

require 'phactory/bootstrap.php';

```