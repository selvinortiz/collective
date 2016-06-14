<?php
namespace SelvinOrtiz\Collective;

use SelvinOrtiz\Collective\Behavior\Arrayable;
use SelvinOrtiz\Collective\Behavior\Countable;
use SelvinOrtiz\Collective\Behavior\Traversable;
use SelvinOrtiz\Collective\Behavior\Serializable;

/**
 * Class Collective
 *
 * @version 0.2.0
 * @package SelvinOrtiz\Collective
 */
class Collective implements \ArrayAccess, \Countable, \Iterator, \Serializable
{
	/**
	 * @var array
	 */
	protected $input;

	use Arrayable;
	use Countable;
	use Traversable;
	use Serializable;

	/**
	 * Collective constructor.
	 *
	 * @param array $input
	 */
	public function __construct(array $input = [])
	{
		$this->input = $input;
	}

	/**
	 * Returns the native array used to seed this collective
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->input;
	}

	/**
	 * Returns the first item in the collection
	 *
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function first(callable $callback = null)
	{
		$callback = $callback ?: $this->callbackReturnValue();

		foreach ($this->input as $index => $value)
		{
			if ($callback($value, $index))
			{
				return $value;
			}
		}
	}

	/**
	 * Returns the last item in the collection
	 *
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function last(callable $callback = null)
	{
		if ($callback === null)
		{
			$input = array_slice($this->input, -1);

			return array_pop($input);
		}

		foreach (array_reverse($this->input) as $index => $value)
		{
			if ($callback($value, $index))
			{
				return $value;
			}
		}
	}

	/**
	 * @param callable $callback
	 * @param bool     $keepKeys
	 *
	 * @return static
	 */
	public function apply(callable $callback, $keepKeys = false)
	{
		$values = array_map($callback, $this->input);

		return new static($keepKeys ? $values : array_values($values));
	}

	/**
	 * @param callable $callback
	 * @param bool     $keepKeys
	 *
	 * @return Collective
	 */
	public function filter(callable $callback, $keepKeys = false)
	{
		$values = array_filter($this->input, $callback);

		return new static($keepKeys ? $values : array_values($values));
	}

	/**
	 * @param callable $callback
	 *
	 * @since 0.2.0
	 * @return mixed
	 */
	public function then(callable $callback)
	{
		return $callback($this);
	}

	/**
	 * @return callable
	 */
	public function callbackReturnValue()
	{
		return function ($value)
		{
			return $value;
		};
	}
}
