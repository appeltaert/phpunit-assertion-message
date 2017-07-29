<?php

namespace Appeltaert\PAM\Printer;


interface PrinterInterface
{
    /**
     * @param array $collection
     * @return string
     */
    public function parse(array $collection);
}