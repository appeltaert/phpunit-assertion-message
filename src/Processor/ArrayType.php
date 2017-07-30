<?php

namespace Appeltaert\PAM\Processor;


class ArrayType implements ProcessorInterface
{
    function getIdentifier()
    {
        return 'Array';
    }

    function accepts($context)
    {
        return is_array($context);
    }

    function normalize(array $collection, $context, $verbose)
    {
        return $context;
    }
}