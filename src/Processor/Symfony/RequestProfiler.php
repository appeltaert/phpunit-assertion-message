<?php

namespace Appeltaert\PAM\Processor\Symfony;

use Appeltaert\PAM\Processor\ProcessorInterface;
use function Appeltaert\PAM\callGetters;

class RequestProfiler implements ProcessorInterface
{
    public function getIdentifier()
    {
        return 'Request profile';
    }

    public function accepts($context)
    {
        $expected = 'Symfony\Component\HttpKernel\DataCollector\RequestDataCollector';
        return is_object($context) && $context instanceof $expected;
    }

    public function normalize(array $collection, $context, $verbose)
    {
        $return = [];

        $return = array_merge($return, callGetters($context, [
            'Format',
            'Route',
            'StatusText',
            'StatusCode',
            'ContentType',
            'PathInfo',
            'Method',
            'Locale',
            'RouteParams',
            'Redirect',
            'SessionAttributes',
            'SessionMetaData',
        ]));

        $controllerInfo = $context->getController();
        $return['Action'] = $controllerInfo['class'] .'::'. $controllerInfo['method'];

        $return = array_filter($return);

        return $return;
    }
}
