<?php
namespace SelvinOrtiz\Collective\Behavior;


/**
 * Class Serializable
 *
 * @package SelvinOrtiz\Collective\Behavior
 *
 * @property array $input
 */
trait Serializable
{
	public function serialize()
	{
		return serialize($this->input);
	}

	public function unserialize($input)
	{
		$this->input = unserialize($input);
	}
}
