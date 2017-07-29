<?php

namespace Appeltaert\PAM\Formatter;


interface FormatterInterface
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
     * @return array
     */
    public function normalize(array $collection, $context);
}