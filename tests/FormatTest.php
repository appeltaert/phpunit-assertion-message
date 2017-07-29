<?php

namespace Tests;


use Appeltaert\PAM\Msg;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Msg::format()
     */
    function testMessage()
    {
        $this->assertEquals('hello world',
            Msg::format('hello world'));
    }

    /**
     * @covers Msg::format()
     */
    function testMessageWithArguments()
    {
        $this->assertEquals('hello world',
            Msg::format('hello%s%s', null, [' ', 'world']));
    }
}