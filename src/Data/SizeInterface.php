<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

/**
 * @template T of float|int
 *
 * @property-read T $width
 * @property-read T $height
 */
interface SizeInterface extends \Stringable
{
    /**
     * @return T
     */
    public function getArea(): int|float;

    /**
     * @return non-empty-string
     */
    public function __toString(): string;
}
