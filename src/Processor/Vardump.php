<?php

namespace Appeltaert\PAM\Processor;


use function Appeltaert\PAM\flattenVar;

class Vardump implements ProcessorInterface
{
    function getIdentifier()
    {
        return 'Vardump';
    }

    function accepts($context)
    {
        return is_scalar($context) || is_object($context);
    }

    function normalize(array $collection, $context, $verbose)
    {
        return [gettype($context) => flattenVar($context)];
    }
}