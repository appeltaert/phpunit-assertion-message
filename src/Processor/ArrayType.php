<?php

namespace Appeltaert\PAM\Processor;

class ArrayType implements ProcessorInterface
{
    public function getIdentifier()
    {
        return 'Array';
    }

    public function accepts($context)
    {
        return is_array($context);
    }

    public function normalize(array $collection, $context, $verbose)
    {
        return $context;
    }
}
