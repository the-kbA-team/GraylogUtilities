<?php

namespace Tests\kbATeam\GraylogUtilities;

use PHPUnit\Framework\TestCase;
use kbATeam\GraylogUtilities\Obfuscator;

/**
 * Class ObfuscatorTest
 * @package Tests\kbATeam\GraylogUtilities
 */
class ObfuscatorTest extends TestCase
{
    /**
     * Test obfuscation without keys to obfuscate.
     */
    public function testEmptyObfuscation(): void
    {
        $obfuscator = new Obfuscator();
        $data = [
            'foo' => 'bar',
            'bar' => 'baz'
        ];
        static::assertSame($data, $obfuscator->obfuscate($data));
    }

    /**
     * Test simple password obfuscation.
     */
    public function testSimplePasswordObfuscation(): void
    {
        $obfuscator = new Obfuscator(['password', 'passwd']);
        $data = [
            'foo' => [
                'bar' => 'baz'
            ],
            'PaSsWoRd' => 'secret',
            'passwd' => 'another secret'
        ];
        $expect = [
            'foo' => [
                'bar' => 'baz'
            ],
            'PaSsWoRd' => '********',
            'passwd' => '********'
        ];
        static::assertSame($expect, $obfuscator->obfuscate($data));
    }

    /**
     * Test setting an alternate obfuscation string.
     */
    public function testAlternateObfuscationString(): void
    {
        $obfuscator = new Obfuscator();
        $obfuscator->addKey('password');
        $obfuscator->setObfuscationString('xxxx');
        $data = [
            'foo' => [
                'password' => 'secret'
            ],
            'bar' => 'baz'
        ];
        $expect = [
            'foo' => [
                'password' => 'xxxx'
            ],
            'bar' => 'baz'
        ];
        static::assertSame($expect, $obfuscator->obfuscate($data));
    }
}
