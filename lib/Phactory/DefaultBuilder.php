<?php

namespace Phactory;

use Phactory\HasOneRelationship;

class DefaultBuilder
{
	private $count = array();

	public function create($blueprint)
	{
		if (!isset($this->count[$blueprint->name]))
			$this->count[$blueprint->name] = 0;

		$count = ++$this->count[$blueprint->name];

		$self = $this;

		$blueprint = array_map(function ($value) use ($count, $self) {
			if (is_string($value))
				return str_replace('#{sn}', str_pad($count, 4, '0', STR_PAD_LEFT), $value);
			else if ($value instanceof HasOneRelationship)
				return $self->create($value->blueprint());
			else
				return $value;
		}, $blueprint->values());

		return (object)$blueprint;
	}
}