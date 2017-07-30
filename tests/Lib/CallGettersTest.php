<?php

namespace Appeltaert\PAM\Tests\Lib;


use function Appeltaert\PAM\callGetters;

class stub {
    function getGetter() {
        return "getted";
    }
}

class CallGettersTest extends \PHPUnit_Framework_TestCase
{
    function testDoesItGetIt() {
        $this->assertSame(['Getter' => 'getted'], callGetters(new stub, ['Getter']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testDoesItNotGetIt() {
        $this->assertSame(['Getter' => 'getted'], callGetters(new stub, ['Whoops']));
    }
}