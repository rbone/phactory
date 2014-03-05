<?php

namespace Phactory;

class Triggers
{
    private $triggers;

    public function __construct($triggers = null)
    {
        $this->triggers = $triggers;
    }

    public function beforeSave($name, $type, $object)
    {
        $this->event($name, $type, $object, 'beforeSave');
    }

    public function afterSave($name, $type, $object)
    {
        $this->event($name, $type, $object, 'afterSave');
    }

    protected function event($name, $type, $object, $event)
    {
        if (is_null($this->triggers)) {
            return;
        }
        
        $event = ucfirst($event);

        if (method_exists($this->triggers, "{$name}{$event}")) {
            call_user_func(array($this->triggers, "{$name}{$event}"), $object);
        }
        
        if ($type) {
            
            $type = ucfirst($type);

            if (method_exists($this->triggers, "{$name}{$type}{$event}")) {
                call_user_func(array($this->triggers, "{$name}{$type}{$event}"), $object);
            }
        }
    }
}
