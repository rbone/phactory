<?php

class DependenciesTest extends PHPUnit_Framework_TestCase
{
    public function testDependency()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';
        $entry = Phactory::entry();

        $this->assertSame($entry->designer, $entry->design->designer);
    }

    public function testMultiLevelDependencyWithMethodCalls()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';
        Phactory::builder(new TestBuilder);
        $entry = Phactory::entry();

        $this->assertSame($entry->designer, $entry->design->attachment->creator());
    }

    public function testDependencyWithMethodAndNullValues()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry(array(
            'design' => Phactory::design(array(
                'attachment' => Phactory::attachment(array('creator' => null)),
            ))
        ));

        $this->assertNull($entry->designer);
    }

    public function testDependencyWithPropertyAndNullValues()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry(array(
            'design' => Phactory::design(array(
                'attachment' => Phactory::attachment(array('co_creator' => null)),
            ))
        ));

        $this->assertNull($entry->co_designer);
    }

    public function testDependencyWithArrayAndNullValues()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry(array(
            'design' => Phactory::design(array(
                'request' => Phactory::request(array('date' => null)),
            ))
        ));

        $this->assertNull($entry->request_date);
    }

    public function testCustomDependencyClass()
    {
        Phactory::reset();
        Phactory::$dependencyClass = 'CustomDependency';

        $entry = Phactory::entry(array(
            'budget' => Phactory::budget(array('amount' => null))
        ));

        $this->assertNull($entry->budget_amount);
    }
}

class CustomDependency extends \Phactory\Dependency
{
    protected function has($part, $subject)
    {
        return (
            method_exists($subject, $part)
            || (is_array($subject) && array_key_exists($part, $subject))
            || (is_object($subject) && property_exists($subject, $part))
            // Custom property existance checking
            || (method_exists($subject, 'hasAttribute') && $subject->hasAttribute($part))
        );
    }
}

class EntryPhactory
{
    public function blueprint()
    {
        return array(
            'title' => 'Food goes in here',
            'designer' => Phactory::uses('design.attachment.creator'),
            'co_designer' => Phactory::uses('design.attachment.co_creator'),
            'design' => Phactory::hasOne('design'),
            'request_date' => Phactory::uses('design.request.date'),
            'budget' => Phactory::hasOne('budget'),
            'budget_amount' => Phactory::uses('budget.amount'),
        );
    }
}

class RequestPhactory
{
    public function blueprint()
    {
        return array(
            'date' => '2000-12-31',
            'time' => '23:50:00',
            'observations' => 'Lorem ipsum dolor sit aemeth',
        );
    }
}

class DesignPhactory
{
    public function blueprint()
    {
        return array(
            'type' => 'jpg',
            'path' => '/some/place/elsewhere.jpg',
            'attachment' => Phactory::hasOne('attachment'),
            'designer' => Phactory::uses('attachment.creator'),
            'request' => Phactory::hasOne('request'),
        );
    }
}

class AttachmentPhactory
{
    public function blueprint()
    {
        return array(
            'type' => 'jpg',
            'content' => '@^&#$^#@&*$',
            'creator' => Phactory::hasOne('designer'),
            'co_creator' => Phactory::hasOne('designer'),
        );
    }
}

class DesignerPhactory
{
    public function blueprint()
    {
        return array(
            'first_name' => 'Fronzel',
            'last_name' => 'Neekburm',
            'email' => 'user#{sn}@example.org',
        );
    }
}

class BudgetPhactory
{
    public function blueprint()
    {
        return array(
            'amount' => 100.00,
        );
    }
}

class TestBuilder extends \Phactory\Builder
{
    protected function toObject($name, $values)
    {
        if ($name == 'attachment') {
            return new Attachment($values);
        } elseif ($name == 'budget') {
            return new Budget($values);
        } elseif ($name == 'request') {
            return $values;
        } else {
            return (object) $values;
        }
    }
}

class Budget
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // Custom property checking
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->data);
    }
}

class Attachment
{
    private $data;

    public $co_creator;

    public function __construct($data)
    {
        $this->data = $data;
        $this->co_creator = $data['co_creator'];
    }

    public function type()
    {
        return $this->data['type'];
    }

    public function content()
    {
        return $this->data['content'];
    }

    public function creator()
    {
        return $this->data['creator'];
    }
}
