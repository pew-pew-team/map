<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

final readonly class Position
{
    /**
     * @param int<0, max> $x
     * @param int<0, max> $y
     */
    public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {}
}
