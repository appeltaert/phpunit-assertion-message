<?php

namespace Appeltaert\PAM\Tests\Lib;

use function Appeltaert\PAM\flattenVar;

class toStringStub
{
    public function __toString()
    {
        return 'toString';
    }
}

class toJsonStub implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [1,2,3];
    }
}

class toJsonStubFails implements \JsonSerializable
{
    public $r;
    public function jsonSerialize()
    {
        $this->r = fopen('php://input', 'r');
        return $this->r;
    }
}

/**
 * @covers ::flattenVar
 */
class FlattenVarTest extends \PHPUnit_Framework_TestCase
{
    public function testScalars()
    {
        $this->assertSame('string', flattenVar('string'));
        $this->assertSame(123, flattenVar(123));
    }

    public function testResource()
    {
        $res = fopen('php://input', 'r');
        $this->assertTrue(preg_match('/stream\(#\d+\)/', flattenVar($res)) === 1);
        fclose($res);
    }

    public function testObjectToString()
    {
        $this->assertSame('toString', flattenVar(new toStringStub()));
    }

    public function testDateTime()
    {
        $this->assertSame(
            'DateTime(2010-01-01T00:00:00+0000)',
            flattenVar(new \DateTime('1-1-2010', new \DateTimeZone('utc')))
        );
    }

    public function testObjectJson()
    {
        $this->assertSame('[1,2,3]', flattenVar(new toJsonStub()));
    }

    public function testJsonFailResolvesToPrintr()
    {
        $stub = new toJsonStubFails();
        $this->assertContains('Appeltaert\PAM\Tests\Lib\toJsonStubFails Object([r] => Resource id', flattenVar($stub));
        fclose($stub->r);
    }
}
