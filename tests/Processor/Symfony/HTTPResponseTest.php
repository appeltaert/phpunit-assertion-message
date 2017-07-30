<?php

namespace Appeltaert\PAM\Tests\Processor\Symfony;



use Appeltaert\PAM\Processor\Symfony\HTTPResponse;

/**
 * @covers HTTPResponse
 *
 * @todo Better coverage, distinctive output tests on normalize() based on verbosity
 */
class HTTPResponseTest extends \PHPUnit_Framework_TestCase
{
    private $accepted = 'Symfony\Component\HttpFoundation\Response';

    /**
     * @covers HTTPResponse::accepts()
     */
    function testAccepts()
    {
        $case = new HTTPResponse();
        $this->assertFalse($case->accepts(new \stdClass()));
        $this->assertTrue($case->accepts(
            $this->getMockBuilder($this->accepted)->getMock()
        ));
    }

    /**
     * @covers HTTPResponse::normalize()
     */
    function testNonVerbose()
    {
        $case = new HTTPResponse();

        $mock = $this->getMockBuilder($this->accepted)
            ->setMethods(['getStatusCode', 'getContent'])
            ->allowMockingUnknownTypes()
            ->getMock();

        $mock->method('getStatusCode')->willReturn(200);

        $mock->method('getContent')
            ->willReturn(file_get_contents(__DIR__ . '/../../Resources/Processor/Symfony/HttpResponse/ise.html'));

        /** @noinspection PhpUndefinedFieldInspection */
        $headers = $mock->headers = $this->getMockBuilder('stdClass')
            ->setMethods(['all', 'get', 'has', 'getCookies'])
            ->getMock();

        $headers->method('get')
            ->willReturnCallback(function($arg) {
                return $arg;
            });

        $this->assertSame([
            'Code' => 200,
            'Content-type' => 'content-type',
            'Exception' => 'InvalidArgumentException',
        ], $case->normalize([], $mock, false));
    }
}