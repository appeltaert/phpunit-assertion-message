<?php

namespace Appeltaert\PAM;


use Appeltaert\PAM\Formatter\ArrayFlattener;
use Appeltaert\PAM\Formatter\FormatterInterface;
use Appeltaert\PAM\Formatter\SymfonyHTTPResponse;
use Appeltaert\PAM\Printer\Plain;
use Appeltaert\PAM\Printer\PrinterInterface;

class PAM
{
    /**
     * @var FormatterInterface[]
     */
    static private $defaultFormatters;

    /**
     * @var PrinterInterface
     */
    static private $defaultPrinter;

    /**
     * @var Env
     */
    static private $defaultEnv;

    /**
     * @var FormatterInterface[]
     */
    private $formatters;

    /**
     * @var PrinterInterface
     */
    private $printer;

    /**
     * @var Env
     */
    private $env;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $context;

    /**
     * @var array
     */
    private $printargs;

    /**
     * @param string $message
     * @param array $context
     * @param array $printargs
     */
    public function __construct($message, array $context = [], array $printargs = [])
    {
        $this->message = $this->printargs ? vsprintf($message, $printargs) : $message;
        $this->context = $context;
        $this->printargs = $printargs;

        if (!self::$defaultFormatters) {
            self::$defaultFormatters = [
                new SymfonyHTTPResponse(),
                new ArrayFlattener()
            ];
        }
        $this->formatters = self::$defaultFormatters;

        if (!self::$defaultPrinter) {
            self::$defaultPrinter = new Plain;
        }
        $this->printer = self::$defaultPrinter;

        if (!self::$defaultEnv) {
            $argStr = implode(' ', $_SERVER['argv']);
            self::$defaultEnv = new Env(
                strpos($argStr, '--debug') !== false,
                preg_match('/-v|--verbose/', $argStr)
            );
        }
        $this->env = self::$defaultEnv;
    }

    /**
     * @param $context
     * @return string
     */
    private function process($context = null)
    {
        $collection = [];
        foreach($this->formatters as $decorator) {
            if (!$decorator->accepts($context)) {
                continue;
            }
            $collection[$decorator->getIdentifier()] = $decorator->normalize($collection, $context);
            break;
        }

        if (!$collection) {
            return '';
        }

        return $this->printer->parse($collection);
    }

    /**
     * @param array $formatters
     * @param PrinterInterface|null $printer
     */
    static public function setDefaults(array $formatters = [],
                                       PrinterInterface $printer = null,
                                       Env $env = null)
    {
        self::$defaultFormatters = $formatters;
        self::$defaultPrinter = $printer;
        self::$defaultEnv = $env;
    }


    public function __toString()
    {
        if (!$this->env->isDebug() || !$this->context) {
            return $this->message;
        }

        $processedContext = '';
        foreach($this->context as $ctx) {
            $processedContext .= $this->process($ctx) . "\n";
        }

        return $this->message . "\n\n" . $processedContext . "\n";
    }
}