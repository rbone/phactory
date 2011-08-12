<?php

namespace Phactory;

class Triggers
{
	private $triggers;

	public function __construct($triggers=null)
	{
		$this->triggers = $triggers;
	}

	public function beforesave($name, $type, $object)
	{
		$this->event($name, $type, $object, 'beforesave');
	}

	public function aftersave($name, $type, $object)
	{
		$this->event($name, $type, $object, 'aftersave');
	}

	protected function event($name, $type, $object, $event)
	{
		if (is_null($this->triggers))
			return;

		if (method_exists($this->triggers, "{$name}_{$event}"))
			call_user_func(array($this->triggers, "{$name}_{$event}"), $object);

		if ($type && method_exists($this->triggers, "{$name}_{$type}_{$event}"))
			call_user_func(array($this->triggers, "{$name}_{$type}_{$event}"), $object);
	}
}