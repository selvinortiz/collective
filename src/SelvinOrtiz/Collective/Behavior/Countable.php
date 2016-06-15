<?php

namespace SelvinOrtiz\Collective\Behavior;

/**
 * Class Countable
 *
 * @package SelvinOrtiz\Collective\Behavior
 *
 * @property array $input
 */
trait Countable
{
    /**
     * @return int
     */
    public function count()
    {
        return count($this->input);
    }
}
