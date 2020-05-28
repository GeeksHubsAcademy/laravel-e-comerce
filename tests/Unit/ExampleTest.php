<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

        $this->assertSame($this->suma(2,2),4);
        $this->assertSame($this->suma(2,5),7);
    }
    public function suma($a,$b)
    {
        $result = $a+$b;
        return $result;
    }
}
