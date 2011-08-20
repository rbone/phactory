<?php

namespace Phactory;

use \Phactory;

class HasOneRelationship
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

	public function create()
	{
		return Phactory::create_blueprint($this->name, $this->type, $this->override);
	}
}