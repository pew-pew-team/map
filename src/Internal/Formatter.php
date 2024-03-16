<?php

declare(strict_types=1);

namespace PewPew\Map\Internal;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal PewPew\Map
 */
final readonly class Formatter
{
    /**
     * @param int<1, max> $value
     */
    public static function indent(string $content, int $value = 1): string
    {
        return \str_repeat(' ', $value * 4)
            . $content;
    }

    /**
     * @param int<1, max> $value
     */
    public static function indentAll(string $content, int $value = 1): string
    {
        $lines = \explode("\n", $content);

        foreach ($lines as $i => $line) {
            $lines[$i] = self::indent($line, $value);
        }

        return \implode("\n", $lines);
    }

    /**
     * @param int<1, max> $value
     */
    public static function indentAllBody(string $content, int $value = 1): string
    {
        return \ltrim(self::indentAll($content, $value));
    }
}
