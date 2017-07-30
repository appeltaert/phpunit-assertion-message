<?php

namespace Appeltaert\PAM\Tests\Processor\Symfony;


use Appeltaert\PAM\Processor\Symfony\RequestProfiler;

/**
 * @covers RequestProfiler
 */
class RequestProfilerTest extends \PHPUnit_Framework_TestCase
{
    private $accepted = 'Symfony\Component\HttpKernel\DataCollector\RequestDataCollector';

    function testAccepts()
    {
        $case = new RequestProfiler();
        $this->assertFalse($case->accepts(new \stdClass()));
        $this->assertTrue($case->accepts(
            $this->getMockBuilder($this->accepted)->getMock()
        ));
    }

    function testNormalize()
    {
        $case = new RequestProfiler();

        $methodsExtracted = [
            'Format',
            'StatusText',
            'Route',
            'StatusCode',
            'ContentType',
            'PathInfo'
        ];

        $mock = $this->getMockBuilder($this->accepted)
            ->setMethods(array_map(function($v) { return 'get' . $v; }, $methodsExtracted))
            ->allowMockingUnknownTypes()
            ->getMock();

        $return = $case->normalize([], $mock, false);

        $this->assertEquals(array_fill_keys($methodsExtracted, null), $return);
    }
}