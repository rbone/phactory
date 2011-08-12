<?php

namespace Phactory;

use Phactory\HasOneRelationship;
use Phactory\BelongsToRelationship;
use Phactory\Dependancy;

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

		$blueprint = array_map(function ($value) use ($count, $self) {
			if (is_string($value))
				return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
			else if ($value instanceof HasOneRelationship)
				return $self->create($value->blueprint());
			else
				return $value;
		}, $blueprint->values());

		$blueprint = array_map(function ($value) use($blueprint) {
			if ($value instanceof Dependancy && $value->met($blueprint))
				return $value->meet($blueprint);

			return $value;
		}, $blueprint);

		$object = $this->to_object($name, $blueprint);

		\Phactory::triggers()->beforesave($name, $type, $object);

		$object = $this->save_object($name, $object);

		\Phactory::triggers()->aftersave($name, $type, $object);

		return $object;
	}

	protected function to_object($name, $blueprint)
	{
		return (object)$blueprint;
	}

	protected function save_object($name, $object)
	{
		return $object;
	}
}
