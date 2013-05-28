<?php

namespace Phactory;

class Builder
{
	private $count = array();

	public function create($blueprint)
	{
		$name = $blueprint->name;
		$type = $blueprint->type;
		if (!isset($this->count[$name]))
			$this->count[$name] = 0;

		$count = ++$this->count[$name];

		$self = $this;

		$values = $blueprint->values();

		$strings = array_map(function($value) use ($count) {
			return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
		}, $blueprint->strings());

		$relationships = array_map(function($value) {
			return $value->create();
		}, $blueprint->relationships());

		$values = array_merge($values, $strings, $relationships);

		foreach ($blueprint->dependencies() as $key => $dep)
		{
			$values[$key] = $dep->meet($values);
		}

		$object = $this->to_object($name, $values);

		\Phactory::triggers()->beforesave($name, $type, $object);

		$object = $this->save_object($name, $object);

		\Phactory::triggers()->aftersave($name, $type, $object);

		return $object;
	}

	protected function to_object($name, $values)
	{
		return (object)$values;
	}

	protected function save_object($name, $object)
	{
		return $object;
	}
}
