<?php

namespace Appeltaert\PAM\Processor\Symfony;


use Appeltaert\PAM\Processor\ProcessorInterface;

class HTTPResponse implements ProcessorInterface
{
    function getIdentifier()
    {
        return 'HTTP response';
    }

    function accepts($context)
    {
        $accepted = '\Symfony\Component\HttpFoundation\Response';
        return is_object($context)
            && $context instanceof $accepted;
    }

    function normalize(array $collection, $context, $verbose)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        /** @noinspection PhpUndefinedMethodInspection */

        $lines = [];

        $lines['Code'] = $context->getStatusCode();

        if ($verbose) {
            $lines['Headers'] = $context->headers->all();
        }
        else {
            $lines['Content-type'] = $context->headers->get('content-type');
            if ($context->headers->has('location')) {
                $lines['Redirect-to'] = $context->headers->get('location');
            }
        }

        if ($cookies = $context->headers->getCookies()) {
            $lines['Cookies'] = $cookies;
        }

        if (stripos($context->getContent(), 'exception') !== false) {
            // title has the error
            preg_match('/<title>([^<]*)<\/title>/i', $context->getContent(), $matches);
            // otherwise look for an h1
            isset($matches[1]) or preg_match('/<h1>([^<]*)<\/h1>/i', $context->getContent(), $matches);
            $lines['Exception'] = isset($matches[1]) ? trim($matches[1]) : 'Unknown exception';
        }

        return $lines;
    }
}