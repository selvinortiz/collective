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
     * Returns all keys for elements in the collection
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->input));
    }

    /**
     * @since 0.3.0
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->input));
    }

    /**
     * Returns the first item in the collection
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
     * Returns the last item in the collection
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
     * Applies the callback to each item in the collection
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
     * @param  callable  $callback
     * @param  mixed     $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        foreach ($this->input as $key => $value)
        {
            $initial = $callback($value, $initial, $key);
        }

        return $initial;
    }

    /**
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->input));
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
}
