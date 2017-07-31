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
     * @var bool
     */
    private $supportingColors;

    /**
     * @param bool $debug
     * @param bool $verbose
     * @param bool $supportsColors
     */
    public function __construct($debug = null, $verbose = null, $supportsColors = null)
    {
        $argStr = implode(' ', $_SERVER['argv']);

        $this->debug = null !== $debug
            ? (bool)$debug
            : strpos($argStr, '--debug') !== false;

        $this->verbose = null !== $verbose
            ? (bool) $verbose
            : preg_match('/-v|--verbose/', $argStr) === 1;

        // this one probably needs more work, f.e. checking if no ansi is passed as an arg etc
        $this->supportingColors = null !== $supportsColors
            ? (bool)$supportsColors
            : posix_isatty("php://input") === true;
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

    /**
     * @return bool
     */
    public function isSupportingColors()
    {
        return $this->supportingColors;
    }
}
