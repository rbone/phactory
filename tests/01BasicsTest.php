<?php

class BasicsTest extends PHPUnit_Framework_TestCase
{

    public function setup()
    {
        Phactory::reset();
    }

    public function testBasicCreate()
    {
        $user = Phactory::user();

        $this->assertEquals($user, (object) array(
                'first_name' => 'Fronzel',
                'last_name' => 'Neekburm',
                'email' => 'user0001@example.org',
        ));
    }

    public function testSerialNumberIncrements()
    {
        $one = Phactory::user();
        $two = Phactory::user();

        $this->assertNotEquals($one->email, $two->email);
        $this->assertEquals($one->email, 'user0001@example.org');
        $this->assertEquals($two->email, 'user0002@example.org');
    }

    public function testBlueprintsOverlay()
    {
        $admin = Phactory::user('admin');

        $this->assertEquals($admin, (object) array(
                'first_name' => 'Fronzel',
                'last_name' => 'Neekburm',
                'email' => 'user0001@example.org',
                'is_admin' => true,
        ));
    }

    public function testOverrideAttributes()
    {
        $user = Phactory::user(array(
                'last_name' => 'Blarg#{sn}',
                'address' => (object) array(
                    'street' => 'Sesame St.',
                )
        ));

        $this->assertEquals($user, (object) array(
                'first_name' => 'Fronzel',
                'last_name' => 'Blarg0001',
                'email' => 'user0001@example.org',
                'address' => (object) array(
                    'street' => 'Sesame St.',
                )
        ));

        $admin = Phactory::user('admin', array(
                'first_name' => 'Admin',
        ));

        $this->assertEquals($admin, (object) array(
                'first_name' => 'Admin',
                'last_name' => 'Neekburm',
                'email' => 'user0002@example.org',
                'is_admin' => true,
        ));
    }
}

class UserPhactory
{
    public function blueprint()
    {
        return array(
            'first_name' => 'Fronzel',
            'last_name' => 'Neekburm',
            'email' => 'user#{sn}@example.org',
        );
    }

    public function admin()
    {
        return array(
            'is_admin' => true,
        );
    }
}
