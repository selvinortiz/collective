<?php
namespace SelvinOrtiz\Collective\Behavior;


/**
 * Class Traversable
 *
 * @package SelvinOrtiz\Collective\Behavior
 *
 * @property array $input
 */
trait Traversable
{
	protected $position = 0;

	function rewind()
	{
		$this->position = 0;
	}

	function current()
	{
		return $this->input[$this->position];
	}

	function key()
	{
		return $this->position;
	}

	function next()
	{
		++$this->position;
	}

	function valid()
	{
		return isset($this->input[$this->position]);
	}
}
