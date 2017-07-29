<?php

namespace Appeltaert\PAM;


class Env
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @var bool
     */
    private $verbose;

    /**
     * @param bool $debug
     * @param bool $verbose
     */
    public function __construct($debug, $verbose)
    {
        $this->debug = (bool)$debug;
        $this->verbose = (bool)$verbose;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @return bool
     */
    public function isVerbose()
    {
        return $this->verbose;
    }
}