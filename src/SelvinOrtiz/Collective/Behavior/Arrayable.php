<?php
namespace SelvinOrtiz\Collective\Behavior;


/**
 * Class Arrayable
 *
 * @package SelvinOrtiz\Collective\Behavior
 *
 * @property array $input
 */
trait Arrayable
{
	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->input[] = $value;
		}
		else
		{
			$this->input[$offset] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->input[$offset]);
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed|null
	 */
	public function offsetGet($offset)
	{
		return isset($this->input[$offset]) ? $this->input[$offset] : null;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->input[$offset]);
	}
}
