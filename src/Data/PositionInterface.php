<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

/**
 * @template T of float|int
 *
 * @property-read T $x
 * @property-read T $y
 */
interface PositionInterface extends \Stringable
{
    /**
     * Adds the given position to this position.
     *
     * @param self $position
     *
     * @return self<T>
     */
    public function add(self $position): self;

    /**
     * Subtracts the given position from this position.
     *
     * @param self $position
     *
     * @return self<T>
     */
    public function sub(self $position): self;

    /**
     * @return SizeInterface<T>
     */
    public function toSize(): SizeInterface;

    /**
     * @return non-empty-string
     */
    public function __toString(): string;
}
