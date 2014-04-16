# Triggers

Triggers are for all the annoying side effects your app depends on that
a basic Phactory can't create. For example, imagine you had this UserPhactory:

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

It lets us create either a basic user and a system user. However my app expects
the system user to have a userid of 1, but it's all up to my ORM to determine
what objects get what id's and Phactory doesn't have any way to influence it
in its blueprints, I can't just set userid to 1 in my system blueprint.

There is a way around this, which is where triggers come in. Triggers are
basic observers in Phactory that let you know when an object is about to be
saved, or has been saved and gives you a chance to do something. Here's what
a trigger might look like:

```php
<?php

class MyFrameworksTrigger
{
    private $sequences_incremented = false;

    public function userBeforeSave($user)
    {
        if (!$this->sequences_incremented)
        {
            // set the starting userid sequence to 100000
            
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

And is registered via:

```php
<?php

Phactory::triggers(new MyFrameworksTrigger);

```

Now whenever I create a user, a trigger will check if the sequences have been
changed to start at 100000 instead of 1, and whenever I create a system user
(which would be a fixture), the userid is rewritten to 1.

This works ok, but I encourage you not to use triggers if you don't need them,
they're essentially a giant hack to ensure Phactory can still work even when
dealing with crufty parts of your system that have odd requirements.
