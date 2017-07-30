<?php

namespace Appeltaert\PAM;

/**
 * @param $v
 * @return mixed|string
 */
function flattenVar($v)
{
    if (is_scalar($v)) {
        return $v;
    } elseif (is_object($v) && method_exists($v, '__toString')) {
        return (string) $v;
    } elseif(is_resource($v)) {
        return print_r($v, true);
    } elseif ($encoded = json_encode($v)) {
        return $encoded;
    } else {
        return serialize($v);
    }
}

/**
 * @param object $obj
 * @param array $getters
 * @return array
 */
function callGetters($obj, array $getters)
{
    $return = [];
    foreach($getters as $getter) {
        $caller = "get$getter";
        if (!method_exists($obj, $caller)) {
            throw new \InvalidArgumentException();
        }
        $return[$getter] = $obj->$caller();
    }
    return $return;
}