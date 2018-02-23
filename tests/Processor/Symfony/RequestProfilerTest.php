<?php

namespace Appeltaert\PAM\Tests\Processor\Symfony;

use Appeltaert\PAM\Processor\Symfony\RequestProfiler;

/**
 * @covers RequestProfiler
 */
class RequestProfilerTest extends \PHPUnit_Framework_TestCase
{
    private $accepted = 'Symfony\Component\HttpKernel\DataCollector\RequestDataCollector';

    public function testAccepts()
    {
        $case = new RequestProfiler();
        $this->assertFalse($case->accepts(new \stdClass()));
        $this->assertTrue($case->accepts(
            $this->getMockBuilder($this->accepted)->getMock()
        ));
    }

    public function testNormalize()
    {
        $case = new RequestProfiler();

        $methodsExtracted = [
            'Format',
            'StatusText',
            'Route',
            'StatusCode',
            'ContentType',
            'PathInfo',
            'Method',
            'Locale',
            'RouteParams',
            'Controller',
            'Redirect',
            'SessionAttributes',
            'SessionMetaData',
        ];

        $calls = array_map(function ($v) {
            return 'get' . $v;
        }, $methodsExtracted);

        $calls = array_fill_keys($calls, 1);

        $calls['getController'] = ['class' => 'Controller', 'method' => 'Action'];

        $mock = $this->getMockBuilder($this->accepted)
            ->setMethods(array_keys($calls))
            ->allowMockingUnknownTypes()
            ->getMock()
        ;
        foreach($calls as $method => $value) {
            $mock->method($method)->willReturn($value);
        }

        $return = $case->normalize([], $mock, false);

        $expected = array_fill_keys($methodsExtracted, 1);
        $expected['Action'] = 'Controller::Action';
        unset($expected['Controller']);

        $this->assertEquals($expected, $return);
    }
}
