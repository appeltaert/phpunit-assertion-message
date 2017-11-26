<?php

namespace Appeltaert\PAM\Processor;

use function Appeltaert\PAM\flattenVar;

class Vardump implements ProcessorInterface
{
    public function getIdentifier()
    {
        return 'Vardump';
    }

    public function accepts($context)
    {
        return is_scalar($context) || is_object($context);
    }

    public function normalize(array $collection, $context, $verbose)
    {
        return [gettype($context) => flattenVar($context)];
    }
}
