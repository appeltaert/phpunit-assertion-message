<?php

namespace Appeltaert\PAM\Tests\Printer;

use Appeltaert\PAM\Env;
use Appeltaert\PAM\Printer\Plain;

/**
 * @covers Plain
 */
class PlainTest extends \PHPUnit_Framework_TestCase
{
    private $collection = [
        'Array' => [
            'key' => 'val',
            'longer key2' => [
                'key3' => 'val3',
                'key4' => [
                    'key5' => 'val5'
                ]
            ]
        ]
    ];

    function maxDepthProvider()
    {
        return [
            [0, file_get_contents(__DIR__ . '/../Resources/Printer/Plain/parsedArrayDepth0.txt')],
            [1, file_get_contents(__DIR__ . '/../Resources/Printer/Plain/parsedArrayDepth1.txt')],
            [2, file_get_contents(__DIR__ . '/../Resources/Printer/Plain/parsedArrayDepth2.txt')],
        ];
    }

    /**
     * Inherently tests the key-lengths calculations also
     *
     * @dataProvider maxDepthProvider
     * @param int $depth
     * @param string $expected
     */
    function testMaxDepth($depth, $expected)
    {
        $printer = new Plain(
            new Env(true, false, false),
            ' ',
            $depth
        );
        $this->assertSame($expected, $printer->parse($this->collection));
    }

    /**
     *
     */
    function testWhitespaceStrings()
    {
        $printer = new Plain(
            new Env(true, false, false),
            '+', 2
        );
        $this->assertSame(
            str_replace('~', '+', file_get_contents(__DIR__ . '/../Resources/Printer/Plain/parsedArrayDepth2WhitespaceReplacements.txt')),
            $printer->parse($this->collection)
        );
    }
}