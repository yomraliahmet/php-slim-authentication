<?php

use PHPUnit\Framework\TestCase;

/** @test */
class FirstTest extends TestCase
{
    public function testCarp()
    {
        $a = 4;
        $b = 5;
        $c = $a * $b;

        // Doğruluğu kontrol ediliyor.
        $this->assertEquals($c, 20);
    }

    public function testTopla()
    {
        $a = 4;
        $b = 5;
        $c = $a + $b;

        // Doğruluğu kontrol ediliyor.
        $this->assertEquals($c, 9);
    }
}