<?php

namespace Appeltaert\PAM\Tests;


use Appeltaert\PAM\Env;
use Appeltaert\PAM\PAM;

class PAMTest extends \PHPUnit_Framework_TestCase
{
    private $arrayContextDump = "\n\nArray: key: val\n\n";
    private $arrayContext = ['key' => 'val'];

    function setUp()
    {
        PAM::setDefaults([], null, new Env(true, false, false));
    }

    /**
     * @covers PAM::__toString
     */
    function testBasic()
    {
        $this->assertEquals('hello world',
            (string)new PAM('hello world'));
    }

    /**
     * @covers PAM::__toString
     */
    function testNoOutputWithNoDebug()
    {
        PAM::setDefaults([], null, new Env(false));
        $this->assertEquals('hello world',
            (string)new PAM('hello world', [['qwer' => 'qwer']]));
    }

    /**
     * @covers PAM::__toString
     */
    function testWithArgs()
    {
        $this->assertEquals('hello world',
            (string)new PAM('hello%s%s', [], [' ', 'world']));
    }

    /**
     * @covers PAM::__toString
     */
    function testWithContext()
    {
        $this->assertEquals("hello" . $this->arrayContextDump,
            (string)new PAM('hello', [$this->arrayContext]));
    }

    /**
     * @covers PAM::__toString
     */
    function testWithContextAndArgs()
    {
        $this->assertEquals("hello world" . $this->arrayContextDump,
            (string)new PAM('hello%s%s', [$this->arrayContext], [' ', 'world']));
    }
}