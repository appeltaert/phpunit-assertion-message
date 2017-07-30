<?php

namespace Appeltaert\PAM\Tests;


use Appeltaert\PAM\PAM;

class PAMTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PAM::__toString
     */
    function testMessage()
    {
        $this->assertEquals('hello world',
            (string)new PAM('hello world'));
    }

    /**
     * @covers PAM::__toString
     */
    function testMessageWithArguments()
    {
        $this->assertEquals('hello world',
            (string)new PAM('hello%s%s', [], [' ', 'world']));
    }
}