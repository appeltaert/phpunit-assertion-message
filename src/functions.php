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
    }
    if (is_object($v)) {
        if (method_exists($v, '__toString')) {
            return (string) $v;
        } elseif ($v instanceof \DateTimeInterface) {
            return sprintf('%s(%s)', get_class($v), $v->format(\DateTime::ISO8601));
        }
    }
    if (is_resource($v)) {
        return sprintf('%s(#%d)', get_resource_type($v), $v);
    }
    if ($encoded = json_encode($v)) {
        return $encoded;
    }

    return substr(preg_replace('/\n|(\s\s)/', '', print_r($v, true)), 0, 255);
}

/**
 * @param mixed $obj
 * @param array $getters
 * @return array
 */
function callGetters($obj, array $getters)
{
    if (!is_object($obj)) {
        throw new \InvalidArgumentException;
    }

    $return = [];
    foreach ($getters as $getter) {
        $caller = "get$getter";
        if (!method_exists($obj, $caller)) {
            throw new \InvalidArgumentException();
        }
        $return[$getter] = $obj->$caller();
    }
    return $return;
}
