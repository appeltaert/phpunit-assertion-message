<?php

namespace Appeltaert\PAM\Formatter;


class SymfonyHTTPResponse implements FormatterInterface
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

    /**
     * @param array $collection
     * @param $context
     * @return array
     * @noinspection PhpUndefinedFieldInspection
     */
    function normalize(array $collection, $context)
    {
        $lines = [];

        $lines['Code'] = $context->getStatusCode();

        if ($context->headers->has('location')) {
            $lines['Redirect to'] = $context->headers->get('location');

        }

//        $lines['Headers'] = [
//            'werewr' => ['qwerqwre', 'qwerqwer']
//        ];
//
//        $lines['Headers'] = [
//            'cache-control' =>
//                [
//                    'no-cache',
//                ],
//            'x-debug-token' =>'qer'
//        ];
//        ob_get_level()?ob_end_clean():'';echo '<pre>',__FILE__,':',__LINE__;var_export($context->headers->all());die;

        $lines['Headers'] = $context->headers->all();

        /** @noinspection PhpUndefinedFieldInspection */
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

//        $lines['Headers2'] = [
//            'x-debug-token' => ['qewr', 'qwer' => ['qwer','qwer']],
//            'cache-control' => 'no-cache',
//        ];

        return $lines;
    }
}