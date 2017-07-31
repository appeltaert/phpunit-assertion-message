<?php

namespace Appeltaert\PAM;


use Appeltaert\PAM\Processor\ArrayType;
use Appeltaert\PAM\Processor\ProcessorInterface;
use Appeltaert\PAM\Processor\Symfony\HTTPResponse;
use Appeltaert\PAM\Processor\Symfony\RequestProfiler;
use Appeltaert\PAM\Printer\Plain;
use Appeltaert\PAM\Printer\PrinterInterface;
use Appeltaert\PAM\Processor\Vardump;

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
     * @param string $message
     * @param array $context
     * @param array $printargs
     */
    public function __construct($message, array $context = [], array $printargs = [])
    {
        $this->message = $printargs ? vsprintf($message, $printargs) : $message;
        $this->context = $context;

        if (!self::$defaultEnv) {
            self::$defaultEnv = new Env;
        }
        $this->env = self::$defaultEnv;

        if (!self::$defaultProcessors) {
            self::$defaultProcessors = [
                new HTTPResponse(),
                new RequestProfiler(),
                new ArrayType(),
                new Vardump(),
            ];
        }
        $this->processors = self::$defaultProcessors;

        if (!self::$defaultPrinter) {
            self::$defaultPrinter = new Plain($this->env);
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
        if ($processors) {
            self::$defaultProcessors = $processors;
        }
        if ($printer) {
            self::$defaultPrinter = $printer;
        }
        if ($env) {
            self::$defaultEnv = $env;
        }
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
