<?php

class DependenciesTest extends PHPUnit_Framework_TestCase
{
    public function testDependency()
    {
        Phactory::reset();
        $entry = Phactory::entry();

        $this->assertSame($entry->designer, $entry->design->designer);
    }

    public function testMultiLevelDependencyWithMethodCalls()
    {
        Phactory::reset();
        Phactory::builder(new TestBuilder);
        $entry = Phactory::entry();

        $this->assertSame($entry->designer, $entry->design->attachment->creator());
    }

    public function testDependencyWithMethodAndNullValues()
    {
        Phactory::reset();
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry([
            'design' => Phactory::design([
                'attachment' => Phactory::attachment(['creator' => null]),
            ])
        ]);

        $this->assertNull($entry->designer);
    }

    public function testDependencyWithPropertyAndNullValues()
    {
        Phactory::reset();
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry([
            'design' => Phactory::design([
                'attachment' => Phactory::attachment(['co_creator' => null]),
            ])
        ]);

        $this->assertNull($entry->co_designer);
    }

    public function testDependencyWithArrayAndNullValues()
    {
        Phactory::reset();
        Phactory::builder(new TestBuilder);

        $entry = Phactory::entry([
            'design' => Phactory::design([
                'request' => Phactory::request(['date' => null]),
            ])
        ]);

        $this->assertNull($entry->request_date);
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

class TestBuilder extends \Phactory\Builder
{
    protected function toObject($name, $values)
    {
        if ($name == 'attachment') {
            return new Attachment($values);
        } elseif ($name == 'request') {
            return $values;
        } else {
            return (object) $values;
        }
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
