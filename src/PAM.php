<?php

namespace Appeltaert\PAM;


use Appeltaert\PAM\Processor\ArrayFlattener;
use Appeltaert\PAM\Processor\ProcessorInterface;
use Appeltaert\PAM\Processor\Symfony\HTTPResponse;
use Appeltaert\PAM\Processor\Symfony\RequestProfiler;
use Appeltaert\PAM\Printer\Plain;
use Appeltaert\PAM\Printer\PrinterInterface;

class PAM
{
    /**
     * @var ProcessorInterface[]
     */
    static private $defaultProcessors;

    /**
     * @var PrinterInterface
     */
    static private $defaultPrinter;

    /**
     * @var Env
     */
    static private $defaultEnv;

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

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

        if (!self::$defaultEnv) {
            $argStr = implode(' ', $_SERVER['argv']);
            self::$defaultEnv = new Env(
                strpos($argStr, '--debug') !== false,
                preg_match('/-v|--verbose/', $argStr)
            );
        }
        $this->env = self::$defaultEnv;

        if (!self::$defaultProcessors) {
            self::$defaultProcessors = [
                new HTTPResponse(),
                new ArrayFlattener(),
                new RequestProfiler()
            ];
        }
        $this->processors = self::$defaultProcessors;

        if (!self::$defaultPrinter) {
            self::$defaultPrinter = new Plain;
        }
        $this->printer = self::$defaultPrinter;
    }

    /**
     * @param $context
     * @return string
     */
    private function process($context = null)
    {
        $collection = [];
        foreach($this->processors as $processor) {
            if (!$processor->accepts($context)) {
                continue;
            }
            $collection[$processor->getIdentifier()] = $processor->normalize($collection, $context, $this->env->isVerbose());
            break;
        }

        if (!$collection) {
            return '';
        }

        return $this->printer->parse($collection);
    }

    /**
     * @param array $processors
     * @param PrinterInterface|null $printer
     * @param Env|null $env
     */
    static public function setDefaults(array $processors = [],
                                       PrinterInterface $printer = null,
                                       Env $env = null)
    {
        self::$defaultProcessors = $processors;
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