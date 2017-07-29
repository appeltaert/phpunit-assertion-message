<?php

namespace Appeltaert\PAM;

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