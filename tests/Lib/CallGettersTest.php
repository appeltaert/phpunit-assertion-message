<?php

namespace Appeltaert\PAM\Tests\Lib;


use function Appeltaert\PAM\callGetters;

class Stub {
    function getGetter() {
        return "getted";
    }
}

/**
 * @covers ::callGetters
 */
class CallGettersTest extends \PHPUnit_Framework_TestCase
{
    function testDoesItGetIt() {
        $this->assertSame(['Getter' => 'getted'], callGetters(new Stub, ['Getter']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testDoesItNotGetIt() {
        $this->assertSame(['Getter' => 'getted'], callGetters(new Stub, ['Whoops']));
    }
}