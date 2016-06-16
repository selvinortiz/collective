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
 * @version 0.4.0
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
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->input = $input;
    }

    /**
     * Returns $default or an item by key in the collection using dot notation
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
     * Sets a item by key in the collection using dot notation
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
     * Returns all keys for items in the collection
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->input));
    }

    /**
     * Returns all items in the collection with their keys reset
     *
     * @since 0.3.0
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->input));
    }

    /**
     * Returns $default or the first item in the collection
     *
     * @param callable $callback
     * @param mixed    $default
     *
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        if (empty($this->input)) {
            return $default;
        }

        if (null === $callback) {
            return reset($this->input);
        }

        foreach ($this->input as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Returns $default or the last item in the collection
     *
     * @param callable $callback
     * @param mixed    $default
     *
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        if (empty($this->input)) {
            return $default;
        }

        if (null === $callback) {
            return end($this->input);
        }

        return (new static(array_reverse($this->input)))->first($callback, $default);
    }

    /**
     * Returns static($default) or all items in the collection after applying $callback
     *
     * @param callable $callback
     * @param array    $default
     *
     * @return static
     */
    public function map(callable $callback, array $default = [])
    {
        if (empty($this->input)) {
            return new static($default);
        }

        $result = [];

        foreach ($this->input as $key => $value) {
            $result[$key] = $callback($value, $key);
        }

        return new static(empty($result) ? $default : $result);
    }

    /**
     * Returns static($default) or all filtered items in the collection
     *
     * @param callable $callback
     * @param array    $default
     *
     * @return static
     */
    public function filter(callable $callback = null, array $default = [])
    {
        if (null === $callback) {
            return new static(array_filter($this->input));
        }

        $result = [];

        foreach ($this->input as $key => $value) {
            if ($callback($value, $key)) {
                $result[$key] = $value;
            }
        }

        return new static(empty($result) ? $default : $result);
    }

    /**
     * Returns a single value obtained from reduction all items in the collection
     *
     * @param  callable $callback
     * @param  mixed    $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        foreach ($this->input as $key => $value) {
            $initial = $callback($value, $initial, $key);
        }

        return $initial;
    }

    /**
     * Returns a collection with items in reversed order
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->input));
    }

    /**
     * Flattens all items into a single level collection
     *
     * @param $depth
     *
     * @return static
     */
    public function flatten($depth = INF)
    {
        $result = [];

        foreach ($this->input as $key => $value) {
            if (is_array($value)) {
                if (1 === $depth) {
                    $result = array_merge($result, $value);
                } else {
                    /**
                     * @var static $value
                     */
                    $value  = (new static($value))->flatten($depth - 1);
                    $result = array_merge($result, $value->toArray());
                }
            } else {
                $result[] = $value;
            }
        }

        return new static($result);
    }

    /**
     * Enables other functions to be piped through without breaking the chain
     *
     * @param callable $callback
     *
     * @since 0.2.0
     * @return mixed
     */
    public function then(callable $callback)
    {
        return $callback($this);
    }
}
