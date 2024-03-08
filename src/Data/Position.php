<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

readonly class Position implements \Stringable
{
    /**
     * @param int<0, max> $x
     * @param int<0, max> $y
     */
    public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {}

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('object<Position> { x: %d, y: %d }', [
            $this->x,
            $this->y,
        ]);
    }
}
