<?php

namespace kbATeam\GraylogUtilities;

/**
 * Class Obfuscator
 * Obfuscate keys in an array.
 * @package kbATeam\GraylogUtilities
 */
class Obfuscator
{
    /**
     * @var array<int, string> Keys to obfuscate.
     */
    private $keys = [];

    /**
     * @var string The string to obfuscate the defined keys with.
     */
    private $obfuscationString = '********';

    /**
     * Obfuscator constructor.
     * @param array<int, string> $keys Keys to obfuscate.
     */
    public function __construct(array $keys = [])
    {
        foreach ($keys as $key) {
            $this->addKey($key);
        }
    }

    /**
     * Add a key to the list of keys to obfuscate in an array.
     * @param string $key
     */
    public function addKey(string $key): void
    {
        if (!in_array($key, $this->keys, true)) {
            $this->keys[] = strtolower($key);
        }
    }

    /**
     * Define the obfuscation string to use.
     * @param string $obfuscationString
     */
    public function setObfuscationString(string $obfuscationString): void
    {
        $this->obfuscationString = $obfuscationString;
    }

    /**
     * Obfuscate the contents of the previously defined keys in the given array
     * with the previously defined string.
     * @param array<mixed> $array
     * @return array<mixed>
     */
    public function obfuscate(array $array): array
    {
        if ($this->keys === []) {
            return $array;
        }
        array_walk_recursive(
            $array,
            function (&$contents, $key) {
                if (isset($contents)
                    && in_array(strtolower($key), $this->keys, true)
                ) {
                    $contents = $this->obfuscationString;
                }
            }
        );
        return $array;
    }
}
