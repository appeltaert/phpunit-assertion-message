<?php

namespace Appeltaert\PAM\Printer;

use Appeltaert\PAM\Env;

interface PrinterInterface
{
    /**
     * @param Env $env
     */
    public function __construct(Env $env);

    /**
     * @param array $collection
     * @return string
     */
    public function parse(array $collection);
}
