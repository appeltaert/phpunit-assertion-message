<?php

namespace Appeltaert\PAM\Formatter;


class ArrayFlattener implements FormatterInterface
{
    function getIdentifier()
    {
        return 'Array';
    }

    function accepts($context)
    {
        return is_array($context);
    }

    function normalize(array $collection, $context)
    {
        return $context;
    }
}