<?php
use PHPUnit\Framework\TestCase;

class EjemploTest extends TestCase
{
    public function test_suma_basica()
    {
        $resultado = 2 + 2;
        $this->assertEquals(4, $resultado);
    }
}
