<?php

namespace Phactory;

use Phactory\HasOneRelationship;
use Phactory\BelongsToRelationship;
use Phactory\Dependancy;

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
			else if ($value instanceof BelongsToRelationship)
				return $self->create($value->blueprint());
			else
				return $value;
		}, $blueprint->values());

		$blueprint = array_map(function ($value) use($blueprint) {
			if ($value instanceof Dependancy && $value->met($blueprint))
				return $value->meet($blueprint);

			return $value;
		}, $blueprint);

		return $this->toObject($blueprint);
	}

	protected function toObject($blueprint)
	{
		return (object)$blueprint;
	}
}