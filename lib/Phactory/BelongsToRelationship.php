<?php

namespace Phactory;

use \Phactory;

class BelongsToRelationship
{
	private $name;
	private $type;
	private $override;

	public function __construct($name, $type, $override)
	{
		$this->name = $name;
		$this->type = $type;
		$this->override = $override;
	}

	public function blueprint()
	{
		$blueprint = Phactory::get_blueprint($this->name, $this->type, $this->override);

		return $blueprint;
	}
}