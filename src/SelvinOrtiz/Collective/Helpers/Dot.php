<?php

namespace SelvinOrtiz\Collective\Helpers;

/**
 * Class Dot
 *
 * @since   0.3.0
 * @package SelvinOrtiz\Helpers
 */
class Dot
{
    /**
     * @param array  $arr
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function get($arr, $key, $default = null)
    {
        if (strpos($key, '.') !== false && count(($keys = explode('.', $key)))) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $arr)) {
                    return $default;
                }

                $arr = $arr[$key];
            }

            return $arr;
        }

        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }

    /**
     * @param array  $arr
     * @param string $key
     * @param array  $value
     *
     * @return array
     */
    public static function set(&$arr, $key, $value)
    {
        if (strpos($key, '.') !== false && ($keys = explode('.', $key)) && count($keys)) {
            while (count($keys) > 1) {
                $key = array_shift($keys);

                if (!isset($arr[$key]) || !is_array($arr[$key])) {
                    $arr[$key] = [];
                }

                $arr = &$arr[$key];
            }

            $arr[array_shift($keys)] = $value;
        } else {
            $arr[$key] = $value;
        }
    }
}
