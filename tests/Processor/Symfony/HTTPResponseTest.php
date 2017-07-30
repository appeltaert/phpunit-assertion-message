<?php

namespace Appeltaert\PAM\Tests\Processor\Symfony;


//use Appeltaert\PAM\Processor\HTTPResponse;
//
//class SymfonyHTTPResponseTest extends \PHPUnit_Framework_TestCase
//{
//    function testAccepts() {
//        $case = new HTTPResponse();
//        $this->assertFalse($case->accepts(new \stdClass()));
//        $this->assertTrue($case->accepts(
//            $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->getMock()
//        ));
//    }
//
//    function testFormat() {
//        $case = new HTTPResponse();
//        $mock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')
//            ->setMethods(['getStatusCode', 'getContent'])
//            ->allowMockingUnknownTypes()
//            ->getMock();
//        $mock->method('getStatusCode')->willReturn(200);
//        $this->assertContains('Code: 200', $case->format("hello world", $mock));
//    }
//}