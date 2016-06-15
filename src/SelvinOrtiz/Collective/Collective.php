<?php

namespace SelvinOrtiz\Collective;

use SelvinOrtiz\Collective\Behavior\Arrayable;
use SelvinOrtiz\Collective\Behavior\Countable;
use SelvinOrtiz\Collective\Behavior\Traversable;
use SelvinOrtiz\Collective\Behavior\Serializable;
use SelvinOrtiz\Collective\Helpers\Dot;

/**
 * Class Collective
 *
 * @version 0.3.0
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
     * @param $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->input) ? $this->input[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function __set($key, $value)
    {
        $this->input[$key] = $value;

        return $this;
    }

    /**
     * Gets a value by key in the collection using dot notation
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Dot::get($this->input, $key, $default);
    }

    /**
     * Sets a value by key in the collection using dot notation
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        Dot::set($this->input, $key, $value);

        return $this;
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

        foreach ($this->input as $index => $value) {
            if ($callback($value, $index)) {
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
        if ($callback === null) {
            $input = array_slice($this->input, -1);

            return array_pop($input);
        }

        foreach (array_reverse($this->input) as $index => $value) {
            if ($callback($value, $index)) {
                return $value;
            }
        }
    }

    /**
     * Applies the callback to each item in the collection
     *
     * @param  callable $callback
     *
     * @return static
     */
    public function map(callable $callback)
    {
        if (empty($this->input)) {
            return new static();
        }

        $values = [];

        foreach ($this->input as $key => $item) {
            $values[$key] = $callback($item, $key);
        }

        return new static($values);
    }

    /**
     * @param callable $callback
     *
     * @return Collective
     */
    public function filter(callable $callback)
    {
        $values = array_filter($this->input, $callback);

        return new static($values);
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
     * @return static
     */
    public function resetKeys()
    {
        return new static(array_values($this->input));
    }

    /**
     * @return callable
     */
    public function callbackReturnValue()
    {
        return function ($value) {
            return $value;
        };
    }
}
