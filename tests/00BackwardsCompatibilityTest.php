<?php
/**
 * @deprecated
 */
class BackwardsCompatibilityTest extends PHPUnit_Framework_TestCase
{

    public function setup()
    {
        Phactory::reset();
    }

    /**
     * @covers \Phactory::has_one
     */
    public function testPhactoryHasOne()
    {
        $message = Phactory::message();

        $this->assertEquals($message, (object) array(
                'title' => 'OMGWTFBBQ!',
                'content' => 'Food goes in here.',
                'employer' => (object) array(
                    'first_name' => 'Fronzel',
                    'last_name' => 'Neekburm',
                    'email' => 'user0001@example.org',
                )
        ));
    }

    /**
     * @covers \Phactory::create_blueprint
     */
    public function testPhactoryCreateBlueprint()
    {
        $this->assertEquals(
            Phactory::create_blueprint('message', 'admin', array('title' => 'Good title')),
            (object) array(
                'title' => 'Good title',
                'content' => 'Food goes in here.',
                'employer' => (object) array(
                    'is_admin' => true,
                    'first_name' => 'Fronzel',
                    'last_name' => 'Neekburm',
                    'email' => 'user0001@example.org',
                )
            )
        );
    }

    /**
     * @covers \Phactory::get_blueprint
     */
    public function testPhactoryGetBlueprint()
    {
        $this->assertInstanceOf(
            '\\Phactory\\Blueprint',
            Phactory::get_blueprint('message', 'admin', array('title' => 'Good title'))
        );
    }

    /**
     * @covers \Phactory\Blueprint::is_fixture
     */
    public function testBlueprintIsFixture()
    {
        $this->assertTrue(Phactory::get_blueprint('employer', 'owner')->is_fixture());
    }

    /**
     * @covers \Phactory\Builder::to_object
     */
    public function testBuilderToObject()
    {
        Phactory::builder(new DeprecatedCustomBuilder);

        $this->assertInstanceOf('DeprecatedCustomObject', Phactory::employer());
    }

    /**
     * @covers \Phactory\Builder::save_object
     */
    public function testBuilderSaveObject()
    {
        Phactory::builder(new DeprecatedCustomBuilder);

        $employer = Phactory::employer();

        $this->assertTrue($employer->saved);
    }

    /**
     * @covers \Phactory\Fixtures::has_fixture
     */
    public function testFixturesHasFixture()
    {
        $this->assertFalse(Phactory::fixtures()->has_fixture('employer', 'unregistered_fixture'));
    }

    /**
     * @covers \Phactory\Fixtures::get_fixture
     * @covers \Phactory\Fixtures::set_fixture
     */
    public function testFixturesGetSetFixture()
    {
        Phactory::fixtures()->set_fixture('employer', 'unknown', array(
            'first_name' => 'Anonymous',
            'last_name' => 'Employer',
            'email' => 'anonymous@example.org',
        ));

        $this->assertEquals(
            Phactory::fixtures()->get_fixture('employer', 'unknown'),
            array(
                'first_name' => 'Anonymous',
                'last_name' => 'Employer',
                'email' => 'anonymous@example.org',
            )
        );
    }

    /**
     * @covers \Phactory\Triggers::before_save
     * @covers \Phactory\Triggers::after_save
     */
    public function testTriggers()
    {
        Phactory::builder(new DeprecatedCustomBuilder);
        Phactory::triggers(new DeprecatedFrameworkTriggers);

        Phactory::fixtures()->set_fixture('employer', 'unknown', array(
            'first_name' => 'Anonymous',
            'last_name' => 'Employer',
            'email' => 'anonymous@example.org',
        ));

        $employer = Phactory::employer();

        $this->assertTrue($employer->beforeSave);
        $this->assertTrue($employer->afterSave);
    }
}


class EmployerPhactory
{
    public function blueprint()
    {
        return array(
            'first_name' => 'Fronzel',
            'last_name' => 'Neekburm',
            'email' => 'user#{sn}@example.org',
        );
    }

    public function owner_fixture()
    {
        return array(
            'first_name' => 'Mr.',
            'last_name' => 'Owner',
            'email' => 'owner@example.org',
        );
    }

    public function admin()
    {
        return array(
            'is_admin' => true,
        );
    }
}

class DeprecatedCustomBuilder extends \Phactory\Builder
{
    public function to_object($name, $blueprint)
    {
        $object = new DeprecatedCustomObject;
        foreach ($blueprint as $attribute => $value) {
            $object->$attribute = $value;
        }

        return $object;
    }

    public function save_object($name, $object)
    {
        $object->saved = true;

        return $object;
    }
}

class DeprecatedCustomObject
{
    public $saved = false;
    public $beforeSave = false;
    public $afterSave = false;
}

class DeprecatedFrameworkTriggers
{
    public function employer_beforesave($object)
    {
        $object->beforeSave = true;
    }

    public function employer_aftersave($object)
    {
        $object->afterSave = true;
    }
}

class MessagePhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'OMGWTFBBQ!',
            'content' => 'Food goes in here.',
            'employer' => Phactory::has_one('employer'),
        );
    }

    public function admin()
    {
        return array(
            'employer' => Phactory::has_one('employer', 'admin'),
        );
    }
}