<?php

namespace kbATeam\GraylogUtilities;

use Psr\Log\LogLevel;

/**
 * Class LogTypes
 * Define PSR-3 log types and get a list of defined log types.
 * @package kbATeam\GraylogUtilities
 */
class LogTypes
{
    /**
     * @var array<int, string> Array of allowed log level types.
     */
    private static $allowedTypes = [
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
     * @var array<int, string>
     */
    private $types = [];

    /**
     * LogTypes constructor.
     * @param array<int, string> $types
     * @return void
     */
    public function __construct(array $types = [])
    {
        foreach ($types as $type) {
            $this->add($type);
        }
    }

    /**
     * Add PSR-3 log type.
     * @param string $type PSR-3 log type
     */
    public function add(string $type): void
    {
        /**
         * Don't add in case of an invalid type or in case the type has already
         * been added.
         */
        if (in_array($type, self::$allowedTypes, true)
            && !in_array($type, $this->types, true)
        ) {
            $this->types[] = $type;
        }
    }

    /**
     * Get all configured PSR-3 log types.
     * Returns all possible log types in case none were defined. This is the
     * default behavior for logging.
     * @return array<int, string>
     */
    public function get(): array
    {
        if ($this->types === []) {
            return self::$allowedTypes;
        }
        return $this->types;
    }
}
