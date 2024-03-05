<?php

namespace Tests\kbATeam\GraylogUtilities;

use kbATeam\GraylogUtilities\LogTypes;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Class LogTypesTest
 * @package Tests\kbATeam\GraylogUtilities
 */
class LogTypesTest extends TestCase
{
    /**
     * @var array<int, string> Array of allowed log level types.
     */
    private static $allLogtypes = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG
    ];

    /**
     * Assert that an empty instance of log types results in all log types being returned.
     */
    public function testEmptyLogTypes(): void
    {
        $types = new LogTypes();
        static::assertSame(self::$allLogtypes, $types->get());
    }

    /**
     * Test that adding the same log level twice will return only the log level
     * only once.
     */
    public function testDuplicateLogTypes(): void
    {
        $types = new LogTypes([LogLevel::ALERT]);
        $types->add(LogLevel::ALERT);
        static::assertSame([LogLevel::ALERT], $types->get());
    }

    /**
     * Data provider for invalid log levels/types.
     * @return array<mixed>
     */
    public static function provideInvalidTypes(): array
    {
        return [
            ['1'],
            ['PANIC!'],
            ['ALRT']
        ];
    }

    /**
     * Test that invalid type are not added.
     * @param string $loglevel
     * @dataProvider provideInvalidTypes
     */
    public function testInvalidTypes(string $loglevel): void
    {
        $types = new LogTypes([LogLevel::ALERT]);
        $types->add($loglevel);
        static::assertSame([LogLevel::ALERT], $types->get());
    }
}
