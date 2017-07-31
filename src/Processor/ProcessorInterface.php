<?php

namespace Appeltaert\PAM\Processor;


interface ProcessorInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param mixed $context
     * @return bool
     */
    public function accepts($context);

    /**
     * @param array $collection
     * @param mixed $context
     * @param bool $verbose
     * @return array
     */
    public function normalize(array $collection, $context, $verbose);
}
