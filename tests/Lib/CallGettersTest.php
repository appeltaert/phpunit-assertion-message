<?php

namespace Appeltaert\PAM\Tests\Lib;

use function Appeltaert\PAM\callGetters;

class Stub
{
    public function getGetter()
    {
        return "getted";
    }
}

/**
 * @covers ::callGetters
 */
class CallGettersTest extends \PHPUnit_Framework_TestCase
{
    public function testDoesItGetIt()
    {
        $this->assertSame(['Getter' => 'getted'], callGetters(new Stub, ['Getter']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDoesItNotGetIt()
    {
        $this->assertSame(['Getter' => 'getted'], callGetters(new Stub, ['Whoops']));
    }
}
